<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MODEL: Cadastro_model
 * Onde moram as consultas SQL(sem pagar aluguel). Se o dado não aparece ou vem errado, 
 * o primeiro lugar para olhar é aqui, isso se eu nao bagunçar o codigo daqui pra frente.
 */
class Cadastro_model extends CI_Model {

    // Nome da tabela definido aqui para facilitar. Se mudar o nome no banco, muda só aqui! (vou ter muito problema por que coloquei cadastro e usuarios sendo duas coisas diferentes, mas que parecem ser do mesmo lugar, good.
    protected $table = 'cadastros';

    /**
     * BUSCADOR DE CADASTROS (USADO NA LISTAGEM PRINCIPAL)
     * Esse aqui é o motor da tabela de início. Ele lida com paginação e filtros.
     * "Onde estão os corpos? Ah, estão aqui nos cadastros filtrados."
     */
    public function get_cadastros_filtrados($id_usuario = null, $data_filtro = null, $cidade = null, $limit = 10, $offset = 0, $sort_by = 'id', $sort_order = 'desc') {
        
        // Pega os dados do cadastro e o nome de quem cadastrou (JOIN)
        $this->db->select('cadastros.*, usuarios.nome as nome_cadastrador');
        $this->db->from($this->table);
        $this->db->join('usuarios', 'usuarios.id = cadastros.usuario_id', 'left');

        // Filtros: Só aplica se o usuário preencheu na tela
        if (!empty($id_usuario)) $this->db->where('cadastros.usuario_id', $id_usuario);
        if (!empty($cidade)) $this->db->where('cadastros.cidade', $cidade);
        if (!empty($data_filtro)) $this->db->where('DATE(cadastros.data_criacao)', $data_filtro);

        /**
         * GUARDA DE SEGURANÇA (Ordenação Dinâmica)
         * Aqui você bloqueia engraçadinhos que tentam injetar comandos SQL via URL. (eu nem testei)
         */
        $allowed_cols = array('id', 'nome', 'cidade', 'data_criacao');
        $sort_by = in_array($sort_by, $allowed_cols) ? $sort_by : 'id';
        $sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';

        $this->db->order_by($sort_by, $sort_order);
        
        // Paginação: 'limit' é quantos por página, 'offset' é a partir de qual registro. (aqui eu penei)
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result_array();
    }

    /**
     * CONTADOR DE REGISTROS
     * Serve para o sistema saber quantas páginas o rodapé da tabela deve mostrar.
     */
    public function contar_total_filtrado($id_usuario = null, $data_filtro = null, $cidade = null) {
        if (!empty($id_usuario)) $this->db->where('usuario_id', $id_usuario);
        if (!empty($cidade)) $this->db->where('cidade', $cidade);
        if (!empty($data_filtro)) $this->db->where('DATE(data_criacao)', $data_filtro);
        
        return $this->db->count_all_results($this->table);
    }

    /**
     * COLETOR E DE CIDADES
     * Busca os nomes das cidades para preencher os 'selects' dos filtros.
     * Ele garante que não venha cidade vazia ou nula.
     */
    public function get_cidades_distintas() {
        $this->db->select('cidade');
        $this->db->distinct(); // Não repete nomes (Ex: se tem 10 'João Pessoa', traz só 1)
        $this->db->from($this->table);
        $this->db->where('cidade IS NOT NULL AND cidade != ""');
        $this->db->order_by('cidade', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * SALVAR (CREATE)
     * "Entra um dado, sai uma esperança de que ele não se perca."
     */
    public function inserir_cadastro($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * BUSCAR UM SÓ (READ)
     * Usado quando você quer ver os detalhes de apenas UM cliente.
     */
    public function get_cadastro_por_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }

    /**
     * ATUALIZAR (UPDATE) (ACTUALIZAR) (για ενημέρωση)
     * O famoso 'salvar alterações'.
     */
    public function atualizar_cadastro($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * DELETAR (DELETE)
     * "Aperte F para prestar homenagens ao dado que se foi."
     */
    public function deletar_cadastro($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}