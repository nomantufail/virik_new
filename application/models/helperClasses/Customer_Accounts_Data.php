<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Customer_Accounts_Data {

    public $contractorName;
    public $companyName;
    public $contractorId;
    public $companyId;
    public $route;
    public $tanker_number;
    public $trip_date;
    public $filling_date;
    public $entry_date;
    public $total_freight;
    public $customer_commission;
    public $customer_freights;
    public $received;
    public $received_dates;
    public $total_received;
    public $trip_id;
    public $payments;
    public $product;
    public $total_freights;
    public $add_payment_btn;
    public $ci;

    function Customer_Accounts_Data($trips){
        $this->ci =& get_instance();

        $this->total_freight = 0;
        $this->total_received = 0;
        $this->set_data($trips);

    }

    private function  set_data($trip){

        $this->trip_id = $trip->trip_id;
        //$route = $this->ci->routes_model->route($trip->route_id);
        //$this->route = $trip->trip_related_details[0]->sourceCity." to ".$trip->trip_related_details[0]->destinationCity;
        //$tanker = $this->ci->tankers_model->tanker($trip->tanker_id);
        $this->tanker_number = $trip->tanker_number;
        $this->trip_date = $trip->decanding_date;
        $this->filling_date = $trip->filling_date;
        $this->entry_date = $trip->entry_date;
        $company = $this->ci->companies_model->company($trip->companyId);
        $this->companyName = $trip->companyName;
        //$contractor = $this->ci->carriageContractors_model->carriageContractor($trip->contractorId);
        $this->contractorName = $trip->contractorName;
        $this->contractorId = $trip->contractorId;
        $this->companyId = $trip->companyId;
        //$this->total_freight = $trip->freight_unit * $trip->product_quantity;
        $this->customer_commission = 100 - ($trip->contractor_commission +$trip->contractor_commission_1+ $trip->contractor_commission_2);


        $counter = 0;
        foreach($trip->trip_related_details as $trip_detail){
            $total_freights = $trip_detail->freight_unit * $trip_detail->product_quantity;
            $customer_freights = ($total_freights * $this->customer_commission/100);
            $received = 0;
            $received_date = 'n/a';
            $payments = $this->ci->accounts_model->customer_payments($trip_detail->trip_details_id, $trip->customerId);
            $this->payments = $payments;
            foreach($payments as $payment){
                $received = $received + $payment->amount;
                $received_date = $this->ci->carbon->createFromFormat('Y-m-d', $payments[0]->payment_date)->toFormattedDateString();
                $this->total_received += $payment->amount;
            }
            $add_payment_link = '<a href='.base_url().'customers/add_payment/'.$trip_detail->trip_details_id."/".$trip->customerId."/".$customer_freights.' class="add_payment_link" style="background-color: rgba(0,0,0,0); border: 0px; width: 100%; height: 100%;"><i class="fa fa-plus-circle" style="color: red"></i> Pay</a>';
            $counter++;
            $class = ($counter == sizeof($trip->trip_related_details))?"":'multiple_entites';
            $this->total_freights = $this->total_freights."<div class=$class>".$total_freights."</div>";
            $this->customer_freights = $this->customer_freights."<div class=$class>".$this->customer_commission."% = ".$customer_freights."</div>";
            $this->received = $this->received."<div class=$class>".$received."</div>";
            $this->received_dates = $this->received_dates."<div class=$class>".$received_date."</div>";
            $this->route = $this->route."<div class=$class>".$trip_detail->sourceCity." to ".$trip_detail->destinationCity."</div>";
            $this->product = $this->product."<div class=$class>".$trip_detail->product."</div>";
            $this->add_payment_btn = $this->add_payment_btn."<div class=$class>".$add_payment_link."</div>";
            $this->total_freight += $total_freights;
        }

        /*$this->received = 0;
        $payments = $this->ci->accounts_model->customer_payments($trip->trips_details_id, $trip->customerId);
        $this->payments = $payments;
        foreach($payments as $payment){
            $this->received = $this->received + $payment->amount;
        }*/

    }

} 