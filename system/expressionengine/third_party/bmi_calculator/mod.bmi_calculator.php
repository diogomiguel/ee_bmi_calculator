<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BMI Calculator Module
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Modules
 * @author		Diogo Silva
 * @copyright	Copyright (c) 2014 Twentyfoursquare
 * @link		http://24sq.com
 */

class Bmi_calculator {

	private $EE;
	public $error_msgs;
	private $unit = 'metric';
	
	function __construct() {
		$this->EE =& get_instance();
		$this->EE->lang->loadfile('bmi_calculator');
		$this->EE->load->library('form_validation');
		$this->EE->load->helper('form');
				
	}

	function bmi_form() {
		
		
		$form_id = $this->EE->TMPL->fetch_param('form_id');
		$form_class = $this->EE->TMPL->fetch_param('form_class');
		
		

		$hidden_fields = array(
			'return_url' => $this->EE->TMPL->fetch_param('return')
		);

		// If none generate return URL from current URL
		if ($hidden_fields['return_url'] === FALSE) {
			$hidden_fields['return_url'] = $this->EE->uri->uri_string;
		}
		
		// Build an array with the form data
	    $form_data = array(
	        "id" => $form_id,
	        "class" => $form_class,
	        "hidden_fields" => $hidden_fields,
			'action' => $this->EE->functions->fetch_site_index().QUERY_MARKER.
						'ACT='.$this->EE->functions->fetch_action_id('Bmi_calculator', 'form_submission')
	    );

		// start our form output
		$out = $this->EE->functions->form_declaration($form_data);
		
		//Restore POST Variable
		if ($this->EE->session->flashdata('post') && !$this->EE->input->post('title')) {
			$_POST = $this->EE->session->flashdata('post');
		}
		
		// display any error messages from a submitted form
		$form_error = $this->EE->session->flashdata('form_error');
		if (!empty($form_error)) {
			$out .= '<div id="form-bmi-errors"><p><span>' . lang('bmi_calculator_form_amend') . '</span></p><ul>'.$form_error.'</ul></div>';
		}
		
		$tag_vars = array();
		
		// parse tagdata variables
		$out .= $this->EE->TMPL->tagdata;

		// end form output and return
		$out .= '</form>';
		return $out;
	}
	
	
	

	function form_submission()
	{
		
		$return_url = $this->EE->functions->create_url($this->EE->input->post('return_url', TRUE));
		
		// Get all post data
		foreach($_POST as $key => $value) {
			$data[$key] = $this->EE->input->post($key);
		}

		$valid = $this->_validate_bmi_submission();
		$bmi = FALSE;
		
		if (!$valid) {
			//Persist POST
			$this->EE->session->set_flashdata('post', $data);
			
		} else {
			//Calculate depending on selected metric system
			if ($this->unit == "metric") {
				$w = (int)$this->EE->input->post('kg');
				$h = (int)$this->EE->input->post('cm');
			} else {
				$w = (int)$this->EE->input->post('st') * 14 + (int)$this->EE->input->post('lb');
				$h = (int)$this->EE->input->post('ft') * 12 + (int)$this->EE->input->post('in');
			}
			$bmi = $this->_calculate_bmi($w, $h, $this->unit);
			$this->EE->session->set_flashdata('bmi', $bmi);
		}
		
		//Is AJAX? => RETURN JSON IF NOT REDIRECT
		if ($this->EE->input->is_ajax_request()) {
			if ($bmi === FALSE) {
				$response = array('success' => false, 'errors' => lang('bmi_calculator_error'));
			} else {
				$response = array('success' => true, 'bmi' => $bmi);
			}
			header('Content-type: application/json');
			echo json_encode($response);
			die();
		} else {
			$this->EE->functions->redirect($return_url);
		}
		
	}	
	
	function my_bmi() {
	
		//Returns session saved bmi 
		if ($this->EE->session->flashdata('bmi')) {
			return $this->EE->session->flashdata('bmi');
		} else {
			return 'XX.X';
		}
	}
	
	function _validate_bmi_submission() {
		$this->error_msgs = '';
		//Validation Rules
		
		//Metric
		if ($this->EE->input->post('cm') != '') {
			$config = array( 
				array(
					'field' => 'cm',
					'label' => lang('label_cm'),
					'rules' => 'required|is_natural_no_zero|max_length[3]'
				),
				array(
					'field' => 'kg',
					'label' => lang('label_kg'),
					'rules' => 'required|is_natural_no_zero|max_length[3]'
				)
			);
		}
		//Imperial
		else
		{
			$this->unit = 'imperial';
			$config = array( 
				array(
					'field' => 'ft',
					'label' => lang('label_ft'),
					'rules' => 'required|is_natural_no_zero|max_length[1]'
				),
				array(
					'field' => 'in',
					'label' => lang('label_in'),
					'rules' => 'required|is_natural|max_length[2]'
				),
				array(
					'field' => 'st',
					'label' => lang('label_st'),
					'rules' => 'required|is_natural_no_zero|max_length[2]'
				),
				array(
					'field' => 'lb',
					'label' => lang('label_lb'),
					'rules' => 'required|is_natural|max_length[2]'
				),
			);
		}
	
		
		$this->EE->form_validation->set_rules($config); 
		$this->EE->form_validation->set_error_delimiters('<li class="error">', '</li>');
		
		if ($this->EE->form_validation->run() == FALSE) {
			$this->error_msgs .= $this->EE->form_validation->error_string();
			$this->EE->session->set_flashdata('form_error', $this->error_msgs);
			return false;
		}
		
		
		
		return true;
	}
	
	private function _calculate_bmi($w, $h, $u = "metric", $r = 2) {
		
		if(!is_numeric($w) || !is_numeric($h)) {
       		return 0;
       	}
		
		// Return BMI value
		switch($u) { 
			case "imperial": 
				return round((($w*703)/($h*$h)), $r);
				break;
			case "metric":
				return round(($w/($h / 100 *$h / 100)), $r); 
				break;
		}
	}
	
}

/* End of file mod.bmi_calculator.php */ 
/* Location: ./system/expressionengine/third_party/bmi_calculator/mod.bmi_calculator.php */ 