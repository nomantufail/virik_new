<?php
class Companies_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function companies(){
        $this->db->order_by("name", "asc");
        $companies = $this->db->get('companies')->result();
        return $companies;
    }

    public function limited_companies($limit, $start) {

        $this->db->order_by("entryDate", 'asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get("companies");
        return $query->result();
    }

    public function search_limited_companies($limit, $start, $keys, $sort) {
        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->like('name',$keys['name']);
        $this->db->limit($limit, $start);
        $query = $this->db->get("companies");
        return $query->result();
    }
    public function count_searched_companies($keys) {
        $this->db->order_by("entryDate", 'asc');
        $this->db->like('name',$keys['name']);
        $query = $this->db->get("companies");
        return $query->num_rows();
    }

    public function company($id){
        $result = $this->db->get_where('companies', array('id'=>$id))->result();
        if($result){
            $company = $result[0];
            return $company;
        }else{
            return null;
        }
    }

    public function add_companies(){
        $data = array(
            'name'=>$this->input->post('name'),
            'phone'=>$this->input->post('phone'),
            'email'=>$this->input->post('email'),
            'address'=>$this->input->post('address'),
            'image'=>$this->input->post('image'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('companies', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}