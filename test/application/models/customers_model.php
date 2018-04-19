<?php
class Customers_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function customers($orderby = 'asc'){
        $this->db->order_by("id", $orderby);
        $customers = $this->db->get('customers')->result();
        return $customers;
    }

    public function limited_customers($limit, $start) {

        $this->db->order_by("entryDate", 'asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get("customers");
        return $query->result();
    }

    public function search_limited_customers($limit, $start, $keys, $sort) {

        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->like('name',$keys['name']);
        $this->db->limit($limit, $start);
        $query = $this->db->get("customers");
        return $query->result();
    }
    public function count_searched_customers($keys) {

        $this->db->order_by("entryDate", 'asc');
        $this->db->like('name',$keys['name']);
        $query = $this->db->get("customers");
        return $query->num_rows();
    }

    public function customer($id){
        $result = $this->db->get_where('customers', array('id'=>$id))->result();
        if($result){
            $customer = $result[0];
            return $customer;
        }else{
            return null;
        }
    }

    public function add_customer(){
       $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'email'=>$this->input->post('email'),
            'idCard'=>$this->input->post('idCard'),
            'address'=>$this->input->post('address'),
            'image'=>$this->input->post('image'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('customers', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function re_submission(){
        $records = $this->db->get_where('customers', array(
            'name' => $this->input->post('name'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('name'),
            'idCard' => $this->input->post('idCard'),
            'image' => $this->input->post('image'),
        ))->num_rows();

        if($records >= 1){
            return true;
        }else{
            return false;
        }
    }

}