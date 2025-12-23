<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro_model extends CI_Model {

    protected $table = 'cadastros';

    // Salva novo registro. Se o controller esquecer o usuario_id, pegamos da sessão.
    public function inserir_cadastro($data) {
        if (!isset($data['usuario_id'])) {
            $data['usuario_id'] = $this->session->userdata('id_usuario');
        }
        return $this->db->insert($this->table, $data);
    }

    /**
     * BUSCA COM FILTROS E SEGURANÇA
     * Essa função centraliza todas as listagens do sistema.
     * Ela já aplica a trava: Admin vê tudo, Comum vê só o dele.
     */
    public function get_cadastros_filtrados($id_usuario = null, $data_filtro = null, $cidade = null) {
        // Seleciona campos e traz o nome do dono do registro via JOIN
        $this->db->select('cadastros.*, usuarios.nome as nome_cadastrador');
        $this->db->from($this->table);
        $this->db->join('usuarios', 'usuarios.id = cadastros.usuario_id', 'left');

        // Filtros dinâmicos (vêm do formulário de busca)
        if (!empty($id_usuario)) {
            $this->db->where('cadastros.usuario_id', $id_usuario);
        }

        if (!empty($data_filtro)) {
            $this->db->where("DATE(cadastros.data_criacao) =", $data_filtro);
        }

        if (!empty($cidade)) {
            $this->db->like('cadastros.cidade', $cidade);
        }

        // TRAVA DE PERMISSÃO: Se não for admin, filtra pelo usuário logado
        if ($this->session->userdata('nivel_acesso') != 'admin') {
            $this->db->where('cadastros.usuario_id', $this->session->userdata('id_usuario'));
        }

        $this->db->order_by('cadastros.id', 'DESC');
        return $this->db->get()->result_array();
    }

    // Busca um único registro para edição ou visualização
    public function get_cadastro_por_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }

    // Atualiza os dados de um ID específico
    public function atualizar_cadastro($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Remove do banco (Lembrar que o Controller só libera isso para Admin)
    public function deletar_cadastro($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}