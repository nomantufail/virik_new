<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/7/15
 * Time: 6:42 AM
 */

class Sale_Invoice {

    public $id;
    public $customer;
    public $date;
    public $extra_info;
    public $entries;
    public $received;

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

    public function grand_total_sale_price()
    {
        $grand_total = 0;
        foreach($this->entries as $entry)
        {
            $grand_total += round($entry->salePricePerItem * $entry->quantity, 3);
        }
        return $grand_total;
    }
    public function remaining()
    {
        return $this->grand_total_sale_price() - $this->received;
    }
} 