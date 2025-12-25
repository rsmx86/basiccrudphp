<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logado')) redirect('auth');
        $this->load->model('cadastro_model');
    }

    public function clientes() {
        $data['titulo'] = "Relatório de Clientes";
        $data['usuarios'] = $this->db->get('usuarios')->result_array(); 
        
        $this->db->select('distinct(cidade)');
        $data['cidades'] = $this->db->get('cadastros')->result_array();

        $this->load->view('includes/header', $data);
        $this->load->view('relatorio_filtro_view', $data);
        $this->load->view('includes/footer');
    }

    // FUNÇÃO CENTRAL QUE CONTROLA O QUE APARECE NO PDF, CSV E PREVIEW
    private function _aplicar_filtros_comuns() {
        $usuario_id  = $this->input->get('filtro_usuario');
        $cidade      = $this->input->get('cidade');
        $data_inicio = $this->input->get('data_inicio');
        $data_fim    = $this->input->get('data_fim');

        $this->db->select('c.*, u.nome as cadastrado_por');
        $this->db->from('cadastros c');
        $this->db->join('usuarios u', 'u.id = c.usuario_id', 'left');

        // REMOVIDA A TRAVA DE USUÁRIO COMUM: Agora todos podem filtrar por qualquer usuário
        if (!empty($usuario_id)) {
            $this->db->where('c.usuario_id', $usuario_id);
        }

        if (!empty($cidade)) $this->db->where('c.cidade', $cidade);
        
        // Filtro de Período
        if (!empty($data_inicio)) $this->db->where('DATE(c.data_criacao) >=', $data_inicio);
        if (!empty($data_fim)) $this->db->where('DATE(c.data_criacao) <=', $data_fim);
    }
    
    public function preview() {
        // Usa a mesma lógica de filtros do PDF/CSV
        $this->_aplicar_filtros_comuns();
        $this->db->limit(15); // Limita a prévia para ser rápido
        $this->db->order_by('c.id', 'DESC');
        
        $data['cadastros'] = $this->db->get()->result_array();

        // Carrega a view parcial da tabela
        $this->load->view('relatorio_preview_tabela', $data);
    }

    public function gerar_pdf() {
        $this->_aplicar_filtros_comuns();
        $data['cadastros'] = $this->db->get()->result_array();
        
        // Dados extras para o cabeçalho do PDF
        $data['usuarios'] = $this->db->get('usuarios')->result_array(); 

        $this->load->library('pdf');
        $html = $this->load->view('relatorio_pdf_view', $data, true);
        $this->pdf->generate($html, "Relatorio_Gestao_" . date('Ymd'));
    }

    public function gerar_csv() {
        $this->_aplicar_filtros_comuns();
        $resultados = $this->db->get()->result_array();

        $cidade_filtro = $this->input->get('cidade') ? mb_strtoupper($this->input->get('cidade')) : 'TODAS';
        $data_ini = $this->input->get('data_inicio') ? date('d/m/Y', strtotime($this->input->get('data_inicio'))) : 'INICIO';
        $data_fim = $this->input->get('data_fim') ? date('d/m/Y', strtotime($this->input->get('data_fim'))) : date('d/m/Y');
        
        $filename = "Relatorio_Gestao_".date('Ymd').".csv";
        header("Content-Type: application/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");

        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

        fputcsv($file, array('RELATORIO DE CADASTROS'), ';');
        fputcsv($file, array('FILTRO CIDADE:', $cidade_filtro), ';');
        fputcsv($file, array('PERIODO:', $data_ini . ' A ' . $data_fim), ';');
        fputcsv($file, array('TOTAL DE REGISTROS:', count($resultados)), ';');
        fputcsv($file, array(''), ';');

        fputcsv($file, array('ID', 'NOME', 'CPF', 'CIDADE', 'BAIRRO', 'CADASTRADO POR', 'DATA'), ';');

        foreach ($resultados as $row) {
            fputcsv($file, array(
                $row['id'],
                mb_strtoupper($row['nome']),
                $row['cpf'],
                mb_strtoupper($row['cidade']),
                mb_strtoupper($row['bairro']),
                $row['cadastrado_por'],
                date('d/m/Y H:i', strtotime($row['data_criacao']))
            ), ';');
        }

        fclose($file);
        exit;
    }
}