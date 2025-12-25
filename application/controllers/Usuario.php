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

    public function criar() {
        $data['titulo'] = 'Novo Usuário';
        $data['user'] = NULL; 

        $this->load->view('includes/header', $data);
        $this->load->view('usuario_form_view', $data); 
        $this->load->view('includes/footer');
    }

    public function salvar() {
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->criar();
        } else {
            $dados = [
                'nome'             => $this->input->post('nome'),
                'email'            => $this->input->post('email'),
                'senha'            => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'nivel_acesso'     => $this->input->post('nivel_acesso') ? $this->input->post('nivel_acesso') : 'user',
                'admin_criador_id' => $this->session->userdata('id_usuario')
            ];

            if ($this->usuario_model->inserir_usuario($dados)) {
                $this->session->set_flashdata('sucesso', 'Usuário criado com sucesso! Senha: 123456');
            } else {
                $this->session->set_flashdata('erro', 'Erro ao inserir no banco.');
            }
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
}