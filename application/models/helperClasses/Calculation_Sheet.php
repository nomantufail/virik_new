<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/21/15
 * Time: 2:20 AM
 */

class Calculation_Sheet {

    public $trip_detail_id;
    public $trip_id;
    public $source;
    public $destination;
    public $productName;
    public $invoice_date;
    public $invoice_number;
    public $tanker_number;
    public $contractor;
    public $dis_qty;
    public $ending_quantity;
    public $shortage_quantity;
    public $customer_freight_unit;
    public $company_freight_unit;
    public $freight_on_shortage_quantity;
    public $freight_amount;
    public $shortage_rate;
    public $shortage_amount;
    public $payable_before_tax;
    public $tax;
    public $tax_amount;
    public $net_payable;
    public $raw_shortage_details;

    public function __construct($obj){
        $this->set_data($obj);
    }

    public function set_data($obj)
    {
        $this->trip_detail_id = $obj->detail_id;
        $this->source = $obj->source_city_name;
        $this->destination = $obj->destination_city_name;
        $this->productName = $obj->productName;
        $this->invoice_date = $obj->invoice_date;
        $this->invoice_number = $obj->invoice_number;
        $this->tanker_number = $obj->tanker_number;
        $this->contractor = $obj->contractor_name;
        $this->dis_qty = $obj->dis_qty;

        $shortage_details = $this->shortage_details($obj->shortage_quantity, $obj->shortage_rate);
        $this->raw_shortage_details = $shortage_details;
        $this->shortage_quantity = round(doubleval($shortage_details['qty']), 3);
        $this->shortage_rate = round(doubleval($shortage_details['price_unit']), 3);
        $this->ending_quantity = $this->dis_qty - $this->shortage_quantity;

        $this->customer_freight_unit = $obj->customer_freight_unit;
        $this->company_freight_unit = $obj->company_freight_unit;

        $this->freight_on_shortage_quantity = $this->customer_freight_unit * $this->shortage_quantity;

        $this->freight_amount = $this->ending_quantity * $this->customer_freight_unit;

        $this->shortage_amount = $this->shortage_quantity * $this->shortage_rate;

        $this->payable_before_tax = $this->freight_amount - $this->shortage_amount;
        $this->tax = $obj->tax;
        $this->tax_amount = round(($this->tax / 100) * $this->ending_quantity * $this->customer_freight_unit , 3);
        $this->net_payable = $this->payable_before_tax - $this->tax_amount;
    }

    public function shortage_details($qty , $rate)
    {
        $shortage_details = array(
            'qty' => $qty,
            'price_unit' => $rate,
        );
        return $shortage_details;
    }

} 