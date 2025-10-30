<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secretario extends CI_Controller {


	public function index()
	{
		$this->load->view('secretario/dashboard');
	}

	public function agendamentos()
	{
		$this->load->view('secretario/agendamentos');
	}
	
	public function pacientes()
	{
		$this->load->view('secretario/pacientes');
	}

	public function medicos()
	{
		$this->load->view('secretario/medico');
	}
}
