<?php
class Agendamentos_model extends CI_Model {
    public function get_appointments($query = '') {
        $this->db->select('a.ID_Agendamento as id, p.Nome as patient_name, m.Nome as doctor_name, a.Data_Agendamento as date, a.Hora_Agendamento as time, a.Status as status');
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        if ($query) {
            $this->db->group_start();
            $this->db->like('p.Nome', $query);
            $this->db->or_like('m.Nome', $query);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_appointment($id) {
        $this->db->select('a.ID_Agendamento as id, a.ID_Paciente as patient_id, CONCAT(p.Nome, " ", p.Sobrenome) as patient_name, a.ID_Medico as doctor_id, CONCAT(m.Nome, " ", m.Sobrenome) as doctor_name, a.ID_Horario as horario_id, a.Data_Agendamento as date, a.Hora_Agendamento as time, a.Status as status, a.Motivo as motivo');
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        $this->db->where('a.ID_Agendamento', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_patients($query = '') {
        $this->db->select('ID_Paciente as id, CONCAT(Nome, " ", Sobrenome) as name, BI as bi');
        $this->db->from('Pacientes');
        if ($query) {
            $this->db->group_start();
            $this->db->like('CONCAT(Nome, " ", Sobrenome)', $query);
            $this->db->or_like('BI', $query);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_doctors($query = '') {
        $this->db->select('ID_Medico as id, CONCAT(Nome, " ", Sobrenome) as name, BI as bi, Especialidade as specialty');
        $this->db->from('Medicos');
        if ($query) {
            $this->db->group_start();
            $this->db->like('CONCAT(Nome, " ", Sobrenome)', $query);
            $this->db->or_like('BI', $query);
            $this->db->or_like('Especialidade', $query);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function create_appointment($data) {
        if ($this->db->insert('Agendamentos', $data)) {
            return ['success' => 'Agendamento criado com sucesso', 'id' => $this->db->insert_id()];
        } else {
            return ['error' => 'Erro ao criar agendamento: ' . $this->db->error()['message']];
        }
    }

    public function update_appointment($id, $data) {
        $this->db->where('ID_Agendamento', $id);
        if ($this->db->update('Agendamentos', $data)) {
            return ['success' => 'Agendamento atualizado com sucesso'];
        } else {
            return ['error' => 'Erro ao atualizar agendamento: ' . $this->db->error()['message']];
        }
    }

    public function delete_appointment($id) {
        $this->db->where('ID_Agendamento', $id);
        if ($this->db->delete('Agendamentos')) {
            return ['success' => 'Agendamento excluído com sucesso'];
        } else {
            return ['error' => 'Erro ao excluir agendamento: ' . $this->db->error()['message']];
        }
    }
}
