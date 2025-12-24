<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Segurança: se não estiver logado, volta para o login
        if (!$this->session->userdata('logado')) redirect('auth');
        
        $this->load->model('cadastro_model');
        $this->load->model('usuario_model');
    }

    public function clientes() {
        $data['titulo'] = "Relatório de Clientes";

        // Busca usuários para o select do filtro
        $data['usuarios'] = $this->db->get('usuarios')->result_array(); 
        
        // Busca cidades únicas para o filtro
        $this->db->select('distinct(cidade)');
        $data['cidades'] = $this->db->get('cadastros')->result_array();

        $this->load->view('includes/header', $data);
        $this->load->view('relatorio_filtro_view', $data);
        $this->load->view('includes/footer');
    }

    public function gerar_pdf() {
        // 1. Captura os filtros da URL
        $usuario_id = $this->input->get('filtro_usuario');
        $cidade     = $this->input->get('cidade');
        $data_filtro = $this->input->get('filtro_data');

        // 2. Monta a Query com JOIN para pegar o nome do usuário que cadastrou
        $this->db->select('c.*, u.nome as cadastrado_por');
        $this->db->from('cadastros c');
        $this->db->join('usuarios u', 'u.id = c.usuario_id', 'left');

        // Regra de segurança: Se não for admin, vê apenas os seus próprios cadastros
        if ($this->session->userdata('nivel_acesso') != 'admin') {
            $this->db->where('c.usuario_id', $this->session->userdata('usuario_id'));
        } elseif (!empty($usuario_id)) {
            $this->db->where('c.usuario_id', $usuario_id);
        }

        if (!empty($cidade)) $this->db->where('c.cidade', $cidade);
        if (!empty($data_filtro)) $this->db->where('DATE(c.data_cadastro)', $data_filtro);

        $data['cadastros'] = $this->db->get()->result_array();

        // 3. Carrega a biblioteca PDF
        $this->load->library('pdf');
        
        // Transforma a view em string para o Dompdf processar
        $html = $this->load->view('relatorio_pdf_view', $data, true);

        // 4. Gera o arquivo
        $this->pdf->generate($html, "Relatorio_Gestão_" . date('Ymd'));
    }
}