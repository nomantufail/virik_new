<?php
class Sorting_Model extends CI_Model {

    public $counter;
    public function __construct(){
        parent::__construct();
        $this->counter = 0;
    }

    public function sort_journal($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'voucher_journal.id';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "voucher_journal.voucher_date":
                if(bigger_date($first_obj->voucher_date , $second_obj->voucher_date) == true){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            default:
                if($first_obj->voucher_id > $second_obj->voucher_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }

    public function sort_shortage_report($obj1, $obj2)
    {
        ///********************************************************////
        $ans = 0;
        if($obj1->invoice_number < $obj2->invoice_number)
        {
            $ans = -1;
        }else{
            $ans = 1;
        }

        return $ans;
    }

    public function sort_company_accounts($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'trip_id';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "trip_id":
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            case "trip_type":
                if($first_obj->type > $second_obj->type){
                    $ans = 1;
                }else if($first_obj->type < $second_obj->type){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "tanker":
                $ans = strcmp($first_obj->tanker->tanker_number, $second_obj->tanker->tanker_number);
                break;
            case "entry_date":
                $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                break;
            case "product":
                $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                break;
            case "route":
                $route_1 = $first_obj->trip_related_details[0]->source->name." to ".$first_obj->trip_related_details[0]->destination->name;
                $route_2 = $second_obj->trip_related_details[0]->source->name." to ".$second_obj->trip_related_details[0]->destination->name;
                $ans = strcmp($route_1, $route_2);
                break;
            case "wht":
                $wht_1 = $first_obj->trip_related_details[0]->get_wht_amount($first_obj->company->wht);
                $wht_2 = $second_obj->trip_related_details[0]->get_wht_amount($second_obj->company->wht);
                if($wht_1 > $wht_2){
                    $ans = 1;
                }else if($wht_1 < $wht_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "total_freight":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_company();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_company();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "cmp_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->company_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->company_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "company_commission":
                $company_commission_1 = $first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1);
                $company_commission_2 = $second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1);
                if($company_commission_1 > $company_commission_2){
                    $ans = 1;
                }else if($company_commission_1 < $company_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_company();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_company();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1) - $first_obj->trip_related_details[0]->get_paid_to_company());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1) - $second_obj->trip_related_details[0]->get_paid_to_company());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor":
                $contractor_1 = $first_obj->contractor->name;
                $contractor_2 = $second_obj->contractor->name;
                $ans = strcmp($contractor_1, $contractor_2);
                break;
            case "contractor_freight":
                $contractor_freight_1 = $first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company());
                $contractor_freight_2 = $second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($second_obj->get_contractor_freight_according_to_company());
                if($contractor_freight_1 > $contractor_freight_2){
                    $ans = 1;
                }else if($contractor_freight_1 < $contractor_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_contractor();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_contractor();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $first_obj->trip_related_details[0]->get_paid_to_contractor());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $second_obj->trip_related_details[0]->get_paid_to_contractor());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            default:
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }

    public function sort_contractor_accounts($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'trip_id';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "trip_id":
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            case "trip_type":
                if($first_obj->type > $second_obj->type){
                    $ans = 1;
                }else if($first_obj->type < $second_obj->type){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "tanker":
                $ans = strcmp($first_obj->tanker->tanker_number, $second_obj->tanker->tanker_number);
                break;
            case "entry_date":
                $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                break;
            case "product":
                $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                break;
            case "route":
                $route_1 = $first_obj->trip_related_details[0]->source->name." to ".$first_obj->trip_related_details[0]->destination->name;
                $route_2 = $second_obj->trip_related_details[0]->source->name." to ".$second_obj->trip_related_details[0]->destination->name;
                $ans = strcmp($route_1, $route_2);
                break;
            case "wht":
                $wht_1 = $first_obj->trip_related_details[0]->get_wht_amount($first_obj->company->wht);
                $wht_2 = $second_obj->trip_related_details[0]->get_wht_amount($second_obj->company->wht);
                if($wht_1 > $wht_2){
                    $ans = 1;
                }else if($wht_1 < $wht_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "cmp_total_freight":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_company();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_company();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "total_freight_for_customer":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_customer();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_customer();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "cmp_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->company_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->company_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->customer_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->customer_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "company_commission":
                $company_commission_1 = $first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1);
                $company_commission_2 = $second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1);
                if($company_commission_1 > $company_commission_2){
                    $ans = 1;
                }else if($company_commission_1 < $company_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "company_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_company();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_company();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "company_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1) - $first_obj->trip_related_details[0]->get_paid_to_company());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1) - $second_obj->trip_related_details[0]->get_paid_to_company());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor":
                $contractor_1 = $first_obj->contractor->name;
                $contractor_2 = $second_obj->contractor->name;
                $ans = strcmp($contractor_1, $contractor_2);
                break;
            case "contractor_freight":
                $contractor_freight_1 = $first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company());
                $contractor_freight_2 = $second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($second_obj->get_contractor_freight_according_to_company());
                if($contractor_freight_1 > $contractor_freight_2){
                    $ans = 1;
                }else if($contractor_freight_1 < $contractor_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_contractor();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_contractor();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $first_obj->trip_related_details[0]->get_paid_to_contractor());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $second_obj->trip_related_details[0]->get_paid_to_contractor());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "contractor_commission":
                $contractor_commission_1 = $first_obj->trip_related_details[0]->get_contractor_commission_amount($first_obj->contractor->commission_1);
                $contractor_commission_2 = $second_obj->trip_related_details[0]->get_contractor_commission_amount($second_obj->contractor->commission_1);
                if($contractor_commission_1 > $contractor_commission_2){
                    $ans = 1;
                }else if($contractor_commission_1 < $contractor_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer":
                $customer_1 = $first_obj->customer->name;
                $customer_2 = $second_obj->customer->name;
                $ans = strcmp($customer_1, $customer_2);
                break;

            case "customer_net_freight":
                $customer_commission_1 = $first_obj->trip_related_details[0]->get_customer_freight_amount($first_obj->customer->freight);
                $customer_commission_2 = $second_obj->trip_related_details[0]->get_customer_freight_amount($second_obj->customer->freight);
                if($customer_commission_1 > $customer_commission_2){
                    $ans = 1;
                }else if($customer_commission_1 < $customer_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_customer();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_customer();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_customer_commission_amount($first_obj->customer->freight) - $first_obj->trip_related_details[0]->get_paid_to_customer());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_customer_commission_amount($first_obj->customer->freight) - $second_obj->trip_related_details[0]->get_paid_to_customer());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "service_charges":
                $charges_1 = ($first_obj->trip_related_details[0]->contractor_benefits());
                $charges_2 = ($second_obj->trip_related_details[0]->contractor_benefits());
                if($charges_1 > $charges_2){
                    $ans = 1;
                }else if($charges_1 < $charges_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            default:
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }

    public function sort_customer_accounts($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'trip_id';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "trip_id":
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            case "trip_type":
                if($first_obj->type > $second_obj->type){
                    $ans = 1;
                }else if($first_obj->type < $second_obj->type){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "tanker":
                $ans = strcmp($first_obj->tanker->tanker_number, $second_obj->tanker->tanker_number);
                break;
            case "entry_date":
                $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                break;
            case "product":
                $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                break;
            case "route":
                $route_1 = $first_obj->trip_related_details[0]->source->name." to ".$first_obj->trip_related_details[0]->destination->name;
                $route_2 = $second_obj->trip_related_details[0]->source->name." to ".$second_obj->trip_related_details[0]->destination->name;
                $ans = strcmp($route_1, $route_2);
                break;

            case "total_freight_for_customer":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_customer();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_customer();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->customer_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->customer_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "company":
                $company_1 = $first_obj->company->name;
                $company_2 = $second_obj->company->name;
                $ans = strcmp($company_1, $company_2);
                break;

            case "contractor":
                $contractor_1 = $first_obj->contractor->name;
                $contractor_2 = $second_obj->contractor->name;
                $ans = strcmp($contractor_1, $contractor_2);
                break;

            case "customer":
                $customer_1 = $first_obj->customer->name;
                $customer_2 = $second_obj->customer->name;
                $ans = strcmp($customer_1, $customer_2);
                break;

            case "customer_net_freight":
                $customer_commission_1 = $first_obj->trip_related_details[0]->get_customer_freight_amount($first_obj->customer->freight);
                $customer_commission_2 = $second_obj->trip_related_details[0]->get_customer_freight_amount($second_obj->customer->freight);
                if($customer_commission_1 > $customer_commission_2){
                    $ans = 1;
                }else if($customer_commission_1 < $customer_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_customer();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_customer();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_customer_freight_amount($first_obj->customer->freight) - $first_obj->trip_related_details[0]->get_paid_to_customer());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_customer_freight_amount($first_obj->customer->freight) - $second_obj->trip_related_details[0]->get_paid_to_customer());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            default:
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }

    public function sort_trip_details($obj1, $obj2)
    {
        $ci = CI_Controller::get_instance();

        ///********************************************************////
        $default_sort_by = 'detail_id';
        $default_order = 'asc';
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:$default_sort_by;
        $order = (isset($_GET['order']))?$_GET['order']:$default_order;
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////


        $ans = 0;
        switch($sort_by)
        {
            case "detail_id":
                if($first_obj->product_detail_id > $second_obj->product_detail_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            case "product":
                $ans = strcmp($first_obj->product->name, $second_obj->product->name);
                break;
            case "source":
                $source_1 = $first_obj->source->name;
                $source_2 = $second_obj->source->name;
                $ans = strcmp($source_1, $source_2);
                break;
            case "destination":
                $destination_1 = $first_obj->destination->name;
                $destination_2 = $second_obj->destination->name;
                $ans = strcmp($destination_1, $destination_2);
                break;
            case "stn_number":
                $stn_number_1 = $first_obj->stn_number;
                $stn_number_2 = $second_obj->stn_number;
                $ans = strcmp($stn_number_1, $stn_number_2);
                break;

            case "shortage_voucher":
                $shortage_voucher_1 = $first_obj->shortage_voucher;
                $shortage_voucher_2 = $second_obj->shortage_voucher;
                if($shortage_voucher_1 > $shortage_voucher_2){
                    $ans = 1;
                }else if($shortage_voucher_1 < $shortage_voucher_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            default:
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }

    public function sort_multiple_sorting_info($array_1, $array_2)
    {
        $ans = 0;
        if($array_1['column_priority'] > $array_2['column_priority'])
        {
            $ans = 1;
        }
        else if($array_1['column_priority'] > $array_2['column_priority'])
        {
            $ans = -1;
        }
        return $ans;
    }

    public function sort_trips($obj1, $obj2)
    {
        $ci = CI_Controller::get_instance();

        /*-----------getting sorting information----------*/
        $module = '';
        if($ci->uri->segment(3) == 'primary' || $ci->uri->segment(2) == '' || $ci->uri->segment(2) == 'index')
        {
            $module = 'primary_trips';
        }
        else if($ci->uri->segment(3) == 'secondary')
        {
            $module = 'secondary_trips';
        }
        else if($ci->uri->segment(3) == 'secondary_local')
        {
            $module = 'secondary_local_trips';
        }
        $multiple_sorting_info = $ci->helper_model->multiple_sorting_info($module);//var_dump($multiple_sorting_info); die();

        /*------------------------------------------------*/

        $ans = 0;
        if(isset($_GET['sort_by']))
        {
            /*----------Sorting trip products----------*/
            /*usort($obj1->trip_related_details, array("Sorting_Model", "sort_trip_details"));
            usort($obj2->trip_related_details, array("Sorting_Model", "sort_trip_details"));*/
            /*-----------------------------------------*/

            ///********************************************************////
            $default_sort_by = ($ci->uri->segment(2) == 'secondary')?'trip_id':'trip_id';
            $default_order = ($ci->uri->segment(2) == 'secondary')?'desc':'desc';
            $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:$default_sort_by;
            $order = (isset($_GET['order']))?$_GET['order']:$default_order;
            $first_obj = ($order == 'asc')?$obj1:$obj2;
            $second_obj = ($order == 'asc')?$obj2: $obj1;
            /////**********************************//////
            switch($sort_by)
            {
                case "trip_id":
                    if($first_obj->trip_id > $second_obj->trip_id){
                        $ans = 1;
                    }else{
                        $ans = -1;
                    }
                    break;
                case "trip_type":
                    if($first_obj->type > $second_obj->type){
                        $ans = 1;
                    }else if($first_obj->type < $second_obj->type){
                        $ans = -1;
                    }else{
                        $ans = 0;
                    }
                    break;

                case "entryDate":
                    $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                    break;
                case "product":
                    $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                    break;
                case "product_quantity":
                    if($first_obj->trip_related_details[0]->product_quantity > $second_obj->trip_related_details[0]->product_quantity){
                        $ans = 1;
                    }else if($first_obj->trip_related_details[0]->product_quantity < $second_obj->trip_related_details[0]->product_quantity){
                        $ans = -1;
                    }else{
                        $ans = 0;
                    }
                    break;
                case "source":
                    $source_1 = $first_obj->trip_related_details[0]->source->name;
                    $source_2 = $second_obj->trip_related_details[0]->source->name;
                    $ans = strcmp($source_1, $source_2);
                    break;
                case "destination":
                    $destination_1 = $first_obj->trip_related_details[0]->destination->name;
                    $destination_2 = $second_obj->trip_related_details[0]->destination->name;
                    $ans = strcmp($destination_1, $destination_2);
                    break;

                case "company":
                    $company_1 = $first_obj->company->name;
                    $company_2 = $second_obj->company->name;
                    $ans = strcmp($company_1, $company_2);
                    break;

                case "contractor":
                    $contractor_1 = $first_obj->contractor->name;
                    $contractor_2 = $second_obj->contractor->name;
                    $ans = strcmp($contractor_1, $contractor_2);
                    break;

                case "customer":
                    $customer_1 = $first_obj->customer->name;
                    $customer_2 = $second_obj->customer->name;
                    $ans = strcmp($customer_1, $customer_2);
                    break;

                case "tanker_number":
                    $tanker_1 = $first_obj->tanker->tanker_number;
                    $tanker_2 = $second_obj->tanker->tanker_number;
                    $ans = strcmp($tanker_1, $tanker_2);
                    break;

                case "stn_number":
                    $stn_number_1 = $first_obj->trip_related_details[0]->stn_number;
                    $stn_number_2 = $second_obj->trip_related_details[0]->stn_number;
                    $ans = strcmp($stn_number_1, $stn_number_2);
                    break;

                case "shortage_voucher":
                    $shortage_voucher_1 = $first_obj->trip_related_details[0]->shortage_voucher;
                    $shortage_voucher_2 = $second_obj->trip_related_details[0]->shortage_voucher;

                    if($shortage_voucher_1 > $shortage_voucher_2){
                        $ans = 1;
                    }else if($shortage_voucher_1 < $shortage_voucher_2){
                        $ans = -1;
                    }else{
                        $ans = 0;
                    }
                    break;

                default:
                    if($first_obj->trip_id > $second_obj->trip_id){
                        $ans = 1;
                    }else{
                        $ans = -1;
                    }

            }
        }
        else
        {
            foreach($multiple_sorting_info as $multiple_sort)
            {

                $first_obj = ($multiple_sort['column_order'] == 'asc')?$obj1:$obj2;
                $second_obj = ($multiple_sort['column_order'] == 'asc')?$obj2: $obj1;

                switch($multiple_sort['column_name'])
                {
                    case "trip_id":
                        if($first_obj->trip_id == $second_obj->trip_id)
                        {
                            break 1;
                        }else{
                            if($first_obj->trip_id > $second_obj->trip_id){
                                $ans = 1;
                            }else{
                                $ans = -1;
                            }
                            break 2;
                        }
                    case "trip_type":
                        if($first_obj->type == $second_obj->type)
                        {
                            break 1;
                        }
                        else
                        {
                            if($first_obj->type > $second_obj->type){
                                $ans = 1;
                            }else if($first_obj->type < $second_obj->type){
                                $ans = -1;
                            }else{
                                $ans = 0;
                            }
                            break 2;
                        }

                    case "entryDate":
                        if($first_obj->dates->entry_date == $second_obj->dates->entry_date)
                        {
                            break 1;
                        }
                        else
                        {
                            $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                            break 2;
                        }
                    case "product":
                        if($first_obj->trip_related_details[0]->product->name == $second_obj->trip_related_details[0]->product->name)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                            break 2;
                        }
                    case "product_quantity":
                        if($first_obj->trip_related_details[0]->product_quantity == $second_obj->trip_related_details[0]->product_quantity)
                        {
                            break 1;
                        }else{
                            if($first_obj->trip_related_details[0]->product_quantity > $second_obj->trip_related_details[0]->product_quantity){
                                $ans = 1;
                            }else if($first_obj->trip_related_details[0]->product_quantity < $second_obj->trip_related_details[0]->product_quantity){
                                $ans = -1;
                            }else{
                                $ans = 0;
                            }
                            break 2;
                        }
                    case "source":
                        $source_1 = $first_obj->trip_related_details[0]->source->name;
                        $source_2 = $second_obj->trip_related_details[0]->source->name;
                        if($source_1 == $source_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($source_1, $source_2);
                            break 2;
                        }
                    case "destination":
                        $destination_1 = $first_obj->trip_related_details[0]->destination->name;
                        $destination_2 = $second_obj->trip_related_details[0]->destination->name;
                        if($destination_1 == $destination_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($destination_1, $destination_2);
                            break 2;
                        }

                    case "company":
                        $company_1 = $first_obj->company->name;
                        $company_2 = $second_obj->company->name;
                        if($company_1 == $company_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($company_1, $company_2);
                            break 2;
                        }

                    case "contractor":
                        $contractor_1 = $first_obj->contractor->name;
                        $contractor_2 = $second_obj->contractor->name;
                        if($contractor_1 == $contractor_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($contractor_1, $contractor_2);
                            break 2;
                        }

                    case "customer":
                        $customer_1 = $first_obj->customer->name;
                        $customer_2 = $second_obj->customer->name;
                        if($customer_1 == $customer_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($customer_1, $customer_2);
                            break 2;
                        }

                    case "tanker_number":
                        $tanker_1 = $first_obj->tanker->tanker_number;
                        $tanker_2 = $second_obj->tanker->tanker_number;
                        if($tanker_1 == $tanker_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($tanker_1, $tanker_2);
                            break 2;
                        }

                    case "stn_number":
                        $stn_number_1 = $first_obj->trip_related_details[0]->stn_number;
                        $stn_number_2 = $second_obj->trip_related_details[0]->stn_number;
                        if($stn_number_1 == $stn_number_2)
                        {
                            break 1;
                        }else{
                            $ans = strcmp($stn_number_1, $stn_number_2);
                            break 2;
                        }

                    case "shortage_voucher":
                        $shortage_voucher_1 = $first_obj->trip_related_details[0]->shortage_voucher;
                        $shortage_voucher_2 = $second_obj->trip_related_details[0]->shortage_voucher;

                        if($shortage_voucher_1 == $shortage_voucher_2)
                        {
                            break 1;
                        }else{
                            if($shortage_voucher_1 > $shortage_voucher_2){
                                $ans = 1;
                            }else if($shortage_voucher_1 < $shortage_voucher_2){
                                $ans = -1;
                            }else{
                                $ans = 0;
                            }
                            break 2;
                        }


                    default:
                        if($first_obj->trip_id > $second_obj->trip_id){
                            $ans = 1;
                        }else{
                            $ans = -1;
                        }

                }
            }
        }





        return $ans;
    }

    public function sort_searched_tankers($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'status';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "status":
                if($first_obj->free == true && $second_obj->free == false){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;

            default:
                if($first_obj->free == true && $second_obj->free == false){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
        }

        return $ans;
    }
    public function sort_tanker_income_statement($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'tankers.truck_number';
        $order = (isset($_GET['order']))?$_GET['order']:'asc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "tankers.truck_number":
                $ans = strcmp($first_obj->tanker_number, $second_obj->tanker_number);
                break;
            case "shortage_expenses":
                if($first_obj->shortage_expense > $second_obj->shortage_expense){
                    $ans = 1;
                }else if($first_obj->shortage_expense < $second_obj->shortage_expense){
                    $ans = -1;
                }
                break;
            case "other_expenses":
                if($first_obj->other_expense > $second_obj->other_expense){
                    $ans = 1;
                }else if($first_obj->other_expense < $second_obj->other_expense){
                    $ans = -1;
                }
                break;
            case "total_expenses":
                if($first_obj->total_expense() > $second_obj->total_expense()){
                    $ans = 1;
                }else if($first_obj->total_expense() < $second_obj->total_expense()){
                    $ans = -1;
                }
                break;
            case "total_income":
                if($first_obj->total_income > $second_obj->total_income){
                    $ans = 1;
                }else if($first_obj->total_income < $second_obj->total_income){
                    $ans = -1;
                }
                break;
            case "profit":
                if($first_obj->profit_loss() > $second_obj->profit_loss()){
                    $ans = 1;
                }else if($first_obj->profit_loss() < $second_obj->profit_loss()){
                    $ans = -1;
                }
                break;
            default:
                $ans = strcmp($first_obj->tanker_number, $second_obj->tanker_number);
                break;
        }

        return $ans;
    }

    public function sort_routes($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'freight';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "freight":
                if($first_obj->freight > $second_obj->freight){
                    $ans = 1;
                }else if($first_obj->freight < $second_obj->freight){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            default:
                if($first_obj->freight > $second_obj->freight){
                    $ans = 1;
                }else if($first_obj->freight < $second_obj->freight){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
        }

        return $ans;
    }

    public function sort_tankers_asc($obj1, $obj2)
    {
        $first_obj = $obj1;
        $second_obj = $obj2;

        $ans = 0;
        $ans = strcmp($first_obj->truck_number, $second_obj->truck_number);
        return $ans;
    }


    public function sort_manage_accounts($obj1, $obj2)
    {
        ///********************************************************////
        $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'trip_id';
        $order = (isset($_GET['order']))?$_GET['order']:'desc';
        $first_obj = ($order == 'asc')?$obj1:$obj2;
        $second_obj = ($order == 'asc')?$obj2: $obj1;
        /////**********************************//////

        $ans = 0;
        switch($sort_by)
        {
            case "trip_id":
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }
                break;
            case "trip_type":
                if($first_obj->type > $second_obj->type){
                    $ans = 1;
                }else if($first_obj->type < $second_obj->type){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "tanker":
                $ans = strcmp($first_obj->tanker->tanker_number, $second_obj->tanker->tanker_number);
                break;
            case "entry_date":
                $ans = datecmp($first_obj->dates->entry_date, $second_obj->dates->entry_date);
                break;
            case "product":
                $ans = strcmp($first_obj->trip_related_details[0]->product->name, $second_obj->trip_related_details[0]->product->name);
                break;
            case "route":
                $route_1 = $first_obj->trip_related_details[0]->source->name." to ".$first_obj->trip_related_details[0]->destination->name;
                $route_2 = $second_obj->trip_related_details[0]->source->name." to ".$second_obj->trip_related_details[0]->destination->name;
                $ans = strcmp($route_1, $route_2);
                break;
            case "stn":
                $stn_1 = intval($first_obj->trip_related_details[0]->stn_number);
                $stn_2 = intval($second_obj->trip_related_details[0]->stn_number);

                //$ans = strcmp($stn_1, $stn_2);
                if($stn_1 > $stn_2)
                {
                    $ans = 1;
                }else if($stn_1 < $stn_2)
                {
                    $ans = -1;
                }
                break;
            case "wht":
                $wht_1 = $first_obj->trip_related_details[0]->get_wht_amount($first_obj->company->wht);
                $wht_2 = $second_obj->trip_related_details[0]->get_wht_amount($second_obj->company->wht);
                if($wht_1 > $wht_2){
                    $ans = 1;
                }else if($wht_1 < $wht_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "cmp_total_freight":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_company();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_company();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "total_freight_for_customer":
                $total_freight_1 = $first_obj->trip_related_details[0]->get_total_freight_for_customer();
                $total_freight_2 = $second_obj->trip_related_details[0]->get_total_freight_for_customer();
                if($total_freight_1 > $total_freight_2){
                    $ans = 1;
                }else if($total_freight_1 < $total_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "cmp_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->company_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->company_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_freight_unit":
                $cmp_freight_unit_1 = $first_obj->trip_related_details[0]->customer_freight_unit;
                $cmp_freight_unit_2 = $second_obj->trip_related_details[0]->customer_freight_unit;
                if($cmp_freight_unit_1 > $cmp_freight_unit_2){
                    $ans = 1;
                }else if($cmp_freight_unit_1 < $cmp_freight_unit_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "company_commission":
                $company_commission_1 = $first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1);
                $company_commission_2 = $second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1);
                if($company_commission_1 > $company_commission_2){
                    $ans = 1;
                }else if($company_commission_1 < $company_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "company_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_company();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_company();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "company_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_company_commission_amount($first_obj->company->commission_1) - $first_obj->trip_related_details[0]->get_paid_to_company());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_company_commission_amount($second_obj->company->commission_1) - $second_obj->trip_related_details[0]->get_paid_to_company());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor":
                $contractor_1 = $first_obj->contractor->name;
                $contractor_2 = $second_obj->contractor->name;
                $ans = strcmp($contractor_1, $contractor_2);
                break;
            case "contractor_freight":
                $contractor_freight_1 = $first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company());
                $contractor_freight_2 = $second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($second_obj->get_contractor_freight_according_to_company());
                if($contractor_freight_1 > $contractor_freight_2){
                    $ans = 1;
                }else if($contractor_freight_1 < $contractor_freight_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_contractor();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_contractor();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;
            case "contractor_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $first_obj->trip_related_details[0]->get_paid_to_contractor());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_contractor_freight_amount_according_to_company($first_obj->get_contractor_freight_according_to_company()) - $second_obj->trip_related_details[0]->get_paid_to_contractor());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "contractor_commission":
                $contractor_commission_1 = $first_obj->trip_related_details[0]->get_contractor_commission_amount($first_obj->contractor->commission_1);
                $contractor_commission_2 = $second_obj->trip_related_details[0]->get_contractor_commission_amount($second_obj->contractor->commission_1);
                if($contractor_commission_1 > $contractor_commission_2){
                    $ans = 1;
                }else if($contractor_commission_1 < $contractor_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer":
                $customer_1 = $first_obj->customer->name;
                $customer_2 = $second_obj->customer->name;
                $ans = strcmp($customer_1, $customer_2);
                break;

            case "customer_net_freight":
                $customer_commission_1 = $first_obj->trip_related_details[0]->get_customer_freight_amount($first_obj->customer->freight);
                $customer_commission_2 = $second_obj->trip_related_details[0]->get_customer_freight_amount($second_obj->customer->freight);
                if($customer_commission_1 > $customer_commission_2){
                    $ans = 1;
                }else if($customer_commission_1 < $customer_commission_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_paid":
                $paid_1 = $first_obj->trip_related_details[0]->get_paid_to_customer();
                $paid_2 = $second_obj->trip_related_details[0]->get_paid_to_customer();
                if($paid_1 > $paid_2){
                    $ans = 1;
                }else if($paid_1 < $paid_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "customer_remaining":
                $remaining_1 = ($first_obj->trip_related_details[0]->get_customer_commission_amount($first_obj->customer->freight) - $first_obj->trip_related_details[0]->get_paid_to_customer());
                $remaining_2 = ($second_obj->trip_related_details[0]->get_customer_commission_amount($first_obj->customer->freight) - $second_obj->trip_related_details[0]->get_paid_to_customer());
                if($remaining_1 > $remaining_2){
                    $ans = 1;
                }else if($remaining_1 < $remaining_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "service_charges":
                $charges_1 = ($first_obj->trip_related_details[0]->contractor_benefits());
                $charges_2 = ($second_obj->trip_related_details[0]->contractor_benefits());
                if($charges_1 > $charges_2){
                    $ans = 1;
                }else if($charges_1 < $charges_2){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            case "billed":
                $first_num = ($first_obj->trip_related_details[0]->bill->id);
                $second_num = ($second_obj->trip_related_details[0]->bill->id);
                if($first_num > $second_num){
                    $ans = 1;
                }else if($first_num < $second_num){
                    $ans = -1;
                }else{
                    $ans = 0;
                }
                break;

            default:
                if($first_obj->trip_id > $second_obj->trip_id){
                    $ans = 1;
                }else{
                    $ans = -1;
                }

        }

        return $ans;
    }
}