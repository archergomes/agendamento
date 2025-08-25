<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		// Adicione autenticação, se necessário (ex.: ion_auth)
		// if (!$this->ion_auth->logged_in()) {
		//     redirect('login');
		// }
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

	/*	public function index()
		{
			$this->load->view('admin/dashboard');
		}

		public function pacientes()
		{
			$this->load->view('admin/pacientes');
		}

		public function medicos()
		{
			$this->load->view('admin/medicos');
		}
		public function secretatios()
		{
			$this->load->view('admin/secretarios');
		}
		public function agendamentos()
		{
			$this->load->view('admin/agendamentos');
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

		public function cad_agendamento(){
			$this->load->view('admin/cad_agendamentos');
		}*/


}
