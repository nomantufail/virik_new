<?php
class Parent_Model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function join_vouchers()
    {
        $this->db->join('voucher_entry', 'voucher_entry.journal_voucher_id = voucher_journal.id','left');
    }
    public function join_trips_and_details()
    {
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
    }
    public function join_trips_details_and_products()
    {
        $this->db->join('products', 'products.id = trips_details.product','left');
    }
    public function join_trips_and_customers()
    {
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
    }

    public function join_trip_details_and_cities()
    {
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
    }

    public function join_trips_and_contractors()
    {
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');
    }

    public function join_trips_and_companies()
    {
        $this->db->join('companies', 'companies.id = trips.company_id','left');
    }

    public function join_trips_and_tankers()
    {
        $this->db->join('tankers', 'tankers.id = trips.tanker_id','left');
    }

    public function join_trips_details_and_destination_shortage()
    {
        $this->db->join('voucher_journal', 'voucher_journal.id = trips_details.shortage_voucher_dest','left');
    }
    public function join_trips_details_and_decanding_shortage()
    {
        $this->db->join('voucher_journal', 'voucher_journal.id = trips_details.shortage_voucher_decnd','left');
    }

    public function join_trips_with_every_thing()
    {
        $this->join_trips_and_details();
        $this->join_trips_details_and_products();
        $this->join_trips_and_customers();
        $this->join_trips_and_contractors();
        $this->join_trips_and_companies();
        $this->join_trips_and_tankers();
        $this->join_trip_details_and_cities();
    }

    public function select_calculation_sheet_stuff()
    {
        $this->db->select("
        source_cities.cityName as source_city_name, destination_cities.cityName as destination_city_name,
        trips.invoice_date, invoice_number, tankers.truck_number as tanker_number, carriage_contractors.name as contractor_name,
        companies.name as company_name, trips_details.product_quantity as dis_qty, trips_details.freight_unit as customer_freight_unit,
        trips_details.company_freight_unit, trips_details.id as detail_id,
        trips_details.shortage_voucher_dest, trips_details.shortage_voucher_decnd,
        trips.company_commission_2 as tax, products.type, products.productName,
        voucher_journal.shortage_quantity, voucher_journal.shortage_rate,
        voucher_journal.price_unit, voucher_journal.id as journal_voucher_id,
        ");
    }

    public function black_oil()
    {
        $this->db->where('products.type','black oil');
    }
    public function white_oil()
    {
        $this->db->where('products.type','white oil');
    }
}

?>