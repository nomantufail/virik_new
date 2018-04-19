<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Tanker{

    public $id;
    public $tanker_number;
    public $capacity;

    public $ci;

    function __construct($id, $number, $givenCapacity){
        //$this->ci =& get_instance();

        $this->id = $id;
        $this->tanker_number = $number;
        $this->capacity = $givenCapacity;

    }


}
