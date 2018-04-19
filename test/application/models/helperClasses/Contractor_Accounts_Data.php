<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Contractor_Accounts_Data {

    public $companyName;
    public $customerName;
    public $companyId;
    public $customerId;
    public $route;
    public $trip_date;
    public $filling_date;
    public $entry_date;
    public $total_freight;
    public $total_freight_for_company;
    public $contractor_freights;
    public $contractor_commission;
    public $company_commission;
    public $customer_commission;
    public $total_freights;
    public $paid_to_contractor;
    public $total_paid_to_contractor;
    public $received_dates;
    public $trip_id;
    public $payments;
    public $product;
    public $ci;

    function Contractor_Accounts_Data($trips){
        $this->ci =& get_instance();
        $this->total_freight = 0;
        $this->total_paid_to_contractor = 0;

        $this->set_data($trips);

    }

    private function  set_data($trip){

        $this->trip_id = $trip->trip_id;
        //$route = $this->ci->routes_model->route($trip->route_id);
        $this->trip_date = $trip->entry_date;
        $this->filling_date = $trip->filling_date;
        $this->entry_date = $trip->entry_date;
        //$customer = $this->ci->customers_model->customer($trip->customer_id);
        $this->customerName = $trip->customerName;
        //$company = $this->ci->companies_model->company($trip->companyId);
        $this->companyName = $trip->companyName;
        $this->companyId = $trip->companyId;
        $this->customerId = $trip->customerId;

        $this->contractor_commission =($trip->contractor_commission + $trip->contractor_commission_1 + $trip->contractor_commission_2) - ($trip->company_commission_1 +$trip->company_commission_2+ $trip->company_commission_3);
        $this->customer_commission = 100 - $trip->contractor_commission;
        $counter = 0;
        foreach($trip->trip_related_details as $trip_detail){
            $total_freights = $trip_detail->freight_unit * $trip_detail->product_quantity;
            $this->total_freight += $total_freights;
            $this->total_freight_for_company += $trip_detail->company_freight_unit * $trip_detail->product_quantity;
            $counter ++;
            $class = ($counter == sizeof($trip->trip_related_details))?"":'multiple_entites';

            $paid_to_contractor = '';
            $received_date = 'n/a';
            $payments = $this->ci->accounts_model->customer_payments($trip_detail->trip_details_id, $trip->customerId);
            if(sizeof($payments) >=1){
                $paid_to_contractor = ($this->contractor_commission * $total_freights)/100;
                $this->total_paid_to_contractor += $paid_to_contractor;
                $received_date = $this->ci->carbon->createFromFormat('Y-m-d', $payments[0]->payment_date)->toFormattedDateString();
            }else{
                $this->paid_to_contractor = 0;
            }

            $contractor_freights = ($total_freights * $this->contractor_commission/100);

            $this->total_freights = $this->total_freights."<div class=$class>".($trip_detail->freight_unit * $trip_detail->product_quantity)."</div>";
            $this->product = $this->product."<div class=$class>".$trip_detail->product."</div>";
            $this->received_dates = $this->received_dates."<div class=$class>".$received_date."</div>";
            $this->contractor_freights = $this->contractor_freights."<div class=$class>".$this->contractor_commission."% = ".$contractor_freights."</div>";
            $this->paid_to_contractor = $this->paid_to_contractor."<div class=$class>".$paid_to_contractor."</div>";
            $this->route = $this->route."<div class=$class>".$trip_detail->sourceCity." to ".$trip_detail->destinationCity."</div>";
        }
        //$this->customer_freight = 100 - $trip->contractor_commission;
        $this->company_commission = $trip->company_commission_1 + $trip->company_commission_2;


    }

} 