<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 4/17/2016
 * Time: 11:35 AM
 */
include_once(APPPATH.'repositories/Repository.php');

class TripsRepository extends Repository{

    private $db;
    public function __construct()
    {
        $this->db = &get_instance()->db;
    }

    public function get($keys, $limit, $start, $sort){
        //applying keys....
        include_once(APPPATH."serviceProviders/Sort.php");
        $sorting_info = Sort::columns($keys['module']);

        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 2){
                $this->db->where('stn_number !=','');
            }
            if($keys['trip_status'] == 1)
            {
                $this->db->where('stn_number','');
            }
        }
        if($keys['from'] != ''){
            $this->db->where('entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('entryDate',$keys['entryDate']);
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
                $this->db->where_in('destination_id',$keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id',$keys['company']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id',$keys['contractor']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id',$keys['customer']);
        }
        if($keys['tanker'] != '' ){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['stn_number'] != '' ){
            $this->db->like('stn_number', $keys['stn_number']);
        }

        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }


        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4 OR trip_type_id = 1 OR trip_type_id = 5)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 3)";
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
        ///////////////////////////////////////////////////////

        $this->db->select('*');
        $this->db->limit($limit, $start);
        foreach($sorting_info as $sort)
        {
            $this->db->order_by($sort['sort_by'],$sort['order_by']);
        }
        $trips = $this->db->get('trips_view')->result();

        print_r($trips);die();
        return $trips;
    }
} 