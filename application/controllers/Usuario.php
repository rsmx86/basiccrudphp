<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logado')) {
            redirect('auth');
        }

        $liberados = ['perfil', 'atualizar_perfil', 'processar_troca_senha'];
        $metodo = $this->router->fetch_method();

        if (!in_array($metodo, $liberados) && $this->session->userdata('nivel_acesso') !== 'admin') {
            redirect('cadastro');
        }
    }

    public function index() {
        $data = [
            'titulo'   => "Gerenciamento de Usuários",
            'usuarios' => $this->usuario_model->get_all_users()
        ];
        $this->load->view('includes/header', $data);
        $this->load->view('usuario_listagem_view', $data);
        $this->load->view('includes/footer');
    }

    public function perfil() {
        $id = $this->session->userdata('id_usuario');
        $data = [
            'usuario' => $this->usuario_model->get_user_by_id($id),
            'titulo'  => 'Meu Perfil'
        ];
        if (!$data['usuario']) redirect('auth');
        $this->load->view('includes/header', $data);
        $this->load->view('usuario_editar_view', $data);
        $this->load->view('includes/footer');
    }

    public function criar() {
        $data['titulo'] = 'Novo Usuário';
        $this->load->view('includes/header', $data);
        $this->load->view('usuario_form_view', $data);
        $this->load->view('includes/footer');
    }

    public function salvar() {
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->criar();
        } else {
            $dados = [
                'nome'             => $this->input->post('nome'),
                'email'            => $this->input->post('email'),
                'senha'            => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'nivel_acesso'     => 'comum',
                'admin_criador_id' => $this->session->userdata('id_usuario')
            ];
            $this->usuario_model->inserir_usuario($dados);
            $this->session->set_flashdata('sucesso', 'Usuário criado!');
            redirect('usuario');
        }
    }

    public function editar($id = NULL) {
        if ($id === NULL) redirect('usuario');
        $data = [
            'usuario' => $this->usuario_model->get_user_by_id($id),
            'titulo'  => 'Editar Usuário'
        ];
        if (!$data['usuario']) redirect('usuario');
        $this->load->view('includes/header', $data);
        $this->load->view('usuario_editar_view', $data);
        $this->load->view('includes/footer');
    }

    public function atualizar($id) {
        $dados = [
            'nome'         => $this->input->post('nome'),
            'nivel_acesso' => $this->input->post('nivel_acesso')
        ];
        $nova_senha = $this->input->post('nova_senha');
        if (!empty($nova_senha)) {
            if (strlen($nova_senha) < 6) {
                $this->session->set_flashdata('erro', 'Senha muito curta (min. 6).');
                redirect("usuario/editar/$id");
                return;
            }
            $dados['senha'] = password_hash($nova_senha, PASSWORD_DEFAULT);
        }
        $this->usuario_model->atualizar_usuario($id, $dados);
        $this->session->set_flashdata('sucesso', 'Atualizado com sucesso!');
        redirect('usuario');
    }

    public function deletar($id) {
        if ($this->input->post('senha_master') === 'qwaszx123') {
            $this->usuario_model->excluir_usuario_com_custodia($id, $this->session->userdata('id_usuario'));
            $this->session->set_flashdata('sucesso', 'Usuário removido.');
        } else {
            $this->session->set_flashdata('erro', 'Senha Master incorreta.');
        }
        redirect('usuario');
    }

    public function atualizar_perfil() {
        $id = $this->session->userdata('id_usuario');
        $nome = $this->input->post('nome');
        if ($nome) {
            $this->usuario_model->atualizar_usuario($id, ['nome' => $nome]);
            $this->session->set_userdata('nome_usuario', $nome);
            $this->session->set_flashdata('sucesso', 'Perfil atualizado!');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function processar_troca_senha() {
    // 1. Limpa qualquer saída anterior para não quebrar o JSON
    if (ob_get_level() > 0) ob_clean();
    header('Content-Type: application/json');

    $id = $this->session->userdata('id_usuario');
    $user = $this->usuario_model->get_user_by_id($id);
    
    $atual    = $this->input->post('senha_atual');
    $nova     = $this->input->post('nova_senha');
    $confirma = $this->input->post('confirma_senha');

    if (!password_verify($atual, $user['senha'])) {
        echo json_encode(['status' => 'error', 'message' => 'Senha atual incorreta.']);
        return;
    }

    if (strlen($nova) < 6 || $nova !== $confirma) {
        echo json_encode(['status' => 'error', 'message' => 'A confirmação não confere ou a senha é curta.']);
        return;
    }
    
    // coluna no array DEVE ser igual ao do Banco de Dados
    $dados_atualizacao = [
        'senha' => password_hash($nova, PASSWORD_DEFAULT),
        'ultima_alteracao_senha' => date('Y-m-d H:i:s') 
    ];

    if ($this->usuario_model->atualizar_usuario($id, $dados_atualizacao)) {
        // Removemos o sess_destroy daqui por um segundo para testar o retorno
        // O deslogar faremos via JavaScript após o alert de sucesso
        echo json_encode([
            'status' => 'success', 
            'message' => 'Senha alterada com sucesso!',
            'redirect' => site_url('auth/logout') // Crie um método logout ou redirecione para login
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar no banco.']);
    }

    }
}