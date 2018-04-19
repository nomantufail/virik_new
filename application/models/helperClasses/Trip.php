<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */

/*
 *  including required classes
 */
include_once(APPPATH."models/helperClasses/TripDates.php");
include_once(APPPATH."models/helperClasses/Company.php");
include_once(APPPATH."models/helperClasses/Customer.php");
include_once(APPPATH."models/helperClasses/Contractor.php");


class Trip{

    public $trip_id;
    public $type;
    public $customer;
    public $contractor;
    public $company;
    public $tanker;
    public $driver_1;
    public $driver_2;
    public $driver_3;

    public $start_meter;
    public $end_meter;
    public $fuel_consumed;

    public $dates;

    public $invoice_number;
    //this array will contain trip_related_details objects
    public $trip_related_details;


    public $ci;

    function __construct(){
        //$this->ci =& get_instance();

        //setting default values
        $this->trip_related_details = array();
        $this->dates = new TripDates("","","","","","","");
        $this->company = new Company("","","","");
        $this->customer = new Customer("","","");
        $this->contractor = new Contractor("","","");

    }

    function get_contractor_freight_according_to_company()
    {
        return (100 - $this->company->wht);
    }

    function is_complete()
    {
        $complete = true;
        foreach($this->trip_related_details as $detail)
        {
            if($detail->stn_number == '')
            {
                $complete = false;
            }
        }
        return $complete;
    }

}
