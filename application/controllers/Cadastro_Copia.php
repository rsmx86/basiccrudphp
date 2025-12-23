<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro extends CI_Controller { // <--- CLASSE 'Cadastro'

    public function __construct() {
        parent::__construct();
        $this->load->model('cadastro_model');
        
        // Middleware de Autenticação
        if (!$this->session->userdata('logado')) {
            redirect('auth');
        }
    }

    public function index() {
    $id_user = $this->input->get('filtro_usuario');
    $data_f = $this->input->get('filtro_data');

    $data['cadastros'] = $this->cadastro_model->get_cadastros_filtrados($id_user, $data_f);
    
    // CORREÇÃO AQUI: 
    // Em vez de usar get_all_users() que filtra o logado, 
    // vamos buscar direto da tabela para o Admin ver todo mundo no filtro.
    $data['lista_usuarios'] = $this->db->get('usuarios')->result_array();

    $data['titulo'] = "Gestão de Cadastros";
    $this->load->view('cadastro_listagem_view', $data);
}
    
    public function criar() {
        $data['titulo'] = 'Novo Cadastro';
        $this->load->view('cadastro_form_view', $data);
    }
    
    // MÉTODO JÁ EXISTENTE (STORE)
    public function store() {
        // ... (código do store) ...
        $this->load->library('form_validation'); 

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['titulo'] = 'Novo Cadastro';
            $this->load->view('cadastro_form_view', $data);
        } else {
            $data = array(
                'nome' => $this->input->post('nome'),
                'endereco' => $this->input->post('endereco'),
                'bairro' => $this->input->post('bairro'),
                'usuario_id' => $this->session->userdata('id_usuario') // Garantindo que o ID do cadastrador vai junto
            );
            
            if ($this->cadastro_model->inserir_cadastro($data)) {
                $this->session->set_flashdata('sucesso', 'Cadastro realizado com sucesso!');
            } else {
                $this->session->set_flashdata('erro', 'Erro ao cadastrar.');
            }
            
            redirect('cadastro'); 
        }
    }
    
    // NOVO MÉTODO: CARREGAR PARA EDIÇÃO (EDITAR)
    public function editar($id) {
        // 1. Busca os dados do registro pelo ID
        $data['cadastro'] = $this->cadastro_model->get_cadastro_por_id($id);

        // 2. Verifica se o registro existe
        if (empty($data['cadastro'])) {
            show_404(); 
            return;
        }
        
        // 3. Regra de segurança: Usuário comum SÓ pode editar o que cadastrou
        if ($this->session->userdata('nivel_acesso') != 'admin' && 
            $data['cadastro']['usuario_id'] != $this->session->userdata('id_usuario')) {
            
            $this->session->set_flashdata('erro', 'Você não tem permissão para editar este registro.');
            redirect('cadastro');
            return;
        }

        $data['titulo'] = 'Editar Cadastro: ' . $data['cadastro']['nome'];
        
        // 4. Carrega o formulário
        $this->load->view('cadastro_form_view', $data);
    }
	// Dentro de application/controllers/Cadastro.php

// NOVO MÉTODO: 5. Função para processar e salvar a edição (UPDATE - POST)
	public function atualizar($id) {
    $this->load->library('form_validation'); 

    // 1. Regras de validação (As mesmas do STORE)
    $this->form_validation->set_rules('nome', 'Nome', 'required');
    $this->form_validation->set_rules('endereco', 'Endereço', 'required');
    $this->form_validation->set_rules('bairro', 'Bairro', 'required');
		

    if ($this->form_validation->run() == FALSE) {
        // Falha na validação: recarrega a tela de edição
        
        // *Rebuscando os dados para recarregar o formulário com o estado anterior*
        $data['cadastro'] = $this->cadastro_model->get_cadastro_por_id($id);
        $data['titulo'] = 'Editar Cadastro: ' . $data['cadastro']['nome'];
        
        $this->load->view('cadastro_form_view', $data);
    } else {
        // Sucesso na validação:
        
        // 2. Coleta de dados
        $dados_atualizados = array(
            'nome' => $this->input->post('nome'),
            'endereco' => $this->input->post('endereco'),
            'bairro' => $this->input->post('bairro')
        );
        
        // 3. Salva no banco de dados via Model
        if ($this->cadastro_model->atualizar_cadastro($id, $dados_atualizados)) {
            $this->session->set_flashdata('sucesso', 'Cadastro atualizado com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao atualizar. Tente novamente.');
        }
        
        // 4. Redireciona para a listagem
        redirect('cadastro'); 
    }
	
} 
	// -------------------
	// Função para excluir um registro (DELETE)
public function deletar($id) {
    // 1. Busca os dados para aplicar a regra de segurança
    $cadastro = $this->cadastro_model->get_cadastro_por_id($id);
    
    // 2. Verifica se o registro existe
    if (empty($cadastro)) {
        $this->session->set_flashdata('erro', 'Registro não encontrado.');
        redirect('cadastro');
        return;
    }
    
    // 3. Regra de segurança: SÓ o admin ou o próprio cadastrador podem excluir
    if ($this->session->userdata('nivel_acesso') != 'admin' && 
        $cadastro['usuario_id'] != $this->session->userdata('id_usuario')) {
        
        $this->session->set_flashdata('erro', 'Você não tem permissão para excluir este registro.');
        redirect('cadastro');
        return;
    }

    // 4. Executa a exclusão
    if ($this->cadastro_model->deletar_cadastro($id)) {
        $this->session->set_flashdata('sucesso', 'Cadastro excluído com sucesso!');
    } else {
        $this->session->set_flashdata('erro', 'Erro ao excluir o cadastro.');
    }
    
    // 5. Redireciona para a listagem
    redirect('cadastro');
}

} 