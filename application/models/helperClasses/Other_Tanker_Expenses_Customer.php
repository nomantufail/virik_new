<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Other_Tanker_Expenses_Customer {

    public $expense_date;
    public $tanker_number;
    public $amount;
    public $description;
    public $tanker_id;

    public $ci;

    function __construct($expense){
        $this->ci =& get_instance();

        //assigning default values;
        $this->amount = 0;

        $this->set_data($expense);

    }

    private function  set_data($expense){

        //setting tanker Expense
        $this->expense_date = $expense->expense_date;
        $this->tanker_id = $expense->tanker_id;
        $this->description = $expense->description;
        $this->amount = $expense->amount;

        $tanker = $this->ci->tankers_model->tanker($expense->tanker_id);
        $this->tanker_number = $tanker->truck_number;

    }

}
