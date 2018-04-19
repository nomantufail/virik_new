<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Related_Data {

    public $trip_id ;
    public $trip_details_id;
    public $sourceCity ;
    public $sourceCityId ;
    public $destinationCity ;
    public $destinationCityId;
    public $product ;
    public $productId;
    public $product_quantity;
    public $qty_at_destination;
    public $qty_after_decanding;
    public $price_unit;
    public $freight_unit;
    public $company_freight_unit;
    public $stn_number;

    //test arae
    public $customer_payments;
    ////////////////////////////////////////////////

    public $ci;

    function Trip_Related_Data(){
        $this->ci =& get_instance();
        $this->customer_payments = array();

    }
    
    function setData($r)
    {
        $this->trip_id = $r->trip_id;
        $this->trip_details_id = $r->trips_details_id;
        $this->sourceCity = $r->sourceCityName ;
        $this->sourceCityId = $r->sourceCityId ;
        $this->destinationCity = $r->destinationCityName ;
        $this->destinationCityId = $r->destinationCityId ;
        $this->product = $r->productName ;
        $this->productId = $r->productId ;
        $this->product_quantity = $r->product_quantity ;
        $this->qty_at_destination = $r->qty_at_destination ;
        $this->qty_after_decanding = $r->qty_after_decanding ;
        $this->price_unit = $r->price_unit ;
        $this->freight_unit = $r->freight_unit ;
        $this->company_freight_unit = $r->company_freight_unit ;
        $this->stn_number = $r->stn_number ;
    }

}
