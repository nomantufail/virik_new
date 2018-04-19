<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Voucher_Entry {

    public $id;
    public $ac_type;
    public $title;
    public $account_title_id;
    public $related_agent;
    public $related_agents_list;
    public $related_agent_id;
    public $related_agent_name;
    public $description;
    public $debit;
    public $credit;
    public $dr_cr;
    public $journal_voucher_id;
    public $person_tid;

    public $active;//optional
    public $voucher_date; //optional

    public $ci;

    function Voucher_Entry(){
        $this->ci =& get_instance();

        $this->setDebit(0);
        $this->setCredit(0);
        $this->setRelated_agent("");
        $this->related_agents_list = array();
    }

    public function get_account_holder_type()
    {
        $person_tid = explode('.', $this->person_tid);
        return $person_tid[0];
    }

    public function setId($givenId)
    {
        $this->id = $givenId;
    }

    public function setAc_type($givenAc_type)
    {
        $this->ac_type = $givenAc_type;
    }

    public function setTitle($givenTitle)
    {
        $this->title = $givenTitle;
    }
    public function setAccount_title_id($id)
    {
        $this->account_title_id = $id;
    }

    public function setRelated_person_tid($related_person_tid)
    {
        $arr = explode('.',$related_person_tid);
        if(sizeof($arr) == 2){
            if($arr[0] != '' && $arr[1] != ''){
                $table = $arr[0];
                $id = $arr[1];
                $this->ci->db->select('name');
                $result = $this->ci->db->get_where($table, array('id'=>$id))->result();
                $related_agent_name = $result[0]->name;
                $this->setRelated_agent_name($related_agent_name);
                $this->setRelated_agent($table);
                $this->setRelated_agent_id($id);
            }
        }
    }

    public function setRelated_agent_name($name)
    {
        $this->related_agent_name = $name;
    }
    public function setRelated_agent_id($id)
    {
        $this->related_agent_id = $id;
    }
    public function setRelated_agent($agent)
    {
        $this->related_agent = $agent;

        //now fetching all the agents from database for given type
        $this->setRelated_agents_list($agent);
    }
    public function setRelated_agents_list($agent)
    {
        if($agent == 'customers'){
            $agents = $this->ci->customers_model->customers();
            $this->related_agents_list = $agents;
        }
        if($agent == 'other_agents'){
            $agents = $this->ci->otherAgents_model->otherAgents();
            $this->related_agents_list = $agents;
        }
        if($agent == 'carriage_contractors'){
            $agents = $this->ci->carriageContractors_model->carriageContractors();
            $this->related_agents_list = $agents;
        }
        if($agent == 'companies'){
            $agents = $this->ci->companies_model->companies();
            $this->related_agents_list = $agents;
        }
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setDebit($amount)
    {
        $this->debit = $amount;
    }
    public function setCredit($amount)
    {
        $this->credit = $amount;
    }
    public function setDr_cr($arg)
    {
        $this->dr_cr = $arg;
    }
    public function setJournal_voucher_id($id)
    {
        $this->journal_voucher_id = $id;
    }



}
