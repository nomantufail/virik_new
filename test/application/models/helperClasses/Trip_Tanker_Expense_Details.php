<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Tanker_Expense_Details {

    public $expense_id;
    public $id;
    public $trip_id;
    public $tanker_id;
    public $expense_date;
    public $description;
    public $amount;
    public $route;

    public $ci;

    function __construct($trip_tanker_expense){
        $this->ci =& get_instance();

        //assigning default values;

        $this->set_data($trip_tanker_expense);

    }

    private function  set_data($trip_tanker_expense){

        $this->expense_id = $trip_tanker_expense->id;
        $this->trip_id = $trip_tanker_expense->trip_id;
        $trip = $this->ci->trips_model->trip_details($trip_tanker_expense->trip_id);
        $this->route = $trip->trip_related_details[0]->sourceCity." To ".$trip->trip_related_details[0]->destinationCity;
        $this->amount = $trip_tanker_expense->amount;
        $this->description = $trip_tanker_expense->description;
        $this->driver_id= $trip_tanker_expense->tanker_id;
        $this->expense_date = $trip_tanker_expense->expense_date;
        $this->id = $trip_tanker_expense->id;
    }

}
