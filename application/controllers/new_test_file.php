<?php
/**
 * Created by ZeenomLabs.
 * User: Noman Tufail
 * Date: 2/19/15
 * Time: 7:05 AM
 */
namespace ;

class new_test_file extends CI_Controller {

 public function __construct()
    {
        parent::__construct();

        include_once("libraries/php/helper.php");

        $this->load->helper(array('form', 'url', 'captcha'));
        $this->load->library(array('form_validation','email','Carbon','Helper','pagination'));
        $this->load->model(array(
            'admin_model',
            'accounts_model',
            'trips_model',
            'customers_model',
            'companies_model',
            'tankers_model',
            'carriageContractors_model',
            'manageCommissions_model',
            'routes_model',
            'drivers_model',
            'helper_model',
            'otherAgents_model',
        ));

        //checking the login state below...
        $this->login = $this->helper_model->is_login();

    }

    function _remap($method){
        if($this->login == false){
            $this->login();
        }else{
            $this->_call_with_args($method);
        }
    }

}