<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Trip_Details {

    public $contractorName;
    public $customerName;
    public $companyName;
    public $contractorId;
    public $companyId;
    public $customerId;
    public $route;
    public $route_id;
    public $filling_date;
    public $entry_date;
    public $email_date;
    public $receiving_date;
    public $stn_receiving_date;
    public $decanding_date;
    public $invoice_date;
    public $total_freight;
    public $freight_unit;
    public $company_commission_1;
    public $company_commission_2;
    public $company_commission_3;
    public $contractor_commission;
    public $contractor_commission_1;
    public $contractor_commission_2;
    public $driver_id_1;
    public $driver_name_1;
    public $driver_id_2;
    public $driver_name_2;
    public $driver_id_3;
    public $driver_name_3;
    public $customer_freight;
    public $final_quantity;
    public $trip_id;
    public $invoice_number;
    public $tanker_number;
    public $tanker_id;
    public $start_meter_reading;
    public $end_meter_reading;
    public $type;
    public $complete;

    //this array will contain trip_related_details objects
    public $trip_related_details;

    public $ci;

    function Trip_Details($trips){
        $this->ci =& get_instance();

        //setting default values
        $this->trip_related_details = array();
        $this->total_freight = 0;

        $this->complete = true;

        $this->set_data($trips);
    }

    private function  set_data($trip){

        $this->trip_id = $trip->trip_id;

    //query starts..........*****************************************************************************
        //select starts...
        $this->ci->db->select('trips.id as trip_id,
         trips.customer_id, customers.name as customerName,
          trips.contractor_id, carriage_contractors.name as contractorName,
           trips.company_id, companies.name as companyName, trips.tanker_id,
           trips.type as trip_type,
            tankers.truck_number as tanker_number, trips.contractor_commission,
            trips.contractor_commission_1, trips.contractor_commission_2,
             trips.company_commission_1, trips.company_commission_2,
             trips.start_meter, trips.end_meter,
              trips.driver_id_1, drivers_1.name as driver_1_name, trips.driver_id_2,
               drivers_2.name as driver_2_name, trips.driver_id_3,
                drivers_3.name as driver_3_name, trips.filling_date, trips.decanding_date,
                 trips.email_date, trips.stn_receiving_date, trips.receiving_date,
                  trips.invoice_date, trips.invoice_number, trips.entryDate,
                   trips_details.id as trips_details_id, trips_details.product_quantity,
                    trips_details.qty_at_destination, trips_details.qty_after_decanding,
                     trips_details.price_unit, freight_unit, trips_details.stn_number,
                      trips_details.source, trips_details.destination, trips_details.product,
                      source_cities.cityName as sourceCityName, trips_details.source as sourceCityId,
                      destination_cities.cityName as destinationCityName, trips_details.destination as destinationCityId,
                      products.productName,trips_details.product as productId, trips_details.company_freight_unit,

                      ');   //select ends...

        $this->ci->db->from('trips');
        $this->ci->db->distinct('trips_details.id');
        //join starts..
        $this->ci->db->join('trips_details', 'trips_details.trip_id = trips.id');
        $this->ci->db->join('customers', 'customers.id = trips.customer_id');
        $this->ci->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id');
        $this->ci->db->join('companies', 'companies.id = trips.company_id');
        $this->ci->db->join('tankers', 'tankers.id = trips.tanker_id');
        $this->ci->db->join('drivers as drivers_1', 'drivers_1.id = trips.driver_id_1');
        $this->ci->db->join('drivers as drivers_2', 'drivers_2.id = trips.driver_id_2');
        $this->ci->db->join('drivers as drivers_3', 'drivers_3.id = trips.driver_id_3');
        $this->ci->db->join('cities as source_cities', 'source_cities.id = trips_details.source');
        $this->ci->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination');
        $this->ci->db->join('products', 'products.id = trips_details.product');
        //join ends..

        //where starts...
        $this->ci->db->where('trips.id',$trip->trip_id);
        //where ends...
        $trip_data = $this->ci->db->get()->result();
    //query ends...****************************************************************************************


        include_once(APPPATH."models/helperClasses/Trip_Related_Data.php");

        foreach($trip_data as $trip_d)
        {

            $trip_related_detail = new Trip_Related_Data();

            $trip_related_detail->trip_id = $trip_d->trip_id;
            $trip_related_detail->trip_details_id = $trip_d->trips_details_id;
            $trip_related_detail->sourceCity = $trip_d->sourceCityName ;
            $trip_related_detail->sourceCityId = $trip_d->sourceCityId ;
            $trip_related_detail->destinationCity = $trip_d->destinationCityName ;
            $trip_related_detail->destinationCityId = $trip_d->destinationCityId ;
            $trip_related_detail->product = $trip_d->productName ;
            $trip_related_detail->productId = $trip_d->productId ;
            $trip_related_detail->product_quantity = $trip_d->product_quantity ;
            $trip_related_detail->qty_at_destination = $trip_d->qty_at_destination ;
            $trip_related_detail->qty_after_decanding = $trip_d->qty_after_decanding ;
            $trip_related_detail->price_unit = $trip_d->price_unit ;
            $trip_related_detail->freight_unit = $trip_d->freight_unit ;
            $trip_related_detail->company_freight_unit = $trip_d->company_freight_unit ;
            $trip_related_detail->stn_number = $trip_d->stn_number ;

            //check weather the trip is complete or not
            if($trip_d->stn_number == ''){ $this->complete = false; }
            ////////////////////////////////////////////////////////////////

            //insert trip_related_detail int trip_related_details array
            array_unshift($this->trip_related_details, $trip_related_detail);

            //calculating total_freight
            $this->total_freight += ($trip_d->product_quantity * $trip_d->freight_unit);
        }

        $this->num_trip_details = sizeof($trip_data);

        //setting tip_unique_data (this class variables)
        $this->type = $trip_data[0]->trip_type;
        $this->customerId = $trip_data[0]->customer_id;
        $this->customerName = $trip_data[0]->customerName;

        $this->contractorId = $trip_data[0]->contractor_id ;
        $this->contractorName = $trip_data[0]->contractorName ;

        $this->companyId = $trip_data[0]->company_id ;
        $this->companyName = $trip_data[0]->companyName ;

        $this->tanker_id = $trip_data[0]->tanker_id ;
        $this->tanker_number = $trip_data[0]->tanker_number ;

        $this->entry_date = $trip_data[0]->entryDate ;
        $this->filling_date = $trip_data[0]->filling_date ;
        $this->receiving_date = $trip_data[0]->receiving_date ;
        $this->decanding_date = $trip_data[0]->decanding_date ;
        $this->stn_receiving_date = $trip_data[0]->stn_receiving_date ;
        $this->email_date = $trip_data[0]->email_date ;
        $this->invoice_date = $trip_data[0]->invoice_date ;

        $this->contractor_commission = $trip_data[0]->contractor_commission ;
        $this->contractor_commission_2 = $trip_data[0]->contractor_commission_2 ;
        $this->company_commission_1 = $trip_data[0]->company_commission_1 ;
        $this->company_commission_2 = $trip_data[0]->company_commission_2 ;

        $this->customer_freight = 100 - $this->contractor_commission;

        $this->driver_id_1 = $trip_data[0]->driver_id_1 ;
        $this->driver_name_1 = $trip_data[0]->driver_2_name ;

        $this->driver_id_2 = $trip_data[0]->driver_id_2 ;
        $this->driver_name_2 = $trip_data[0]->driver_2_name ;

        $this->driver_id_3 = $trip_data[0]->driver_id_3 ;
        $this->driver_name_3 = $trip_data[0]->driver_3_name ;

        $this->invoice_number = $trip_data[0]->invoice_number;

        $this->start_meter_reading = $trip_data[0]->start_meter;
        $this->start_meter_reading = $trip_data[0]->end_meter;

    }

}
