<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/7/15
 * Time: 6:42 AM
 */

class Sale_Invoice_Entry {

    public $id;
    public $product;
    public $quantity;
    public $salePricePerItem;
    public $invoice;
    public function __construct(&$whole_obj)
    {
        $this->invoice = $whole_obj;
    }

    public function total_cost()
    {
        return round($this->quantity * $this->salePricePerItem, 3);
    }
} 