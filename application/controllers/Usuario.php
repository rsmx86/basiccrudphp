<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
        $this->load->model('usuario_model'); 
        
        // RESTRIÇÃO GERAL: Garante que apenas usuários logados podem acessar qualquer método deste Controller
        if (!$this->session->userdata('logado')) {
            redirect('auth');
        }
        
        // RESTRIÇÃO DE NÍVEL: Verifica se o usuário logado tem permissão de Admin (necessária para criar/salvar)
        if ($this->session->userdata('nivel_acesso') !== 'admin') {
            // Se não for admin, impede o acesso e redireciona para a listagem principal
            $this->session->set_flashdata('erro', 'Acesso negado: Somente administradores podem gerenciar usuários do sistema.');
            redirect('cadastro');
        }
    }

    public function criar() {
        $data['titulo'] = 'Criar Novo Usuário do Sistema';
        
        // A checagem de Admin já foi feita no __construct, então o Admin pode prosseguir.
        $this->load->view('usuario_form_view', $data); 
    }

    public function salvar() {
        // As regras de validação permanecem robustas
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        // 'is_unique' é fundamental para garantir que não haja emails duplicados
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
        $this->form_validation->set_rules('confirma_senha', 'Confirmação de Senha', 'required|matches[senha]');
        
        if ($this->form_validation->run() == FALSE) {
            // Se falhar na validação, volta ao formulário (chamando criar() novamente para recarregar a view)
            $this->criar();
        } else {
            // Obtém o nome do Admin logado para usar na mensagem de sucesso
            $admin_nome = $this->session->userdata('nome_usuario'); 
            
            $data = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                // USANDO PASSWORD_DEFAULT: É o método mais seguro e recomendado (substitui o antigo md5/sha1/hash5)
                'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'nivel_acesso' => 'comum', // Novo usuário criado pelo Admin é sempre 'comum'
				'admin_criador_id' => $this->session->userdata('id_usuario')
            );

            if ($this->usuario_model->inserir_usuario($data)) {
                $this->session->set_flashdata('sucesso', "Usuário criado com sucesso por {$admin_nome}. Nível de acesso definido como 'comum'.");
            } else {
                $this->session->set_flashdata('erro', 'Erro desconhecido ao tentar criar o usuário no banco de dados.');
            }
            // Redireciona para a listagem principal, onde o Admin pode ver o resultado
            redirect('cadastro'); 
        }
    }
	// Dentro de application/controllers/Usuario.php
// o __construct() já garante que só o Admin pode estar aqui!!!!

	public function index() {
		$data['titulo'] = 'Gerenciamento de Usuários do Sistema';
		$data['usuarios'] = $this->usuario_model->get_all_users(); // Chama a nova função
    
		$this->load->view('usuario_listagem_view', $data); // Iremos criar esta View?
	}

	// Função para carregar os dados de um usuário no formulário de edição
public function editar($id) {
    $this->load->model('usuario_model'); // Garantindo que o Model está carregado
    
    $data['usuario'] = $this->usuario_model->get_user_by_id($id);

    if (empty($data['usuario'])) {
        $this->session->set_flashdata('erro', 'Usuário não encontrado.');
        redirect('usuario');
        return;
    }
    
    // Regra de segurança: O Admin não pode editar a si mesmo (para evitar se despromover)
    if ($data['usuario']['id'] == $this->session->userdata('id_usuario')) {
        $this->session->set_flashdata('erro', 'Você não pode editar o seu próprio perfil de administrador por aqui.');
        redirect('usuario');
        return;
    }

    $data['titulo'] = 'Editar Usuário: ' . $data['usuario']['nome'];
    $this->load->view('usuario_editar_view', $data); // Esta View será o próximo passo
}

// Função para processar o formulário de edição e salvar as alterações
public function atualizar($id) {
    $this->load->model('usuario_model'); // Garantindo que o Model está carregado
    $this->load->library('form_validation');

    // Validação da mudança de Nível de Acesso (apenas 'admin' ou 'comum')
    $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
    $this->form_validation->set_rules('nivel_acesso', 'Nível de Acesso', 'required|in_list[admin,comum]');

    if ($this->form_validation->run() == FALSE) {
        // Falha na validação: retorna para a tela de edição
        $this->editar($id);
    } else {
        $dados_atualizados = array(
            'nome' => $this->input->post('nome'),
            'nivel_acesso' => $this->input->post('nivel_acesso') // O campo chave para promoção/degradação
        );
        
        // Lógica opcional para alterar a senha
        $nova_senha = $this->input->post('nova_senha');
        if (!empty($nova_senha)) {
            if (strlen($nova_senha) < 6) {
                $this->session->set_flashdata('erro', 'A nova senha deve ter pelo menos 6 caracteres.');
                return $this->editar($id);
            }
            // Salvando a nova senha criptografada
            $dados_atualizados['senha'] = password_hash($nova_senha, PASSWORD_DEFAULT);
        }

        if ($this->usuario_model->atualizar_usuario($id, $dados_atualizados)) {
            $this->session->set_flashdata('sucesso', 'Usuário e nível de acesso atualizados com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao atualizar o usuário.');
        }

        redirect('usuario'); // Redireciona para a listagem de usuários
    }
}
}