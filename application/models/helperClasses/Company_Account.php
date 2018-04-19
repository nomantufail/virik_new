<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Company_Account{

    public $account_id;
    public $amount_paid;
    public $payment_date;

    public $ci;

    function __construct(){
        //$this->ci =& get_instance();

        //setting default values
        $this->amount_paid = 0;


    }


}
