<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Agendamentos_model');
        $this->output->set_content_type('application/json');
    }

    public function metrics()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }
        $data = $this->Admin_model->get_metrics();
        echo json_encode($data);
    }

    public function activity()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }
        $search_query = $this->input->get('query', TRUE);
        $data = $this->Admin_model->get_activities($search_query);
        echo json_encode($data);
    }

    public function create_patient()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_paciente = $data['bi'] ?? '';
        $name = $data['name'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? null;
        $birthday = $data['birthday'] ?? null;
        $gender = $data['gender'] ?? null;
        $address = $data['address'] ?? null;

        if (!$id_paciente || !$name || !$phone) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI, nome e telefone são obrigatórios']);
            return;
        }

        $this->db->where('ID_Paciente', $id_paciente);
        if ($this->db->get('Pacientes')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI já cadastrado']);
            return;
        }

        $names = explode(' ', $name, 2);
        $insert_data = [
            'ID_Paciente' => $id_paciente,
            'Nome' => $names[0],
            'Sobrenome' => $names[1] ?? '',
            'Telefone' => $phone,
            'Email' => $email,
            'Data_Nascimento' => $birthday,
            'Genero' => $gender,
            'Endereco' => $address
        ];

        if ($this->db->insert('Pacientes', $insert_data)) {
            echo json_encode(['success' => 'Paciente cadastrado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao cadastrar paciente']);
        }
    }

    public function update_patient()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_paciente = $data['bi'] ?? '';
        $name = $data['name'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? null;
        $birthday = $data['birthday'] ?? null;
        $gender = $data['gender'] ?? null;
        $address = $data['address'] ?? null;

        if (!$id_paciente || !$name || !$phone) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Nome, BI e telefone são obrigatórios']);
            return;
        }

        if (
            $this->Admin_model->update_patient($id_paciente, [
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'birthday' => $birthday,
                'gender' => $gender,
                'address' => $address
            ])
        ) {
            echo json_encode(['success' => 'Paciente atualizado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao atualizar paciente']);
        }
    }

    public function create_doctor()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_medico = $data['bi'] ?? '';
        $name = $data['name'] ?? '';
        $specialty = $data['specialty'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? null;
        $license_number = $data['licenseNumber'] ?? '';

        if (!$id_medico || !$name || !$specialty || !$phone || !$license_number) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI, nome, especialidade, telefone e número da licença são obrigatórios']);
            return;
        }

        $this->db->where('ID_Medico', $id_medico);
        if ($this->db->get('Medicos')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI já cadastrado']);
            return;
        }

        $this->db->where('Numero_Licenca', $license_number);
        if ($this->db->get('Medicos')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Número da licença já cadastrado']);
            return;
        }

        $names = explode(' ', $name, 2);
        $insert_data = [
            'ID_Medico' => $id_medico,
            'Nome' => $names[0],
            'Sobrenome' => $names[1] ?? '',
            'Telefone' => $phone,
            'Email' => $email,
            'Especialidade' => $specialty,
            'Numero_Licenca' => $license_number
        ];

        if ($this->db->insert('Medicos', $insert_data)) {
            echo json_encode(['success' => 'Médico cadastrado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao cadastrar médico']);
        }
    }

    public function update_doctor()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_medico = $data['bi'] ?? '';
        $name = $data['name'] ?? '';
        $specialty = $data['specialty'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $license_number = $data['licenseNumber'] ?? '';

        if (!$id_medico || !$name || !$specialty || !$phone || !$license_number) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Nome, especialidade, BI, telefone e número da licença são obrigatórios']);
            return;
        }

        $this->db->where('Numero_Licenca', $license_number);
        $this->db->where('ID_Medico !=', $id_medico);
        if ($this->db->get('Medicos')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Número da licença já cadastrado para outro médico']);
            return;
        }

        $names = explode(' ', $name, 2);
        $update_data = [
            'Nome' => $names[0],
            'Sobrenome' => $names[1] ?? '',
            'Telefone' => $phone,
            'Email' => $email,
            'Especialidade' => $specialty,
            'Numero_Licenca' => $license_number
        ];

        $this->db->where('ID_Medico', $id_medico);
        if ($this->db->update('Medicos', $update_data)) {
            echo json_encode(['success' => 'Médico atualizado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao atualizar médico']);
        }
    }

    public function get_doctors()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        try {
            $search_query = $this->input->get('query', TRUE);
            $this->db->select('ID_Medico AS id, CONCAT(Nome, " ", Sobrenome) AS name, Telefone AS phone, Email AS email, Especialidade AS specialty, Numero_Licenca AS licenseNumber');
            if ($search_query) {
                $this->db->group_start();
                $this->db->like('CONCAT(Nome, " ", Sobrenome)', $search_query);
                $this->db->or_like('ID_Medico', $search_query);
                $this->db->or_like('Numero_Licenca', $search_query);
                $this->db->or_like('Especialidade', $search_query);
                $this->db->group_end();
            }
            $query = $this->db->get('Medicos');
            if ($query === FALSE) {
                log_message('error', 'Erro na consulta SQL: ' . $this->db->last_query());
                $this->output->set_status_header(500);
                echo json_encode(['error' => 'Erro na consulta ao banco de dados']);
                return;
            }
            $result = $query->result_array();
            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', 'Exceção em get_doctors: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro interno ao buscar médicos: ' . $e->getMessage()]);
        }
    }

    public function delete_doctor()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_medico = $data['bi'] ?? '';

        if (!$id_medico) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI do médico é obrigatório']);
            return;
        }

        $this->db->where('ID_Medico', $id_medico);
        $this->db->where('Status !=', 'Cancelado');
        $agendamentos = $this->db->get('Agendamentos')->num_rows();
        if ($agendamentos > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Não é possível excluir o médico, pois ele possui agendamentos ativos']);
            return;
        }

        $this->db->where('ID_Medico', $id_medico);
        if ($this->db->delete('Medicos')) {
            echo json_encode(['success' => 'Médico excluído com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao excluir médico']);
        }
    }

    public function slots()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $medico_id = $this->input->get('medico_id', TRUE);
        $data_horario = $this->input->get('data_horario', TRUE);
        $this->db->select('ID_Horario AS id, ID_Medico AS medico_id, Data_Horario AS date, Hora_Inicio AS start_time, Hora_Fim AS end_time, Tipo_Horario AS type, Motivo AS motivo');
        $this->db->where('Data_Horario >=', date('Y-m-d'));
        if ($medico_id) {
            $this->db->where('ID_Medico', $medico_id);
        }
        if ($data_horario) {
            $this->db->where('Data_Horario', $data_horario);
        }
        $query = $this->db->get('HorariosDisponiveis');
        echo json_encode($query->result_array());
    }

    public function create_agendamento()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_paciente = $data['ID_Paciente'] ?? '';
        $id_medico = $data['ID_Medico'] ?? '';
        $id_horario = $data['ID_Horario'] ?? '';
        $motivo = $data['Motivo'] ?? '';

        if (!$id_paciente || !$id_medico || !$id_horario || !$motivo) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Paciente, médico, horário e motivo são obrigatórios']);
            return;
        }

        $this->db->where('ID_Horario', $id_horario);
        $this->db->where('Data_Horario >=', date('Y-m-d'));
        $horario = $this->db->get('HorariosDisponiveis')->row_array();
        if (!$horario) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Horário inválido ou já ocupado']);
            return;
        }

        $agendamento_data = [
            'ID_Paciente' => $id_paciente,
            'ID_Medico' => $id_medico,
            'ID_Horario' => $id_horario,
            'Data_Agendamento' => $horario['Data_Horario'],
            'Hora_Agendamento' => $horario['Hora_Inicio'],
            'Status' => 'Agendado',
            'Motivo' => $motivo
        ];

        if ($this->db->insert('Agendamentos', $agendamento_data)) {
            $this->db->where('ID_Horario', $id_horario);
            $this->db->delete('HorariosDisponiveis');
            echo json_encode(['success' => 'Agendamento criado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao criar agendamento']);
        }
    }

    public function update_agendamento()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        $id_paciente = $data['ID_Paciente'] ?? '';
        $id_medico = $data['ID_Medico'] ?? '';
        $id_horario = $data['ID_Horario'] ?? '';
        $status = $data['Status'] ?? '';
        $motivo = $data['Motivo'] ?? '';

        if (!$id || !$id_paciente || !$id_medico || !$id_horario || !$status || !$motivo) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'ID, paciente, médico, horário, status e motivo são obrigatórios']);
            return;
        }

        $this->db->where('ID_Horario', $id_horario);
        $this->db->where('Data_Horario >=', date('Y-m-d'));
        $horario = $this->db->get('HorariosDisponiveis')->row_array();
        if (!$horario) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Horário inválido ou já ocupado']);
            return;
        }

        $agendamento_data = [
            'ID_Paciente' => $id_paciente,
            'ID_Medico' => $id_medico,
            'ID_Horario' => $id_horario,
            'Data_Agendamento' => $horario['Data_Horario'],
            'Hora_Agendamento' => $horario['Hora_Inicio'],
            'Status' => $status,
            'Motivo' => $motivo
        ];

        $this->db->where('ID_Agendamento', $id);
        if ($this->db->update('Agendamentos', $agendamento_data)) {
            $this->db->where('ID_Horario', $id_horario);
            $this->db->delete('HorariosDisponiveis');
            echo json_encode(['success' => 'Agendamento atualizado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao atualizar agendamento']);
        }
    }

    public function get_appointment()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        try {
            $id = $this->input->get('id', TRUE);
            if (!$id) {
                $this->output->set_status_header(400);
                echo json_encode(['error' => 'ID do agendamento não fornecido']);
                return;
            }
            $appointment = $this->Agendamentos_model->get_appointment($id);
            if ($appointment) {
                echo json_encode($appointment);
            } else {
                $this->output->set_status_header(404);
                echo json_encode(['error' => 'Agendamento não encontrado']);
            }
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao carregar agendamento: ' . $e->getMessage()]);
        }
    }

    public function get_patients()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        try {
            $search_query = $this->input->get('query', TRUE);
            $this->db->select('ID_Paciente AS id, CONCAT(Nome, " ", Sobrenome) AS name, Telefone AS phone, Email AS email, Data_Nascimento AS birthday, Genero AS gender, Endereco AS address');
            if ($search_query) {
                $this->db->group_start();
                $this->db->like('CONCAT(Nome, " ", Sobrenome)', $search_query);
                $this->db->or_like('ID_Paciente', $search_query);
                $this->db->group_end();
            }
            $query = $this->db->get('Pacientes');
            if ($query === FALSE) {
                log_message('error', 'Erro na consulta SQL: ' . $this->db->last_query());
                $this->output->set_status_header(500);
                echo json_encode(['error' => 'Erro na consulta ao banco de dados']);
                return;
            }
            $result = $query->result_array();
            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', 'Exceção em get_patients: ' . $e->getMessage());
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro interno ao buscar pacientes: ' . $e->getMessage()]);
        }
    }

    public function delete_patient()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id_paciente = $data['bi'] ?? '';

        if (!$id_paciente) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI do paciente é obrigatório']);
            return;
        }

        $this->db->where('ID_Paciente', $id_paciente);
        $this->db->where('Status !=', 'Cancelado');
        $agendamentos = $this->db->get('Agendamentos')->num_rows();
        if ($agendamentos > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Não é possível excluir o paciente, pois ele possui agendamentos ativos']);
            return;
        }

        $this->db->where('ID_Paciente', $id_paciente);
        if ($this->db->delete('Pacientes')) {
            echo json_encode(['success' => 'Paciente excluído com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao excluir paciente']);
        }
    }

    public function get_appointments()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        try {
            $search_query = $this->input->get('query', TRUE);
            $appointments = $this->Agendamentos_model->get_appointments($search_query);
            echo json_encode($appointments);
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao carregar agendamentos: ' . $e->getMessage()]);
        }
    }

    public function delete_appointment()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        if (!$id) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'ID do agendamento não fornecido']);
            return;
        }
        $result = $this->Agendamentos_model->delete_appointment($id);
        echo json_encode($result);
    }

    // Adicione este método no controlador Api
    public function get_patient()
    {
        if ($this->input->method() !== 'get') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $bi = $this->input->get('bi', TRUE);
        if (!$bi) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI é obrigatório']);
            return;
        }

        $this->db->select('ID_Paciente, Nome, Sobrenome, Telefone, Email, Data_Nascimento, Genero, Endereco, Contato_Emergencia');
        $this->db->where('ID_Paciente', $bi); // ou 'BI' se você usar campo BI
        $query = $this->db->get('Pacientes');

        if ($query->num_rows() == 0) {
            $this->output->set_status_header(404);
            echo json_encode(['error' => 'Paciente não encontrado']);
            return;
        }

        $patient = $query->row_array();
        echo json_encode($patient);
    }
}
