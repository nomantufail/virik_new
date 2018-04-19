<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Expenses_Customer {

    public $trip_id;
    public $trip_date;
    public $tanker_number;
    public $tanker_expense;
    public $drivers_expense;
    public $shortage_at_destination;
    public $shortage_after_decanding;
    public $total_shortage;
    public $expense;
    public $product;
    public $price_unit;
    public $total_expense;
    public $total_quantity;
    public $route;

    public $ci;

    function __construct($trip){
        $this->ci =& get_instance();

        //assigning default values;
        $this->tanker_expense = 0;
        $this->drivers_expense = 0;
        $this->pd_expense = 0;

        $this->set_data($trip);

    }

    private function  set_data($trip){

        $this->trip_id = $trip->trip_id;
        $this->route = $trip->route;

        //setting trip_date
        $this->trip_date = $trip->entry_date;

        //setting tanker Expense
        $tanker_expenses = $this->ci->tankers_model->given_trip_tanker_expenses($trip->tanker_id, $trip->trip_id);
        foreach($tanker_expenses as $expense){
            $this->tanker_expense += $expense->amount;
        }

        //setting drivers expenses
        $driver_1_expenses_amount = 0;
        $driver_2_expenses_amount = 0;
        $driver_3_expenses_amount = 0;

        $driver_1_expenses = $this->ci->drivers_model->given_trip_driver_expenses($trip->driver_id_1, $trip->trip_id);
        foreach($driver_1_expenses as $expense){
            $driver_1_expenses_amount += $expense->amount;
        }

        $driver_2_expenses = $this->ci->drivers_model->given_trip_driver_expenses($trip->driver_id_2, $trip->trip_id);
        foreach($driver_2_expenses as $expense){
            $driver_2_expenses_amount += $expense->amount;
        }

        $driver_3_expenses = $this->ci->drivers_model->given_trip_driver_expenses($trip->driver_id_3, $trip->trip_id);
        foreach($driver_3_expenses as $expense){
            $driver_3_expenses_amount += $expense->amount;
        }
        $this->drivers_expense = $driver_1_expenses_amount + $driver_2_expenses_amount + $driver_3_expenses_amount;

        //setting product_expense
        $counter = 0;
        foreach($trip->trip_related_details as $trip_related_data)
        {
            $total_quantity = $trip_related_data->product_quantity;
            $shortage_at_destination = $trip_related_data->product_quantity - $trip_related_data->qty_at_destination;
            $shortage_after_decanding = $trip_related_data->qty_at_destination - $trip_related_data->qty_after_decanding;
            $price_unit = $trip_related_data->price_unit;
            $total_shortage = $shortage_at_destination + $shortage_after_decanding;
            $expense = $total_shortage * $trip_related_data->price_unit;
            $product = $trip_related_data->product;
            $this->total_expense += $expense;

            $counter++;
            $class = ($counter == sizeof($trip->trip_related_details))?"":'multiple_entites';
            $this->shortage_after_decanding = $this->shortage_after_decanding."<div class=$class>".$shortage_after_decanding."</div>";
            $this->shortage_at_destination = $this->shortage_at_destination."<div class=$class>".$shortage_at_destination."</div>";
            $this->total_shortage = $this->total_shortage."<div class=$class>".$total_shortage."</div>";
            $this->expense = $this->expense."<div class=$class>".$this->ci->helper_model->money($expense)."</div>";
            $this->total_quantity = $this->total_quantity."<div class=$class>".$this->ci->helper_model->money($total_quantity)."</div>";
            $this->price_unit = $this->price_unit."<div class=$class>".$this->ci->helper_model->money($price_unit)."</div>";
            $this->product = $this->product."<div class=$class>".$product."</div>";

        }
        /*$this->qty_difference_after_decanding = substr($this->qty_difference_after_decanding, 0, -4);
        $this->qty_difference_at_destination = substr($this->qty_difference_at_destination, 0, -4);
        $this->total_difference = substr($this->total_difference, 0, -4);
        $this->expense = substr($this->expense, 0, -4);
        $this->total_quantity = substr($this->total_quantity, 0, -4);*/
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        //setting tanker number
        $tanker = $this->ci->tankers_model->tanker($trip->tanker_id);
        $this->tanker_number = $tanker->truck_number;

    }

}
