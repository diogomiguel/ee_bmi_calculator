<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD . 'bmi_calculator/config.php';

class Bmi_calculator_upd {
	public $name    = BMI_CALCULATOR_NAME;
	public $version = BMI_CALCULATOR_VERSION;

	function __construct() {
		$this->EE =& get_instance();
	}

	function install() {

		// install module 
    	$this->EE->db->insert(
        	'modules',
        	array(
            	'module_name' => 'Bmi_calculator',
            	'module_version' => $this->version, 
            	'has_cp_backend' => 'n',
            	'has_publish_fields' => 'n'
        	)
    	);

		// register form actions
		$this->EE->db->insert('actions', array(
			'class'		=> 'Bmi_calculator',
			'method'	=> 'form_submission'));
		
		return TRUE;
	}

	

	function update($current = '') {
		if (empty($current)) return FALSE;

		
		if ($current < $this->version) return TRUE;
		else return FALSE;
	}

	function uninstall() {
		$this->EE->load->dbforge();

		// delete module table
		$this->EE->db->where('module_name', "Bmi_calculator");
		$this->EE->db->delete('modules');

		// delete form submission class
		$this->EE->db->where('class', 'Bmi_calculator');
		$this->EE->db->delete('actions');




		return TRUE;
	}
}

/* End of file upd.bmi_calculator.php */ 
/* Location: ./system/expressionengine/third_party/bmi_calculator/upd.bmi_calculator.php */ 