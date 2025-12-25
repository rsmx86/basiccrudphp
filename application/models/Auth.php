<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Carrega o model de usuário para interagir com a tabela 'usuarios'
        $this->load->model('usuario_model'); 
    }

    // Método Padrão (Acessado via: http://localhost/ci3/auth)
    public function index() {
        // Se o usuário já estiver logado, redireciona para a página principal do CRUD
        if ($this->session->userdata('logado')) {
            redirect('cadastro');
        }
        $this->load->view('login_view');
    }

    public function login() {
        // 1. Regras de Validação
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Falhou na validação: volta para a tela de login
            $this->load->view('login_view');
        } else {
            $email = $this->input->post('email');
            $senha = $this->input->post('senha');

            //  Chama o Model para verificar as credenciais
            $usuario = $this->usuario_model->verificar_login($email, $senha);

            if ($usuario) {
                //  Sucesso: Cria a sessão e armazena os dados importantes
                $session_data = array(
                    'id_usuario'    => $usuario->id,
                    'nome_usuario'  => $usuario->nome,
                    'email_usuario' => $usuario->email,
                    'nivel_acesso'  => $usuario->nivel_acesso, // 'admin' ou 'user'
                    'logado'        => TRUE
                );
                $this->session->set_userdata($session_data);
                redirect('cadastro'); // Redireciona para o Controller de CRUD
            } else {
                //  Falha: Exibe mensagem de erro
                $this->session->set_flashdata('erro_login', 'Email ou senha inválidos.');
                redirect('auth');
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
    
    // MÉTODO CRUCIAL: Cria um usuário admin inicial para testar
    // ACESSE ESTE MÉTODO APENAS UMA VEZ PARA INSERIR O PRIMEIRO USUÁRIO
    public function criar_admin_inicial() {
        // Senha '123456' hasheada de forma segura
        $senha_hash = password_hash('123456', PASSWORD_DEFAULT); 
        $data = array(
            'nome' => 'Admin Teste',
            'email' => 'admin@teste.com',
            'senha' => $senha_hash,
            'nivel_acesso' => 'admin'
        );
        $this->db->insert('usuarios', $data);
        
        echo "<h1>ADMIN CRIADO!</h1>";
        echo "Email: <b>admin@teste.com</b><br>";
        echo "Senha: <b>123456</b><br>";
        echo "<p>Agora, acesse <a href='".base_url('auth')."'>Página de Login</a></p>";
    }
}