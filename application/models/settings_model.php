<?php
class Settings_Model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function save_accounting_year()
    {
        $date = $this->input->post('accounting_year_from');
        $data = array(
            'from'=>$date,
        );
        $this->db->where(array('id >'=>0));
        if($this->db->update('accounting_year',$data)){
            return true;
        }else{
            return false;
        }
    }

    public function system_settings($title='')
    {
        if($title == '')
        {
            $this->db->select('*');
            $result = $this->db->get('system_settings')->result();
            return $result;
        }
        else
        {
            $this->db->select('value');
            $result = $this->db->get_where('system_settings',array(
                'title'=>$title,
            ))->result();
            return $result[0]->value;
        }
    }

}