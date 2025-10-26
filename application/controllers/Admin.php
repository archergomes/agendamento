<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
	}

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
		$this->load->view('admin/cad_paciente');
	}

	public function cad_secretario()
	{
		$this->load->view('admin/cad_secretario');
	}

	public function cad_medico()
	{
		$this->load->view('admin/cad_medico');
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

	public function cancel_appointment()
	{
		// Force JSON output header sempre, antes de qualquer coisa
		$this->output->set_content_type('application/json');

		if ($this->input->method() !== 'post') {
			$this->output->set_status_header(405)->set_output(json_encode(['error' => 'Método não permitido']));
			return;
		}

		// ... resto do código igual ...

		// No else do result:
		// Já tem set_content_type, mas garanta:
		$this->output
			->set_status_header(500)
			->set_output(json_encode(['error' => 'Erro ao cancelar agendamento']));
	}

	// Assuming you also need delete_appointment if not already implemented
	public function delete_appointment()
	{
		if ($this->input->method() !== 'post') {
			show_error('Método não permitido', 405);
		}

		// Parse JSON input
		$headers = $this->input->request_headers();
		if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'application/json') !== false) {
			$input = json_decode($this->input->raw_input_stream, true);
		} else {
			$input = $this->input->post();
		}

		$id = $input['id'] ?? null;
		if (!$id) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['error' => 'ID do agendamento não fornecido']));
			return;
		}

		$result = $this->Admin_model->delete_appointment($id);
		if ($result) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['success' => 'Agendamento excluído com sucesso']));
		} else {
			$this->output
				->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode(['error' => 'Erro ao excluir agendamento']));
		}
	}
}
