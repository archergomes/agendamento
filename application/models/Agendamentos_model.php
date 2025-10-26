<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agendamentos_model extends CI_Model
{

    public function get_appointments($query = '')
    {
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

    public function get_appointment($id)
    {
        $this->db->select('a.ID_Agendamento as id, a.ID_Paciente as patient_id, CONCAT(p.Nome, " ", p.Sobrenome) as patient_name, a.ID_Medico as doctor_id, CONCAT(m.Nome, " ", m.Sobrenome) as doctor_name, a.ID_Horario as horario_id, a.Data_Agendamento as date, a.Hora_Agendamento as time, a.Status as status, a.Motivo as motivo');
        $this->db->from('Agendamentos a');
        $this->db->join('Pacientes p', 'a.ID_Paciente = p.ID_Paciente');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        $this->db->where('a.ID_Agendamento', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_patients($query = '')
    {
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

    public function get_doctors($query = '')
    {
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

    public function create_appointment($data)
    {
        if ($this->db->insert('Agendamentos', $data)) {
            return ['success' => 'Agendamento criado com sucesso', 'id' => $this->db->insert_id()];
        } else {
            return ['error' => 'Erro ao criar agendamento: ' . $this->db->error()['message']];
        }
    }

    public function update_appointment($id, $data)
    {
        $this->db->where('ID_Agendamento', $id);
        if ($this->db->update('Agendamentos', $data)) {
            return ['success' => 'Agendamento atualizado com sucesso'];
        } else {
            return ['error' => 'Erro ao atualizar agendamento: ' . $this->db->error()['message']];
        }
    }

    public function delete_appointment($id)
    {
        $this->db->where('ID_Agendamento', $id);
        if ($this->db->delete('Agendamentos')) {
            return ['success' => 'Agendamento excluído com sucesso'];
        } else {
            return ['error' => 'Erro ao excluir agendamento: ' . $this->db->error()['message']];
        }
    }

    // Get médicos (com filtro por especialidade, query, limit/offset)
    public function get_medicos($specialty = null, $query = '', $limit = null, $offset = null)
    {
        $this->db->select('*');
        $this->db->from('Medicos');

        if ($specialty) {
            $this->db->where('Especialidade', $specialty);
        }
        if ($query) {
            $this->db->group_start();
            $this->db->like('Nome', $query);
            $this->db->or_like('Sobrenome', $query);
            $this->db->group_end();
        }

        $this->db->order_by('Nome', 'ASC');
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        $q = $this->db->get();
        return $q->result();  // Array de objetos
    }

    // Count total de médicos (para paginação)
    public function count_medicos($specialty = null, $query = '')
    {
        $this->db->from('Medicos');
        if ($specialty) {
            $this->db->where('Especialidade', $specialty);
        }
        if ($query) {
            $this->db->group_start();
            $this->db->like('Nome', $query);
            $this->db->or_like('Sobrenome', $query);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    // Verifica se horário está disponível (considerando tabela Horarios)
    public function is_slot_available($data_agendamento, $hora_agendamento, $medico_id)
    {
        if (!strtotime($data_agendamento)) return false;  // Valida data

        // Conta ocupados
        $this->db->from('Agendamentos');
        $this->db->where('Data_Agendamento', $data_agendamento);
        $this->db->where('Hora_Agendamento', $hora_agendamento);
        $this->db->where('ID_Medico', $medico_id);
        $this->db->where_in('Status', ['Pendente', 'Confirmado']);
        $occupied = $this->db->count_all_results();

        if ($occupied > 0) return false;

        // Verifica Horarios
        $dia_semana = date('l', strtotime($data_agendamento));
        $dia_semana_pt = [
            'Monday' => 'Segunda',
            'Tuesday' => 'Terça',
            'Wednesday' => 'Quarta',
            'Thursday' => 'Quinta',
            'Friday' => 'Sexta',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        $dia_enum = $dia_semana_pt[$dia_semana] ?? null;
        if (!$dia_enum) return false;

        $this->db->from('Horarios');
        $this->db->where('ID_Medico', $medico_id);
        $this->db->where('Dia_Semana', $dia_enum);
        $this->db->where('Hora_Inicio <=', $hora_agendamento);
        $this->db->where('Hora_Fim >=', $hora_agendamento);
        return $this->db->count_all_results() > 0;
    }

    // Cria agendamento (alinhado com create_appointment)
    public function create_agendamento($data)
    {
        if ($this->db->insert('Agendamentos', $data)) {
            return ['success' => 'Agendamento criado com sucesso', 'id' => $this->db->insert_id()];
        } else {
            return ['error' => 'Erro ao criar agendamento: ' . $this->db->error()['message']];
        }
    }

    // Get horários disponíveis para data/médico (da tabela Horarios, menos ocupados)
    public function get_available_slots($data_consulta, $medico_id)
    {
        // Pega dia da semana
        $dia_semana = date('l', strtotime($data_consulta));
        $dia_semana_pt = [
            'Monday' => 'Segunda',
            'Tuesday' => 'Terça',
            'Wednesday' => 'Quarta',
            'Thursday' => 'Quinta',
            'Friday' => 'Sexta',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        $dia_enum = $dia_semana_pt[$dia_semana] ?? 'Segunda';

        // Puxa horários da tabela Horarios para o médico e dia
        $this->db->select('Hora_Inicio, Hora_Fim');
        $this->db->from('Horarios');
        $this->db->where('ID_Medico', $medico_id);
        $this->db->where('Dia_Semana', $dia_enum);
        $schedule = $this->db->get()->result_array();

        if (empty($schedule)) {
            return [];  // Nenhum horário disponível no dia
        }

        // Gera slots de 30min dentro dos horários (ex: 08:00-09:00 gera 08:00, 08:30)
        $all_slots = [];
        foreach ($schedule as $range) {
            $start = new DateTime($range['Hora_Inicio']);
            $end = new DateTime($range['Hora_Fim']);
            $interval = new DateInterval('PT30M');
            while ($start < $end) {
                $all_slots[] = $start->format('H:i');
                $start->add($interval);
            }
        }

        // Remove horários ocupados de Agendamentos
        $this->db->select('Hora_Agendamento');
        $this->db->from('Agendamentos');
        $this->db->where('Data_Agendamento', $data_consulta);
        $this->db->where('ID_Medico', $medico_id);
        $this->db->where_in('Status', ['Pendente', 'Confirmado']);
        $occupied = $this->db->get()->result_array();
        $occupied_times = array_column($occupied, 'Hora_Agendamento');
        $available = array_diff($all_slots, $occupied_times);

        return array_values($available);  // Array de horários livres
    }

    // // Get agendamentos do paciente (versão única, retorna arrays para view)
    // public function get_appointments_by_patient($paciente_id)
    // {
    //     $this->db->select('a.ID_Agendamento, a.Data_Agendamento, a.Hora_Agendamento, a.Status, a.Motivo, CONCAT(m.Nome, " ", m.Sobrenome) as medico_nome, m.Especialidade');
    //     $this->db->from('Agendamentos a');
    //     $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
    //     $this->db->where('a.ID_Paciente', $paciente_id);
    //     $this->db->order_by('a.Data_Agendamento', 'ASC');
    //     $query = $this->db->get();
    //     return $query->result_array();  // Arrays: $appt['medico_nome'], etc.
    // }

    // Get paciente by id_referencia
    public function get_paciente_by_id_referencia($id_referencia)
    {
        $this->db->where('ID_Paciente', $id_referencia);
        return $this->db->get('Pacientes')->row();
    }

    // Buscar agendamentos do paciente com informações do médico
    public function get_appointments_by_patient($paciente_id)
    {
        $this->db->select('
        a.ID_Agendamento,
        a.Data_Agendamento,
        a.Hora_Agendamento,
        a.Status,
        a.Motivo,
        a.created_at,
        m.Nome as medico_nome,
        m.Sobrenome as medico_sobrenome,
        m.Especialidade
    ');
        $this->db->from('agendamentos a');
        $this->db->join('Medicos m', 'a.ID_Medico = m.ID_Medico');
        $this->db->where('a.ID_Paciente', $paciente_id);
        $this->db->order_by('a.Data_Agendamento DESC, a.Hora_Agendamento DESC');

        return $this->db->get()->result_array();
    }

    // Buscar agendamento por ID
    public function get_appointment_by_id($appointment_id)
    {
        return $this->db->get_where('agendamentos', ['ID_Agendamento' => $appointment_id])->row();
    }

    // Novo: Para cancelar (update status, com verificação de paciente)
    public function cancel_appointment($id)
    {
        $this->db->where('ID_Agendamento', $id);
        $this->db->where('ID_Paciente', $this->session->userdata('paciente_id'));  // Segurança
        if ($this->db->update('Agendamentos', ['Status' => 'Cancelado'])) {
            return ['success' => 'Agendamento cancelado com sucesso'];
        } else {
            return ['error' => 'Erro ao cancelar: ' . $this->db->error()['message']];
        }
    }

    // No final do model, atualize para logar erros (opcional, para debug)
    public function get_especialidades()
    {
        $this->db->select('ID_Especialidade, Nome');  // Ajuste se colunas forem 'ID_Especialidade, Nome'
        $this->db->from('Especialidades');  // Confirme nome da tabela no BD
        $this->db->order_by('nome', 'ASC');
        $result = $this->db->get()->result();

        // Debug: Log se vazio (verifique application/logs/)
        if (empty($result)) {
            log_message('error', 'get_especialidades: Tabela vazia ou inexistente');
        }

        return $result;
    }
}
