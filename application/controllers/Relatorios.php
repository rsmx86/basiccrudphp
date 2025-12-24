<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logado')) redirect('auth');
        $this->load->model('cadastro_model');
        $this->load->model('usuario_model');
    }

    public function clientes() {
    $data['titulo'] = "Relatório de Clientes";

    // Em vez de usar o método do model que tem a trava de segurança,
    // fazemos uma busca limpa apenas para o preenchimento do filtro.
    $data['usuarios'] = $this->db->get('usuarios')->result_array(); 
    
    // Pegar cidades únicas cadastradas para o filtro
    $this->db->select('distinct(cidade)');
    $data['cidades'] = $this->db->get('cadastros')->result_array();

    $this->load->view('includes/header', $data);
    $this->load->view('relatorio_filtro_view', $data);
    $this->load->view('includes/footer');
}
	
	public function gerar_pdf() {
		$cidade = $this->input->get('cidade');
		$usuario_id = $this->input->get('usuario_id');

		$this->db->select('c.*, u.nome as cadastrado_por');
		$this->db->from('cadastros c');
    
    // Mudamos de admin_criador_id para usuario_id conforme a sua imagem
		$this->db->join('usuarios u', 'u.id = c.usuario_id', 'left');

    if (!empty($cidade)) {
        $this->db->where('c.cidade', $cidade);
    }

    if (!empty($usuario_id)) {
        $this->db->where('c.usuario_id', $usuario_id);
    }

    $data['resultados'] = $this->db->get()->result_array();
    
    $this->load->view('teste_relatorio_view', $data);
}
}