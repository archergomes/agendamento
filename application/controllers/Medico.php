<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medico extends CI_Controller {


	public function index()
	{
		$this->load->view('medicos/dashboard');
	}

	public function disponibilidade()
	{
		$this->load->view('medicos/disponibilidade');
	}

	public function consultas()
	{
		$this->load->view('medicos/m_consultas');
	}

	public function pacientes()
	{
		$this->load->view('medicos/m_pacientes');
	}

	public function prontuarios()
	{
		$this->load->view('medicos/disponibilidade');
	}

	public function prescricoes()
	{
		$this->load->view('medicos/prescricoes');
	}

	public function laudos()
	{
		$this->load->view('medicos/laudos');
	}

	public function horarios()
	{
		$this->load->view('medicos/meus_horarios');
	}

	public function relatorios()
	{
		$this->load->view('medicos/relatorios');
	}

	public function perfil()
	{
		$this->load->view('medicos/perfil');
	}
}
