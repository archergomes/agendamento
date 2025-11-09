<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->database();
    }

    // ==================== PÁGINAS ====================

    public function index()
    {
        $data['metrics'] = $this->Admin_model->get_metrics();
        $this->load->view('admin/dashboard', $data);
    }

    public function pacientes()
    {
        $data['pacientes'] = $this->Admin_model->get_pacientes();
        $this->load->view('admin/pacientes', $data);
    }

    public function medicos()
    {
        $data['medicos'] = $this->Admin_model->get_medicos();
        $this->load->view('admin/medicos', $data);
    }

    public function secretarios()
    {
        $data['secretarios'] = $this->Admin_model->get_secretarios();
        $this->load->view('admin/secretarios', $data);
    }

    public function agendamentos()
    {
        $data['agendamentos'] = $this->Admin_model->get_agendamentos();
        $this->load->view('admin/agendamentos', $data);
    }

    public function cad_paciente()
    {
        $bi = $this->input->get('bi');
        $data = [];

        if ($bi) {
            $this->db->select('p.*, u.Email');
            $this->db->from('Pacientes p');
            $this->db->join('usuarios u', 'p.ID_Usuario = u.ID_Usuario', 'left');
            $this->db->where('p.BI', $bi);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $data['paciente'] = $query->row_array();
            }
        }

        $this->load->view('admin/cad_paciente', $data);
    }

    public function cad_secretario()
    {
        $this->load->view('admin/cad_secretario');
    }

    public function cad_medico()
    {
        $bi = $this->input->get('bi');
        $data = ['edit_mode' => !empty($bi), 'bi' => $bi];
        $this->load->view('admin/cad_medico', $data);
    }

    public function relatorios()
    {
        $this->load->view('admin/relatorios');
    }

    public function configuracoes()
    {
        $this->load->view('admin/config');
    }

    public function cad_agendamento()
    {
        $data['medicos'] = $this->Admin_model->get_medicos();
        $data['pacientes'] = $this->Admin_model->get_pacientes();
        $this->load->view('admin/cad_agendamentos', $data);
    }

    public function disponibilidade()
    {
        $data['medicos'] = $this->Admin_model->get_medicos();
        $this->load->view('admin/disponibilidade', $data);
    }

    // ==================== API JSON ====================

    // Busca pacientes (AJAX)
    public function get_patients()
    {
        $this->output->set_content_type('application/json');
        $query = $this->input->get('query');
        $pacientes = $this->Admin_model->get_pacientes($query);
        echo json_encode($pacientes);
    }

    // Excluir paciente por BI
    public function delete_patient()
    {
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $input = json_decode($this->input->raw_input_stream, true);
        $bi = $input['bi'] ?? null;

        if (!$bi) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI é obrigatório']);
            return;
        }

        // Busca ID_Usuario
        $this->db->select('ID_Usuario');
        $this->db->where('BI', $bi);
        $paciente = $this->db->get('Pacientes')->row_array();

        if (!$paciente) {
            $this->output->set_status_header(404);
            echo json_encode(['error' => 'Paciente não encontrado']);
            return;
        }

        $this->db->trans_start();

        // Deleta da tabela pacientes
        $this->db->where('BI', $bi);
        $this->db->delete('Pacientes');

        // Deleta usuário associado
        if ($paciente['ID_Usuario']) {
            $this->db->where('ID_Usuario', $paciente['ID_Usuario']);
            $this->db->delete('usuarios');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao excluir paciente']);
            return;
        }

        echo json_encode(['success' => 'Paciente excluído com sucesso']);
    }

    // Atualizar paciente (edição)
    public function update_patient()
    {
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $data = json_decode($this->input->raw_input_stream, true);

        $bi = $data['BI'] ?? null;
        $email = $data['Email'] ?? null;
        $senha = $data['Senha'] ?? null;

        if (!$bi || !$email) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'BI e Email são obrigatórios']);
            return;
        }

        // Busca paciente e usuário
        $this->db->select('p.ID_Usuario, u.ID_Usuario as user_id');
        $this->db->from('Pacientes p');
        $this->db->join('usuarios u', 'p.ID_Usuario = u.ID_Usuario', 'left');
        $this->db->where('p.BI', $bi);
        $result = $this->db->get()->row_array();

        if (!$result) {
            $this->output->set_status_header(404);
            echo json_encode(['error' => 'Paciente não encontrado']);
            return;
        }

        $this->db->trans_start();

        // Atualiza dados do paciente
        $paciente_data = [
            'Nome' => $data['Nome'] ?? '',
            'Sobrenome' => $data['Sobrenome'] ?? '',
            'Telefone' => $data['Telefone'] ?? '',
            'Data_Nascimento' => $data['Data_Nascimento'] ?? null,
            'Genero' => $data['Genero'] ?? null,
            'Endereco' => $data['Endereco'] ?? null,
            'Contato_Emergencia' => $data['Contato_Emergencia'] ?? null
        ];
        $this->db->where('BI', $bi);
        $this->db->update('Pacientes', $paciente_data);

        // Atualiza usuário
        $usuario_data = ['Email' => $email];
        if ($senha && strlen($senha) >= 6) {
            $usuario_data['Senha'] = password_hash($senha, PASSWORD_BCRYPT);
        }

        $this->db->where('ID_Usuario', $result['ID_Usuario']);
        $this->db->update('usuarios', $usuario_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao atualizar paciente']);
            return;
        }

        echo json_encode(['success' => 'Paciente atualizado com sucesso']);
    }

    // Cancelar agendamento
    public function cancel_appointment()
    {
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $input = json_decode($this->input->raw_input_stream, true);
        $id = $input['id'] ?? null;

        if (!$id) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'ID do agendamento é obrigatório']);
            return;
        }

        $result = $this->Admin_model->cancel_appointment($id);
        if ($result) {
            echo json_encode(['success' => 'Agendamento cancelado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao cancelar agendamento']);
        }
    }

    // Excluir agendamento
    public function delete_appointment()
    {
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $input = json_decode($this->input->raw_input_stream, true);
        $id = $input['id'] ?? null;

        if (!$id) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'ID do agendamento não fornecido']);
            return;
        }

        $result = $this->Admin_model->delete_appointment($id);
        if ($result) {
            echo json_encode(['success' => 'Agendamento excluído com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Erro ao excluir agendamento']);
        }
    }

    // // ==================== API JSON PARA MÉDICOS ====================

    // public function get_doctor_by_bi()
    // {
    //     $this->output->set_content_type('application/json');
    //     $bi = $this->input->get('bi');

    //     if (!$bi) {
    //         echo json_encode(['error' => 'BI obrigatório']);
    //         return;
    //     }

    //     $this->db->where('BI', $bi);
    //     $medico = $this->db->get('Medicos')->row_array();

    //     if (!$medico) {
    //         echo json_encode(['error' => 'Médico não encontrado']);
    //         return;
    //     }

    //     // Não retorna a senha
    //     unset($medico['Senha']);
    //     echo json_encode($medico);
    // }

    // public function create_doctor()
    // {
    //     // Garantir JSON
    //     $this->output->set_content_type('application/json');

    //     // Ler JSON do corpo da requisição
    //     $input = file_get_contents('php://input');
    //     $data = json_decode($input, true);

    //     // Debug: logar entrada
    //     log_message('debug', 'create_doctor input: ' . $input);

    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         echo json_encode(['error' => 'JSON inválido']);
    //         return;
    //     }

    //     // Campos obrigatórios
    //     $required = ['BI', 'Nome', 'Sobrenome', 'Telefone', 'Especialidade', 'Numero_Licenca', 'Senha'];
    //     foreach ($required as $field) {
    //         if (empty($data[$field])) {
    //             echo json_encode(['error' => "Campo obrigatório: $field"]);
    //             return;
    //         }
    //     }

    //     // BI único
    //     $this->db->where('BI', $data['BI']);
    //     if ($this->db->get('Medicos')->num_rows() > 0) {
    //         echo json_encode(['error' => 'BI já cadastrado']);
    //         return;
    //     }

    //     // Senha mínima
    //     if (strlen($data['Senha']) < 6) {
    //         echo json_encode(['error' => 'Senha deve ter no mínimo 6 caracteres']);
    //         return;
    //     }

    //     // Hash da senha
    //     $data['Senha'] = password_hash($data['Senha'], PASSWORD_BCRYPT);

    //     // Inserir
    //     $result = $this->db->insert('Medicos', $data);

    //     if ($result) {
    //         log_message('debug', 'Médico cadastrado: ' . $data['BI']);
    //         echo json_encode(['success' => 'Médico cadastrado com sucesso']);
    //     } else {
    //         log_message('error', 'Erro DB: ' . $this->db->last_query());
    //         echo json_encode(['error' => 'Erro ao salvar no banco']);
    //     }
    // }

    // public function update_doctor()
    // {
    //     $this->output->set_content_type('application/json');
    //     $data = json_decode($this->input->raw_input_stream, true);

    //     $bi = $data['BI'] ?? null;
    //     if (!$bi) {
    //         echo json_encode(['error' => 'BI é obrigatório']);
    //         return;
    //     }

    //     // Busca médico
    //     $this->db->where('BI', $bi);
    //     $medico = $this->db->get('Medicos')->row_array();
    //     if (!$medico) {
    //         echo json_encode(['error' => 'Médico não encontrado']);
    //         return;
    //     }

    //     // Atualiza apenas os campos permitidos
    //     $update = [
    //         'Nome' => $data['Nome'] ?? $medico['Nome'],
    //         'Sobrenome' => $data['Sobrenome'] ?? $medico['Sobrenome'],
    //         'Telefone' => $data['Telefone'] ?? $medico['Telefone'],
    //         'Email' => $data['Email'] ?? $medico['Email'],
    //         'Especialidade' => $data['Especialidade'] ?? $medico['Especialidade'],
    //         'Numero_Licenca' => $data['Numero_Licenca'] ?? $medico['Numero_Licenca']
    //     ];

    //     // Senha opcional
    //     if (!empty($data['Senha']) && strlen($data['Senha']) >= 6) {
    //         $update['Senha'] = password_hash($data['Senha'], PASSWORD_BCRYPT);
    //     }

    //     $this->db->where('BI', $bi);
    //     $this->db->update('Medicos', $update);

    //     if ($this->db->affected_rows() >= 0) {
    //         echo json_encode(['success' => 'Médico atualizado com sucesso']);
    //     } else {
    //         echo json_encode(['error' => 'Erro ao atualizar médico']);
    //     }
    // }

    // Admin.php - Adicione validações

    public function get_minha_disponibilidade()
    {
        $this->output->set_content_type('application/json');

        // Verificar se é médico
        $user_id = $this->session->userdata('user_id');
        $tipo_usuario = $this->session->userdata('tipo_usuario');

        if ($tipo_usuario !== 'Medico') {
            echo json_encode(['error' => 'Acesso não autorizado']);
            return;
        }

        $medico = $this->db->where('ID_Usuario', $user_id)->get('Medicos')->row_array();
        if (!$medico) {
            echo json_encode(['error' => 'Médico não encontrado']);
            return;
        }

        // Verificar se o médico existe na tabela Medicos
        if (!$this->Admin_model->medico_existe($medico['ID_Medico'])) {
            echo json_encode(['error' => 'Médico não encontrado na base de dados']);
            return;
        }

        $disponibilidade = $this->Admin_model->get_disponibilidade_medico($medico['ID_Medico']);
        echo json_encode($disponibilidade);
    }

    public function salvar_minha_disponibilidade()
    {
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        // Verificar se é médico
        $user_id = $this->session->userdata('user_id');
        $tipo_usuario = $this->session->userdata('tipo_usuario');

        if ($tipo_usuario !== 'Medico') {
            echo json_encode(['error' => 'Acesso não autorizado']);
            return;
        }

        $medico = $this->db->where('ID_Usuario', $user_id)->get('Medicos')->row_array();
        if (!$medico) {
            echo json_encode(['error' => 'Médico não encontrado']);
            return;
        }

        // Verificar se o médico existe na tabela Medicos
        if (!$this->Admin_model->medico_existe($medico['ID_Medico'])) {
            echo json_encode(['error' => 'Médico não encontrado na base de dados']);
            return;
        }

        $input = json_decode($this->input->raw_input_stream, true);
        $disponibilidade = $input['disponibilidade'] ?? [];

        // Log para debug
        log_message('debug', 'Tentando salvar disponibilidade para médico ID: ' . $medico['ID_Medico']);
        log_message('debug', 'Dados recebidos: ' . print_r($disponibilidade, true));

        $result = $this->Admin_model->salvar_disponibilidade($medico['ID_Medico'], $disponibilidade);
        echo json_encode($result);
    }


    // Método temporário para debug - remova depois
    public function debug_disponibilidade()
    {
        $this->output->set_content_type('application/json');

        $user_id = $this->session->userdata('user_id');
        $medico = $this->db->where('ID_Usuario', $user_id)->get('Medicos')->row_array();

        if (!$medico) {
            echo json_encode(['error' => 'Médico não encontrado']);
            return;
        }

        $debug_info = [
            'user_id' => $user_id,
            'medico' => $medico,
            'tabela_horarios_existe' => $this->db->table_exists('horarios'),
            'medico_existe' => $this->Admin_model->medico_existe($medico['ID_Medico']),
            'disponibilidade_atual' => $this->Admin_model->get_disponibilidade_medico($medico['ID_Medico'])
        ];

        echo json_encode($debug_info);
    }
}
