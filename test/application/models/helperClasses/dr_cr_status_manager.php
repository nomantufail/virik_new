<?php
/**
 * Created by PhpStorm.
 * User: noman
 * Date: 6/26/2015
 * Time: 12:52 AM
 */

class Dr_Cr_Status_Manager {

    public $statuses;
    public function __construct($statuses)
    {
        $this->statuses = Arrays::groupBy($statuses, Functions::extractField('trip_detail_id'));
    }

    public function get_status($trip_detail_id, $for)
    {
        $statuses = $this->statuses[$trip_detail_id];

        $dr_cr = '';
        $debit_voucher_id = 0;
        $credit_voucher_id = 0;
        switch($for)
        {
            case "contractor_freight":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if((strtolower($entry->account_title) == strtolower("Contractor freight A/C from company") ||
                            strtolower($entry->account_title) == strtolower("Contractor freight A/C to customer")))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "contractor_freight_without_shortage":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("Contractor Freight A/C From Company Without Shortage"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "contractor_commission":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("Contractor commission A/C"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "contractor_service_charges":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("Contractor service charges"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "customer_freight":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("customer freight a/c"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "customer_freight_without_shortage":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("Customer Freight A/c Without Shortage"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "total_freight_for_company":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("company freight a/c"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "company_commission":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("company commission a/c"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;

            case "company_wht":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("company w.h.t a/c"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;
            case "payable_before_tax":
                $dr = -1;
                $cr = -1;
                foreach($statuses as $entry){
                    if(strtolower($entry->account_title) == strtolower("Contractor commission A/C"))
                    {
                        if($entry->dr_cr == 1)
                        {
                            $debit_voucher_id = $entry->voucher_id;
                            $dr = 1;
                        }
                        if($entry->dr_cr == 0)
                        {
                            $credit_voucher_id = $entry->voucher_id;
                            $cr = 1;
                        }
                    }
                }
                break;
        }

        if($dr != -1){
            $dr_status = "<a class='btn btn-xs btn-success' href='".base_url()."accounts/journal/users/1/?voucher_id=".$debit_voucher_id."' target='_blank'> Dr </a>";
            $dr_cr = $dr_status;
        }
        if($cr != -1){
            $cr_status = "<a class='btn btn-xs btn-danger' href='".base_url()."accounts/journal/users/1/?voucher_id=".$credit_voucher_id."' target='_blank'> Cr </a>";
            $dr_cr .= $cr_status;
        }
        /*if($dr != -1 && $cr != -1){
            $dr_cr = 'Dr / Cr';
        }*/



        return ($dr_cr != '')?$dr_cr."<br>":$dr_cr;

    }

} 