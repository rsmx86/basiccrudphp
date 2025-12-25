<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cadastro_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('form');

        if (!$this->session->userdata('logado')) redirect('auth');
    }

    public function index() {
        $data['titulo'] = "Gestão de Cadastros";
        
        // 1. Parâmetros de Filtro e Paginação
        $usuario_id  = $this->input->get('filtro_usuario');
        $data_filtro = $this->input->get('filtro_data');
        $cidade      = $this->input->get('cidade');
        $limit       = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $offset      = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 0;

        // 2. Configuração da Paginação
        $config['base_url']             = site_url('cadastro/index');
        $config['total_rows']           = $this->cadastro_model->contar_total_filtrado($usuario_id, $data_filtro, $cidade);
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['reuse_query_string']   = TRUE;

        // Estilização Bootstrap 4
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0">';
        $config['full_tag_close']   = '</ul>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close']    = '</a></li>';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['attributes']       = array('class' => 'page-link');
        $config['next_link']        = 'Próximo';
        $config['prev_link']        = 'Anterior';

        $this->pagination->initialize($config);

        // 3. Busca de Dados
        $data['cadastros'] = $this->cadastro_model->get_cadastros_filtrados($usuario_id, $data_filtro, $cidade, $limit, $offset);
        $data['paginacao'] = $this->pagination->create_links();
        $data['total_registros'] = $config['total_rows'];
        $data['itens_por_pagina'] = $limit;

        $data['lista_usuarios'] = $this->db->get('usuarios')->result_array();
        $data['lista_cidades'] = $this->cadastro_model->get_cidades_distintas();

        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_listagem_view', $data);
        $this->load->view('includes/footer');
    }

    // --- MANTENHA SEUS MÉTODOS CRIAR, STORE, EDITAR, ATUALIZAR E DELETAR ABAIXO ---
    public function criar() {
        $data['titulo'] = 'Novo Cadastro';
        $data['form_action'] = site_url('cadastro/store'); 
        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_form_view', $data);
        $this->load->view('includes/footer');
    }

    public function store() {
        $this->_regras_validacao();
        if ($this->form_validation->run() == FALSE) {
            $this->criar();
        } else {
            $dados = $this->_preparar_dados();
            $dados['usuario_id']   = $this->session->userdata('id_usuario');
            $dados['data_criacao'] = date('Y-m-d H:i:s'); 
            if ($this->cadastro_model->inserir_cadastro($dados)) {
                $this->session->set_flashdata('sucesso', 'Cadastro realizado!');
            }
            redirect('cadastro');
        }
    }

    public function editar($id) {
        $data['cadastro'] = $this->cadastro_model->get_cadastro_por_id($id);
        if (empty($data['cadastro']) || !$this->_tem_permissao($data['cadastro'])) {
            redirect('cadastro');
            return;
        }
        $data['titulo'] = 'Editar Cadastro';
        $data['form_action'] = site_url('cadastro/atualizar/' . $id); 
        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_form_view', $data);
        $this->load->view('includes/footer');
    }

    public function atualizar($id) {
        $this->_regras_validacao();
        if ($this->form_validation->run() == FALSE) {
            $this->editar($id);
        } else {
            $dados = $this->_preparar_dados();
            $this->cadastro_model->atualizar_cadastro($id, $dados);
            $this->session->set_flashdata('sucesso', 'Cadastro atualizado!');
            redirect('cadastro');
        }
    }

    public function deletar($id) {
        if ($this->session->userdata('nivel_acesso') === 'admin') {
            $this->cadastro_model->deletar_cadastro($id);
            $this->session->set_flashdata('sucesso', 'Registro excluído!');
        }
        redirect('cadastro');
    }

    private function _regras_validacao() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $id_atual = $this->uri->segment(3); 
        $is_unique = $id_atual ? '' : '|is_unique[cadastros.cpf]';
        $this->form_validation->set_rules('cpf', 'CPF', 'required' . $is_unique);
    }

    private function _preparar_dados() {
        return array(
            'nome'     => $this->input->post('nome'),
            'cpf'      => $this->input->post('cpf'),
            'cep'      => $this->input->post('cep'),
            'endereco' => $this->input->post('endereco'),
            'bairro'   => $this->input->post('bairro'),
            'cidade'   => $this->input->post('cidade')
        );
    }
        
    private function _tem_permissao($cadastro) {
        $is_admin = ($this->session->userdata('nivel_acesso') == 'admin');
        $is_owner = ($cadastro['usuario_id'] == $this->session->userdata('id_usuario'));
        return ($is_admin || $is_owner);
    }
}