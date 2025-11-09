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

        // Dados
        $BI = trim($data['BI'] ?? '');
        $Nome = trim($data['Nome'] ?? '');
        $Sobrenome = trim($data['Sobrenome'] ?? '');
        $Telefone = trim($data['Telefone'] ?? '');
        $Data_Nascimento = $data['Data_Nascimento'] ?? null;
        $Genero = $data['Genero'] ?? null;
        $Endereco = trim($data['Endereco'] ?? '') ?: null;
        $Contato_Emergencia = trim($data['Contato_Emergencia'] ?? '') ?: null;
        $Email = trim($data['Email'] ?? '');
        $Senha = $data['Senha'] ?? '';

        // Validação
        if (!$BI || !$Nome || !$Sobrenome || !$Telefone || !$Data_Nascimento || !$Genero || !$Email || !$Senha) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            return;
        }

        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Email inválido.']);
            return;
        }

        if (strlen($Senha) < 6) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'A senha deve ter pelo menos 6 caracteres.']);
            return;
        }

        // Verifica BI e Email duplicados
        $this->db->where('BI', $BI);
        if ($this->db->get('Pacientes')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI já cadastrado.']);
            return;
        }

        $this->db->where('Email', $Email);
        if ($this->db->get('usuarios')->num_rows() > 0) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Email já está em uso.']);
            return;
        }

        // Transação
        $this->db->trans_start();

        // 1. Cria usuário
        $this->db->insert('usuarios', [
            'Email' => $Email,
            'Senha' => password_hash($Senha, PASSWORD_BCRYPT),
            'Tipo_Usuario' => 'paciente',
            'ID_Referencia' => $BI,
            'Criado_Em' => date('Y-m-d H:i:s')
        ]);
        $ID_Usuario = $this->db->insert_id();

        // 2. Cria paciente
        $this->db->insert('Pacientes', [
            'BI' => $BI,
            'Nome' => $Nome,
            'Sobrenome' => $Sobrenome,
            'Telefone' => $Telefone,
            'Data_Nascimento' => $Data_Nascimento,
            'Genero' => $Genero,
            'Endereco' => $Endereco,
            'Contato_Emergencia' => $Contato_Emergencia,
            'ID_Usuario' => $ID_Usuario
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao cadastrar. Tente novamente.']);
            return;
        }

        echo json_encode(['success' => 'Paciente cadastrado com sucesso!']);
    }

    public function update_patient()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $BI = trim($data['BI'] ?? '');
        if (!$BI) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI é obrigatório.']);
            return;
        }

        $update_data = [
            'Nome'               => trim($data['Nome'] ?? ''),
            'Sobrenome'          => trim($data['Sobrenome'] ?? ''),
            'Telefone'           => trim($data['Telefone'] ?? ''),
            'Data_Nascimento'    => $data['Data_Nascimento'] ?? null,
            'Genero'             => $data['Genero'] ?? null,
            'Endereco'           => trim($data['Endereco'] ?? '') ?: null,
            'Contato_Emergencia' => trim($data['Contato_Emergencia'] ?? '') ?: null
        ];

        $this->db->where('BI', $BI);
        if ($this->db->update('Pacientes', $update_data)) {
            echo json_encode(['success' => 'Paciente atualizado com sucesso!']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao atualizar paciente.']);
        }
    }

    public function create_doctor()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        log_message('debug', 'create_doctor input: ' . $input);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]);
            return;
        }

        $required = ['BI', 'Nome', 'Sobrenome', 'Telefone', 'Email', 'Especialidade', 'Numero_Licenca', 'Senha'];
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                echo json_encode(['error' => "Campo obrigatório: $field"]);
                return;
            }
        }

        $bi = trim($data['BI']);
        $email = trim($data['Email']);

        // Validações
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Email inválido']);
            return;
        }
        if (strlen($data['Senha']) < 6) {
            echo json_encode(['error' => 'Senha deve ter no mínimo 6 caracteres']);
            return;
        }
        if (!preg_match('/^\d{9}$/', $data['Telefone'])) {
            echo json_encode(['error' => 'Telefone deve ter 9 dígitos']);
            return;
        }

        // Verifica duplicatas
        if ($this->db->where('BI', $bi)->get('Medicos')->num_rows() > 0) {
            echo json_encode(['error' => 'BI já cadastrado']);
            return;
        }
        if ($this->db->where('Email', $email)->get('usuarios')->num_rows() > 0) {
            echo json_encode(['error' => 'Email já cadastrado']);
            return;
        }

        // === TRANSAÇÃO COM LOG COMPLETO ===
        $this->db->trans_start();

        $this->db->insert('usuarios', [
            'Email' => $email,
            'Senha' => password_hash($data['Senha'], PASSWORD_BCRYPT),
            'Tipo_Usuario' => 'Medico'
        ]);
        $id_usuario = $this->db->insert_id();

        if (!$id_usuario) {
            $this->db->trans_rollback();
            log_message('error', 'Falha ao inserir usuário: ' . $this->db->last_query());
            echo json_encode(['error' => 'Erro ao criar usuário']);
            return;
        }

        $this->db->insert('Medicos', [
            'BI' => $bi,
            'Nome' => $data['Nome'],
            'Sobrenome' => $data['Sobrenome'],
            'Telefone' => $data['Telefone'],
            'Especialidade' => $data['Especialidade'],
            'Numero_Licenca' => $data['Numero_Licenca'],
            'ID_Usuario' => $id_usuario
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $error = $this->db->error();
            log_message('error', 'TRANSAÇÃO FALHOU: ' . json_encode($error));
            log_message('error', 'Query falha: ' . $this->db->last_query());
            echo json_encode(['error' => 'Erro no banco: ' . ($error['message'] ?? 'Desconhecido')]);
            return;
        }

        log_message('debug', "Médico cadastrado com sucesso: BI=$bi, ID_Usuario=$id_usuario");
        echo json_encode(['success' => 'Médico cadastrado com sucesso']);
    }

    public function update_doctor()
    {
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $bi = $data['BI'] ?? null;

        if (!$bi) {
            echo json_encode(['error' => 'BI é obrigatório']);
            return;
        }

        // Busca médico com JOIN
        $this->db->select('m.*, u.ID_Usuario, u.Email as user_email');
        $this->db->from('Medicos m');
        $this->db->join('usuarios u', 'm.ID_Usuario = u.ID_Usuario', 'left');
        $this->db->where('m.BI', $bi);
        $medico = $this->db->get()->row_array();

        if (!$medico) {
            echo json_encode(['error' => 'Médico não encontrado']);
            return;
        }

        $this->db->trans_start();

        // Atualiza usuário
        $user_data = [];
        if (!empty($data['Email']) && $data['Email'] !== $medico['user_email']) {
            if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['error' => 'Email inválido']);
                return;
            }
            if ($this->db->where('Email', $data['Email'])->where('ID_Usuario !=', $medico['ID_Usuario'])->get('usuarios')->num_rows() > 0) {
                echo json_encode(['error' => 'Email já em uso']);
                return;
            }
            $user_data['Email'] = $data['Email'];
        }
        if (!empty($data['Senha']) && strlen($data['Senha']) >= 6) {
            $user_data['Senha'] = password_hash($data['Senha'], PASSWORD_BCRYPT);
        }
        if (!empty($user_data)) {
            $this->db->where('ID_Usuario', $medico['ID_Usuario'])->update('usuarios', $user_data);
        }

        // Atualiza médico
        $medico_data = [
            'Nome' => $data['Nome'] ?? $medico['Nome'],
            'Sobrenome' => $data['Sobrenome'] ?? $medico['Sobrenome'],
            'Telefone' => $data['Telefone'] ?? $medico['Telefone'],
            'Especialidade' => $data['Especialidade'] ?? $medico['Especialidade'],
            'Numero_Licenca' => $data['Numero_Licenca'] ?? $medico['Numero_Licenca']
        ];
        $this->db->where('BI', $bi)->update('Medicos', $medico_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['error' => 'Erro ao atualizar médico']);
            return;
        }

        echo json_encode(['success' => 'Médico atualizado com sucesso']);
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
