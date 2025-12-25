<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CONTROLLER: Relatorios
 * Este arquivo é o "Cérebro (cabeçudo)" dos relatórios. Ele recebe os filtros da tela, 
 * vai no banco de dados e decide se vai gerar um PDF, um CSV ou uma prévia, bla bla bla.
 */
class Relatorios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // SEGURANÇA: Se não tiver logado, manda pro login. "Xô, curioso!"
        if (!$this->session->userdata('logado')) redirect('auth');
        
        // Carrega o ajudante de banco de dados
        $this->load->model('cadastro_model');
    }

    /**
     * TELA INICIAL DE FILTROS
     * Caminho: site.com/index.php/relatorios/clientes
     */
    public function clientes() {
        $data['titulo'] = "Relatório de Clientes";
        
        // Puxa todos os usuários para o "select" de filtros
        $data['usuarios'] = $this->db->get('usuarios')->result_array(); 
        
        // Puxa cidades únicas para o "select" (não queremos cidades repetidas na lista)
        $this->db->select('distinct(cidade)');
        $data['cidades'] = $this->db->get('cadastros')->result_array();

        // Monta a página (Header + Miolo + Footer)
        $this->load->view('includes/header', $data);
        $this->load->view('relatorio_filtro_view', $data);
        $this->load->view('includes/footer');
    }

    /**
     * O MOTOR DE BUSCA (_aplicar_filtros_comuns)
     * Este método é 'private' porque ele só funciona aqui dentro. 
     * Ele evita que a gente tenha que escrever os filtros 3 vezes (PDF, CSV e Preview).
	 * Se a gente for adicionar mais colunas no filtro a gente só adiciona aqui, mais facil, menos trabalho
     */
    private function _aplicar_filtros_comuns() {
        // Pega o que o usuário digitou na tela de filtros
        $usuario_id  = $this->input->get('filtro_usuario');
        $cidade      = $this->input->get('cidade');
        $data_inicio = $this->input->get('data_inicio');
        $data_fim    = $this->input->get('data_fim');

        // Prepara a consulta SQL (O famoso SELECT * FROM ...) o db->join aqui é a conexao entra as tableas, lembrando que o relatorio precisa do nome
        $this->db->select('c.*, u.nome as cadastrado_por');
        $this->db->from('cadastros c');
        $this->db->join('usuarios u', 'u.id = c.usuario_id', 'left');

        // Se escolheu usuário, filtra. Se não, traz todos.
        if (!empty($usuario_id)) {
            $this->db->where('c.usuario_id', $usuario_id);
        }

        if (!empty($cidade)) $this->db->where('c.cidade', $cidade);
        
        // Filtro de Datas: "De tal dia até tal dia"
        if (!empty($data_inicio)) $this->db->where('DATE(c.data_criacao) >=', $data_inicio);
        if (!empty($data_fim)) $this->db->where('DATE(c.data_criacao) <=', $data_fim);
    }
    
    /**
     * VISUALIZAÇÃO RÁPIDA (PREVIEW)
     * É chamada via JavaScript (Fetch) sem recarregar a página.
     */
    public function preview() {
        $this->_aplicar_filtros_comuns(); // Liga o motor de busca
        $this->db->limit(15); // "Só mostra os 15 primeiros pra não travar tudo ou ficar feio no layout, vou aumentar ou diminuir dependendo."
        $this->db->order_by('c.id', 'DESC');
        
        $data['cadastros'] = $this->db->get()->result_array();

        // Manda apenas a tabela para a tela, não a página inteira.
        $this->load->view('relatorio_preview_tabela', $data);
    }

    /**
     * GERADOR DE PDF
     * Usa a biblioteca 'dompdf' para transformar HTML em pdf
     */
    public function gerar_pdf() {
        $this->_aplicar_filtros_comuns(); // Liga o motor de busca
        $data['cadastros'] = $this->db->get()->result_array();
        
        $data['usuarios'] = $this->db->get('usuarios')->result_array(); 

        // Carrega a biblioteca 'DOMPDF' (provavelmente)
        $this->load->library('pdf');
        
        // Transforma a View em uma string (texto) para o PDF entender
        $html = $this->load->view('relatorio_pdf_view', $data, true);
        
        // Gera e baixa o arquivo com a data de hoje no nome
        $this->pdf->generate($html, "Relatorio_Gestao_" . date('Ymd'));
    }

    /**
     * GERADOR DE EXCEL/CSV
     * sem HTML. Escrevemos linha por linha direto no arquivo.
     */
    public function gerar_csv() {
        $this->_aplicar_filtros_comuns(); // Liga o motor de busca
        $resultados = $this->db->get()->result_array();

        // Organiza as datas para aparecerem bonitas no cabeçalho do Excel, espero.
        $cidade_filtro = $this->input->get('cidade') ? mb_strtoupper($this->input->get('cidade')) : 'TODAS';
        $data_ini = $this->input->get('data_inicio') ? date('d/m/Y', strtotime($this->input->get('data_inicio'))) : 'INICIO';
        $data_fim = $this->input->get('data_fim') ? date('d/m/Y', strtotime($this->input->get('data_fim'))) : date('d/m/Y');
        
        $filename = "Relatorio_Gestao_".date('Ymd').".csv";
        
        // Avisa o navegador que isso é um arquivo para baixar, não uma página. será?
        header("Content-Type: application/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");

        // Abre a "caneta" para escrever o arquivo
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Correção para o Excel entender acentos (BOM)

        // Escreve o cabeçalho do relatório
        fputcsv($file, array('RELATORIO DE CADASTROS'), ';');
        fputcsv($file, array('FILTRO CIDADE:', $cidade_filtro), ';');
        fputcsv($file, array('PERIODO:', $data_ini . ' A ' . $data_fim), ';');
        fputcsv($file, array('TOTAL DE REGISTROS:', count($resultados)), ';');
        fputcsv($file, array(''), ';');

        // Títulos das colunas, tive erro de fechamento aqui 
        fputcsv($file, array('ID', 'NOME', 'CPF', 'CIDADE', 'BAIRRO', 'CADASTRADO POR', 'DATA'), ';');

        // Loop: Pega cada cliente do banco e joga uma linha no Excel
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

        fclose($file); // "Fecha a caneta"
        exit; // Para o sistema aqui (não queremos que ele tente carregar mais nada) (e se carregar paciencia)
    }
}