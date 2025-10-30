<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pacientes_model extends CI_Model
{
	public function get_pacientes($query = '')
	{
		$this->db->select('p.ID_Paciente, CONCAT(p.Nome, " ", p.Sobrenome) AS Nome_Completo, p.Telefone, u.Email, p.Data_Nascimento, p.Genero, p.Endereco');
		$this->db->from('Pacientes p');
		$this->db->join('Usuarios u', 'p.ID_Usuario = u.ID_Usuario', 'left');  // LEFT JOIN: carrega mesmo sem usuário
		if ($query) {
			$this->db->group_start();
			$this->db->like('CONCAT(p.Nome, " ", p.Sobrenome)', $query);
			$this->db->or_like('p.ID_Paciente', $query);
			$this->db->or_like('u.Email', $query);  // Filtro por email também
			$this->db->group_end();
		}
		$this->db->order_by('CONCAT(p.Nome, " ", p.Sobrenome)', 'ASC');
		$result = $this->db->get()->result_array();
		log_message('debug', 'Pacientes com JOIN Email: ' . count($result) . ' | Query: ' . $this->db->last_query());
		return $result;
	}
    

    public function get_patient($bi)
    {
        $this->db->where('ID_Paciente', $bi);
        return $this->db->get('Pacientes')->row_array();
    }

    public function create_patient($data)
    {
        if ($this->db->insert('Pacientes', $data)) {
            return ['success' => 'Paciente criado com sucesso', 'id' => $this->db->insert_id()];
        } else {
            return ['error' => 'Erro ao criar: ' . $this->db->error()['message']];
        }
    }

    public function update_patient($bi, $data)
    {
        $this->db->where('ID_Paciente', $bi);
        if ($this->db->update('Pacientes', $data)) {
            return ['success' => 'Paciente atualizado com sucesso'];
        } else {
            return ['error' => 'Erro ao atualizar: ' . $this->db->error()['message']];
        }
    }

    public function delete_patient($bi)
    {
        $this->db->where('ID_Paciente', $bi);
        if ($this->db->delete('Pacientes')) {
            return ['success' => 'Paciente excluído com sucesso'];
        } else {
            return ['error' => 'Erro ao excluir: ' . $this->db->error()['message']];
        }
    }
}