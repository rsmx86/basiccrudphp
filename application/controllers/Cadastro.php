<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Carrega os recursos necessários para o controller funcionar
        $this->load->model('cadastro_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('url'); // ESSENCIAL para o site_url() funcionar
        $this->load->helper('form');

        // Proteção de login
        if (!$this->session->userdata('logado')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['titulo'] = "Gestão de Cadastros";
        
        // 1. FILTROS E PAGINAÇÃO
        $usuario_id  = $this->input->get('filtro_usuario');
        $data_filtro = $this->input->get('filtro_data');
        $cidade      = $this->input->get('cidade');
        $limit       = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $offset      = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 0;

        // 2. LÓGICA DE ORDENAÇÃO
        $sort_by    = $this->input->get('sort_by') ? $this->input->get('sort_by') : 'id';
        $sort_order = $this->input->get('sort_order') ? $this->input->get('sort_order') : 'desc';

        // 3. CONFIGURAÇÃO DA PAGINAÇÃO
        $config['base_url']             = site_url('cadastro/index');
        $config['total_rows']           = $this->cadastro_model->contar_total_filtrado($usuario_id, $data_filtro, $cidade);
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['reuse_query_string']   = TRUE;
        $this->pagination->initialize($config);

        // 4. BUSCA COM ORDENAÇÃO
        $data['cadastros'] = $this->cadastro_model->get_cadastros_filtrados(
            $usuario_id, $data_filtro, $cidade, $limit, $offset, $sort_by, $sort_order
        );
        
        $data['paginacao'] = $this->pagination->create_links();
        $data['total_registros'] = $config['total_rows'];
        $data['itens_por_pagina'] = $limit;
        $data['lista_usuarios'] = $this->db->get('usuarios')->result_array();
        $data['lista_cidades'] = $this->cadastro_model->get_cidades_distintas();

        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_listagem_view', $data);
        $this->load->view('includes/footer');
    }

    public function criar() {
        $data['titulo'] = 'Novo Cadastro';
        $data['form_action'] = 'cadastro/store'; 
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
                $this->session->set_flashdata('sucesso', 'Cadastro realizado com sucesso!');
            }
            redirect('cadastro');
        }
    }

    public function editar($id) {
        $data['cadastro'] = $this->cadastro_model->get_cadastro_por_id($id);
        
        if (empty($data['cadastro']) || !$this->_tem_permissao($data['cadastro'])) {
            $this->session->set_flashdata('erro', 'Registro não encontrado ou acesso negado.');
            redirect('cadastro');
            return;
        }

        $data['titulo'] = 'Editar Cadastro';
        $data['form_action'] = 'cadastro/atualizar/' . $id; 
        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_form_view', $data);
        $this->load->view('includes/footer');
    }

    public function atualizar($id) {
        $cadastro = $this->cadastro_model->get_cadastro_por_id($id);
        if (!$this->_tem_permissao($cadastro)) {
            redirect('cadastro');
            return;
        }

        $this->_regras_validacao(true);

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
        } else {
            $this->session->set_flashdata('erro', 'Acesso negado.');
        }
        redirect('cadastro');
    }

    private function _regras_validacao($edicao = false) {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('cpf', 'CPF', 'required');
    }

    private function _preparar_dados() {
        return array(
            'nome'     => mb_strtoupper($this->input->post('nome')),
            'cpf'      => $this->input->post('cpf'),
            'cep'      => $this->input->post('cep'),
            'endereco' => mb_strtoupper($this->input->post('endereco')),
            'bairro'   => mb_strtoupper($this->input->post('bairro')),
            'cidade'   => mb_strtoupper($this->input->post('cidade'))
        );
    }

    private function _tem_permissao($cadastro) {
        $nivel = $this->session->userdata('nivel_acesso');
        $id_logado = $this->session->userdata('id_usuario');
        return ($nivel === 'admin' || $cadastro['usuario_id'] == $id_logado);
    }
}