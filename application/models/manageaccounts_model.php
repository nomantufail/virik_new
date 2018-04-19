<?php

class ManageAccounts_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }


    public function dr_cr_statuses(&$accounts)
    {
        $trip_detail_ids = property_to_array('trip_detail_id', $accounts);

        if(sizeof($trip_detail_ids) == 0)
            return null;

        $this->db->select('*');
        $this->db->where_in('trip_detail_id',$trip_detail_ids);
        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
        return new Dr_Cr_Status_Manager($result);

    }

    public function search_white_oil($keys, $limit, $start){
        //applying keys....
        include_once(APPPATH."serviceProviders/Sort.php");
        $sorting_info = Sort::columns('manage_accounts_white_oil');

        /*
         * -------------------------------
         *  Search By Account Titles
         * -------------------------------
         */
        if($keys['account_title'] != '')
        {
            $trip_detail_ids = [];
            if($keys['dr_cr'] != ''){
                switch($keys['dr_cr'])
                {
                    case 1:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',1);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 2:
                        $debit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','1');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $debit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$debit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 0:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',0);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 3:
                        $credit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','0');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $credit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$credit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                }
            }




            if(sizeof($trip_detail_ids) == 0)
                $trip_detail_ids[] = 0;
        }

        /*-------------------------------*/


        $this->db->select('*');
        /*
         * ------------------------------
         * SEARCH BY TRIPS BILLING
         * ------------------------------
         * */
        if($keys['bill_status'] != '')
        {
            if($keys['bill_status'] == 1){
                $this->db->where('bill_id !=', 0);

                if($keys['billed_from'] != '')
                    $this->db->where('billed_date_time >',$keys['billed_from']);
                if($keys['billed_to'] != '')
                    $this->db->where('billed_date_time <',$keys['billed_to']);
            }
            if($keys['bill_status'] == 0){
                $this->db->where('bill_id', 0);
            }

        }

        /*--------------------------------------------*/


        /*
         * ------------------------------
         * OPEN AND CLOSED TRIPS
         * -----------------------------
         * */
        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 1){
                $this->db->where('stn_number', '');
            }else if ($keys['trip_status'] == 2){
                $this->db->where('stn_number !=', '');
            }
        }
        /*----------------------------------------*/

        if($keys['account_title'] != '')
        {
            if(sizeof($trip_detail_ids) > 0)
                $this->db->where_in('trip_detail_id',$trip_detail_ids);
        }

        if($keys['from'] != ''){
            $this->db->where('trip_date >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trip_date <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }

        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 1 OR trip_type_id = 3)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary_local'){
                $where = "(trip_type_id = 6)";
                $this->db->where($where);
            }
        }
        if($keys['trip_master_types'] != '' ){
            $trip_types = array();
            foreach($keys['trip_master_types'] as $type)
            {
                switch($type)
                {
                    case "primary":
                        $trip_types = [1,2,4,5];
                        break;
                    case "secondary":
                        array_push($trip_types,3);
                        break;
                    case "secondary_local":
                        array_push($trip_types,6);
                        break;
                }
            }
            $this->db->where_in('trip_type_id',$trip_types);
        }

        if($keys['tanker'] != ''){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trip_date',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where_in('product_id',$keys['product']);
        }
        if($keys['trips_routes'] != '')
        {
            $where = "(";
            foreach($keys['trips_routes'] as $route)
            {
                $route_parts = explode('_',$route);
                $where.="(source_id = ".$route_parts[0]." AND destination_id = ".$route_parts[1].") OR ";
            }
            $where.=")";
            $where_parts = explode(') OR )',$where);
            $where = $where_parts[0];
            $where.="))";
            $this->db->where($where);
        }
        else
        {
            if($keys['source'] != ''){
                $this->db->where_in('source_id',$keys['source']);
            }
            if($keys['destination'] != ''){
                $this->db->where_in('destination_id', $keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id', $keys['company']);
        }
        if($keys['cmp_freight_unit'] != ''){
            $this->db->where('company_freight_unit', $keys['cmp_freight_unit']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit', $keys['cst_freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('wht',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('company_commission',$keys['company_commission']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id', $keys['contractor']);
        }
        if($keys['contractor_commission'] != '' ){
            $this->db->where('contractor_commission', $keys['contractor_commission']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id', $keys['customer']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit',$keys['cst_freight_unit']);
        }

        /*
         * --------------------------------------
         * filter by trip details ids
         * --------------------------------------
         * */
        if(isset($keys['trip_detail_ids']) && $keys['trip_detail_ids'] != ''){
            $this->db->where_in('trip_detail_id',$keys['trip_detail_ids']);
        }

        foreach($sorting_info as $sort){
            $this->db->order_by($sort['sort_by'],$sort['order_by']);
        }
        $this->db->limit($limit, $start);
        $accounts = $this->db->get('manage_accounts_white_oil_view')->result();

        return $accounts;
    }

    public function count_searched_white_oil_accounts($keys)
    {
        return 10000;
        //applying keys....



        /*
         * -------------------------------
         *  Search By Account Titles
         * -------------------------------
         */

        if($keys['account_title'] != '')
        {
            $trip_detail_ids = [];
            if($keys['dr_cr'] != ''){
                switch($keys['dr_cr'])
                {
                    case 1:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',1);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 2:
                        $debit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','1');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $debit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$debit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 0:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',0);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 3:
                        $credit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','0');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $credit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$credit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                }
            }




            if(sizeof($trip_detail_ids) == 0)
                $trip_detail_ids[] = 0;
        }


        /*-------------------------------*/


        $this->db->select('*');
        /*
         * ------------------------------
         * SEARCH BY TRIPS BILLING
         * ------------------------------
         * */
        if($keys['bill_status'] != '')
        {
            if($keys['bill_status'] == 1){
                $this->db->where('bill_id !=', 0);

                if($keys['billed_from'] != '')
                    $this->db->where('billed_date_time >',$keys['billed_from']);
                if($keys['billed_to'] != '')
                    $this->db->where('billed_date_time <',$keys['billed_to']);
            }
            if($keys['bill_status'] == 0){
                $this->db->where('bill_id', 0);
            }

        }

        /*--------------------------------------------*/


        /*
         * ------------------------------
         * OPEN AND CLOSED TRIPS
         * -----------------------------
         * */
        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 1){
                $this->db->where('stn_number', '');
            }else if ($keys['trip_status'] == 2){
                $this->db->where('stn_number !=', '');
            }
        }
        /*----------------------------------------*/

        if($keys['account_title'] != '')
        {
            if(sizeof($trip_detail_ids) > 0)
                $this->db->where_in('trip_detail_id',$trip_detail_ids);
        }

        if($keys['from'] != ''){
            $this->db->where('trip_date >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trip_date <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }

        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 1 OR trip_type_id = 3)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary_local'){
                $where = "(trip_type_id = 6)";
                $this->db->where($where);
            }
        }
        if($keys['trip_master_types'] != '' ){
            $trip_types = array();
            foreach($keys['trip_master_types'] as $type)
            {
                switch($type)
                {
                    case "primary":
                        $trip_types = [1,2,4,5];
                        break;
                    case "secondary":
                        array_push($trip_types,3);
                        break;
                    case "secondary_local":
                        array_push($trip_types,6);
                        break;
                }
            }
            $this->db->where_in('trip_type_id',$trip_types);
        }

        if($keys['tanker'] != ''){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trip_date',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where_in('product_id',$keys['product']);
        }
        if($keys['trips_routes'] != '')
        {
            $where = "(";
            foreach($keys['trips_routes'] as $route)
            {
                $route_parts = explode('_',$route);
                $where.="(source_id = ".$route_parts[0]." AND destination_id = ".$route_parts[1].") OR ";
            }
            $where.=")";
            $where_parts = explode(') OR )',$where);
            $where = $where_parts[0];
            $where.="))";
            $this->db->where($where);
        }
        else
        {
            if($keys['source'] != ''){
                $this->db->where_in('source_id',$keys['source']);
            }
            if($keys['destination'] != ''){
                $this->db->where_in('destination_id', $keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id', $keys['company']);
        }
        if($keys['cmp_freight_unit'] != ''){
            $this->db->where('company_freight_unit', $keys['cmp_freight_unit']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit', $keys['cst_freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('wht',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('company_commission',$keys['company_commission']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id', $keys['contractor']);
        }
        if($keys['contractor_commission'] != '' ){
            $this->db->where('contractor_commission', $keys['contractor_commission']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id', $keys['customer']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit',$keys['cst_freight_unit']);
        }
        /*
         * --------------------------------------
         * filter by trip details ids
         * --------------------------------------
         * */
        if(isset($keys['trip_detail_ids']) && $keys['trip_detail_ids'] != ''){
            $this->db->where_in('trip_detail_id',$keys['trip_detail_ids']);
        }

        $this->db->select("trip_detail_id");
        $result = $this->db->get("manage_accounts_white_oil_view")->num_rows();
        return $result;
    }
    public function search_black_oil($keys, $limit, $start){
        //applying keys....
        include_once(APPPATH."serviceProviders/Sort.php");
        $sorting_info = Sort::columns('manage_accounts_black_oil');

        /*
         * -------------------------------
         *  Search By Account Titles
         * -------------------------------
         */

        if($keys['account_title'] != '')
        {
            $trip_detail_ids = [];
            if($keys['dr_cr'] != ''){
                switch($keys['dr_cr'])
                {
                    case 1:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',1);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 2:
                        $debit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','1');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $debit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$debit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 0:
                        $this->db->select('ma.trip_detail_id');
                        $this->db->join('dr_cr_status_for_manage_accounts_view as dr_cr_status','dr_cr_status.trip_detail_id = ma.trip_detail_id','left');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status.dr_cr',0);
                        $result = $this->db->get('manage_accounts_white_oil_view as ma')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                    case 3:
                        $credit_ids[] = 0;
                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where('account_title_id',$keys['account_title']);
                        $this->db->where('dr_cr_status_for_manage_accounts_view.dr_cr','0');
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $credit_ids = property_to_array('trip_detail_id',$result);

                        $this->db->select('dr_cr_status_for_manage_accounts_view.trip_detail_id');
                        $this->db->where_not_in('dr_cr_status_for_manage_accounts_view.trip_detail_id',$credit_ids);
                        $result = $this->db->get('dr_cr_status_for_manage_accounts_view')->result();
                        $trip_detail_ids = property_to_array('trip_detail_id',$result);
                        break;
                }
            }




            if(sizeof($trip_detail_ids) == 0)
                $trip_detail_ids[] = 0;
        }

        /*-------------------------------*/


        $this->db->select('*');
        /*
         * ------------------------------
         * SEARCH BY TRIPS BILLING
         * ------------------------------
         * */
        if($keys['bill_status'] != '')
        {
            if($keys['bill_status'] == 1){
                $this->db->where('bill_id !=', 0);

                if($keys['billed_from'] != '')
                    $this->db->where('billed_date_time >',$keys['billed_from']);
                if($keys['billed_to'] != '')
                    $this->db->where('billed_date_time <',$keys['billed_to']);
            }
            if($keys['bill_status'] == 0){
                $this->db->where('bill_id', 0);
            }

        }

        /*--------------------------------------------*/


        /*
         * ------------------------------
         * OPEN AND CLOSED TRIPS
         * -----------------------------
         * */
        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 1){
                $this->db->where('stn_number', '');
            }else if ($keys['trip_status'] == 2){
                $this->db->where('stn_number !=', '');
            }
        }
        /*----------------------------------------*/

        if($keys['account_title'] != '')
        {
            if(sizeof($trip_detail_ids) > 0)
                $this->db->where_in('trip_detail_id',$trip_detail_ids);
        }

        if($keys['from'] != ''){
            $this->db->where('trip_date >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trip_date <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }

        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 1 OR trip_type_id = 3)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary_local'){
                $where = "(trip_type_id = 6)";
                $this->db->where($where);
            }
        }
        if($keys['trip_master_types'] != '' ){
            $trip_types = array();
            foreach($keys['trip_master_types'] as $type)
            {
                switch($type)
                {
                    case "primary":
                        $trip_types = [1,2,4,5];
                        break;
                    case "secondary":
                        array_push($trip_types,3);
                        break;
                    case "secondary_local":
                        array_push($trip_types,6);
                        break;
                }
            }
            $this->db->where_in('trip_type_id',$trip_types);
        }

        if($keys['tanker'] != ''){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trip_date',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where_in('product_id',$keys['product']);
        }
        if($keys['trips_routes'] != '')
        {
            $where = "(";
            foreach($keys['trips_routes'] as $route)
            {
                $route_parts = explode('_',$route);
                $where.="(source_id = ".$route_parts[0]." AND destination_id = ".$route_parts[1].") OR ";
            }
            $where.=")";
            $where_parts = explode(') OR )',$where);
            $where = $where_parts[0];
            $where.="))";
            $this->db->where($where);
        }
        else
        {
            if($keys['source'] != ''){
                $this->db->where_in('source_id',$keys['source']);
            }
            if($keys['destination'] != ''){
                $this->db->where_in('destination_id', $keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id', $keys['company']);
        }
        if($keys['cmp_freight_unit'] != ''){
            $this->db->where('company_freight_unit', $keys['cmp_freight_unit']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit', $keys['cst_freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('wht',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('company_commission',$keys['company_commission']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id', $keys['contractor']);
        }
        if($keys['contractor_commission'] != '' ){
            $this->db->where('contractor_commission', $keys['contractor_commission']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id', $keys['customer']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('customer_freight_unit',$keys['cst_freight_unit']);
        }

        /*
         * --------------------------------------
         * filter by trip details ids
         * --------------------------------------
         * */
        if(isset($keys['trip_detail_ids']) && $keys['trip_detail_ids'] != ''){
            $this->db->where_in('trip_detail_id',$keys['trip_detail_ids']);
        }

        foreach($sorting_info as $sort){
            $this->db->order_by($sort['sort_by'],$sort['order_by']);
        }
        $this->db->limit($limit, $start);
        $accounts = $this->db->get('manage_accounts_black_oil_view')->result();

        return $accounts;
    }

    public function count_searched_black_oil_accounts($keys)
    {
        return 10000;
        $this->db->select("trip_detail_id");
        $result = $this->db->get("manage_accounts_black_oil_view")->num_rows();
        return $result;
    }


    public function fetch_trips_ids_by_trips_details_ids($details_ids)
    {
        $this->db->select('trips.id');
        $this->db->from("trips");
        $this->db->distinct();
        $this->db->join('trips_details','trips_details.trip_id = trips.id', 'left');
        $this->db->where('trips.active',1);
        $this->db->where_in('trips_details.id',$details_ids);
        $result = $this->db->get()->result();
        $trips_ids[0] = 0;
        foreach($result as $record){
            array_push($trips_ids, $record->id);
        }
        return $trips_ids;

    }

    public function save_voucher($account_holder = "users")
    {
        $trips_details_ids = $this->input->post('trips_details_ids');
        $trips_details_ids_array = explode('_',$trips_details_ids);
        $key = array_search('0', $trips_details_ids_array);
        unset($trips_details_ids_array[$key]);
        if(sizeof($trips_details_ids_array) == 0)
        {
            return false;
        }


        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$this->input->post('voucher_date'),
            'detail' => $this->input->post('voucher_details'),
            'person_tid' => $account_holder.".1",
            'transaction_column'=>$this->input->post('transaction_column'),
            'manage_account_type'=>(isset($_POST['manage_account_type']))?$_POST['manage_account_type']:'',
            'tanker_id'=>(isset($_POST['tankers']))?$_POST['tankers']:0,
        );
        $result = $this->db->insert('voucher_journal', $journal_voucher_data);
        $inserted_voucher_id = $this->db->insert_id();
        if($result == true){
            $voucher_entries = array();
            $entries_counter = $this->input->post('pannel_count');
            for($counter = 1; $counter < $entries_counter; $counter++){
                $entry['account_title_id'] = $this->input->post('tr_title_'.$counter);
                $entry['description'] = $this->input->post('description_'.$counter);
                $related_other_agent = ($this->input->post('agent_type_'.$counter) == 'other_agents')?$this->input->post('agent_id_'.$counter):0;
                $related_customer = ($this->input->post('agent_type_'.$counter) == 'customers')?$this->input->post('agent_id_'.$counter):0;
                $related_contractor = ($this->input->post('agent_type_'.$counter) == 'carriage_contractors')?$this->input->post('agent_id_'.$counter):0;
                $related_company = ($this->input->post('agent_type_'.$counter) == 'companies')?$this->input->post('agent_id_'.$counter):0;

                $entry['related_company'] = $related_company;
                $entry['related_other_agent'] = $related_other_agent;
                $entry['related_customer'] = $related_customer;
                $entry['related_contractor'] = $related_contractor;
                $entry['debit_amount'] = ($this->input->post('payment_type_'.$counter) == 0)?0.00:$this->input->post('amount_'.$counter);
                $entry['credit_amount'] = ($this->input->post('payment_type_'.$counter) == 1)?0.00:$this->input->post('amount_'.$counter);
                $entry['dr_cr'] = $this->input->post('payment_type_'.$counter);
                $entry['journal_voucher_id'] = $inserted_voucher_id;

                array_unshift($voucher_entries, $entry);
            }
            if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
                $trip_ids_voucher_ids = array();
                foreach($trips_details_ids_array as $id){
                    $record = array(
                        'trip_detail_id'=>$id,
                        'voucher_id'=>$inserted_voucher_id,
                    );
                    array_push($trip_ids_voucher_ids, $record);
                }
                $result = $this->db->insert_batch('trip_detail_voucher_relation', $trip_ids_voucher_ids);
                if($result == true){
                    return true;
                }
            }
        }
        return false;

    }

    public function save_custom_voucher($account_holder = "users")
    {
       // $trips_details_ids = $this->input->post('trips_details_ids');

        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$this->input->post('voucher_date'),
            'detail' => $this->input->post('voucher_details'),
            'person_tid' => $account_holder.".1",
            'tanker_id'=>$this->input->post('tankers'),
            'trip_id'=>$this->input->post('trip_id'),
        );
        $result = $this->db->insert('voucher_journal', $journal_voucher_data);
        $inserted_voucher_id = $this->db->insert_id();
        if($result == true){
            $voucher_entries = array();
            $entries_counter = $this->input->post('pannel_count');
            for($counter = 1; $counter < $entries_counter; $counter++){
                $entry['account_title_id'] = $this->input->post('tr_title_'.$counter);
                $entry['description'] = $this->input->post('description_'.$counter);
                $related_other_agent = ($this->input->post('agent_type_'.$counter) == 'other_agents')?$this->input->post('agent_id_'.$counter):0;
                $related_customer = ($this->input->post('agent_type_'.$counter) == 'customers')?$this->input->post('agent_id_'.$counter):0;
                $related_contractor = ($this->input->post('agent_type_'.$counter) == 'carriage_contractors')?$this->input->post('agent_id_'.$counter):0;
                $related_company = ($this->input->post('agent_type_'.$counter) == 'companies')?$this->input->post('agent_id_'.$counter):0;

                $entry['related_company'] = $related_company;
                $entry['related_other_agent'] = $related_other_agent;
                $entry['related_customer'] = $related_customer;
                $entry['related_contractor'] = $related_contractor;
                $entry['debit_amount'] = ($this->input->post('payment_type_'.$counter) == 0)?0.00:$this->input->post('amount_'.$counter);
                $entry['credit_amount'] = ($this->input->post('payment_type_'.$counter) == 1)?0.00:$this->input->post('amount_'.$counter);
                $entry['dr_cr'] = $this->input->post('payment_type_'.$counter);
                $entry['journal_voucher_id'] = $inserted_voucher_id;

                array_unshift($voucher_entries, $entry);
            }
            if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
                return true;
            }
        }
        return false;

    }

    public function bill_trips($detail_ids)
    {
        $this->db->trans_start();
        /*$now = Carbon::now();
        $now = $now->addHours(4);*/
        $bill = array(
            'billed_date_time'=>$this->input->post('bill_date'),
        );
        $this->db->insert('bills',$bill);
        $bill_id = $this->db->insert_id();
        $detail_data = array(
            'bill_id'=>$bill_id,
        );
        $this->db->where_in('trips_details.id',$detail_ids);
        $this->db->update('trips_details',$detail_data);
        $this->db->trans_complete();
        if($this->db->trans_status() == true)
        {
            return true;
        }
        return false;
    }

    public function un_bill_trips($detail_ids)
    {
        $data = array(
            'bill_id'=>0,
        );
        $this->db->where_in('trips_details.id',$detail_ids);
        if($this->db->update('trips_details',$data) == true)
        {
            return true;
        }
        return false;
    }

    public function apply_shortage_details($final_trips)
    {
        $shortage_voucher_ids = array();
        foreach($final_trips as $trip)
        {
            foreach($trip->trip_related_details as $detail)
            {
                if($detail->shortage_voucher_decnd != 0)
                {
                    array_push($shortage_voucher_ids, $detail->shortage_voucher_decnd);
                }
                else if($detail->shortage_voucher_dest != 0)
                {
                    array_push($shortage_voucher_ids, $detail->shortage_voucher_dest);
                }
            }
        }
        /* fetching shortage details by given voucher ids */
        $shortage_details = $this->accounts_model->fetch_shortage_details_by_given_voucher_ids($shortage_voucher_ids);

        /**** Setting Shortage Details ****/
        foreach($final_trips as &$trip)
        {
            foreach($trip->trip_related_details as &$detail)
            {
                foreach($shortage_details as $shortage_detail)
                {
                    if($detail->product_detail_id == $shortage_detail->trip_product_detail_id)
                    {
                        $detail->shortage_detail = $shortage_detail->shortage_detail;
                        $detail->shortage_rate = $shortage_detail->shortage_rate;
                        $detail->shortage_quantity = $shortage_detail->shortage_quantity;
                    }
                }
            }
        }

        return $final_trips;
    }


}
