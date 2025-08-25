<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medico extends CI_Controller {


	public function index()
	{
		$this->load->view('medicos/disponibilidade');
	}
}
