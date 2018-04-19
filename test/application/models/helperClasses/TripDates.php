<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class TripDates{

    public $email_date;
    public $filling_date;
    public $receiving_date;
    public $stn_receiving_date;
    public $decanding_date;
    public $invoice_date;
    public $entry_date;

    public $ci;

    function __construct($email, $filling, $receiving, $stn_receiving, $decanding, $invoice, $entry){
        //$this->ci =& get_instance();

        $this->email_date = $email;
        $this->filling_date = $filling;
        $this->receiving_date = $receiving;
        $this->stn_receiving_date = $stn_receiving;
        $this->decanding_date = $decanding;
        $this->invoice_date = $decanding;
        $this->entry_date = $entry;

    }


}
