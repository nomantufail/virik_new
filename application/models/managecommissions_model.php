<?php
class ManageCommissions_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function contractor_customer_commissions(){
        include_once(APPPATH."models/helperClasses/Contractor_Customer_Commission_Data.php");
        $this->db->order_by("entryDate", "desc");
        $commissions = $this->db->get('contractor_customer_commissions')->result();
        $commissions_data = array();
        foreach($commissions as $commission){
            array_push($commissions_data, new Contractor_Customer_Commission_Data($commission));
        }
        return $commissions_data;
    }
    public function contractor_customer_commissions_simple(){
        $this->db->select('contractor_id, customer_id, freight_commission');
        $commissions = $this->db->get('contractor_customer_commissions')->result();
        foreach($commissions as &$commission)
        {
            $commission->key = $commission->contractor_id."_".$commission->customer_id;
        }
        return $commissions;
    }

    public function search_contractor_customer_commissions(){
        include_once(APPPATH."models/helperClasses/Contractor_Customer_Commission_Data.php");

        $likes = array(
            'customers.name'=>$_GET['customer'],
            'carriage_contractors.name'=>$_GET['contractor'],
            'contractor_customer_commissions.freight_commission'=>$_GET['commission'],
            'contractor_customer_commissions.id'=>$_GET['id'],
        );
        $this->db->select('contractor_customer_commissions.id, customers.name as customerName, carriage_contractors.name as contractorName, contractor_customer_commissions.freight_commission as freight_commission ');
        //$this->db->distinct();
        $this->db->from('contractor_customer_commissions');
        $this->db->join('customers', 'customers.id = contractor_customer_commissions.customer_id');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = contractor_customer_commissions.contractor_id');
        $this->db->like($likes);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_searched_contractor_customer_commissions($keys){
        include_once(APPPATH."models/helperClasses/Contractor_Customer_Commission_Data.php");

        $likes = array(
            'customers.name'=>$keys['customer'],
            'carriage_contractors.name'=>$keys['contractor'],
            'contractor_customer_commissions.freight_commission'=>$keys['commission'],
            'contractor_customer_commissions.id'=>$keys['id'],
        );
        $this->db->select('contractor_customer_commissions.id, customers.name as customerName, carriage_contractors.name as contractorName, contractor_customer_commissions.freight_commission as freight_commission ');
        //$this->db->distinct();
        $this->db->from('contractor_customer_commissions');
        $this->db->join('customers', 'customers.id = contractor_customer_commissions.customer_id');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = contractor_customer_commissions.contractor_id');
        $this->db->like($likes);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function search_limited_contractor_customer_commissions($limit, $start, $keys, $sort) {
        $likes = array(
            'customers.name'=>$keys['customer'],
            'carriage_contractors.name'=>$keys['contractor'],
            'contractor_customer_commissions.freight_commission'=>$keys['commission'],
            'contractor_customer_commissions.id'=>$keys['id'],
        );
        $this->db->select('contractor_customer_commissions.id, customers.name as customerName, carriage_contractors.name as contractorName, contractor_customer_commissions.freight_commission as freight_commission ');
        //$this->db->distinct();
        $this->db->from('contractor_customer_commissions');
        $this->db->join('customers', 'customers.id = contractor_customer_commissions.customer_id');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = contractor_customer_commissions.contractor_id');
        $this->db->like($likes);
        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function contractor_company_commissions(){
        include_once(APPPATH."models/helperClasses/Contractor_Company_Commission_Data.php");
        $this->db->order_by("entryDate", "desc");
        $commissions = $this->db->get('contractor_company_commissions')->result();
        $commissions_data = array();
        foreach($commissions as $commission){
            array_push($commissions_data, new Contractor_Company_Commission_Data($commission));
        }
        return $commissions_data;
    }
    public function contractor_company_commissions_simple(){
        $this->db->select('company_id, contractor_id, commission_1, commission_2');
        $commissions = $this->db->get('contractor_company_commissions')->result();
        foreach($commissions as &$commission)
        {
            $commission->key = $commission->contractor_id."_".$commission->company_id;
        }
        return $commissions;
    }

    public function search_limited_contractor_company_commissions($limit, $start, $keys, $sort){
        $likes = array(
            'companies.name'=>$keys['company'],
            'carriage_contractors.name'=>$keys['contractor'],
            'contractor_company_commissions.commission_1'=>$keys['commission_1'],
            'contractor_company_commissions.commission_2'=>$keys['commission_2'],
            'contractor_company_commissions.commission_3'=>$keys['commission_3'],
            'contractor_company_commissions.id'=>$keys['id'],
        );
        $this->db->select('contractor_company_commissions.id, carriage_contractors.name as contractorName, companies.name as companyName, contractor_company_commissions.commission_1 as commission_1, contractor_company_commissions.commission_2 as commission_2, contractor_company_commissions.commission_3 as commission_3 ');
        //$this->db->distinct();
        $this->db->from('contractor_company_commissions');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = contractor_company_commissions.contractor_id');
        $this->db->join('companies', 'companies.id = contractor_company_commissions.company_id');
        $this->db->like($likes);
        $this->db->order_by($sort['sort_by'], $sort['order']);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_searched_contractor_company_commissions($keys){

        $likes = array(
            'companies.name'=>$keys['company'],
            'carriage_contractors.name'=>$keys['contractor'],
            'contractor_company_commissions.commission_1'=>$keys['commission_1'],
            'contractor_company_commissions.commission_2'=>$keys['commission_2'],
            'contractor_company_commissions.commission_3'=>$keys['commission_3'],
            'contractor_company_commissions.id'=>$keys['id'],
        );
        $this->db->select('contractor_company_commissions.id, carriage_contractors.name as contractorName, companies.name as companyName, contractor_company_commissions.commission_1 as commission_1, contractor_company_commissions.commission_2 as commission_2, contractor_company_commissions.commission_3 as commission_3 ');
        //$this->db->distinct();
        $this->db->from('contractor_company_commissions');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = contractor_company_commissions.contractor_id');
        $this->db->join('companies', 'companies.id = contractor_company_commissions.company_id');
        $this->db->like($likes);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contractor_customer_commission($contractor_id, $customer_id){
        $result = $this->db->get_where('contractor_customer_commissions', array('contractor_id'=>$contractor_id, 'customer_id'=>$customer_id,))->result();
        if($result){
            $commission = $result[0];
            return $commission;
        }else{
            return null;
        }
    }

    public function contractor_company_commission($contractor_id, $company_id){
        $result = $this->db->get_where('contractor_company_commissions', array('contractor_id'=>$contractor_id, 'company_id'=>$company_id,))->result();
        if($result){
            $commission = $result[0];
            return $commission;
        }else{
            return null;
        }
    }

    public function add_contractor_customer_commission(){
        $data = array(
            'contractor_id'=>$this->input->post('contractors'),
            'customer_id'=>$this->input->post('customers'),
            'freight_commission'=>$this->input->post('freight_commission'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('contractor_customer_commissions', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function add_contractor_company_commission(){
        $data = array(
            'contractor_id'=>$this->input->post('contractors'),
            'company_id'=>$this->input->post('companies'),
            'commission_1'=>$this->input->post('commission_1'),
            'commission_2'=>$this->input->post('commission_2'),
            'commission_3'=>$this->input->post('commission_3'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('contractor_company_commissions', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}