<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!defined('BMI_CALCULATOR_VERSION')) {
    // get the version from config.php
    require PATH_THIRD . 'bmi_calculator/config.php';
    define('BMI_CALCULATOR_VERSION', $config['version']);
    define('BMI_CALCULATOR_NAME', $config['name']);
}

/**
 * BMI Calculator Module
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Modules
 * @author		Diogo Silva
 * @copyright	Copyright (c) 2014 Diogo Silva
 * @link		https://github.com/diogomiguel/ee_bmi_calculator
 */

class Bmi_calculator
{
    
    function Bmi_calculator()
    {
        $this->EE =& get_instance();
    }
    
    function index()
    {
    }
    
}
/* End of file mcp.bmi_calculator.php */
/* Location: ./system/expressionengine/third_party/bmi_calculator/mcp.bmi_calculator.php */