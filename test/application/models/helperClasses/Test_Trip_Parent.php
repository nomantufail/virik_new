<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Test_Trip_Parent {

    public $contractorName;
    public $customerName;
    public $companyName;
    public $contractorId;
    public $companyId;
    public $customerId;
    public $route;
    public $route_id;
    public $filling_date;
    public $entry_date;
    public $email_date;
    public $receiving_date;
    public $stn_receiving_date;
    public $decanding_date;
    public $invoice_date;
    public $total_freight;
    public $total_freight_for_company;
    public $freight_unit;
    public $company_commission_1;
    public $company_commission_2;
    public $company_commission_3;
    public $company_freight;
    public $contractor_commission;
    public $contractor_commission_1;
    public $contractor_commission_2;
    public $driver_id_1;
    public $driver_name_1;
    public $driver_id_2;
    public $driver_name_2;
    public $driver_id_3;
    public $driver_name_3;
    public $customer_freight;
    public $final_quantity;
    public $trip_id;
    public $invoice_number;
    public $tanker_number;
    public $tanker_id;
    public $paid_to_customer;
    public $paid_to_contractor;
    public $payment_to_customer_date;
    public $complete;

    //this array will contain trip_related_details objects
    public $trip_related_details;

    public $ci;

    function Test_Trip_Parent(){
        $this->ci =& get_instance();

        //setting default values
        $this->trip_related_details = array();
        $this->total_freight = 0;
        $this->total_freight_for_company = 0;

        $this->complete = true;

    }

    public function set_data($r){

        //check weather the trip is complete or not
        if($r->stn_number == ''){ $this->complete = false; }

        //setting trip_id
        $this->trip_id = $r->trip_id;
        //calculating total_freight
        $this->total_freight += ($r->product_quantity * $r->freight_unit);
        //calculating total_freight_for_company
        $this->total_freight_for_company += ($r->company_freight_unit * $r->product_quantity);
        //setting tip_unique_data (this class variables)
        $this->customerId = $r->customer_id;
        $this->customerName = $r->customerName;

        $this->contractorId = $r->contractor_id ;
        $this->contractorName = $r->contractorName ;

        $this->companyId = $r->company_id ;
        $this->companyName = $r->companyName ;

        $this->tanker_id = $r->tanker_id ;
        $this->tanker_number = $r->tanker_number ;

        $this->entry_date = $r->entryDate ;
        $this->filling_date = $r->filling_date ;
        $this->receiving_date = $r->receiving_date ;
        $this->decanding_date = $r->decanding_date ;
        $this->stn_receiving_date = $r->stn_receiving_date ;
        $this->email_date = $r->email_date ;
        $this->invoice_date = $r->invoice_date ;

        $this->contractor_commission = $r->contractor_commission ;
        $this->contractor_commission_2 = $r->contractor_commission_2 ;
        $this->company_commission_1 = $r->company_commission_1 ;
        $this->company_commission_2 = $r->company_commission_2 ;
        $this->company_commission_3 = $r->company_commission_3 ;
        $this->company_freight = ($r->company_commission_1 +$r->company_commission_2+ $r->company_commission_3);

        $this->customer_freight = 100 - $this->contractor_commission;

        $this->driver_id_1 = $r->driver_id_1 ;
        $this->driver_name_1 = $r->driver_2_name ;

        $this->driver_id_2 = $r->driver_id_2 ;
        $this->driver_name_2 = $r->driver_2_name ;

        $this->driver_id_3 = $r->driver_id_3 ;
        $this->driver_name_3 = $r->driver_3_name ;

        $this->invoice_number = $r->invoice_number;

        $this->paid_to_customer = ($r->paid_to_customer != '')?$r->paid_to_customer:0;
        $this->payment_to_customer_date = $r->payment_to_customer_date;
    }

}
