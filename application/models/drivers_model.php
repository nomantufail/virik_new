<?php
class Drivers_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function drivers(){
        $this->db->order_by("entryDate", "desc");
        $drivers = $this->db->get('drivers')->result();
        return $drivers;
    }

    public function limited_drivers($limit, $start){

        $this->db->order_by("entryDate", "desc");
        $this->db->limit($limit, $start);
        return $this->db->get("drivers")->result();

    }

    public function search_limited_drivers($limit, $start, $keys, $sort) {
        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->like('name',$keys['name']);
        $this->db->limit($limit, $start);
        $query = $this->db->get("drivers");
        return $query->result();
    }
    public function count_searched_drivers($keys) {
        $this->db->order_by("entryDate", 'asc');
        $this->db->like('name',$keys['name']);
        $query = $this->db->get("drivers");
        return $query->num_rows();
    }

    public function driver($id){
        $result = $this->db->get_where('drivers', array('id'=>$id))->result();
        if($result){
            $driver = $result[0];
            return $driver;
        }else{
            return null;
        }
    }

    public function trips($driver_id){
        $this->db->select('id');
        $this->db->where('driver_id_1', $driver_id);
        $this->db->or_where('driver_id_2', $driver_id);
        $this->db->or_where('driver_id_3', $driver_id);
        $trips = $this->db->get('trips')->result();
        $trips_details = array();
        foreach($trips as $trip){
           array_push($trips_details, $this->trips_model->trip_details($trip->id));
        }

        return $trips_details;
    }

    public function trips_by_month($driver_id, $month){
        $this->db->select('id');

        $where = "(`driver_id_1` = '".$driver_id."' OR `driver_id_2` = '".$driver_id."' OR `driver_id_3` = '".$driver_id."')";
        $this->db->or_where($where)->like('filling_date',$month, 'after');
        $trips = $this->db->get('trips')->result();
        $trips_details = array();
        foreach($trips as $trip){
            array_push($trips_details, $this->trips_model->trip_details($trip->id));
        }

        return $trips_details;
    }

    public function add_driver(){
        $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'email'=>$this->input->post('email'),
            'idCard'=>$this->input->post('idCard'),
            'address'=>$this->input->post('address'),
            'image'=>$this->input->post('image'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('drivers', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function trips_expenses($driver_id){
        $expenses = array();

        $this->db->order_by("entryDate", "desc");
        $this->db->where('driver_id_1',$driver_id);
        $this->db->or_where('driver_id_2', $driver_id);
        $this->db->or_where('driver_id_3', $driver_id);
        $driver_trips = $this->db->get('trips')->result();
        foreach($driver_trips as $driver_trip){
            $this->db->order_by("entryDate", "asc");
            $trip_driver_expenses = $this->db->get_where('trips_drivers_expenses', array('driver_id'=>$driver_id, 'trip_id'=>$driver_trip->id))->result();
            foreach($trip_driver_expenses as $trip_driver_expense){
                array_unshift($expenses, $trip_driver_expense);
            }
        }

        return $expenses;
    }

    public function trips_expenses_by_month($driver_id, $month){
        include_once(APPPATH."models/helperClasses/Trip_Driver_Expense_Details.php");
        $this->db->select('id');
        $where = "(`driver_id_1` = '".$driver_id."' OR `driver_id_2` = '".$driver_id."' OR `driver_id_3` = '".$driver_id."')";
        $this->db->or_where($where)->like('filling_date',$month, 'after');
        $trips = $this->db->get('trips')->result();
        $trips_expenses = array();
        foreach($trips as $trip){
            $trip_driver_expenses = $this->db->get_where('trips_drivers_expenses', array('driver_id'=>$driver_id, 'trip_id'=>$trip->id))->result();
            foreach($trip_driver_expenses as $trip_driver_expense){
                array_unshift($trips_expenses, new Trip_Driver_Expense_Details($trip_driver_expense));
            }
        }

        return $trips_expenses;

    }

    //in case when trip id and tanker_id both are given
    public function given_trip_driver_expenses($driver_id, $trip_id){

        $expenses = array();

        $this->db->order_by("entryDate", "asc");
        $this->db->where(array(
            'driver_id'=>$driver_id,
            'trip_id'=>$trip_id,
        ));
        $trip_driver_expenses = $this->db->get('trips_drivers_expenses')->result();
        foreach($trip_driver_expenses as $trip_driver_expense){
            array_unshift($expenses, $trip_driver_expense);
        }

        return $expenses;
    }

    public function add_expense($id){
        $data = array(
            'driver_id' => $id,
            'expense_date'=>easyDate($this->input->post('date')),
            'description'=>$this->input->post('description'),
            'expense'=>$this->input->post('expense'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('drivers_expenses', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function add_trip_expense($id){
        $data = array(
            'driver_id' => $id,
            'trip_id' => $this->input->post('trip_id'),
            'expense_date'=>easyDate($this->input->post('expense_date')),
            'description'=>$this->input->post('description'),
            'amount'=>$this->input->post('amount'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('trips_drivers_expenses', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}