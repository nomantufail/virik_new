<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 12/30/14
 * Time: 9:13 AM
 */

class ThirdPerson extends CI_Model{

    private $id;
    private $name;
    private $date_created;
    private $detail;

    public function __construct(){
        parent::__construct();
    }

    public function update( $name, $detail = "Empty")
    {
        $this->name = $name;
        $this->date_created = date('Y-m-d');
        $this->detail = $detail;
    }

    public function save($data)
    {
        $this->db->insert('third_person', $data);

    }
}

