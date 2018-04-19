<?php
class CarriageContractors_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function carriageContractors($order = 'asc'){
        $this->db->order_by("name", $order);
        $carriageContractors = $this->db->get('carriage_contractors')->result();
        return $carriageContractors;
    }

    public function limited_carriageContractors($limit, $start) {

        $this->db->order_by("entryDate", 'asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get("carriage_contractors");
        return $query->result();
    }
    public function search_limited_contractors($limit, $start, $keys, $sort) {
        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->like('name',$keys['name']);
        $this->db->limit($limit, $start);
        $query = $this->db->get("carriage_contractors");
        return $query->result();
    }
    public function count_searched_contractors($keys) {
        $this->db->order_by("entryDate", 'asc');
        $this->db->like('name',$keys['name']);
        $query = $this->db->get("carriage_contractors");
        return $query->num_rows();
    }
    public function carriageContractor($id){
        $result = $this->db->get_where('carriage_contractors', array('id'=>$id))->result();
        if($result){
            $carriageContractor = $result[0];
            return $carriageContractor;
        }else{
            return null;
        }
    }

    public function add_contractor(){
        $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'email'=>$this->input->post('email'),
            'idCard'=>$this->input->post('idCard'),
            'address'=>$this->input->post('address'),
            'image'=>$this->input->post('image'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('carriage_contractors', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}