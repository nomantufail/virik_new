<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/9/2015
 * Time: 3:42 AM
 */

class TripWithNoShortageGiven {
    public $trip_id;
    public $detail_id;
    public $trip_entry_date;
    public $dest_shrt_id;
    public $decnd_shrt_id;
    public $source;
    public $destination;
    public $product_name;
    public $truck_number;
    public $customer;
    public $company;
    public $contractor;

    public function __construct($trip)
    {
        $this->trip_id = $trip->trip_id;
        $this->detail_id = $trip->detail_id;
        $this->trip_entry_date = $trip->trip_entry_date;
        $this->dest_shrt_id = $trip->dest_shrt_id;
        $this->decnd_shrt_id = $trip->decnd_shrt_id;
        $this->source = $trip->source;
        $this->destination = $trip->destination;
        $this->product_name = $trip->product_name;
        $this->truck_number = $trip->truck_number;
        $this->customer = $trip->customer;
        $this->company = $trip->company;
        $this->contractor = $trip->contractor;
    }

    public function pending_destination()
    {
        return ($this->dest_shrt_id == null)?true:false;
    }

    public function pending_decanding()
    {
        return ($this->decnd_shrt_id == null)?true:false;
    }

} 