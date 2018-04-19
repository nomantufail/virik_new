<?php
/**
 * Created by PhpStorm.
 * User: noman_2
 * Date: 2/8/2016
 * Time: 7:05 AM
 */

class Event
{
    public $db;
    public function __construct(){
        $this->db = & get_instance()->db;
    }
}