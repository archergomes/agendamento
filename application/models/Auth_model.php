<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Carrega a conexão DB
    }

    /**
     * Insere um novo paciente e retorna o ID gerado
     */
    public function insert_paciente($data)
    {
        $this->db->insert('pacientes', $data);
        return $this->db->insert_id(); // Retorna o ID do paciente (assumindo coluna 'id')
    }

    /**
     * Insere um novo usuário e retorna o ID gerado
     */
    public function insert_usuario($data)
    {
        $this->db->insert('usuarios', $data);
        return $this->db->insert_id(); // Retorna Id_Usuario
    }

    /**
     * Busca usuário por email (usado no login)
     */
    public function get_usuario_by_email($email)
    {
        $this->db->select('Id_Usuario, email, Senha, tipo_usuario, id_referencia');  // Use 'Senha' com S maiúsculo
        $this->db->where('email', $email);
        $query = $this->db->get('usuarios');
        return $query->row(); // Retorna objeto com ->Senha
    }

    /**
     * Busca dados do paciente via ID de referência (usado no dashboard/agenda)
     */
    public function get_paciente_by_id_referencia($id_referencia)
    {
        $this->db->where('Id_Paciente', $id_referencia); // Assumindo 'id' em pacientes
        $query = $this->db->get('pacientes');
        return $query->row();
    }

    /**
     * Deleta paciente por ID (para rollback)
     */
    public function delete_paciente($id)
    {
        $this->db->where('Id_Paciente', $id); // Assumindo 'id' em pacientes
        return $this->db->delete('pacientes');
    }

    /**
     * Opcional: Atualiza usuário (ex: trocar senha)
     */
    public function update_usuario($id_usuario, $data)
    {
        $this->db->where('Id_Usuario', $id_usuario); // Usa Id_Usuario
        return $this->db->update('usuarios', $data);
    }

    public function get_paciente_id_by_usuario($id_usuario)
    {
        $this->db->select('p.ID_Paciente');
        $this->db->from('pacientes p');
        $this->db->join('usuarios u', 'u.id_referencia = p.ID_Paciente');
        $this->db->where('u.Id_Usuario', $id_usuario);
        $query = $this->db->get();
        return $query->row() ? $query->row()->ID_Paciente : null;
    }

    // Buscar paciente por ID
    public function get_paciente_by_id($paciente_id)
    {
        return $this->db->get_where('pacientes', array('ID_Paciente' => $paciente_id))->row();
    }

    // Atualizar dados do paciente
    public function update_patient($paciente_id, $data)
    {
        $this->db->where('ID_Paciente', $paciente_id);
        return $this->db->update('pacientes', $data);
    }

    // Buscar usuário por referência (para email)
    public function get_user_by_reference($id_referencia)
    {
        return $this->db->get_where('usuarios', array('id_referencia' => $id_referencia))->row();
    }

    // Atualizar email do usuário
    public function update_user_email($user_id, $email)
    {
        $this->db->where('Id_Usuario', $user_id);
        return $this->db->update('usuarios', array('email' => $email));
    }
}
