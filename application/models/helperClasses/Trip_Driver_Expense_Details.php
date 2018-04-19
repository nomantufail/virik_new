<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Driver_Expense_Details {

    public $expense_id;
    public $id;
    public $trip_id;
    public $driver_id;
    public $expense_date;
    public $description;
    public $amount;
    public $route;

    public $ci;

    function __construct($trip_driver_expense){
        $this->ci =& get_instance();

        //assigning default values;

        $this->set_data($trip_driver_expense);

    }

    private function  set_data($trip_driver_expense){
        $this->expense_id = $trip_driver_expense->id;
        $this->trip_id = $trip_driver_expense->trip_id;
        $trip = $this->ci->trips_model->trip_details($trip_driver_expense->trip_id);
        $this->route = $trip->trip_related_details[0]->sourceCity." To ".$trip->trip_related_details[0]->sourceCity;
        $this->amount = $trip_driver_expense->amount;
        $this->description = $trip_driver_expense->description;
        $this->driver_id= $trip_driver_expense->driver_id;
        $this->expense_date = $trip_driver_expense->expense_date;
        $this->id = $trip_driver_expense->id;
    }

}
