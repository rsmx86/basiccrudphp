<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro_model extends CI_Model {

    protected $table = 'cadastros';

    public function get_cadastros_filtrados($id_usuario = null, $data_filtro = null, $cidade = null, $limit = 10, $offset = 0) {
        $this->db->select('cadastros.*, usuarios.nome as nome_cadastrador');
        $this->db->from($this->table);
        $this->db->join('usuarios', 'usuarios.id = cadastros.usuario_id', 'left');

        if (!empty($id_usuario)) $this->db->where('cadastros.usuario_id', $id_usuario);
        if (!empty($cidade)) $this->db->where('cadastros.cidade', $cidade);
        if (!empty($data_filtro)) $this->db->where('DATE(cadastros.data_criacao)', $data_filtro);

        $this->db->limit($limit, $offset);
        $this->db->order_by('cadastros.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function contar_total_filtrado($id_usuario = null, $data_filtro = null, $cidade = null) {
        if (!empty($id_usuario)) $this->db->where('usuario_id', $id_usuario);
        if (!empty($cidade)) $this->db->where('cidade', $cidade);
        if (!empty($data_filtro)) $this->db->where('DATE(data_criacao)', $data_filtro);
        
        return $this->db->count_all_results($this->table);
    }

    public function get_cidades_distintas() {
        $this->db->select('cidade');
        $this->db->distinct();
        $this->db->from($this->table);
        $this->db->where('cidade IS NOT NULL AND cidade != ""');
        $this->db->order_by('cidade', 'ASC');
        return $this->db->get()->result_array();
    }

    public function inserir_cadastro($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_cadastro_por_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }

    public function atualizar_cadastro($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function deletar_cadastro($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}