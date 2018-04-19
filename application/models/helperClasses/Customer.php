<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Customer{

    public $id;

    //this array will contain trip_related_details objects
    public $name;

    public $freight;

    public $ci;

    function __construct($id, $name, $freight){
        //$this->ci =& get_instance();

        $this->id = $id;
        $this->name = $name;
        $this->freight = $freight;

    }


}
