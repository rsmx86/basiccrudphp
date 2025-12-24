<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Modelos e bibliotecas básicos do módulo
        $this->load->model('cadastro_model');
        $this->load->model('usuario_model');
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Trava de segurança: Se não tiver sessão, volta pro login
        if (!$this->session->userdata('logado')) {
            redirect('auth');
        }
    }

    // Listagem principal com aplicação de filtros
   public function index() {
    $data['titulo'] = "Gestão de Cadastros";
    
    // Pegando parâmetros da URL para filtros (GET)
    $usuario_id  = $this->input->get('filtro_usuario');
    $data_filtro = $this->input->get('filtro_data');
    $cidade      = $this->input->get('cidade');

    // 1. O model de cadastros continua com a regra de permissão (Admin vê tudo / Comum vê o dele)
    $data['cadastros'] = $this->cadastro_model->get_cadastros_filtrados($usuario_id, $data_filtro, $cidade); 
    // Buscamos todos os usuários diretamente do banco para o filtro, ignorando a trava do Model
    $data['lista_usuarios'] = $this->db->get('usuarios')->result_array();

    $this->load->view('includes/header', $data);
    $this->load->view('cadastro_listagem_view', $data);
    $this->load->view('includes/footer');
}

    // Abre o formulário vazio para novo cadastro
    public function criar() {
        $data['titulo'] = 'Novo Cadastro';
        $data['form_action'] = site_url('cadastro/store'); 
        $this->load->view('includes/header', $data);
        $this->load->view('cadastro_form_view', $data);
        $this->load->view('includes/footer');
    }

    // Processa a inserção (POST)
    public function store() {
        $this->_regras_validacao();

        if ($this->form_validation->run() == FALSE) {
            $this->criar();
        } else {
            $dados = $this->_preparar_dados();
            
            // Dados automáticos: ID do autor e timestamp do banco
            $dados['usuario_id']   = $this->session->userdata('id_usuario');
            $dados['data_criacao'] = date('Y-m-d H:i:s'); 

            if ($this->cadastro_model->inserir_cadastro($dados)) {
                $this->session->set_flashdata('sucesso', 'Cadastro realizado com sucesso!');
            } else {
                $this->session->set_flashdata('erro', 'Falha ao salvar no banco.');
            }
            redirect('cadastro');
        }
    }

    // Abre formulário de edição já populado
    public function editar($id) {
        $data['cadastro'] = $this->cadastro_model->get_cadastro_por_id($id);

        // Se o ID não existir ou o usuário tentar acessar o de outro sem ser admin, bloqueia
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

    // Processa a atualização dos dados (POST)
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

    // Deleta o registro (Apenas Admin)
    public function deletar($id) {
        if ($this->session->userdata('nivel_acesso') === 'admin') {
            $this->cadastro_model->deletar_cadastro($id);
            $this->session->set_flashdata('sucesso', 'Registro excluído!');
        }
        redirect('cadastro');
    }

    /* --- MÉTODOS DE APOIO (AUXILIARES) --- */

    // Centraliza as regras do Form Validation para não repetir no store/atualizar
    private function _regras_validacao() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('cep', 'CEP', 'required');
        
        // Lógica de CPF Único: Se for edição, ignora o próprio ID. Se for novo, exige ser único.
        $id_atual = $this->uri->segment(3); 
        $is_unique = $id_atual ? '' : '|is_unique[cadastros.cpf]';
        $this->form_validation->set_rules('cpf', 'CPF', 'required' . $is_unique);
    }

    // Formata o array com o que vem do POST para mandar pro Model
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
        
    // Verifica se o cabra pode mexer no registro (ou é admin ou é o dono)
    private function _tem_permissao($cadastro) {
        $is_admin = ($this->session->userdata('nivel_acesso') == 'admin');
        $is_owner = ($cadastro['usuario_id'] == $this->session->userdata('id_usuario'));
        return ($is_admin || $is_owner);
    }
}