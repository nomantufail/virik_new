<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Driver{

    public $id;

    //this array will contain trip_related_details objects
    public $name;

    public $ci;

    function __construct($id, $name){
        //$this->ci =& get_instance();

        $this->id = $id;
        $this->name = $name;

    }


}
