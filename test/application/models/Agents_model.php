<?php
class Agents_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function get($orderby = 'asc'){
        $this->db->order_by("name", $orderby);
        $agents = $this->db->get('agents')->result();
        return $agents;
    }
    public function get_limited($limit, $start, $keys, $sort) {

        $this->db->order_by($sort['sort_by'], $sort['order']);
        if($keys['agent_id'] != '')
        {
            $this->db->where('id',$keys['agent_id']);
        }
        if($keys['type'] == 'suppliers')
        {
            $this->db->where('type',1);
        }else if($keys['type'] == 'customers'){
            $this->db->where('type',2);
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get("agents");
        return $query->result();
    }
    public function suppliers()
    {
        $this->db->where('type',1);
        $query = $this->db->get("agents");
        return $query->result();
    }
    public function customers()
    {
        $this->db->where('type',2);
        $query = $this->db->get("agents");
        return $query->result();
    }

    public function count($keys = "") {
        if($keys != "")
        {
            if($keys['agent_id'] != '')
            {
                $this->db->where('id',$keys['agent_id']);
            }
        }
        $query = $this->db->get("agents");
        return $query->num_rows();
    }

    public function find($id){
        $result = $this->db->get_where('agents', array('id'=>$id))->result();
        if($result){
            $agent = $result[0];
            return $agent;
        }else{
            return null;
        }
    }

    public function insert(){
       $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'address'=>$this->input->post('address'),
            'type'=>$this->input->post('type'),
       );
        $result = $this->db->insert('agents', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}