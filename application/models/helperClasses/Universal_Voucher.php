<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Universal_Voucher {

    public $trip_id;
    public $trip_detail_id;
    public $person_id;
    public $person_name;    //optional
    public $person;
    public $tanker_id;
    public $price_unit;
    public $shortage_quantity;
    public $shortage_rate;
    public $tanker_number;  //optional
    public $voucher_date;
    public $voucher_details;
    public $voucher_id;     //optional
    public $ignore;
    public $transaction_column;
    public $auto_generated;
    public $voucher_type;

    public $entries;

    function __construct(){
        $this->entries = array();
        $this->price_unit = 0;
        $this->shortage_quantity = 0;
        $this->shortage_rate = 0;
    }

    public function total_debit()
    {
        $total_debit = 0;
        foreach($this->entries as $entry)
        {
            $total_debit += $entry->debit;
        }
        return round($total_debit, 3);
    }
    public function total_credit()
    {
        $total_credit = 0;
        foreach($this->entries as $entry)
        {
            $total_credit += $entry->credit;
        }
        return round($total_credit, 3);
    }

    public function balance()
    {
        return round($this->total_debit() - $this->total_credit());
    }

}
