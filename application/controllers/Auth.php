<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model'); // Carrega o model
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    public function register()
    {
        // Se for GET, carrega a view (opcional, mas bom para fallback)
        if ($this->input->method() !== 'post') {
            $this->load->view('auth/register'); // Assumindo que a view está em application/views/register.php
            return;
        }

        // Validação dos campos (básica; você pode expandir com form_validation library)
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim');
        $this->form_validation->set_rules('sobrenome', 'Sobrenome', 'required|trim');
        $this->form_validation->set_rules('data_nascimento', 'Data de Nascimento', 'required');
        $this->form_validation->set_rules('genero', 'Gênero', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('bi', 'BI', 'required|trim|is_unique[pacientes.bi]'); // Evita BI duplicado
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|is_unique[usuarios.email]'); // Evita email duplicado
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            // Erros de validação: volta para a view com erros
            $this->load->view('auth/register');
            return;
        }

        // Prepara dados para pacientes
        $paciente_data = array(
            'nome' => $this->input->post('nome'),
            'sobrenome' => $this->input->post('sobrenome'),
            'data_nascimento' => $this->input->post('data_nascimento'),
            'genero' => $this->input->post('genero'),
            'telefone' => $this->input->post('telefone'),
            'bi' => $this->input->post('bi')
        );

        // Insere na tabela pacientes e obtém o ID gerado
        $paciente_id = $this->Auth_model->insert_paciente($paciente_data);
        if (!$paciente_id) {
            $this->session->set_flashdata('error', 'Erro ao criar perfil de paciente. Tente novamente.');
            redirect('auth/register');
            return;
        }

        // Prepara dados para usuarios (senha hasheada)
        $senha_hash = password_hash($this->input->post('senha'), PASSWORD_DEFAULT);
        $usuario_data = array(
            'email' => $this->input->post('email'),
            'senha' => $senha_hash,
            'tipo_usuario' => 'Paciente',
            'id_referencia' => $paciente_id
        );

        // Insere na tabela usuarios
        if ($this->Auth_model->insert_usuario($usuario_data)) {
            $this->session->set_flashdata('success', 'Conta criada com sucesso! Faça login para continuar.'); // Opcional: mensagem de sucesso
            redirect('auth/login');
        } else {
            // Erro na inserção de usuario: remove o paciente criado para manter integridade (rollback manual)
            $this->Auth_model->delete_paciente($paciente_id);
            $this->session->set_flashdata('error', 'Erro ao criar credenciais. Tente novamente.');
            redirect('auth/register');
        }
    }

    // Método para login (básico, para completar o controller)
    public function login()
    {
        // Debug: Loga método e AJAX
        log_message('debug', 'Método da requisição: ' . $this->input->method());
        log_message('debug', 'É AJAX? ' . ($this->input->is_ajax_request() ? 'SIM' : 'NÃO'));

        // Se GET, carrega view
        if ($this->input->method() !== 'post') {
            $this->load->view('auth/login');
            return;
        }

        // Validação
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $error_msg = validation_errors() ?: 'Dados inválidos.';

            if ($this->input->is_ajax_request()) {
                log_message('debug', 'Enviando JSON erro de validação');
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => $error_msg
                ]);
                exit(0);
            }

            $this->session->set_flashdata('error', $error_msg);
            $this->load->view('auth/login');
            return;
        }

        $email = $this->input->post('email');
        $senha = $this->input->post('senha');

        $usuario = $this->Auth_model->get_usuario_by_email($email);
        log_message('debug', 'Usuário completo: ' . print_r($usuario, true));  // Debug: mostra o objeto inteiro
        log_message('debug', 'senha acessada: ' . (isset($usuario->senha) ? 'EXISTS, valor: ' . substr($usuario->senha, 0, 10) . '...' : 'NÃO EXISTS'));
        log_message('debug', 'senha empty? ' . (empty($usuario->senha) ? 'SIM' : 'NÃO'));

        log_message('debug', 'Usuário encontrado para ' . $email . ': ' . ($usuario ? 'SIM (Id_Usuario: ' . ($usuario->Id_Usuario ?? 'sem ID') . ')' : 'NÃO'));

        if (!$usuario) {
            $error_msg = 'E-mail não encontrado. Verifique e tente novamente.';

            if ($this->input->is_ajax_request()) {
                log_message('debug', 'Enviando JSON erro: usuário não encontrado');
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => $error_msg
                ]);
                exit(0);
            }

            $this->session->set_flashdata('error', $error_msg);
            redirect('auth/login');
            return;
        }

        // Checa senha com segurança
if (!isset($usuario->Senha) || empty($usuario->Senha)) {
    $error_msg = 'Conta com problema. Contate suporte.';
    
    if ($this->input->is_ajax_request()) {
        log_message('debug', 'Enviando JSON erro: senha ausente');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $error_msg
        ]);
        exit(0);
    }
    
    $this->session->set_flashdata('error', $error_msg);
    redirect('auth/login');
    return;
}

if (!password_verify($senha, $usuario->Senha)) {  // Mude para ->Senha
    $error_msg = 'Senha inválida.';
    
    if ($this->input->is_ajax_request()) {
        log_message('debug', 'Enviando JSON erro: senha incorreta');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $error_msg
        ]);
        exit(0);
    }
    
    $this->session->set_flashdata('error', $error_msg);
    redirect('auth/login');
    return;
}

        // Sucesso: usa Id_Usuario na sessão
        $this->session->set_userdata([
            'user_id' => $usuario->Id_Usuario,  // Ajustado para Id_Usuario
            'email' => $usuario->email,
            'tipo_usuario' => $usuario->tipo_usuario,
            'paciente_id' => $usuario->id_referencia,
            'logged_in' => TRUE
        ]);

        if ($this->input->is_ajax_request()) {
            log_message('debug', 'Enviando JSON sucesso');
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Login efetuado com sucesso!'
            ]);
            exit(0);
        }

        $this->session->set_flashdata('success', 'Bem-vindo!');
        redirect('agenda');
    }

    // Logout (opcional)
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
