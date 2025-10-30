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

    // public function get_pacientes()
    // {
    //     $this->db->select('ID_Paciente, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Telefone, Email, Data_Nascimento, Genero, Endereco');
    //     return $this->db->get('Pacientes')->result_array();
    // }

    // public function get_medicos()
    // {
    //     $this->db->select('m.ID_Medico, CONCAT(m.Nome, " ", m.Sobrenome) AS Nome_Completo, m.Especialidade, m.Telefone, m.Email, m.Data_Inicio, d.Nome_Departamento');
    //     $this->db->from('Medicos m');
    //     $this->db->join('Departamentos d', 'm.ID_Departamento = d.ID_Departamento', 'left');
    //     return $this->db->get()->result_array();
    // }

    // public function get_secretarios()
    // {
    //     $this->db->select('ID_Secretario, CONCAT(Nome, " ", Sobrenome) AS Nome_Completo, Telefone, Email');
    //     return $this->db->get('Secretarios')->result_array();
    // }

    // public function get_agendamentos()
    // {
    //     $this->db->select("a.ID_Agendamento, a.Data_Agendamento, a.Hora_Agendamento, a.Status, a.Motivo, 
    //                       CONCAT(p.Nome, ' ', p.Sobrenome) AS Nome_Paciente, 
    //                       CONCAT(m.Nome, ' ', m.Sobrenome) AS Nome_Medico");
    //     $this->db->from('Agendamentos a');
    //     $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
    //     $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
    //     $this->db->where('a.Status !=', 'Cancelado');
    //     return $this->db->get()->result_array();
    // }

    // Métricas para dashboard
    // public function get_metrics()
    // {
    //     $metrics = [
    //         'total_pacientes' => $this->db->count_all('Pacientes'),
    //         'total_medicos' => $this->db->count_all('Medicos'),
    //         'total_agendamentos' => $this->db->count_all('Agendamentos'),
    //         'agendamentos_pendentes' => $this->db->where('Status', 'Pendente')->count_all_results('Agendamentos')
    //     ];
    //     log_message('debug', 'Métricas carregadas: ' . json_encode($metrics));
    //     return $metrics;
    // }

    // Pacientes (com JOIN para Email de Usuarios — corrigido ORDER BY)
    public function get_pacientes($query = '')
    {
        $this->db->select('p.ID_Paciente, CONCAT(p.Nome, " ", p.Sobrenome) AS Nome_Completo, p.Telefone, u.Email, p.Data_Nascimento, p.Genero, p.Endereco');
        $this->db->from('Pacientes p');
        $this->db->join('Usuarios u', 'p.ID_Usuario = u.ID_Usuario', 'left');
        if ($query) {
            $this->db->group_start();
            $this->db->like('CONCAT(p.Nome, " ", p.Sobrenome)', $query);
            $this->db->or_like('p.ID_Paciente', $query);
            $this->db->or_like('u.Email', $query);
            $this->db->group_end();
        }
        // Correção: ORDER BY como raw string com aspas simples no espaço
        $this->db->order_by("CONCAT(p.Nome, ' ', p.Sobrenome) ASC");
        $result = $this->db->get()->result_array();
        log_message('debug', 'Pacientes com JOIN Email: ' . count($result) . ' | Query: ' . $this->db->last_query());
        return $result;
    }

    // Médicos
    public function get_medicos($query = '')
    {
        $this->db->select('ID_Medico, Nome, Sobrenome, Especialidade, Telefone, Email');
        $this->db->from('Medicos');
        if ($query) {
            $this->db->group_start();
            $this->db->like('CONCAT(Nome, " ", Sobrenome)', $query);
            $this->db->or_like('Especialidade', $query);
            $this->db->group_end();
        }
        $this->db->order_by('Nome', 'ASC');
        $result = $this->db->get()->result_array();
        log_message('debug', 'Médicos carregados: ' . count($result));
        return $result;
    }

    // Secretários (assuma tabela Secretarios similar a Pacientes, ajuste colunas)
    public function get_secretarios($query = '')
    {
        $this->db->select('*');  // Ajuste campos: ID_Secretario, Nome_Completo, etc.
        $this->db->from('Secretarios');
        if ($query) {
            $this->db->like('Nome', $query);
        }
        $this->db->order_by('Nome', 'ASC');
        $result = $this->db->get()->result_array();
        log_message('debug', 'Secretários carregados: ' . count($result));
        return $result;
    }

    // Agendamentos
    public function get_agendamentos($query = '')
    {
        $this->db->select('a.ID_Agendamento, a.Data_Agendamento, a.Hora_Agendamento, a.Status, a.Motivo, p.Nome as paciente_nome, m.Nome as medico_nome');
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        if ($query) {
            $this->db->group_start();
            $this->db->like('p.Nome_Completo', $query);
            $this->db->or_like('m.Nome', $query);
            $this->db->group_end();
        }
        $this->db->order_by('a.Data_Agendamento', 'DESC');
        $result = $this->db->get()->result_array();
        log_message('debug', 'Agendamentos carregados: ' . count($result));
        return $result;
    }

    // CRUD para pacientes (para AJAX delete, etc.)
    public function delete_paciente($id_paciente)
    {
        $this->db->where('ID_Paciente', $id_paciente);
        if ($this->db->delete('Pacientes')) {
            return ['success' => 'Paciente excluído com sucesso'];
        } else {
            return ['error' => 'Erro ao excluir: ' . $this->db->error()['message']];
        }
    }

    public function cancel_appointment($id)
    {
        log_message('debug', 'Iniciando cancel_appointment para ID: ' . $id);  // Log no CI

        if (!$id || !is_numeric($id)) {
            log_message('error', 'ID inválido: ' . $id);
            return false;
        }

        $this->db->where('ID_Agendamento', $id);
        $update_data = [
            'Status' => 'Cancelado',
            'Data_Cancelamento' => date('Y-m-d H:i:s')  // Verifique se esta coluna existe!
        ];

        $result = $this->db->update('Agendamentos', $update_data);  // Nota: Tabela é 'Agendamentos' (plural?)

        $affected = $this->db->affected_rows();
        log_message('debug', 'Update result: ' . ($result ? 'TRUE' : 'FALSE') . ', Affected rows: ' . $affected);

        // Se falhou, logue o erro do DB
        if (!$result) {
            log_message('error', 'DB Error: ' . $this->db->last_query());  // Query executada
            log_message('error', 'DB Error Details: ' . $this->db->error()['message']);  // Erro específico
        }

        return $result;
    }

    public function delete_appointment($id)
    {
        // Similar para delete, se precisar
        log_message('debug', 'Deletando ID: ' . $id);
        $this->db->where('ID_Agendamento', $id);
        $result = $this->db->delete('Agendamentos');
        $affected = $this->db->affected_rows();
        log_message('debug', 'Delete result: ' . ($result ? 'TRUE' : 'FALSE') . ', Affected: ' . $affected);
        if (!$result) {
            log_message('error', 'DB Error: ' . $this->db->error()['message']);
        }
        return $result;
    }


     /**
     * Buscar paciente por BI
     */
    public function get_patient_by_bi($bi)
    {
        $this->db->select('ID_Paciente, Nome, Sobrenome, Data_Nascimento, Genero, Endereco, Telefone, Contato_Emergencia, BI, Criado_Em');
        $this->db->from('pacientes');
        $this->db->where('BI', $bi);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Buscar pacientes (para busca)
     */
    public function search_patients($query)
    {
        $this->db->select('ID_Paciente, Nome, Sobrenome, Data_Nascimento, Genero, Endereco, Telefone, Contato_Emergencia, BI, Criado_Em');
        $this->db->from('pacientes');
        $this->db->group_start();
        $this->db->like('Nome', $query);
        $this->db->or_like('Sobrenome', $query);
        $this->db->or_like('BI', $query);
        $this->db->or_like('Telefone', $query);
        $this->db->or_like('CONCAT(Nome, " ", Sobrenome)', $query);
        $this->db->group_end();
        $this->db->order_by('Nome', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Criar novo paciente
     */
    public function create_patient($data)
    {
        // Verificar se BI já existe
        $this->db->where('BI', $data['BI']);
        $existing = $this->db->get('pacientes')->row();
        
        if ($existing) {
            return ['error' => 'Já existe um paciente com este BI.'];
        }

        // Preparar dados para inserção
        $patient_data = [
            'Nome' => $data['Nome'],
            'Sobrenome' => $data['Sobrenome'],
            'Data_Nascimento' => $data['Data_Nascimento'],
            'Genero' => $data['Genero'],
            'Endereco' => $data['Endereco'] ?? null,
            'Telefone' => $data['Telefone'],
            'Contato_Emergencia' => $data['Contato_Emergencia'] ?? null,
            'BI' => $data['BI'],
            'Criado_Em' => date('Y-m-d H:i:s')
        ];

        $success = $this->db->insert('pacientes', $patient_data);
        
        if ($success) {
            return ['success' => 'Paciente cadastrado com sucesso!'];
        } else {
            return ['error' => 'Erro ao cadastrar paciente.'];
        }
    }

    /**
     * Atualizar paciente
     */
    // public function update_patient($data)
    // {
    //     $bi = $data['BI'];
        
    //     // Preparar dados para atualização
    //     $patient_data = [
    //         'Nome' => $data['Nome'],
    //         'Sobrenome' => $data['Sobrenome'],
    //         'Data_Nascimento' => $data['Data_Nascimento'],
    //         'Genero' => $data['Genero'],
    //         'Endereco' => $data['Endereco'] ?? null,
    //         'Telefone' => $data['Telefone'],
    //         'Contato_Emergencia' => $data['Contato_Emergencia'] ?? null,
    //         'Criado_Em' => date('Y-m-d H:i:s')
    //     ];

    //     $this->db->where('BI', $bi);
    //     $success = $this->db->update('pacientes', $patient_data);
        
    //     if ($success) {
    //         return ['success' => 'Paciente atualizado com sucesso!'];
    //     } else {
    //         return ['error' => 'Erro ao atualizar paciente.'];
    //     }
    // }

    /**
     * Deletar paciente
     */
    public function delete_patient($bi)
    {
        $this->db->where('BI', $bi);
        $success = $this->db->delete('pacientes');
        
        return $success;
    }

    /**
     * Buscar paciente por ID
     */
    public function get_patient_by_id($id)
    {
        $this->db->select('ID_Paciente, Nome, Sobrenome, Data_Nascimento, Genero, Endereco, Telefone, Contato_Emergencia, BI, Criado_Em');
        $this->db->from('pacientes');
        $this->db->where('ID_Paciente', $id);
        $query = $this->db->get();
        return $query->row();
    }
}
