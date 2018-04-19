<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/7/15
 * Time: 6:42 AM
 */

class Purchase_Invoice {

    public $id;
    public $supplier;
    public $date;
    public $extra_info;
    public $entries;
    public $paid;

    public function __construct()
    {
        $this->entries = array();
    }

    public function extra_info_simplified()
    {
        $extra_info = $this->extra_info;
        if(strlen($extra_info) > 50)
        {
            $extra_info = substr($extra_info, 0,50);
            $extra_info.="...";
        }
        return $extra_info;
    }

    public function grand_total_purchase_price()
    {
        $grand_total = 0;
        foreach($this->entries as $entry)
        {
            $grand_total += round($entry->costPerItem * $entry->quantity, 3);
        }
        return $grand_total;
    }

    public function remaining()
    {
        return $this->grand_total_purchase_price() - $this->paid;
    }

} 