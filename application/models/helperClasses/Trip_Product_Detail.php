<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Product_Detail{

    public $product_detail_id;
    public $source;
    public $destination;
    public $product;
    public $product_quantity;
    public $quantity_at_destination;
    public $quantity_after_decanding;
    public $price_unit;
    public $customer_freight_unit;
    public $company_freight_unit;
    public $stn_number;

    //this array will contain accounts objects
    public $customer_accounts;
    public $company_accounts;
    public $contractor_accounts;

    //below array will contains Credit Accounts array
    public $contractor_accounts_entries;
    public $customer_accounts_credit_entries;
    public $user_accounts_entries;

    public $shortage_voucher_dest;
    public $shortage_voucher_decnd;

    public $shortage_detail;
    public $shortage_quantity;
    public $shortage_rate;

    public $bill;
    //whole object reference
    public $whole;

    public $ci;


    function __construct(&$whole){
        $this->whole = $whole;
        //$this->ci =& get_instance();

        //setting default values
        $this->customer_accounts = array();
        $this->company_accounts = array();
        $this->contractor_accounts = array();

        $this->contractor_accounts_entries = array();
        $this->customer_accounts_credit_entries = array();
        $this->company_accounts_credit_entries = array();
        $this->user_accounts_entries = array();

    }

    function get_total_contractor_commission_credit_amount()
    {
        $entries = $this->contractor_accounts_entries;
        $credit_entries = array();
        foreach($entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight commission") &&
                strtolower($entry->get_account_holder_type()) == strtolower("contractors") &&
                $entry->active == 1
            )
            {
                array_push($credit_entries,$entry);
            }
        }

        $total_credit = 0;
        foreach($credit_entries as $entry){
            $total_credit += $entry->credit;
        }
        return round($total_credit,3);
    }

    function get_contractor_commission_credit_account_title_id()
    {
        $title_id = 0;
        foreach($this->contractor_accounts_entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight commission") &&
                strtolower($entry->get_account_holder_type()) == strtolower("contractors") &&
                $entry->active == 1
            )
            {
                return $entry->account_title_id;
            }
        }
        return $title_id;
    }

    function get_total_contractor_service_charges_credit_amount()
    {
        $entries = $this->contractor_accounts_entries;
        $credit_entries = array();
        foreach($entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("service charges") &&
                strtolower($entry->get_account_holder_type()) == strtolower("contractors")
            )
            {
                array_push($credit_entries,$entry);
            }
        }

        $total_credit = 0;
        foreach($credit_entries as $entry){
            $total_credit += $entry->credit;
        }
        return round($total_credit, 3);
    }


    function get_contractor_service_charges_credit_account_title_id()
    {
        $title_id = 0;
        foreach($this->contractor_accounts_entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("service charges") &&
                strtolower($entry->get_account_holder_type()) == strtolower("contractors")
            )
            {
                return $entry->account_title_id;
            }
        }
        return $title_id;
    }
    function get_total_company_commission_credit_amount()
    {
        $entries = $this->contractor_accounts_entries;
        $credit_entries = array();
        foreach($entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight commission") &&
                strtolower($entry->get_account_holder_type()) == strtolower("companies")
            )
            {
                array_push($credit_entries,$entry);
            }
        }

        $total_credit = 0;
        foreach($credit_entries as $entry){
            $total_credit += $entry->credit;
        }
        return round($total_credit, 3);
    }


    function get_company_commission_credit_account_title_id()
    {
        $title_id = 0;
        foreach($this->company_accounts_credit_entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight commission") &&
                strtolower($entry->get_account_holder_type()) == strtolower("companies")
            )
            {
                return $entry->account_title_id;
            }
        }
        return $title_id;
    }

    function get_dr_cr_status($for)
    {
        $dr_cr = '';
        switch($for)
        {
            case "contractor_freight":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if((strtolower($entry->title) == strtolower("Contractor freight A/C from company") ||
                            strtolower($entry->title) == strtolower("Contractor freight A/C to customer")) &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "contractor_freight_without_shortage":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("Contractor Freight A/C From Company Without Shortage") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "contractor_commission":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("Contractor commission A/C") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "contractor_service_charges":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("Contractor service charges") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "customer_freight":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("customer freight a/c") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "customer_freight_without_shortage":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("Customer Freight A/c Without Shortage") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "total_freight_for_company":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("company freight a/c") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "company_commission":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("company commission a/c") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;

            case "company_wht":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("company w.h.t a/c") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;
            case "payable_before_tax":
                $dr = -1;
                $cr = -1;
                foreach($this->user_accounts_entries as $entry){
                    if(strtolower($entry->title) == strtolower("Contractor commission A/C") &&
                        strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                        $entry->active == 1)
                    {
                        $dr = ($entry->dr_cr == 1)?1:$dr;
                        $cr = ($entry->dr_cr == 0)?1:$cr;
                    }
                }
                break;
        }

        if($dr != -1){
            $dr_cr = 'Dr';
        }
        if($cr != -1){
            $dr_cr = 'Cr';
        }
        if($dr != -1 && $cr != -1){
            $dr_cr = 'Dr / Cr';
        }


        return $dr_cr;
    }

    function get_voucher_of_total_freight_for_company()
    {
        foreach($this->user_accounts_entries as $entry){
            if(strtolower($entry->title) == strtolower("company freight a/c") &&
                strtolower($entry->get_account_holder_type()) == strtolower("users") &&
                $entry->active == 1)
            {
                return $entry;
            }
        }
    }

    function get_total_company_wht_credit_amount()
    {
        $entries = $this->company_accounts_credit_entries;
        $credit_entries = array();
        foreach($entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("w.h.t") &&
                strtolower($entry->get_account_holder_type()) == strtolower("companies")
            )
            {
                array_push($credit_entries,$entry);
            }
        }

        $total_credit = 0;
        foreach($credit_entries as $entry){
            $total_credit += $entry->credit;
        }
        return round($total_credit, 3);
    }


    function get_company_wht_credit_account_title_id()
    {
        $title_id = 0;
        foreach($this->company_accounts_credit_entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("w.h.t") &&
                strtolower($entry->get_account_holder_type()) == strtolower("companies")
            )
            {
                return $entry->account_title_id;
            }
        }
        return $title_id;
    }

    function get_total_customer_freight_credit_amount()
    {
        $entries = $this->customer_accounts_credit_entries;
        $credit_entries = array();
        foreach($entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight") &&
                strtolower($entry->get_account_holder_type()) == strtolower("customers") &&
                $entry->active == 1
            )
            {
                array_push($credit_entries,$entry);
            }
        }

        $total_credit = 0;
        foreach($credit_entries as $entry){
            $total_credit += $entry->credit;
        }
        return round($total_credit,3);
    }

    function get_customer_freight_credit_account_title_id()
    {
        $title_id = 0;
        foreach($this->customer_accounts_credit_entries as $entry){
            if(strtolower($entry->ac_type) == strtolower("payable") &&
                strtolower($entry->title) == strtolower("freight") &&
                strtolower($entry->get_account_holder_type()) == strtolower("customers") &&
                $entry->active == 1
            )
            {
                return $entry->account_title_id;
            }
        }
        return $title_id;
    }

    function get_total_freight_for_company($for = 'white_oil')
    {
        if($for == 'black_oil')
        {
            return (($this->product_quantity * $this->company_freight_unit) - $this->getShortageAmount());
        }else{
            return ($this->product_quantity * $this->company_freight_unit);
        }
    }
    function get_total_freight_for_customer($for = 'white_oil')
    {
        if($for == 'black_oil')
        {
            return (($this->product_quantity * $this->customer_freight_unit) - $this->getShortageAmount());
        }else{
            return ($this->product_quantity * $this->customer_freight_unit);
        }
    }

    function get_wht_amount($wht, $for='white_oil')
    {
        return ($wht * $this->get_total_freight_for_company($for)/100);
    }

    function get_company_commission_amount($commission, $for='white_oil')
    {
        return ($commission * $this->get_total_freight_for_company($for)/100);
    }
    function get_contractor_commission_amount($commission, $for='white oil')
    {
        return round(($commission * $this->get_total_freight_for_customer($for)/100),3);
    }

    function get_paid_to_company()
    {
        $temp_total_paid = 0;
        foreach($this->company_accounts as $company_account){
            $temp_total_paid+= $company_account->amount_paid;
        }

        return $temp_total_paid;
    }

    function get_contractor_freight_amount_according_to_company($freight, $for='white_oil')
    {
        return round(($freight * $this->get_total_freight_for_company($for) /100), 3);
    }

    function get_paid_to_contractor() //freight by company
    {
        $temp_total_paid = 0;
        foreach($this->contractor_accounts as $contractor_account){
            $temp_total_paid+= $contractor_account->amount_paid;
        }

        return $temp_total_paid;
    }

    function is_contractor_commission_paid($customer_freight) //commission of contractor
    {
        $customer_freight = $this->get_customer_freight_amount($customer_freight);
        $customer_paid = $this->get_paid_to_customer();
        $customer_remaining = $customer_freight - $customer_paid;
        if($customer_remaining != $customer_freight || $customer_remaining == 0){
            return true;
        }
        return false;
    }

    function get_customer_freight_amount($freight, $for='white_oil')
    {
        return ($freight * $this->get_total_freight_for_customer($for) /100);
    }

    function get_paid_to_customer()
    {
        $temp_total_paid = 0;
        foreach($this->customer_accounts as $customer_account){
            $temp_total_paid+= $customer_account->amount_paid;
        }

        return $temp_total_paid;
    }

    function contractor_benefits($for = 'white_oil')
    {
        $total_freight_for_company = $this->get_total_freight_for_company($for);
        $contractor_commission = $this->whole->contractor->commission_1 - $this->whole->company->wht - $this->whole->company->commission_1;
        $contractor_commission_amount = $this->get_contractor_commission_amount($contractor_commission);
        $company_commission_amount = $this->get_company_commission_amount($this->whole->company->commission_1);
        $wht_amount = $this->get_wht_amount($this->whole->company->wht);
        $customer_freight_amount = $this->get_customer_freight_amount($this->whole->customer->freight);
        $all_agent_freights = $customer_freight_amount + $contractor_commission_amount + $company_commission_amount + $wht_amount;
        $benefits = $total_freight_for_company - $all_agent_freights;

        return round($benefits, 3);

    }

    /**
     * @return mixed
     */
    public function getShortageDetail()
    {
        return $this->shortage_detail;
    }

    public function getShortageQuantity()
    {
        $shortage_detail = $this->getShortageDetail();
        $shortage_details = str_replace(' ','',$shortage_detail);
        $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
        $shortage_details_parts = explode('_&&_', $shortage_details);
        $shortage_details = array(
            'qty'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[1]:0,
            'price_unit'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[2]:0,
        );

        return ( is_double(doubleval($shortage_details['qty'])) == true)?doubleval($shortage_details['qty']):0;
    }
    public function getShortageAmount()
    {
        $shortage_detail = $this->getShortageDetail();
        $shortage_details = str_replace(' ','',$shortage_detail);
        $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
        $shortage_details_parts = explode('_&&_', $shortage_details);
        $shortage_details = array(
            'qty'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[1]:0,
            'price_unit'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[2]:0,
        );

        return $shortage_details['qty'] * $shortage_details['price_unit'];
    }
    public function getPricePerUnit()
    {
        $shortage_detail = $this->getShortageDetail();
        $shortage_details = str_replace(' ','',$shortage_detail);
        $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
        $shortage_details_parts = explode('_&&_', $shortage_details);
        $shortage_details = array(
            'qty'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[1]:0,
            'price_unit'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[2]:0,
        );

        return $shortage_details['price_unit'];
    }

    public function get_contractor_commission_percentage()
    {
        $contractor_commission = $this->whole->contractor->commission_1 - $this->whole->company->wht - $this->whole->company->commission_1;
        return round($contractor_commission, 3);
    }

    /*-------------------------------------------------------------------------------*/
    /*                       Calculating Black Oil Things                            */
    /*-------------------------------------------------------------------------------*/

    /*----- Calculating Shortage quantity ---------*/
    public function get_shortage_quantity_for_black_oil()
    {
        $shortage_quantity = round($this->shortage_quantity, 3);
        return $shortage_quantity;
    }
    /*---------------------------------------------*/

    /*----- Calculating Dis quantity ---------*/
    public function get_dis_quantity_for_black_oil()
    {
        $dis_quantity = round($this->product_quantity, 3);
        return $dis_quantity;
    }
    /*---------------------------------------------*/

    /*----- Calculating Rec quantity ---------*/
    public function get_rec_quantity_for_black_oil()
    {
        $rec_quantity = $this->get_dis_quantity_for_black_oil() - $this->get_shortage_quantity_for_black_oil();
        return $rec_quantity;
    }
    /*---------------------------------------------*/

    /*----- Calculating Freight Per Unit (cmp) ---------*/
    public function get_company_freight_unit_for_black_oil()
    {
        $company_freight_unit = round($this->company_freight_unit, 4);
        return $company_freight_unit;
    }
    /*---------------------------------------------*/

    /*----- Calculating Freight Per Unit (cst) ---------*/
    public function get_customer_freight_unit_for_black_oil()
    {
        $customer_freight_unit = round($this->customer_freight_unit, 4);
        return $customer_freight_unit;
    }
    /*---------------------------------------------*/

    /*----- Calculating Freight On Shortage Quantity (cmp) ---------*/
    public function get_freight_on_shortage_quantity_cmp_for_black_oil()
    {
        $freight_on_shortage_quantity_cmp = $this->get_company_freight_unit_for_black_oil() * $this->get_shortage_quantity_for_black_oil();
        return $freight_on_shortage_quantity_cmp;
    }
    /*---------------------------------------------*/

    /*----- Calculating Freight On Shortage Quantity (cst) ---------*/
    public function get_freight_on_shortage_quantity_cst_for_black_oil()
    {
        $freight_on_shortage_quantity_cst = $this->get_customer_freight_unit_for_black_oil() * $this->get_shortage_quantity_for_black_oil();
        return $freight_on_shortage_quantity_cst;
    }
    /*---------------------------------------------*/

    /*----- Calculating total freight (cmp) ---------*/
    public function get_total_freight_cmp_for_black_oil()
    {
        $total_freight_cmp = $this->get_company_freight_unit_for_black_oil() * $this->get_dis_quantity_for_black_oil();
        return $total_freight_cmp;
    }

    /*---------------------------------------------*/

    /*----- Calculating total freight (cst) ---------*/
    public function get_total_freight_cst_for_black_oil()
    {
        $total_freight_cst = round($this->get_customer_freight_unit_for_black_oil() * $this->get_dis_quantity_for_black_oil(), 3);
        return $total_freight_cst;
    }

    /*---------------------------------------------*/

    /*----- Calculating freight amount (cmp) ---------*/
    public function get_freight_amount_cmp_for_black_oil()
    {
        $freight_amount_cmp = $this->get_total_freight_cmp_for_black_oil() - $this->get_freight_on_shortage_quantity_cmp_for_black_oil();
        return $freight_amount_cmp;
    }
    /*---------------------------------------------*/

    /*----- Calculating freight amount (cst) ---------*/
    public function get_freight_amount_cst_for_black_oil()
    {
        $freight_amount_cst = $this->get_total_freight_cst_for_black_oil() - $this->get_freight_on_shortage_quantity_cst_for_black_oil();
        return $freight_amount_cst;
    }
    /*---------------------------------------------*/

    /*----- Calculating shortage rate ---------*/
    public function get_shortage_rate_for_black_oil()
    {
        $shortage_rate = $this->shortage_rate;
        return $shortage_rate;
    }
    /*---------------------------------------------*/

    /*----- Calculating shortage amount ---------*/
    public function get_shortage_amount_for_black_oil()
    {
        $shortage_amount = $this->get_shortage_rate_for_black_oil() * $this->get_shortage_quantity_for_black_oil();
        return $shortage_amount;
    }

    /*---------------------------------------------*/

    /*----- Calculating payable before tax ---------*/
    public function get_payable_before_tax_for_black_oil()
    {
        $payable_before_tax = $this->get_freight_amount_cmp_for_black_oil() - $this->get_shortage_amount_for_black_oil();
        return $payable_before_tax;
    }

    /*---------------------------------------------*/

    /*----- Calculating wht ---------*/
    public function get_wht_amount_for_black_oil()
    {
        $wht = $this->whole->company->wht;
        $wht_amount = round($wht * $this->get_freight_amount_cmp_for_black_oil()/100, 3);
        return $wht_amount;
    }
    /*---------------------------------------------*/

    /*----- Calculating net payable ---------*/
    public function get_net_payable_for_black_oil()
    {
        $net_payable = $this->get_payable_before_tax_for_black_oil() - $this->get_wht_amount_for_black_oil();
        return $net_payable;
    }

    /*---------------------------------------------*/

    /*----- Calculating contractor commission ---------*/
    public function get_contractor_commission_amount_for_black_oil()
    {
        $contractor_commission = $this->get_contractor_commission_percentage();
        $contractor_commission_amount = round($contractor_commission * $this->get_freight_amount_cst_for_black_oil()/100, 3);
        return $contractor_commission_amount;
    }
    /*---------------------------------------------*/

    /*----- Calculating Contractor net freight ---------*/
    public function get_contractor_net_freight_for_black_oil()
    {
        $contractor_net_freight = $this->get_net_payable_for_black_oil() - $this->get_company_commission_amount_for_black_oil();
        return round($contractor_net_freight, 3);
    }

    /*---------------------------------------------*/

    /*----- Calculating company commission --------*/
    public function get_company_commission_amount_for_black_oil()
    {
        $company_commission = $this->whole->company->commission_1;
        $company_commission_amount = $company_commission * $this->get_freight_amount_cmp_for_black_oil()/100;
        return round($company_commission_amount, 3);
    }
    /*---------------------------------------------*/

    /*----- Calculating Customer Freight amount --------*/
    public function get_customer_freight_amount_for_black_oil()
    {
        $customer_freight_amount = $this->get_total_freight_cst_for_black_oil() - ($this->whole->contractor->commission_1*$this->get_freight_amount_cst_for_black_oil()/100) - ($this->get_freight_on_shortage_quantity_cst_for_black_oil() + $this->get_shortage_amount_for_black_oil());
        return $customer_freight_amount;
    }

    /*---------------------------------------------*/

    /*----- Calculating service charges --------*/
    public function get_service_charges_for_black_oil()
    {
        $all_agent_freights = $this->get_customer_freight_amount_for_black_oil() + $this->get_contractor_commission_amount_for_black_oil() + $this->get_company_commission_amount_for_black_oil() + $this->get_wht_amount_for_black_oil();
        $benefits = $this->get_total_freight_cmp_for_black_oil() - $all_agent_freights;
        $service_charges = round($benefits, 3);
        return $service_charges;
    }
    /*---------------------------------------------*/

    /*-------------------------------------------------------------------------------------------------*/
    /*                                    Calculating Things End                                       */
    /*-------------------------------------------------------------------------------------------------*/
}
