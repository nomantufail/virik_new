<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Customer_Report_Details {

    public $trip_date;
    public $route;
    public $tanker_number;
    public $product_quantity;
    public $stn;
    public $freight_unit;
    public $product;
    public $total_freight;
    public $total_freights;
    public $contractor_commission;
    public $total_contractor_freight;
    public $total_customer_freight;
    public $customer_commission;
    public $customer_freights;
    public $contractor_freights;
    public $paid_to_customer;
    public $customer_remaining;
    public $total_customer_remaining;
    public $total_paid_to_customer;
    public $total_remaining;
    public $trip_id;
    public $ci;

    function Customer_Report_Details($trip){
        $this->ci =& get_instance();

        $this->total_customer_freight = 0;
        $this->total_paid_to_customer = 0;
        $this->total_contractor_freight = 0;
        $this->total_freight = 0;
        $this->set_data($trip);

    }

    private function  set_data($trip){
        //print_r($trip);
        $this->trip_id = $trip->trip_id;
        $this->trip_date = $trip->entry_date;
        $this->tanker_number = $trip->tanker_number;

        $this->contractor_commission = $trip->contractor_commission;
        $this->customer_commission = 100 - $this->contractor_commission;

        $counter = 0;
        foreach($trip->trip_related_details as $trip_detail){
            $counter++;

            $total_freights = $trip_detail->freight_unit * $trip_detail->product_quantity;
            $this->total_freight += $total_freights;

            $paid_to_customer = 0;
            $payments = $this->ci->accounts_model->customer_payments($trip_detail->trip_details_id, $trip->customerId);
            foreach($payments as $payment){
                $paid_to_customer = $paid_to_customer + $payment->amount;
                $this->total_paid_to_customer += $payment->amount;
            }

            $contractor_freights = ($total_freights * $this->contractor_commission/100);
            $customer_freights = ($total_freights * $this->customer_commission/100);

            $this->total_customer_freight += $customer_freights;
            $this->total_contractor_freight += $contractor_freights;

            $customer_remaining = round(($customer_freights - $paid_to_customer) *1000)/1000;
            $this->total_customer_remaining += $customer_remaining;

            $stn_num = ($trip_detail->stn_number == '')?'n/a':$trip_detail->stn_number;

            $class = ($counter == sizeof($trip->trip_related_details))?"":'multiple_entites';
            $this->stn = $this->stn."<div class=$class>".$stn_num."</div>";
            $this->freight_unit = $this->freight_unit."<div class=$class>".$trip_detail->freight_unit."</div>";
            $this->total_freights = $this->total_freights."<div class=$class>".$total_freights."</div>";
            $this->customer_freights = $this->customer_freights."<div class=$class>".$this->customer_commission."% = ".$customer_freights."</div>";
            $this->contractor_freights = $this->contractor_freights."<div class=$class>".$this->contractor_commission."% = ".$contractor_freights."</div>";
            $this->paid_to_customer = $this->paid_to_customer."<div class=$class>".$paid_to_customer."</div>";
            $this->customer_remaining = $this->customer_remaining."<div class=$class>".$customer_remaining."</div>";
            $this->route = $this->route."<div class=$class>".$trip_detail->sourceCity." To ".$trip_detail->destinationCity."</div>";
            $this->product_quantity = $this->product_quantity."<div class=$class>".$trip_detail->product_quantity."</div>";
            $this->product = $this->product."<div class=$class>".$trip_detail->product."</div>";
        }

    }

}