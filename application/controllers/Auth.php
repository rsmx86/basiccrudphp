<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Carrega as bibliotecas necessárias para validação e o model de usuários
        $this->load->library('form_validation');
        $this->load->model('usuario_model'); 
    }

    /**
     * Tela Principal de Login
     */
    public function index() {
        // SEGURANÇA: Se já houver sessão ativa, manda direto para o sistema
        if ($this->session->userdata('logado')) {
            redirect('cadastro');
        }
        $this->load->view('login_view');
    }

    /**
     * Processa a autenticação do usuário
     */
    public function login() {
        // Define as regras para que os campos não cheguem vazios ao banco
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Se o formulário não for preenchido corretamente, recarrega a view com os erros
            $this->load->view('login_view');
        } else {
            $email = $this->input->post('email');
            $senha = $this->input->post('senha');

            // O Model faz a comparação da senha usando password_verify
            $usuario = $this->usuario_model->verificar_login($email, $senha);

            if ($usuario) {
                // Montamos o "pacote" de dados que ficará gravado no navegador do usuário
                $session_data = array(
                    'id_usuario'    => $usuario->id,
                    'nome_usuario'  => $usuario->nome,
                    'email_usuario' => $usuario->email,
                    'nivel_acesso'  => $usuario->nivel_acesso, // Importante para as travas de Admin
                    'logado'        => TRUE
                );
                
                $this->session->set_userdata($session_data);
                redirect('cadastro'); // Entrada liberada
            } else {
                // Mensagem temporária que some após o próximo carregamento de página
                $this->session->set_flashdata('erro_login', 'Email ou senha inválidos.');
                redirect('auth');
            }
        }
    }

    /**
     * Encerra a sessão de forma segura
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
    
    /**
     * UTILITÁRIO: Cria o primeiro administrador
     * DICA: Após usar uma vez, apague este método ou comente-o para segurança.
     */
    public function criar_admin_inicial() {
        $senha_hash = password_hash('123456', PASSWORD_DEFAULT); 
        $data = array(
            'nome'         => 'Admin Teste',
            'email'        => 'admin@teste.com',
            'senha'        => $senha_hash,
            'nivel_acesso' => 'admin'
        );
        
        $this->db->insert('usuarios', $data);
        
        echo "<h2>✅ ADMIN CRIADO COM SUCESSO!</h2>";
        echo "Email: <b>admin@teste.com</b><br>";
        echo "Senha: <b>123456</b><br><br>";
        echo "<a href='".site_url('auth')."'>Ir para Login</a>";
    }
}