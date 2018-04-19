<?php
class OtherAgents_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function otherAgents($orderby = 'desc'){
        $this->db->order_by("entryDate", $orderby);
        $otherAgents = $this->db->get('other_agents')->result();
        return $otherAgents;
    }

    public function limited_otherAgents($limit, $start) {

        $this->db->order_by("entryDate", 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get("other_agents");
        return $query->result();
    }

    public function search_limited_otherAgents($limit, $start, $keys, $sort) {

        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->like('name',$keys['name']);
        $this->db->like('idCard', $keys['idCard']);
        $this->db->limit($limit, $start);
        $query = $this->db->get("other_agents");
        return $query->result();
    }
    public function count_searched_otherAgents($keys) {

        $this->db->order_by("entryDate", 'desc');
        $this->db->like('name',$keys['name']);
        $this->db->like('idCard', $keys['idCard']);
        $query = $this->db->get("other_agents");
        return $query->num_rows();
    }

    public function otherAgent($id){
        $result = $this->db->get_where('other_agents', array('id'=>$id))->result();
        if($result){
            $customer = $result[0];
            return $customer;
        }else{
            return null;
        }
    }

    public function add_otherAgent(){
       $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'email'=>$this->input->post('email'),
            'idCard'=>$this->input->post('idCard'),
            'address'=>$this->input->post('address'),
            'image'=>$this->input->post('image'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('other_agents', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function re_submission(){
        $records = $this->db->get_where('other_agents', array(
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