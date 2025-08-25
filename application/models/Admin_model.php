<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_metrics()
    {
        $today = date('Y-m-d');
        $next_week = date('Y-m-d', strtotime('+7 days'));

        $total_patients = $this->db->count_all('Pacientes');
        $total_doctors = $this->db->count_all('Medicos');
        $this->db->where('Data_Agendamento >=', $today);
        $this->db->where('Data_Agendamento <=', $next_week);
        $this->db->where('Status !=', 'Cancelado');
        $upcoming_appointments = $this->db->count_all_results('Agendamentos');

        return [
            'total_patients' => $total_patients,
            'total_doctors' => $total_doctors,
            'upcoming_appointments' => $upcoming_appointments
        ];
    }

    public function get_activities($search_query = '')
    {
        $activities = [];

        $this->db->select("a.ID_Agendamento, a.Data_Agendamento, a.Hora_Agendamento, a.Status, 
                          CONCAT(p.Nome, ' ', p.Sobrenome) AS Nome_Paciente, 
                          CONCAT(m.Nome, ' ', m.Sobrenome) AS Nome_Medico, p.ID_Paciente");
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        $this->db->where('a.Status !=', 'Cancelado');
        if ($search_query) {
            $this->db->group_start();
            $this->db->like("CONCAT(p.Nome, ' ', p.Sobrenome)", $search_query);
            $this->db->or_like('m.Nome', $search_query);
            $this->db->or_where('p.ID_Paciente', $search_query);
            $this->db->group_end();
        }
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $activities[] = [
                'type' => 'Agendamento',
                'details' => "Paciente: {$row['Nome_Paciente']}, Médico: {$row['Nome_Medico']}, Data: {$row['Data_Agendamento']} {$row['Hora_Agendamento']}",
                'date' => "{$row['Data_Agendamento']} {$row['Hora_Agendamento']}",
                'bi' => $row['ID_Paciente'],
                'action_type' => 'appointment'
            ];
        }

        $this->db->select("ID_Paciente, CONCAT(Nome, ' ', Sobrenome) AS Nome_Completo, Telefone, Email");
        if ($search_query) {
            $this->db->group_start();
            $this->db->like("CONCAT(Nome, ' ', Sobrenome)", $search_query);
            $this->db->or_where('ID_Paciente', $search_query);
            $this->db->group_end();
        }
        $query = $this->db->get('Pacientes');
        foreach ($query->result_array() as $row) {
            $activities[] = [
                'type' => 'Paciente',
                'details' => "Nome: {$row['Nome_Completo']}, BI: {$row['ID_Paciente']}, Telefone: {$row['Telefone']}" . ($row['Email'] ? ", Email: {$row['Email']}" : ''),
                'date' => date('Y-m-d H:i:s'),
                'bi' => $row['ID_Paciente'],
                'action_type' => 'patient'
            ];
        }

        $this->db->select("ID_Medico, CONCAT(Nome, ' ', Sobrenome) AS Nome_Completo, Especialidade, Telefone, Email");
        if ($search_query) {
            $this->db->group_start();
            $this->db->like("CONCAT(Nome, ' ', Sobrenome)", $search_query);
            $this->db->or_where('ID_Medico', $search_query);
            $this->db->group_end();
        }
        $query = $this->db->get('Medicos');
        foreach ($query->result_array() as $row) {
            $activities[] = [
                'type' => 'Médico',
                'details' => "Nome: {$row['Nome_Completo']}, Especialidade: {$row['Especialidade']}, BI: {$row['ID_Medico']}, Telefone: {$row['Telefone']}" . ($row['Email'] ? ", Email: {$row['Email']}" : ''),
                'date' => date('Y-m-d H:i:s'),
                'bi' => $row['ID_Medico'],
                'action_type' => 'doctor'
            ];
        }

        usort($activities, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($activities, 0, 5);
    }

    public function get_patient($id_paciente)
    {
        $this->db->select('ID_Paciente, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Telefone, Email, Data_Nascimento, Genero, Endereco');
        $this->db->where('ID_Paciente', $id_paciente);
        $query = $this->db->get('Pacientes');
        return $query->row_array();
    }

    public function get_doctor($id_medico)
    {
        $this->db->select('ID_Medico, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Especialidade, Telefone, Email');
        $this->db->where('ID_Medico', $id_medico);
        $query = $this->db->get('Medicos');
        return $query->row_array();
    }

    public function update_patient($id_paciente, $data)
    {
        $names = explode(' ', $data['name'], 2);
        $update_data = [
            'Nome' => $names[0],
            'Sobrenome' => $names[1] ?? '',
            'Telefone' => $data['phone'],
            'Email' => $data['email'] ?? null,
            'Data_Nascimento' => $data['birthday'] ?? null,
            'Genero' => $data['gender'] ?? null,
            'Endereco' => $data['address'] ?? null
        ];
        $this->db->where('ID_Paciente', $id_paciente);
        return $this->db->update('Pacientes', $update_data);
    }

    public function update_doctor($id_medico, $data)
    {
        $names = explode(' ', $data['name'], 2);
        $update_data = [
            'Nome' => $names[0],
            'Sobrenome' => $names[1] ?? '',
            'Telefone' => $data['phone'],
            'Email' => $data['email'],
            'Especialidade' => $data['specialty'],
            'Numero_Licenca' => $data['license_number']
        ];
        $this->db->where('ID_Medico', $id_medico);
        return $this->db->update('Medicos', $update_data);
    }

    public function get_pacientes()
    {
        $this->db->select('ID_Paciente, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Telefone, Email, Data_Nascimento, Genero, Endereco');
        return $this->db->get('Pacientes')->result_array();
    }

    public function get_medicos()
    {
        $this->db->select('m.ID_Medico, CONCAT(m.Nome, " ", m.Sobrenome) AS Nome_Completo, m.Especialidade, m.Telefone, m.Email, m.Data_Inicio, d.Nome_Departamento');
        $this->db->from('Medicos m');
        $this->db->join('Departamentos d', 'm.ID_Departamento = d.ID_Departamento', 'left');
        return $this->db->get()->result_array();
    }

    public function get_secretarios()
    {
        $this->db->select('ID_Secretario, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Telefone, Email');
        return $this->db->get('Secretarios')->result_array();
    }

    public function get_agendamentos()
    {
        $this->db->select("a.ID_Agendamento, a.Data_Agendamento, a.Hora_Agendamento, a.Status, a.Motivo, 
                          CONCAT(p.Nome, ' ', p.Sobrenome) AS Nome_Paciente, 
                          CONCAT(m.Nome, ' ', m.Sobrenome) AS Nome_Medico");
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        $this->db->where('a.Status !=', 'Cancelado');
        return $this->db->get()->result_array();
    }
}