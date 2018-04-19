<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Credit_Account{

    public $voucher_id;
    public $credit_amount;
    public $related_agent_type;
    public $related_agent_id;

    public $ci;

    function __construct(){
        //$this->ci =& get_instance();

        //setting default values
        $this->credit_amount = 0;


    }

    public function get_related_agent_name()
    {
        return "test name";
    }

}
