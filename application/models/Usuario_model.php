<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    protected $table = 'usuarios';

    public function verificar_login($email, $senha) {
        $this->db->where('email', $email);
        $user = $this->db->get($this->table)->row();
        
        if ($user && password_verify($senha, $user->senha)) {
            return $user;
        }
        return false;
    }

    public function inserir_usuario($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_all_users() {
        // Buscamos os usuários e o nome de quem os criou (Join)
        $this->db->select('u.*, c.nome as nome_criador');
        $this->db->from('usuarios u');
        $this->db->join('usuarios c', 'c.id = u.admin_criador_id', 'left');
        
        // Esconde o próprio admin logado para evitar auto-exclusão
        $this->db->where('u.id !=', $this->session->userdata('id_usuario')); 
        
        return $this->db->order_by('u.nome', 'ASC')->get()->result_array();
    }

    public function get_user_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function atualizar_usuario($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function excluir_usuario_com_custodia($id_excluir, $id_admin_atual) {
        $this->db->trans_start();

        // 1. Move os cadastros do usuário que será deletado para o admin que está deletando
        $this->db->where('usuario_id', $id_excluir);
        $this->db->update('cadastros', ['usuario_id' => $id_admin_atual]);

        // 2. Apaga o sujeito
        $this->db->where('id', $id_excluir);
        $this->db->delete($this->table);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}