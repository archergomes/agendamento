<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agenda extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Auth_model', 'Agendamentos_model']);  // Carrega models necessários
        $this->load->library('session');
        $this->load->helper('url');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // public function index()
    // {
    //     $data['paciente'] = $this->Auth_model->get_paciente_by_id_referencia($this->session->userdata('paciente_id'));
    //     $data['active_menu'] = 'home';  // Para sidebar dinâmico
    //     $this->load->view('agenda/agenda', $data);
    // }

    public function index()
    {
        $data['paciente'] = $this->Auth_model->get_paciente_by_id_referencia($this->session->userdata('paciente_id'));
        
        // CORREÇÃO: Carrega especialidades do model com fallback
        $data['especialidades'] = $this->Agendamentos_model->get_especialidades();
        if (empty($data['especialidades'])) {
            // Fallback hardcoded se BD falhar (como no exemplo original)
            $data['especialidades'] = (object) [
                (object) ['id' => 1, 'nome' => 'Medicina Geral'],
                (object) ['id' => 2, 'nome' => 'Cardiologia'],
                (object) ['id' => 3, 'nome' => 'Pediatria'],
                (object) ['id' => 4, 'nome' => 'Ortopedia'],
                (object) ['id' => 5, 'nome' => 'Ginecologia'],
                (object) ['id' => 6, 'nome' => 'Neurologia'],
                (object) ['id' => 7, 'nome' => 'Cirurgia Geral']
            ];
        }
        
        $data['active_menu'] = 'home';
        $this->load->view('agenda/agenda', $data);  // Agora $data tem 'especialidades'
    }                           

    public function agendamentos()
    {
        $data['paciente'] = $this->Auth_model->get_paciente_by_id_referencia($this->session->userdata('paciente_id'));
        $data['agendamentos'] = $this->Agendamentos_model->get_appointments_by_patient($this->session->userdata('paciente_id'));
        $data['active_menu'] = 'agendamentos';  // Para sidebar dinâmico
        $this->load->view('agenda/agendamentos', $data);
    }

    // public function perfil()
    // {
    //     $data['paciente'] = $this->Auth_model->get_paciente_by_id_referencia($this->session->userdata('paciente_id'));
    //     $data['active_menu'] = 'perfil';  // Para sidebar dinâmico
    //     $this->load->view('agenda/perfil', $data);
    // }

    // AJAX: Busca médicos por especialidade ou query (com paginação)
    public function get_doctors()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $query = $this->input->post('q', TRUE) ?: '';
        $specialty = $this->input->post('specialty', TRUE) ?: '';
        $limit = (int) $this->input->post('limit', TRUE) ?: 10;
        $offset = (int) $this->input->post('offset', TRUE) ?: 0;

        // Conta total
        $total = $this->Agendamentos_model->count_medicos($specialty, $query);

        $medicos = $this->Agendamentos_model->get_medicos($specialty, $query, $limit, $offset);

        if (empty($medicos)) {
            $response = ['status' => 'error', 'message' => 'Nenhum médico encontrado.', 'data' => [], 'total' => $total];
        } else {
            $formatted = array_map(function ($medico) {
                return [
                    'id' => $medico->ID_Medico,
                    'name' => $medico->Nome . ' ' . $medico->Sobrenome,
                    'specialty' => $medico->Especialidade,
                    'experience' => '5 anos',  // Ajuste se tiver coluna
                    'image' => base_url('assets/img/default-doctor.jpg')
                ];
            }, $medicos);

            $response = ['status' => 'success', 'data' => $formatted, 'total' => $total];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // AJAX: Busca horários disponíveis para data/médico
    public function get_available_slots()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $data_consulta = $this->input->post('data', TRUE);
        $medico_id = $this->input->post('medico_id', TRUE);

        $slots = $this->Agendamentos_model->get_available_slots($data_consulta, $medico_id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success', 'data' => $slots]));
    }

    // AJAX: Lista agendamentos do paciente logado
    public function get_patient_appointments()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $paciente_id = $this->session->userdata('paciente_id');
        $appointments = $this->Agendamentos_model->get_appointments_by_patient($paciente_id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success', 'data' => $appointments]));
    }

    // AJAX: Salva agendamento
    public function save_appointment()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        header('Content-Type: application/json');

        try {
            // Pega dados do POST
            $especialidade = $this->input->post('especialidade', TRUE);
            $medico_id = (int) $this->input->post('medico', TRUE);
            $data_consulta = $this->input->post('data_consulta', TRUE);
            $horario = $this->input->post('horario', TRUE);
            $nome = $this->input->post('nome', TRUE);
            $telefone = $this->input->post('telefone', TRUE);
            $bi = $this->input->post('bi', TRUE);
            $motivo = $this->input->post('motivo', TRUE);

            // Validação
            if (empty($especialidade) || empty($medico_id) || empty($data_consulta) || empty($horario) || empty($nome) || empty($telefone) || empty($bi)) {
                throw new Exception('Dados incompletos. Preencha todos os campos.');
            }

            // Pega ID_Paciente da sessão
            $paciente_id = $this->session->userdata('paciente_id');
            if (!$paciente_id) {
                throw new Exception('Sessão inválida. Faça login novamente.');
            }

            // Verifica disponibilidade
            if (!$this->Agendamentos_model->is_slot_available($data_consulta, $horario, $medico_id)) {
                throw new Exception('Horário já ocupado. Escolha outro.');
            }

            // Dados para insert
            $data_insert = [
                'ID_Paciente' => $paciente_id,
                'ID_Medico' => $medico_id,
                'Data_Agendamento' => $data_consulta,
                'Hora_Agendamento' => $horario,
                'Status' => 'Pendente',
                'Motivo' => !empty($motivo) ? $motivo : NULL
            ];

            // Salva e checa retorno
            $result = $this->Agendamentos_model->create_agendamento($data_insert);
            if (isset($result['success'])) {
                $this->output->set_output(json_encode(['status' => 'success', 'message' => $result['success']]));
            } else {
                throw new Exception($result['error'] ?? 'Erro ao salvar no banco de dados.');
            }
        } catch (Exception $e) {
            log_message('error', 'Erro no save_appointment: ' . $e->getMessage());
            $this->output->set_output(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
        }
    }

    // AJAX: Cancelar agendamento
    public function cancelar_agendamento()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        header('Content-Type: application/json');
        $id = (int) $this->input->post('id', TRUE);
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
            return;
        }
        $result = $this->Agendamentos_model->cancel_appointment($id);
        if (isset($result['success'])) {
            echo json_encode(['status' => 'success', 'message' => $result['success']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['error']]);
        }
    }

    public function perfil()
    {
        // Verificar se o usuário está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $paciente_id = $this->session->userdata('paciente_id');

        // Carregar dados do paciente
        $this->load->model('Auth_model');
        $data['paciente'] = $this->Auth_model->get_paciente_by_id($paciente_id);

        $data['active_menu'] = 'perfil';
        $this->load->view('agenda/perfil', $data);
    }

    public function atualizar_perfil()
    {
        // Verificar se o usuário está logado
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
            return;
        }

        $paciente_id = $this->session->userdata('paciente_id');

        $this->load->model('Auth_model');

        // Validar dados
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('bi', 'BI', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        // Preparar dados para atualização
        $dados_atualizacao = array(
            'Nome' => $this->input->post('nome'),
            'Telefone' => $this->input->post('telefone'),
            'BI' => $this->input->post('bi')
        );

        // Atualizar paciente
        if ($this->Auth_model->update_patient($paciente_id, $dados_atualizacao)) {
            echo json_encode(['status' => 'success', 'message' => 'Perfil atualizado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar perfil.']);
        }
    }
}
