<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Company_Accounts_Data {

    public $contractorName;
    public $customerName;
    public $contractorId;
    public $customerId;
    public $route;
    public $trip_date;
    public $filling_date;
    public $entryDate;
    public $total_freight;
    public $company_commission;
    public $received;
    public $total_freights;
    public $total_freights_for_company;
    public $company_freights;
    public $company_freights_unit;
    public $contractor_freights;
    public $customer_freights;
    public $customer_freights_unit;
    public $total_freight_for_company;
    public $product;

    public $contractor_commission;
    public $paid_to_contractor;
    public $total_paid_to_contractor;
    public $customer_commission;
    public $paid_to_customer;
    public $total_paid_to_customer;

    public $contractor_remaining;
    public $customer_remaining;

    public $total_contractor_remaining;
    public $total_customer_remaining;

    public $trip_id;
    public $payments;
    public $ci;

    function Company_Accounts_Data($trips){
        $this->ci =& get_instance();

        $this->total_paid_to_customer = 0;
        $this->total_paid_to_contractor = 0;
        $this->total_freight = 0;
        $this->total_freight_for_company = 0;
        $this->set_data($trips);

    }

    private function  set_data($trip){

        $this->trip_id = $trip->trip_id;
        //$route = $this->ci->routes_model->route($trip->route_id);
        $this->trip_date = $trip->entry_date;
        $this->filling_date = $trip->filling_date;
        $this->entry_date = $trip->entry_date;
        //$customer = $this->ci->customers_model->customer($trip->customerId);
        $this->customerName = $trip->customerName;
        //$contractor = $this->ci->carriageContractors_model->carriageContractor($trip->contractorId);
        $this->contractorName = $trip->contractorName;
        $this->contractorId = $trip->contractorId;
        $this->customerId = $trip->customerId;

        $this->company_commission = ($trip->company_commission_1 +$trip->company_commission_2+ $trip->company_commission_3);

        $this->contractor_commission = $trip->contractor_commission - $this->company_commission;
        $this->customer_commission = 100-$trip->contractor_commission;

        $counter = 0;
        foreach($trip->trip_related_details as $trip_detail){
            $counter++;

            $total_freights = $trip_detail->freight_unit * $trip_detail->product_quantity;
            $total_freights_for_company = $trip_detail->company_freight_unit * $trip_detail->product_quantity;
            //calculating paid ammounts
            $paid_to_contractor = '';
            $payments = $this->ci->accounts_model->customer_payments($trip_detail->trip_details_id, $trip->customerId);
            if(sizeof($payments) >=1){
                $paid_to_contractor = ($this->contractor_commission * $total_freights)/100;
                $this->total_paid_to_contractor += $paid_to_contractor;
                //$this->received_date = $payments[0]->payment_date;
            }else{
                $this->paid_to_contractor = 0;
            }

            $paid_to_customer = 0;
            $payments = $this->ci->accounts_model->customer_payments($trip_detail->trip_details_id, $trip->customerId);
            foreach($payments as $payment){
                $paid_to_customer = $paid_to_customer + $payment->amount;
                $this->total_paid_to_customer += $payment->amount;
            }

            $contractor_freights = ($total_freights * $this->contractor_commission/100);
            $company_freights = ($total_freights_for_company * $this->company_commission/100);
            $customer_freights = ($total_freights * $this->customer_commission/100);

            $customer_remaining = round(($customer_freights - $paid_to_customer) *1000)/1000;
            $this->total_customer_remaining += $customer_remaining;
            $contractor_remaining = round(($contractor_freights - $paid_to_contractor) *1000)/1000;
            $this->total_contractor_remaining += $contractor_remaining;

            $class = ($counter == sizeof($trip->trip_related_details))?"":'multiple_entites';
            $this->total_freight += $trip_detail->freight_unit * $trip_detail->product_quantity;
            $this->total_freight_for_company += ($trip_detail->company_freight_unit * $trip_detail->product_quantity);
            $this->total_freights = $this->total_freights."<div class=$class>".($trip_detail->freight_unit * $trip_detail->product_quantity)."</div>";
            $this->total_freights_for_company = $this->total_freights_for_company."<div class=$class>".($trip_detail->company_freight_unit * $trip_detail->product_quantity)."</div>";
            $this->product = $this->product."<div class=$class>".$trip_detail->product."</div>";
            $this->paid_to_contractor = $this->paid_to_contractor."<div class=$class>".$paid_to_contractor."</div>";
            $this->paid_to_customer = $this->paid_to_customer."<div class=$class>"."<a class='show_payment_link' href='".base_url()."companies/show_payments/customer/$trip_detail->trip_details_id/$trip->customerId'"."style='display: block; width: 100%; height=100%;'>".$paid_to_customer."</a>"."</div>";
            $this->customer_remaining = $this->customer_remaining."<div class=$class>".$customer_remaining."</div>";
            $this->contractor_remaining = $this->contractor_remaining."<div class=$class>".$contractor_remaining."</div>";
            $this->contractor_freights = $this->contractor_freights."<div class=$class>".$this->contractor_commission."% = ".$contractor_freights."</div>";
            $this->company_freights = $this->company_freights."<div class=$class>".$this->company_commission."% = ".$company_freights."</div>";
            $this->company_freights_unit = $this->company_freights_unit."<div class=$class>".$trip_detail->company_freight_unit."</div>";
            $this->customer_freights = $this->customer_freights."<div class=$class>".$this->customer_commission."% = ".$customer_freights."</div>";
            $this->customer_freights_unit = $this->customer_freights_unit."<div class=$class>".$trip_detail->freight_unit."</div>";
            $this->route = $this->route."<div class=$class>".$trip_detail->sourceCity." to ".$trip_detail->destinationCity."</div>";
        }


    }

} 