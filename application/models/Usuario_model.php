<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    // 
    public function verificar_login($email, $senha) {
        // Busca o usuário pelo email
        $this->db->where('email', $email);
        $query = $this->db->get('usuarios');
        
        if ($query->num_rows() == 1) {
            $usuario = $query->row();
            
            // Verifica a senha digitada ($senha) contra a hash salva no banco ($usuario->senha)
            if (password_verify($senha, $usuario->senha)) { 
                return $usuario; // Retorna os dados do usuário se a senha estiver correta
            }
        }
        return false; // Retorna falso se o email não existir ou a senha estiver errada
    }

    //  FUNÇÃO DE CRIAÇÃO (Para uso do Admin)
    public function inserir_usuario($data) {
        // Insere o novo usuário (com a senha já criptografada pelo Controller)
        return $this->db->insert('usuarios', $data);
    }
	// 

	// 3. FUNÇÃO DE LISTAGEM
	public function get_all_users() {
    
    // Seleciona todos os campos da tabela 'usuarios' e renomeia o nome do criador
    $this->db->select('usuarios.*, criador.nome as nome_criador');
    
    // JOIN com a própria tabela 'usuarios', usando 'criador' como ALIAS
    // Conecta o admin_criador_id da linha atual com o ID da linha do criador
    $this->db->join('usuarios as criador', 'criador.id = usuarios.admin_criador_id', 'left');
    
    // FILTRO: Não listar o próprio usuário logado (opcional, mas recomendado)
    $this->db->where('usuarios.id !=', $this->session->userdata('id_usuario')); 
    
    $this->db->order_by('usuarios.nome', 'ASC');
    
    // Retorna a lista completa com o nome do criador
    return $this->db->get('usuarios')->result_array();
}
	public function get_user_by_id($id) {
    $this->db->where('id', $id);
    return $this->db->get('usuarios')->row_array();
}

// 5. FUNÇÃO PARA ATUALIZAR DADOS DO USUÁRIO
public function atualizar_usuario($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('usuarios', $data);
}
}