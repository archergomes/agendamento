<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {


	public function index()
	{
		$this->load->view('agenda/agenda');
	}

	public function agendamentos(){
		$this->load->view('agenda/agendamentos');
	}

	public function perfil(){
		$this->load->view('agenda/perfil');
	}
}
