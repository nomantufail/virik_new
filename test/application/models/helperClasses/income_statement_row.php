<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 5/18/15
 * Time: 11:49 AM
 */

class Income_Statement_Row {

    public $tanker_id;
    public $tanker_number;
    public $total_income;
    public $secondary_type;
    public $other_expense;
    public $shortage_expense;
    public $profit;

    public function __construct()
    {
        $this->total_income = 0;
    }
    public function total_expense()
    {
        return round($this->other_expense + $this->shortage_expense, 3);
    }

    public function profit_loss()
    {
        return round($this->total_income - $this->total_expense(), 3);
    }
} 