<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro_model extends CI_Model {
    
    // 1. FUNÇÃO DE INSERÇÃO (CREATE)
    public function inserir_cadastro($data) {
        $data['usuario_id'] = $this->session->userdata('id_usuario');
        return $this->db->insert('cadastros', $data);
    }

    // 2. FUNÇÕES DE LISTAGEM (READ) - AGORA COM JOIN PARA PEGAR O NOME
    
    // Lista TODOS os registros (Para Admin)
    public function get_all_cadastros() {
        // Seleciona todos os campos de 'cadastros' e o 'nome' de 'usuarios' apelidado como 'nome_cadastrador'
        $this->db->select('cadastros.*, usuarios.nome as nome_cadastrador');
        
        // LEFT JOIN usuarios ON usuarios.id = cadastros.usuario_id
        $this->db->join('usuarios', 'usuarios.id = cadastros.usuario_id', 'left');
        
        $this->db->order_by('cadastros.id', 'DESC'); 
        return $this->db->get('cadastros')->result_array();
    }

    // Lista apenas os registros do usuário logado
    public function get_cadastros_por_usuario() {
        $id_usuario = $this->session->userdata('id_usuario');
        
        // Seleciona campos e faz o JOIN
        $this->db->select('cadastros.*, usuarios.nome as nome_cadastrador');
        $this->db->join('usuarios', 'usuarios.id = cadastros.usuario_id', 'left');
        
        // Filtra apenas os registros criados por este usuário
        $this->db->where('cadastros.usuario_id', $id_usuario);
        
        $this->db->order_by('cadastros.id', 'DESC');
        return $this->db->get('cadastros')->result_array();
    }
    
    // 3. FUNÇÃO DE BUSCA PARA EDIÇÃO/EXCLUSÃO (READ SINGLE)
    public function get_cadastro_por_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('cadastros')->row_array(); 
    }

    // 4. FUNÇÃO DE ATUALIZAÇÃO (UPDATE)
    public function atualizar_cadastro($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('cadastros', $data);
    }

    // 5. FUNÇÃO DE EXCLUSÃO (DELETE)
    public function deletar_cadastro($id) {
        $this->db->where('id', $id);
        return $this->db->delete('cadastros');
    }
}