<?php

class Trips_model extends CI_Model {

    public function __construct(){
        parent::__construct();

        ini_set('memory_limit', '-1');

    }

    public function trips($where = ''){
        $this->db->order_by("entryDate", "desc");
        if($where == ''){
            $this->db->select("*");
            $this->db->from('trips');
            $this->db->where('active',1);
            $this->db->join('trips_details', 'trips_details.trip_id = trips.id');
            $query = $this->db->get();
        }else{
            $this->db->select("*");
            $this->db->from('trips');
            $this->db->join('trips_details', 'trips_details.trip_id = trips.id');
            $this->db->where($where);
            $this->db->where('active',1);
            $query = $this->db->get();
        }
        $result = $query->result();
        return $result;
    }

    public function trips_for_tankers_on_move($where = ''){
        $this->db->order_by("entryDate", "desc");
        if($where == ''){
            $this->db->select("*");
            $this->db->from('trips');
            $this->db->where('active',1);
            $query = $this->db->get();
        }else{
            $this->db->select("trips.id, trips.entryDate");
            $this->db->from('trips');
            $this->db->join('trips_details', 'trips_details.trip_id = trips.id');
            $this->db->where($where);
            $this->db->where('active',1);
            $query = $this->db->get();
        }
        $result = $query->result();
        return $result;
    }

    public function test_trips_details($trips_ids){
        include_once(APPPATH."models/helperClasses/Trip.php");
        include_once(APPPATH."models/helperClasses/Trip_Product_Detail.php");
        include_once(APPPATH."models/helperClasses/Customer_Account.php");
        include_once(APPPATH."models/helperClasses/Contractor_Account.php");
        include_once(APPPATH."models/helperClasses/Company_Account.php");
        include_once(APPPATH."models/helperClasses/Customer.php");
        include_once(APPPATH."models/helperClasses/Contractor.php");
        include_once(APPPATH."models/helperClasses/Company.php");
        include_once(APPPATH."models/helperClasses/Driver.php");
        include_once(APPPATH."models/helperClasses/Tanker.php");
        include_once(APPPATH."models/helperClasses/City.php");
        include_once(APPPATH."models/helperClasses/Product.php");

        ////**********************selecte statement starts*********************/////
        $this->db->select('trips.id as trip_id,
         trips.customer_id, customers.name as customerName,
          trips.contractor_id, carriage_contractors.name as contractorName,
           trips.company_id, companies.name as companyName, trips.tanker_id,
            tankers.truck_number as tanker_number, trips.contractor_commission,
            trips.contractor_commission_1, trips.contractor_commission_2,
             trips.company_commission_1, trips.company_commission_2 as wht, trips.company_commission_3,
             trips.type as trip_type,
              trips.driver_id_1, drivers_1.name as driver_1_name, trips.driver_id_2,
               drivers_2.name as driver_2_name, trips.driver_id_3,
                drivers_3.name as driver_3_name, trips.filling_date, trips.decanding_date,
                 trips.email_date, trips.stn_receiving_date, trips.receiving_date,
                  trips.invoice_date, trips.invoice_number, trips.entryDate,
                   trips_details.id as trips_details_id, trips_details.product_quantity,
                    trips_details.qty_at_destination, trips_details.qty_after_decanding,
                     trips_details.price_unit, freight_unit, trips_details.stn_number, trips_details.shortage_voucher_dest,
                     trips_details.shortage_voucher_decnd, trips_details.source as source_id,
                      trips_details.destination as destination_id, trips_details.product,
                      source_cities.cityName as sourceCityName, trips_details.source as sourceCityId,
                      destination_cities.cityName as destinationCityName, trips_details.destination as destinationCityId,
                      products.productName,trips_details.product as productId, trips_details.company_freight_unit,
                      customer_accounts.id as customer_account_id,
                      customer_accounts.amount as paid_to_customer, customer_accounts.payment_date as payment_to_customer_date,
                      customer_accounts.id as customer_accounts_id,

                      contractor_accounts.id as contractor_account_id,
                      contractor_accounts.amount as paid_to_contractor, contractor_accounts.payment_date as payment_to_contractor_date,
                      contractor_accounts.id as contractor_accounts_id,

                      company_accounts.id as company_account_id,
                      company_accounts.amount as paid_to_company, company_accounts.payment_date as payment_to_company_date,
                      company_accounts.id as company_accounts_id,
                    ');   //select ends...

        //applying the where condition...
        if(sizeof($trips_ids) >= 1){
            $this->db->where_in('trips.id',$trips_ids);
        }else{
            $this->db->where('trips.id',0);
        }
        ////////////////////////////////////

        $this->db->from('trips');
        $this->db->where('active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies', 'companies.id = trips.company_id','left');

        //joining tankers
        $this->db->join('tankers', 'tankers.id = trips.tanker_id','left');

        //joining drivers
        $this->db->join('drivers as drivers_1', 'drivers_1.id = trips.driver_id_1','left');
        $this->db->join('drivers as drivers_2', 'drivers_2.id = trips.driver_id_2','left');
        $this->db->join('drivers as drivers_3', 'drivers_3.id = trips.driver_id_3','left');

        //joining accounts
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $this->db->order_by('trips.id, trips_details.id, customer_accounts.id, company_accounts.id, contractor_accounts.id');
        $rawTrips = $this->db->get()->result();

        $final_trips_array = array();

        $contractor_account_ids = array();
        $company_account_ids = array();
        $customer_account_ids = array();

        $previous_trip_id = -1;
        $previous_trip_product_detail_id = -1;
        $previous_customer_account_id = -1;
        $previous_contractor_account_id = -1;
        $previous_company_account_id = -1;

        $temp_trip = new Trip();
        $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);
        $temp_customer_account = new Customer_Account();
        $temp_company_account = new Company_Account();
        $temp_contractor_account = new Contractor_Account();

            $count = 0;
            foreach($rawTrips as $record){
                $count++;

                //setting the parent details
                if($record->trip_id != $previous_trip_id)
                {
                    $previous_trip_id = $record->trip_id;

                    //$previous_trip_obj = $temp_trip;
                    $temp_trip = new trip();

                    //setting data in the parent object
                    $temp_trip->trip_id = $record->trip_id;
                    $temp_trip->type = $record->trip_type;
                    $temp_trip->customer = new Customer($record->customer_id, $record->customerName, (100 - $record->contractor_commission) );
                    $temp_trip->contractor = new Contractor($record->contractor_id, $record->contractorName, $record->contractor_commission);
                    $temp_trip->company = new Company($record->company_id, $record->companyName, $record->company_commission_1, $record->wht);
                    $temp_trip->driver_1 = new Driver($record->driver_id_1, $record->driver_1_name);
                    $temp_trip->driver_2 = new Driver($record->driver_id_2, $record->driver_2_name);
                    $temp_trip->driver_3 = new Driver($record->driver_id_3, $record->driver_3_name);

                    $temp_trip->tanker = new Tanker($record->tanker_id, $record->tanker_number);

                    //setting trip dates
                    $temp_trip->dates = new TripDates(
                        $record->email_date,
                        $record->filling_date,
                        $record->receiving_date,
                        $record->stn_receiving_date,
                        $record->decanding_date,
                        $record->invoice_date,
                        $record->entryDate
                    );

                    $temp_trip->invoice_number = $record->invoice_number;

                }/////////////////////////////////////////////////

                /////////////////////////////////////////////////
                if($record->trips_details_id != $previous_trip_product_detail_id)
                {
                    $previous_trip_product_detail_id = $record->trips_details_id;

                    $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);

                    //setting data in the Trip_Product_Data object
                    $temp_trip_product_detail->product_detail_id = $record->trips_details_id;
                    $temp_trip_product_detail->product = new Product($record->productId, $record->productName);
                    $temp_trip_product_detail->source = new City($record->source_id, $record->sourceCityName);
                    $temp_trip_product_detail->destination = new City($record->destination_id, $record->destinationCityName);

                    $temp_trip_product_detail->product_quantity = $record->product_quantity;
                    $temp_trip_product_detail->quantity_at_destination = $record->qty_at_destination;
                    $temp_trip_product_detail->quantity_after_decanding = $record->qty_after_decanding;
                    $temp_trip_product_detail->price_unit = $record->price_unit;
                    $temp_trip_product_detail->customer_freight_unit = $record->freight_unit;
                    $temp_trip_product_detail->company_freight_unit = $record->company_freight_unit;
                    $temp_trip_product_detail->stn_number = $record->stn_number;
                    $temp_trip_product_detail->shortage_voucher_dest = $record->shortage_voucher_dest;
                    $temp_trip_product_detail->shortage_voucher_decnd = $record->shortage_voucher_decnd;
                }/////////////////////////////////////////////////

                ////////************Setting Customer Accounts**********////////////
                if(!in_array($record->customer_account_id, $customer_account_ids))
                {
                    $previous_customer_account_id = $record->customer_account_id;
                    $temp_customer_account = new Customer_Account();

                    //setting data in the parent object
                    $temp_customer_account->account_id = $record->customer_account_id;
                    $temp_customer_account->amount_paid = $record->paid_to_customer;
                    $temp_customer_account->payment_date = $record->payment_to_customer_date;
                }/////////////////////////////////////////////////

                ////////************Setting Contractor Accounts**********////////////
                if(!in_array($record->contractor_account_id, $contractor_account_ids))
                {
                    $previous_contractor_account_id = $record->contractor_account_id;
                    $temp_contractor_account = new Contractor_Account();

                    //setting data in the parent object
                    $temp_contractor_account->account_id = $record->contractor_account_id;
                    $temp_contractor_account->amount_paid = $record->paid_to_contractor;
                    $temp_contractor_account->payment_date = $record->payment_to_contractor_date;
                }/////////////////////////////////////////////////

                ////////************Setting Company Accounts**********////////////
                if(!in_array($record->company_account_id, $company_account_ids))
                {
                    $previous_company_account_id = $record->company_account_id;
                    $temp_company_account = new Company_Account();

                    //setting data in the parent object
                    $temp_company_account->account_id = $record->company_account_id;
                    $temp_company_account->amount_paid = $record->paid_to_company;
                    $temp_company_account->payment_date = $record->payment_to_company_date;
                }/////////////////////////////////////////////////


                if($count != sizeof($rawTrips)){
                    if(!in_array($record->company_account_id, $company_account_ids)){
                        array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                        //pushing the object id
                        array_push($company_account_ids, $record->company_account_id);
                    }
                    if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                        //pushing the object
                        array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                        //pushing the object id
                        array_push($contractor_account_ids, $record->contractor_account_id);
                    }
                    if(!in_array($record->customer_account_id, $customer_account_ids)){
                        //pushing the object
                        array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                        //pushing the object id
                        array_push($customer_account_ids, $record->customer_account_id);
                    }
                    if($rawTrips[$count]->trips_details_id != $record->trips_details_id){
                        array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                    }
                    if($rawTrips[$count]->trip_id != $record->trip_id){
                        array_push($final_trips_array, $temp_trip);
                    }
                }else{
                    if(!in_array($record->company_account_id, $company_account_ids)){
                        array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                    }
                    if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                        array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                    }
                    if(!in_array($record->customer_account_id, $customer_account_ids)){
                        array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                    }

                    array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                    array_push($final_trips_array, $temp_trip);
                }
            }

        return $final_trips_array;


    }

    public function parametrized_trips_engine_by_detail_ids($trips_details_ids, $aimed_for){
        include_once(APPPATH."models/helperClasses/Trip.php");
        include_once(APPPATH."models/helperClasses/Trip_Product_Detail.php");
        include_once(APPPATH."models/helperClasses/Customer_Account.php");
        include_once(APPPATH."models/helperClasses/Contractor_Account.php");
        include_once(APPPATH."models/helperClasses/Company_Account.php");
        include_once(APPPATH."models/helperClasses/Customer.php");
        include_once(APPPATH."models/helperClasses/Contractor.php");
        include_once(APPPATH."models/helperClasses/Company.php");
        include_once(APPPATH."models/helperClasses/Driver.php");
        include_once(APPPATH."models/helperClasses/Tanker.php");
        include_once(APPPATH."models/helperClasses/City.php");
        include_once(APPPATH."models/helperClasses/Product.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");

        ////**********************selecte statement starts*********************/////
        $select = "trips.id as trip_id,
         trips.customer_id, customers.name as customerName,
          trips.contractor_id, carriage_contractors.name as contractorName,
           trips.company_id, companies.name as companyName, trips.tanker_id,
            tankers.truck_number as tanker_number, tankers.capacity, trips.contractor_commission,
            trips.contractor_commission_1, trips.contractor_commission_2,
             trips.company_commission_1, trips.company_commission_2 as wht, trips.company_commission_3,
             trips.type as trip_type,
              trips.driver_id_1, drivers_1.name as driver_1_name, trips.driver_id_2,
               drivers_2.name as driver_2_name, trips.driver_id_3,
                drivers_3.name as driver_3_name, trips.filling_date, trips.decanding_date,
                 trips.email_date, trips.stn_receiving_date, trips.receiving_date,
                  trips.invoice_date, trips.invoice_number, trips.entryDate,
                   trips_details.id as trips_details_id, trips_details.product_quantity,
                    trips_details.qty_at_destination, trips_details.qty_after_decanding,
                     trips_details.price_unit, freight_unit, trips_details.stn_number, trips_details.shortage_voucher_dest,
                     trips_details.shortage_voucher_decnd, trips_details.source as source_id,
                      trips_details.destination as destination_id, trips_details.product,
                      products.type as product_type,
                      source_cities.cityName as sourceCityName, trips_details.source as sourceCityId,
                      destination_cities.cityName as destinationCityName, trips_details.destination as destinationCityId,
                      products.productName,trips_details.product as productId, trips_details.company_freight_unit,
                      customer_accounts.id as customer_account_id,
                      customer_accounts.amount as paid_to_customer, customer_accounts.payment_date as payment_to_customer_date,
                      customer_accounts.id as customer_accounts_id,

                      contractor_accounts.id as contractor_account_id,
                      contractor_accounts.amount as paid_to_contractor, contractor_accounts.payment_date as payment_to_contractor_date,
                      contractor_accounts.id as contractor_accounts_id,

                      company_accounts.id as company_account_id,
                      company_accounts.amount as paid_to_company, company_accounts.payment_date as payment_to_company_date,
                      company_accounts.id as company_accounts_id,

                      trips.start_meter, trips.end_meter, trips.fuel_consumed,
                      ";



        //applying the where condition...
        if(sizeof($trips_details_ids) >= 1){
            $this->db->where_in('trips_details.id',$trips_details_ids);
        }else{
            $this->db->where('trips_details.id',0);
        }
        ////////////////////////////////////

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies', 'companies.id = trips.company_id','left');

        //joining tankers
        $this->db->join('tankers', 'tankers.id = trips.tanker_id','left');

        //joining drivers
        $this->db->join('drivers as drivers_1', 'drivers_1.id = trips.driver_id_1','left');
        $this->db->join('drivers as drivers_2', 'drivers_2.id = trips.driver_id_2','left');
        $this->db->join('drivers as drivers_3', 'drivers_3.id = trips.driver_id_3','left');

        //joining accounts
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        //computing the required query aimed for
        switch($aimed_for)
        {
            case "contractor_accounts":
                //select statement
                $select.="
                    voucher_journal_for_contractor_accounts.id as contractor_accounts_voucher_id,
                    voucher_journal_for_contractor_accounts_entry.credit_amount as contractor_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_contractor_accounts_entry.related_other_agent as contractor_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_contractor_accounts_entry.related_customer as contractor_accounts_voucher_entry_related_customer,
                    voucher_journal_for_contractor_accounts_entry.related_contractor as contractor_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_contractor_accounts_entry.related_company as contractor_accounts_voucher_entry_related_company,
                    voucher_journal_for_contractor_accounts.active as contractor_accounts_voucher_active,
                    voucher_journal_for_contractor_accounts_entry.ac_type as contractor_accounts_voucher_entry_ac_type,
                    voucher_journal_for_contractor_accounts_entry.id as contractor_accounts_voucher_entry_id,
                    account_titles_for_contractor_accounts.title as contractor_accounts_title,
                    voucher_journal_for_contractor_accounts_entry.account_title_id as contractor_accounts_title_id,
                    voucher_journal_for_contractor_accounts.person_tid as contractor_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_contractor_accounts', 'voucher_journal_for_contractor_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_contractor_accounts_entry', 'voucher_journal_for_contractor_accounts_entry.journal_voucher_id = voucher_journal_for_contractor_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_contractor_accounts','account_titles_for_contractor_accounts.id = voucher_journal_for_contractor_accounts_entry.account_title_id','left');

                break;
            case "customer_accounts":
                //select statement
                $select.="
                    voucher_journal_for_customer_accounts.id as customer_accounts_voucher_id,
                    voucher_journal_for_customer_accounts_entry.credit_amount as customer_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_customer_accounts_entry.related_other_agent as customer_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_customer_accounts_entry.related_customer as customer_accounts_voucher_entry_related_customer,
                    voucher_journal_for_customer_accounts_entry.related_contractor as customer_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_customer_accounts_entry.related_company as customer_accounts_voucher_entry_related_company,
                    voucher_journal_for_customer_accounts.active as customer_accounts_voucher_active,
                    voucher_journal_for_customer_accounts_entry.ac_type as customer_accounts_voucher_entry_ac_type,
                    voucher_journal_for_customer_accounts_entry.id as customer_accounts_voucher_entry_id,
                    account_titles_for_customer_accounts.title as customer_accounts_title,
                    voucher_journal_for_customer_accounts_entry.account_title_id as customer_accounts_title_id,
                    voucher_journal_for_customer_accounts.person_tid as customer_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_customer_accounts', 'voucher_journal_for_customer_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_customer_accounts_entry', 'voucher_journal_for_customer_accounts_entry.journal_voucher_id = voucher_journal_for_customer_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_customer_accounts','account_titles_for_customer_accounts.id = voucher_journal_for_customer_accounts_entry.account_title_id','left');

                break;
            case "company_accounts":
                //select statement
                $select.="
                    voucher_journal_for_company_accounts.id as company_accounts_voucher_id,
                    voucher_journal_for_company_accounts_entry.credit_amount as company_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_company_accounts_entry.related_other_agent as company_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_company_accounts_entry.related_customer as company_accounts_voucher_entry_related_customer,
                    voucher_journal_for_company_accounts_entry.related_contractor as company_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_company_accounts_entry.related_company as company_accounts_voucher_entry_related_company,
                    voucher_journal_for_company_accounts.active as company_accounts_voucher_active,
                    voucher_journal_for_company_accounts_entry.ac_type as company_accounts_voucher_entry_ac_type,
                    voucher_journal_for_company_accounts_entry.id as company_accounts_voucher_entry_id,
                    account_titles_for_company_accounts.title as company_accounts_title,
                    voucher_journal_for_company_accounts_entry.account_title_id as company_accounts_title_id,
                    voucher_journal_for_company_accounts.person_tid as company_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_company_accounts', 'voucher_journal_for_company_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_company_accounts_entry', 'voucher_journal_for_company_accounts_entry.journal_voucher_id = voucher_journal_for_company_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_company_accounts','account_titles_for_company_accounts.id = voucher_journal_for_company_accounts_entry.account_title_id','left');

                break;
            default:
                break;
        }
        ///////////////////////////////

        $this->db->select($select);   //select ends...

        /*--**********************joining ends*********************--*/

        $this->db->order_by('trips.id, trips_details.id, customer_accounts.id, company_accounts.id, contractor_accounts.id');
        $rawTrips = $this->db->get()->result();

        $final_trips_array = array();

        //arrays which will hold ids for record settings
        $contractor_account_ids = array();
        $company_account_ids = array();
        $customer_account_ids = array();
        $contractor_accounts_voucher_entry_ids = array();
        $customer_accounts_voucher_entry_ids = array();
        $company_accounts_voucher_entry_ids = array();
        ////////////////////////////////////////////////////

        $previous_trip_id = -1;
        $previous_trip_product_detail_id = -1;
        $previous_customer_account_id = -1;
        $previous_contractor_account_id = -1;
        $previous_company_account_id = -1;

        $temp_trip = new Trip();
        $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);
        $temp_customer_account = new Customer_Account();
        $temp_company_account = new Company_Account();
        $temp_contractor_account = new Contractor_Account();
        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();
        $temp_customer_accounts_voucher_entry = new Voucher_Entry();
        $temp_company_accounts_voucher_entry = new Voucher_Entry();

        $count = 0;
        foreach($rawTrips as $record){
            $count++;


            if($record->trips_details_id != $previous_trip_product_detail_id)
            {
                /**
                 * making a trip object
                 **/
                $temp_trip = new trip();

                //setting data in the parent object
                $temp_trip->trip_id = $record->trip_id;
                $temp_trip->type = $record->trip_type;
                $temp_trip->customer = new Customer($record->customer_id, $record->customerName, (100 - $record->contractor_commission) );
                $temp_trip->contractor = new Contractor($record->contractor_id, $record->contractorName, $record->contractor_commission);
                $temp_trip->company = new Company($record->company_id, $record->companyName, $record->company_commission_1, $record->wht);
                $temp_trip->driver_1 = new Driver($record->driver_id_1, $record->driver_1_name);
                $temp_trip->driver_2 = new Driver($record->driver_id_2, $record->driver_2_name);
                $temp_trip->driver_3 = new Driver($record->driver_id_3, $record->driver_3_name);

                $temp_trip->start_meter = $record->start_meter;
                $temp_trip->end_meter = $record->end_meter;
                $temp_trip->fuel_consumed = $record->fuel_consumed;

                $temp_trip->tanker = new Tanker($record->tanker_id, $record->tanker_number, $record->capacity);

                //setting trip dates
                $temp_trip->dates = new TripDates(
                    $record->email_date,
                    $record->filling_date,
                    $record->receiving_date,
                    $record->stn_receiving_date,
                    $record->decanding_date,
                    $record->invoice_date,
                    $record->entryDate
                );

                $temp_trip->invoice_number = $record->invoice_number;
                /*------------------------------*/


                /**
                 * making a trip object
                 **/
                $previous_trip_product_detail_id = $record->trips_details_id;

                $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);

                //setting data in the Trip_Product_Data object
                $temp_trip_product_detail->product_detail_id = $record->trips_details_id;
                $temp_trip_product_detail->product = new Product($record->productId, $record->productName, $record->product_type);
                $temp_trip_product_detail->source = new City($record->source_id, $record->sourceCityName);
                $temp_trip_product_detail->destination = new City($record->destination_id, $record->destinationCityName);

                $temp_trip_product_detail->product_quantity = $record->product_quantity;
                $temp_trip_product_detail->quantity_at_destination = $record->qty_at_destination;
                $temp_trip_product_detail->quantity_after_decanding = $record->qty_after_decanding;
                $temp_trip_product_detail->price_unit = $record->price_unit;
                $temp_trip_product_detail->customer_freight_unit = $record->freight_unit;
                $temp_trip_product_detail->company_freight_unit = $record->company_freight_unit;
                $temp_trip_product_detail->stn_number = $record->stn_number;
                $temp_trip_product_detail->shortage_voucher_dest = $record->shortage_voucher_dest;
                $temp_trip_product_detail->shortage_voucher_decnd = $record->shortage_voucher_decnd;
            }/////////////////////////////////////////////////

            ////////************Setting Customer Accounts**********////////////
            if(!in_array($record->customer_account_id, $customer_account_ids))
            {
                $previous_customer_account_id = $record->customer_account_id;
                $temp_customer_account = new Customer_Account();

                //setting data in the parent object
                $temp_customer_account->account_id = $record->customer_account_id;
                $temp_customer_account->amount_paid = $record->paid_to_customer;
                $temp_customer_account->payment_date = $record->payment_to_customer_date;
            }/////////////////////////////////////////////////

            ////////************Setting Contractor Accounts**********////////////
            if(!in_array($record->contractor_account_id, $contractor_account_ids))
            {
                $previous_contractor_account_id = $record->contractor_account_id;
                $temp_contractor_account = new Contractor_Account();

                //setting data in the parent object
                $temp_contractor_account->account_id = $record->contractor_account_id;
                $temp_contractor_account->amount_paid = $record->paid_to_contractor;
                $temp_contractor_account->payment_date = $record->payment_to_contractor_date;
            }/////////////////////////////////////////////////

            ////////************Setting Company Accounts**********////////////
            if(!in_array($record->company_account_id, $company_account_ids))
            {
                $previous_company_account_id = $record->company_account_id;
                $temp_company_account = new Company_Account();

                //setting data in the parent object
                $temp_company_account->account_id = $record->company_account_id;
                $temp_company_account->amount_paid = $record->paid_to_company;
                $temp_company_account->payment_date = $record->payment_to_company_date;
            }/////////////////////////////////////////////////


            //gathring data according to aimed for
            switch($aimed_for)
            {
                case "contractor_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids))
                    {
                        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_contractor_accounts_voucher_entry->active = $record->contractor_accounts_voucher_active;
                        $temp_contractor_accounts_voucher_entry->setAc_type($record->contractor_accounts_voucher_entry_ac_type);
                        $temp_contractor_accounts_voucher_entry->setTitle($record->contractor_accounts_title);
                        $temp_contractor_accounts_voucher_entry->setAccount_title_id($record->contractor_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->contractor_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->contractor_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->contractor_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->contractor_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_contractor_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_contractor_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_contractor_accounts_voucher_entry->person_tid = $record->contractor_accounts_voucher_person_tid;
                        $temp_contractor_accounts_voucher_entry->setCredit($record->contractor_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;
                case "customer_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids))
                    {
                        $temp_customer_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_customer_accounts_voucher_entry->active = $record->customer_accounts_voucher_active;
                        $temp_customer_accounts_voucher_entry->setAc_type($record->customer_accounts_voucher_entry_ac_type);
                        $temp_customer_accounts_voucher_entry->setTitle($record->customer_accounts_title);
                        $temp_customer_accounts_voucher_entry->setAccount_title_id($record->customer_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->customer_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->customer_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->customer_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->customer_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_customer_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_customer_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_customer_accounts_voucher_entry->person_tid = $record->customer_accounts_voucher_person_tid;
                        $temp_customer_accounts_voucher_entry->setCredit($record->customer_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                case "company_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids))
                    {
                        $temp_company_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_company_accounts_voucher_entry->active = $record->company_accounts_voucher_active;
                        $temp_company_accounts_voucher_entry->setAc_type($record->company_accounts_voucher_entry_ac_type);
                        $temp_company_accounts_voucher_entry->setTitle($record->company_accounts_title);
                        $temp_company_accounts_voucher_entry->setAccount_title_id($record->company_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->company_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->company_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->company_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->company_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_company_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_company_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_company_accounts_voucher_entry->person_tid = $record->company_accounts_voucher_person_tid;
                        $temp_company_accounts_voucher_entry->setCredit($record->company_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                default:
                    break;
            }
            ///////////////////////////////////////////



            //pushing particals
            if($count != sizeof($rawTrips)){
                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                            //pushing the object id
                            array_push($contractor_accounts_voucher_entry_ids, $record->contractor_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////

                        break;

                    case "customer_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                            //pushing the object id
                            array_push($customer_accounts_voucher_entry_ids, $record->customer_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    case "company_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                            //pushing the object id
                            array_push($company_accounts_voucher_entry_ids, $record->company_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    default:
                        break;
                }
                ///////////////////////////////////////////

                if(!in_array($record->company_account_id, $company_account_ids)){
                    array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                    //pushing the object id
                    array_push($company_account_ids, $record->company_account_id);
                }
                if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                    //pushing the object
                    array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                    //pushing the object id
                    array_push($contractor_account_ids, $record->contractor_account_id);
                }
                if(!in_array($record->customer_account_id, $customer_account_ids)){
                    //pushing the object
                    array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                    //pushing the object id
                    array_push($customer_account_ids, $record->customer_account_id);
                }
                if($rawTrips[$count]->trips_details_id != $record->trips_details_id){
                    array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                    array_push($final_trips_array, $temp_trip);
                }
            }else{

                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "customer_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "company_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    default:
                        break;
                }
                ///////////////////////////////////////////

                if(!in_array($record->company_account_id, $company_account_ids)){
                    array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                }
                if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                    array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                }
                if(!in_array($record->customer_account_id, $customer_account_ids)){
                    array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                }

                array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                array_push($final_trips_array, $temp_trip);
            }
        }

        return $final_trips_array;
    }


    public function parametrized_trips_engine($trips_ids, $aimed_for){
        include_once(APPPATH."models/helperClasses/Trip.php");
        include_once(APPPATH."models/helperClasses/Trip_Product_Detail.php");
        include_once(APPPATH."models/helperClasses/Customer_Account.php");
        include_once(APPPATH."models/helperClasses/Contractor_Account.php");
        include_once(APPPATH."models/helperClasses/Company_Account.php");
        include_once(APPPATH."models/helperClasses/Customer.php");
        include_once(APPPATH."models/helperClasses/Contractor.php");
        include_once(APPPATH."models/helperClasses/Company.php");
        include_once(APPPATH."models/helperClasses/Driver.php");
        include_once(APPPATH."models/helperClasses/Tanker.php");
        include_once(APPPATH."models/helperClasses/City.php");
        include_once(APPPATH."models/helperClasses/Product.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");

        ////**********************selecte statement starts*********************/////
        $select = "trips.id as trip_id,
         trips.customer_id, customers.name as customerName,
          trips.contractor_id, carriage_contractors.name as contractorName,
           trips.company_id, companies.name as companyName, trips.tanker_id,
            tankers.truck_number as tanker_number, tankers.capacity, trips.contractor_commission,
            trips.contractor_commission_1, trips.contractor_commission_2,
             trips.company_commission_1, trips.company_commission_2 as wht, trips.company_commission_3,
             trips.type as trip_type,
              trips.driver_id_1, drivers_1.name as driver_1_name, trips.driver_id_2,
               drivers_2.name as driver_2_name, trips.driver_id_3,
                drivers_3.name as driver_3_name, trips.filling_date, trips.decanding_date,
                 trips.email_date, trips.stn_receiving_date, trips.receiving_date,
                  trips.invoice_date, trips.invoice_number, trips.entryDate,
                   trips_details.id as trips_details_id, trips_details.product_quantity,
                    trips_details.qty_at_destination, trips_details.qty_after_decanding,
                     trips_details.price_unit, freight_unit, trips_details.stn_number, trips_details.shortage_voucher_dest,
                     trips_details.shortage_voucher_decnd, trips_details.source as source_id,
                      trips_details.destination as destination_id, trips_details.product,
                      products.type as product_type,
                      source_cities.cityName as sourceCityName, trips_details.source as sourceCityId,
                      destination_cities.cityName as destinationCityName, trips_details.destination as destinationCityId,
                      products.productName,trips_details.product as productId, trips_details.company_freight_unit,
                      customer_accounts.id as customer_account_id,
                      customer_accounts.amount as paid_to_customer, customer_accounts.payment_date as payment_to_customer_date,
                      customer_accounts.id as customer_accounts_id,

                      contractor_accounts.id as contractor_account_id,
                      contractor_accounts.amount as paid_to_contractor, contractor_accounts.payment_date as payment_to_contractor_date,
                      contractor_accounts.id as contractor_accounts_id,

                      company_accounts.id as company_account_id,
                      company_accounts.amount as paid_to_company, company_accounts.payment_date as payment_to_company_date,
                      company_accounts.id as company_accounts_id,

                      trips.start_meter, trips.end_meter, trips.fuel_consumed,
                      ";



        //applying the where condition...
        if(sizeof($trips_ids) >= 1){
            $this->db->where_in('trips.id',$trips_ids);
        }else{
            $this->db->where('trips.id',0);
        }
        ////////////////////////////////////

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies', 'companies.id = trips.company_id','left');

        //joining tankers
        $this->db->join('tankers', 'tankers.id = trips.tanker_id','left');

        //joining drivers
        $this->db->join('drivers as drivers_1', 'drivers_1.id = trips.driver_id_1','left');
        $this->db->join('drivers as drivers_2', 'drivers_2.id = trips.driver_id_2','left');
        $this->db->join('drivers as drivers_3', 'drivers_3.id = trips.driver_id_3','left');

        //joining accounts
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        //computing the required query aimed for
        switch($aimed_for)
        {
            case "contractor_accounts":
                //select statement
                $select.="
                    voucher_journal_for_contractor_accounts.id as contractor_accounts_voucher_id,
                    voucher_journal_for_contractor_accounts_entry.credit_amount as contractor_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_contractor_accounts_entry.related_other_agent as contractor_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_contractor_accounts_entry.related_customer as contractor_accounts_voucher_entry_related_customer,
                    voucher_journal_for_contractor_accounts_entry.related_contractor as contractor_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_contractor_accounts_entry.related_company as contractor_accounts_voucher_entry_related_company,
                    voucher_journal_for_contractor_accounts.active as contractor_accounts_voucher_active,
                    voucher_journal_for_contractor_accounts_entry.ac_type as contractor_accounts_voucher_entry_ac_type,
                    voucher_journal_for_contractor_accounts_entry.id as contractor_accounts_voucher_entry_id,
                    account_titles_for_contractor_accounts.title as contractor_accounts_title,
                    voucher_journal_for_contractor_accounts_entry.account_title_id as contractor_accounts_title_id,
                    voucher_journal_for_contractor_accounts.person_tid as contractor_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_contractor_accounts', 'voucher_journal_for_contractor_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_contractor_accounts_entry', 'voucher_journal_for_contractor_accounts_entry.journal_voucher_id = voucher_journal_for_contractor_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_contractor_accounts','account_titles_for_contractor_accounts.id = voucher_journal_for_contractor_accounts_entry.account_title_id','left');

                break;
            case "customer_accounts":
                //select statement
                $select.="
                    voucher_journal_for_customer_accounts.id as customer_accounts_voucher_id,
                    voucher_journal_for_customer_accounts_entry.credit_amount as customer_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_customer_accounts_entry.related_other_agent as customer_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_customer_accounts_entry.related_customer as customer_accounts_voucher_entry_related_customer,
                    voucher_journal_for_customer_accounts_entry.related_contractor as customer_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_customer_accounts_entry.related_company as customer_accounts_voucher_entry_related_company,
                    voucher_journal_for_customer_accounts.active as customer_accounts_voucher_active,
                    voucher_journal_for_customer_accounts_entry.ac_type as customer_accounts_voucher_entry_ac_type,
                    voucher_journal_for_customer_accounts_entry.id as customer_accounts_voucher_entry_id,
                    account_titles_for_customer_accounts.title as customer_accounts_title,
                    voucher_journal_for_customer_accounts_entry.account_title_id as customer_accounts_title_id,
                    voucher_journal_for_customer_accounts.person_tid as customer_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_customer_accounts', 'voucher_journal_for_customer_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_customer_accounts_entry', 'voucher_journal_for_customer_accounts_entry.journal_voucher_id = voucher_journal_for_customer_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_customer_accounts','account_titles_for_customer_accounts.id = voucher_journal_for_customer_accounts_entry.account_title_id','left');

                break;
            case "company_accounts":
                //select statement
                $select.="
                    voucher_journal_for_company_accounts.id as company_accounts_voucher_id,
                    voucher_journal_for_company_accounts_entry.credit_amount as company_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_company_accounts_entry.related_other_agent as company_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_company_accounts_entry.related_customer as company_accounts_voucher_entry_related_customer,
                    voucher_journal_for_company_accounts_entry.related_contractor as company_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_company_accounts_entry.related_company as company_accounts_voucher_entry_related_company,
                    voucher_journal_for_company_accounts.active as company_accounts_voucher_active,
                    voucher_journal_for_company_accounts_entry.ac_type as company_accounts_voucher_entry_ac_type,
                    voucher_journal_for_company_accounts_entry.id as company_accounts_voucher_entry_id,
                    account_titles_for_company_accounts.title as company_accounts_title,
                    voucher_journal_for_company_accounts_entry.account_title_id as company_accounts_title_id,
                    voucher_journal_for_company_accounts.person_tid as company_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_company_accounts', 'voucher_journal_for_company_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_company_accounts_entry', 'voucher_journal_for_company_accounts_entry.journal_voucher_id = voucher_journal_for_company_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_company_accounts','account_titles_for_company_accounts.id = voucher_journal_for_company_accounts_entry.account_title_id','left');

                break;
            default:
                break;
        }
        ///////////////////////////////

        $this->db->select($select);   //select ends...

        /*--**********************joining ends*********************--*/

        $this->db->order_by('trips.id, trips_details.id, customer_accounts.id, company_accounts.id, contractor_accounts.id');
        $rawTrips = $this->db->get()->result();

        $final_trips_array = array();

        //arrays which will hold ids for record settings
        $contractor_account_ids = array();
        $company_account_ids = array();
        $customer_account_ids = array();
        $contractor_accounts_voucher_entry_ids = array();
        $customer_accounts_voucher_entry_ids = array();
        $company_accounts_voucher_entry_ids = array();
        ////////////////////////////////////////////////////

        $previous_trip_id = -1;
        $previous_trip_product_detail_id = -1;
        $previous_customer_account_id = -1;
        $previous_contractor_account_id = -1;
        $previous_company_account_id = -1;

        $temp_trip = new Trip();
        $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);
        $temp_customer_account = new Customer_Account();
        $temp_company_account = new Company_Account();
        $temp_contractor_account = new Contractor_Account();
        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();
        $temp_customer_accounts_voucher_entry = new Voucher_Entry();
        $temp_company_accounts_voucher_entry = new Voucher_Entry();

        $count = 0;
        foreach($rawTrips as $record){
            $count++;

            //setting the parent details
            if($record->trip_id != $previous_trip_id)
            {
                $previous_trip_id = $record->trip_id;

                //$previous_trip_obj = $temp_trip;
                $temp_trip = new trip();

                //setting data in the parent object
                $temp_trip->trip_id = $record->trip_id;
                $temp_trip->type = $record->trip_type;
                $temp_trip->customer = new Customer($record->customer_id, $record->customerName, (100 - $record->contractor_commission) );
                $temp_trip->contractor = new Contractor($record->contractor_id, $record->contractorName, $record->contractor_commission);
                $temp_trip->company = new Company($record->company_id, $record->companyName, $record->company_commission_1, $record->wht);
                $temp_trip->driver_1 = new Driver($record->driver_id_1, $record->driver_1_name);
                $temp_trip->driver_2 = new Driver($record->driver_id_2, $record->driver_2_name);
                $temp_trip->driver_3 = new Driver($record->driver_id_3, $record->driver_3_name);

                $temp_trip->start_meter = $record->start_meter;
                $temp_trip->end_meter = $record->end_meter;
                $temp_trip->fuel_consumed = $record->fuel_consumed;

                $temp_trip->tanker = new Tanker($record->tanker_id, $record->tanker_number, $record->capacity);

                //setting trip dates
                $temp_trip->dates = new TripDates(
                    $record->email_date,
                    $record->filling_date,
                    $record->receiving_date,
                    $record->stn_receiving_date,
                    $record->decanding_date,
                    $record->invoice_date,
                    $record->entryDate
                );

                $temp_trip->invoice_number = $record->invoice_number;

            }/////////////////////////////////////////////////

            /////////////////////////////////////////////////
            if($record->trips_details_id != $previous_trip_product_detail_id)
            {
                $previous_trip_product_detail_id = $record->trips_details_id;

                $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);

                //setting data in the Trip_Product_Data object
                $temp_trip_product_detail->product_detail_id = $record->trips_details_id;
                $temp_trip_product_detail->product = new Product($record->productId, $record->productName, $record->product_type);
                $temp_trip_product_detail->source = new City($record->source_id, $record->sourceCityName);
                $temp_trip_product_detail->destination = new City($record->destination_id, $record->destinationCityName);

                $temp_trip_product_detail->product_quantity = $record->product_quantity;
                $temp_trip_product_detail->quantity_at_destination = $record->qty_at_destination;
                $temp_trip_product_detail->quantity_after_decanding = $record->qty_after_decanding;
                $temp_trip_product_detail->price_unit = $record->price_unit;
                $temp_trip_product_detail->customer_freight_unit = $record->freight_unit;
                $temp_trip_product_detail->company_freight_unit = $record->company_freight_unit;
                $temp_trip_product_detail->stn_number = $record->stn_number;
                $temp_trip_product_detail->shortage_voucher_dest = $record->shortage_voucher_dest;
                $temp_trip_product_detail->shortage_voucher_decnd = $record->shortage_voucher_decnd;
            }/////////////////////////////////////////////////

            ////////************Setting Customer Accounts**********////////////
            if(!in_array($record->customer_account_id, $customer_account_ids))
            {
                $previous_customer_account_id = $record->customer_account_id;
                $temp_customer_account = new Customer_Account();

                //setting data in the parent object
                $temp_customer_account->account_id = $record->customer_account_id;
                $temp_customer_account->amount_paid = $record->paid_to_customer;
                $temp_customer_account->payment_date = $record->payment_to_customer_date;
            }/////////////////////////////////////////////////

            ////////************Setting Contractor Accounts**********////////////
            if(!in_array($record->contractor_account_id, $contractor_account_ids))
            {
                $previous_contractor_account_id = $record->contractor_account_id;
                $temp_contractor_account = new Contractor_Account();

                //setting data in the parent object
                $temp_contractor_account->account_id = $record->contractor_account_id;
                $temp_contractor_account->amount_paid = $record->paid_to_contractor;
                $temp_contractor_account->payment_date = $record->payment_to_contractor_date;
            }/////////////////////////////////////////////////

            ////////************Setting Company Accounts**********////////////
            if(!in_array($record->company_account_id, $company_account_ids))
            {
                $previous_company_account_id = $record->company_account_id;
                $temp_company_account = new Company_Account();

                //setting data in the parent object
                $temp_company_account->account_id = $record->company_account_id;
                $temp_company_account->amount_paid = $record->paid_to_company;
                $temp_company_account->payment_date = $record->payment_to_company_date;
            }/////////////////////////////////////////////////


            //gathring data according to aimed for
            switch($aimed_for)
            {
                case "contractor_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids))
                    {
                        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_contractor_accounts_voucher_entry->active = $record->contractor_accounts_voucher_active;
                        $temp_contractor_accounts_voucher_entry->setAc_type($record->contractor_accounts_voucher_entry_ac_type);
                        $temp_contractor_accounts_voucher_entry->setTitle($record->contractor_accounts_title);
                        $temp_contractor_accounts_voucher_entry->setAccount_title_id($record->contractor_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->contractor_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->contractor_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->contractor_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->contractor_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_contractor_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_contractor_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_contractor_accounts_voucher_entry->person_tid = $record->contractor_accounts_voucher_person_tid;
                        $temp_contractor_accounts_voucher_entry->setCredit($record->contractor_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;
                case "customer_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids))
                    {
                        $temp_customer_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_customer_accounts_voucher_entry->active = $record->customer_accounts_voucher_active;
                        $temp_customer_accounts_voucher_entry->setAc_type($record->customer_accounts_voucher_entry_ac_type);
                        $temp_customer_accounts_voucher_entry->setTitle($record->customer_accounts_title);
                        $temp_customer_accounts_voucher_entry->setAccount_title_id($record->customer_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->customer_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->customer_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->customer_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->customer_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_customer_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_customer_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_customer_accounts_voucher_entry->person_tid = $record->customer_accounts_voucher_person_tid;
                        $temp_customer_accounts_voucher_entry->setCredit($record->customer_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                case "company_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids))
                    {
                        $temp_company_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_company_accounts_voucher_entry->active = $record->company_accounts_voucher_active;
                        $temp_company_accounts_voucher_entry->setAc_type($record->company_accounts_voucher_entry_ac_type);
                        $temp_company_accounts_voucher_entry->setTitle($record->company_accounts_title);
                        $temp_company_accounts_voucher_entry->setAccount_title_id($record->company_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->company_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->company_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->company_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->company_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_company_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_company_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_company_accounts_voucher_entry->person_tid = $record->company_accounts_voucher_person_tid;
                        $temp_company_accounts_voucher_entry->setCredit($record->company_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                default:
                    break;
            }
            ///////////////////////////////////////////



            //pushing particals
            if($count != sizeof($rawTrips)){
                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                            //pushing the object id
                            array_push($contractor_accounts_voucher_entry_ids, $record->contractor_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////

                        break;

                    case "customer_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                            //pushing the object id
                            array_push($customer_accounts_voucher_entry_ids, $record->customer_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    case "company_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                            //pushing the object id
                            array_push($company_accounts_voucher_entry_ids, $record->company_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    default:
                        break;
                }
                ///////////////////////////////////////////

                if(!in_array($record->company_account_id, $company_account_ids)){
                    array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                    //pushing the object id
                    array_push($company_account_ids, $record->company_account_id);
                }
                if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                    //pushing the object
                    array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                    //pushing the object id
                    array_push($contractor_account_ids, $record->contractor_account_id);
                }
                if(!in_array($record->customer_account_id, $customer_account_ids)){
                    //pushing the object
                    array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                    //pushing the object id
                    array_push($customer_account_ids, $record->customer_account_id);
                }
                if($rawTrips[$count]->trips_details_id != $record->trips_details_id){
                    array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                }
                if($rawTrips[$count]->trip_id != $record->trip_id){
                    array_push($final_trips_array, $temp_trip);
                }
            }else{

                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "customer_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "company_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    default:
                        break;
                }
                ///////////////////////////////////////////

                if(!in_array($record->company_account_id, $company_account_ids)){
                    array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
                }
                if(!in_array($record->contractor_account_id, $contractor_account_ids)){
                    array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
                }
                if(!in_array($record->customer_account_id, $customer_account_ids)){
                    array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
                }

                array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                array_push($final_trips_array, $temp_trip);
            }
        }

        return $final_trips_array;
    }

    public function parametrized_trips_engine_for_manageaccounts($trip_detail_ids, $aimed_for){
        include_once(APPPATH."models/helperClasses/Trip.php");
        include_once(APPPATH."models/helperClasses/Trip_Product_Detail.php");
        include_once(APPPATH."models/helperClasses/Customer.php");
        include_once(APPPATH."models/helperClasses/Contractor.php");
        include_once(APPPATH."models/helperClasses/Company.php");
        include_once(APPPATH."models/helperClasses/Driver.php");
        include_once(APPPATH."models/helperClasses/Tanker.php");
        include_once(APPPATH."models/helperClasses/City.php");
        include_once(APPPATH."models/helperClasses/Product.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        include_once(APPPATH."models/helperClasses/Bill.php");

        /*
         * -----------------------------
         *  Fetching trips_details ids
         * ----------------------------
         *  The reason is that we want
         *  to use these ids in
         *  where_in query for join
         *  trips table to the journal
         *  tables..
         * --------------------------*/

        /*$this->db->select('id');
        $this->db->where_in('trips_details.trip_id',$trips_ids);
        $result = $this->db->get('trips_details')->result();
        $detail_ids = array();
        foreach($result as $r)
        {
            array_push($detail_ids, $r->id);
        }*/
        /**********************************/

        ////**********************selecte statement starts*********************/////
        $select = "trips.id as trip_id,
         trips.customer_id, customers.name as customerName,
          trips.contractor_id, carriage_contractors.name as contractorName,
           trips.company_id, companies.name as companyName, trips.tanker_id,
            tankers.truck_number as tanker_number, tankers.capacity, trips.contractor_commission,
            trips.contractor_commission_1, trips.contractor_commission_2,
             trips.company_commission_1, trips.company_commission_2 as wht, trips.company_commission_3,
             trips.type as trip_type,
              trips.driver_id_1, drivers_1.name as driver_1_name, trips.driver_id_2,
               drivers_2.name as driver_2_name, trips.driver_id_3,
                drivers_3.name as driver_3_name, trips.filling_date, trips.decanding_date,
                 trips.email_date, trips.stn_receiving_date, trips.receiving_date,
                  trips.invoice_date, trips.invoice_number, trips.entryDate,
                   trips_details.id as trips_details_id, trips_details.product_quantity,
                    trips_details.qty_at_destination, trips_details.qty_after_decanding,
                     trips_details.price_unit, freight_unit, trips_details.stn_number, trips_details.shortage_voucher_dest,
                     trips_details.shortage_voucher_decnd, trips_details.source as source_id,
                      trips_details.destination as destination_id, trips_details.product,
                      source_cities.cityName as sourceCityName, trips_details.source as sourceCityId,
                      destination_cities.cityName as destinationCityName, trips_details.destination as destinationCityId,
                      products.productName,products.type as product_type, trips_details.product as productId, trips_details.company_freight_unit,
                      trips_details.bill_id, bills.billed_date_time,
                      ";

        //applying the where condition...
        if(sizeof($trip_detail_ids) >= 1){
            $this->db->where_in('trips_details.id',$trip_detail_ids);
        }else{
            $this->db->where('trips_details.id',0);
        }
        ////////////////////////////////////

        $this->db->from('trips_details');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips', 'trips.id = trips_details.trip_id','left');
        $this->db->join('bills','bills.id = trips_details.id','left');
        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies', 'companies.id = trips.company_id','left');

        //joining tankers
        $this->db->join('tankers', 'tankers.id = trips.tanker_id','left');

        //joining drivers
        $this->db->join('drivers as drivers_1', 'drivers_1.id = trips.driver_id_1','left');
        $this->db->join('drivers as drivers_2', 'drivers_2.id = trips.driver_id_2','left');
        $this->db->join('drivers as drivers_3', 'drivers_3.id = trips.driver_id_3','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        //computing the required query aimed for
        switch($aimed_for)
        {
            case "contractor_accounts":
                //select statement
                $select.="
                    voucher_journal_for_contractor_accounts.id as contractor_accounts_voucher_id,
                    voucher_journal_for_contractor_accounts_entry.credit_amount as contractor_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_contractor_accounts_entry.related_other_agent as contractor_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_contractor_accounts_entry.related_customer as contractor_accounts_voucher_entry_related_customer,
                    voucher_journal_for_contractor_accounts_entry.related_contractor as contractor_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_contractor_accounts_entry.related_company as contractor_accounts_voucher_entry_related_company,
                    voucher_journal_for_contractor_accounts.active as contractor_accounts_voucher_active,
                    voucher_journal_for_contractor_accounts_entry.ac_type as contractor_accounts_voucher_entry_ac_type,
                    voucher_journal_for_contractor_accounts_entry.id as contractor_accounts_voucher_entry_id,
                    account_titles_for_contractor_accounts.title as contractor_accounts_title,
                    voucher_journal_for_contractor_accounts_entry.account_title_id as contractor_accounts_title_id,
                    voucher_journal_for_contractor_accounts.person_tid as contractor_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_contractor_accounts', 'voucher_journal_for_contractor_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_contractor_accounts_entry', 'voucher_journal_for_contractor_accounts_entry.journal_voucher_id = voucher_journal_for_contractor_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_contractor_accounts','account_titles_for_contractor_accounts.id = voucher_journal_for_contractor_accounts_entry.account_title_id','left');

                break;
            case "customer_accounts":
                //select statement
                $select.="
                    voucher_journal_for_customer_accounts.id as customer_accounts_voucher_id,
                    voucher_journal_for_customer_accounts_entry.credit_amount as customer_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_customer_accounts_entry.related_other_agent as customer_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_customer_accounts_entry.related_customer as customer_accounts_voucher_entry_related_customer,
                    voucher_journal_for_customer_accounts_entry.related_contractor as customer_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_customer_accounts_entry.related_company as customer_accounts_voucher_entry_related_company,
                    voucher_journal_for_customer_accounts.active as customer_accounts_voucher_active,
                    voucher_journal_for_customer_accounts_entry.ac_type as customer_accounts_voucher_entry_ac_type,
                    voucher_journal_for_customer_accounts_entry.id as customer_accounts_voucher_entry_id,
                    account_titles_for_customer_accounts.title as customer_accounts_title,
                    voucher_journal_for_customer_accounts_entry.account_title_id as customer_accounts_title_id,
                    voucher_journal_for_customer_accounts.person_tid as customer_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_customer_accounts', 'voucher_journal_for_customer_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_customer_accounts_entry', 'voucher_journal_for_customer_accounts_entry.journal_voucher_id = voucher_journal_for_customer_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_customer_accounts','account_titles_for_customer_accounts.id = voucher_journal_for_customer_accounts_entry.account_title_id','left');

                break;
            case "company_accounts":
                //select statement
                $select.="
                    voucher_journal_for_company_accounts.id as company_accounts_voucher_id,
                    voucher_journal_for_company_accounts_entry.credit_amount as company_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_company_accounts_entry.related_other_agent as company_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_company_accounts_entry.related_customer as company_accounts_voucher_entry_related_customer,
                    voucher_journal_for_company_accounts_entry.related_contractor as company_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_company_accounts_entry.related_company as company_accounts_voucher_entry_related_company,
                    voucher_journal_for_company_accounts.active as company_accounts_voucher_active,
                    voucher_journal_for_company_accounts_entry.ac_type as company_accounts_voucher_entry_ac_type,
                    voucher_journal_for_company_accounts_entry.id as company_accounts_voucher_entry_id,
                    account_titles_for_company_accounts.title as company_accounts_title,
                    voucher_journal_for_company_accounts_entry.account_title_id as company_accounts_title_id,
                    voucher_journal_for_company_accounts.person_tid as company_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('voucher_journal as voucher_journal_for_company_accounts', 'voucher_journal_for_company_accounts.trip_product_detail_id = trips_details.id','left');
                $this->db->join('voucher_entry as voucher_journal_for_company_accounts_entry', 'voucher_journal_for_company_accounts_entry.journal_voucher_id = voucher_journal_for_company_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_company_accounts','account_titles_for_company_accounts.id = voucher_journal_for_company_accounts_entry.account_title_id','left');

                break;

            case "manage_accounts":
                //select statement
                $select.="
                    voucher_journal_for_manage_accounts.id as manage_accounts_voucher_id,
                    voucher_journal_for_manage_accounts.voucher_date as manage_accounts_voucher_date,
                    voucher_journal_for_manage_accounts_entry.credit_amount as manage_accounts_voucher_entry_credit_amount,
                    voucher_journal_for_manage_accounts_entry.related_other_agent as manage_accounts_voucher_entry_related_other_agent,
                    voucher_journal_for_manage_accounts_entry.related_customer as manage_accounts_voucher_entry_related_customer,
                    voucher_journal_for_manage_accounts_entry.related_contractor as manage_accounts_voucher_entry_related_contractor,
                    voucher_journal_for_manage_accounts_entry.related_company as manage_accounts_voucher_entry_related_company,
                    voucher_journal_for_manage_accounts_entry.dr_cr as manage_accounts_voucher_entry_dr_cr,
                    voucher_journal_for_manage_accounts.active as manage_accounts_voucher_active,
                    account_titles_for_manage_accounts.type as manage_accounts_voucher_entry_ac_type,
                    voucher_journal_for_manage_accounts_entry.id as manage_accounts_voucher_entry_id,
                    trip_detail_voucher_relation_for_manage_accounts.id as trip_detail_voucher_relation_for_manage_accounts_id,
                    account_titles_for_manage_accounts.title as manage_accounts_title,
                    voucher_journal_for_manage_accounts_entry.account_title_id as manage_accounts_title_id,
                    voucher_journal_for_manage_accounts.person_tid as manage_accounts_voucher_person_tid,

                ";
                //joins statements
                $this->db->join('trip_detail_voucher_relation as trip_detail_voucher_relation_for_manage_accounts', 'trip_detail_voucher_relation_for_manage_accounts.trip_detail_id = trips_details.id','left');
                $this->db->join('voucher_journal as voucher_journal_for_manage_accounts', 'voucher_journal_for_manage_accounts.id = trip_detail_voucher_relation_for_manage_accounts.voucher_id','left');
                $this->db->join('voucher_entry as voucher_journal_for_manage_accounts_entry', 'voucher_journal_for_manage_accounts_entry.journal_voucher_id = voucher_journal_for_manage_accounts.id', 'left');
                $this->db->join('account_titles as account_titles_for_manage_accounts','account_titles_for_manage_accounts.id = voucher_journal_for_manage_accounts_entry.account_title_id','left');

                //where statements
                $this->db->where_in('trip_detail_voucher_relation_for_manage_accounts.trip_detail_id',$trip_detail_ids);
                $this->db->where(array(
                    'voucher_journal_for_manage_accounts.active'=>1,
                ));
                break;
            default:
                break;
        }
        ///////////////////////////////

        $this->db->select($select);   //select ends...

        /*--**********************joining ends*********************--*/


        $this->db->order_by('trips.id, trips_details.id');
        $rawTrips = $this->db->get()->result();//die(var_dump($rawTrips));

        $final_trips_array = array();

        //arrays which will hold ids for record settings
        $contractor_account_ids = array();
        $company_account_ids = array();
        $customer_account_ids = array();
        $contractor_accounts_voucher_entry_ids = array();
        $customer_accounts_voucher_entry_ids = array();
        $company_accounts_voucher_entry_ids = array();
        $trip_detail_voucher_relation_for_manage_accounts_ids = array();
        ////////////////////////////////////////////////////

        $previous_trip_id = -1;
        $previous_trip_product_detail_id = -1;
        $previous_customer_account_id = -1;
        $previous_contractor_account_id = -1;
        $previous_company_account_id = -1;

        $temp_trip = new Trip();
        $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);
        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();
        $temp_customer_accounts_voucher_entry = new Voucher_Entry();
        $temp_company_accounts_voucher_entry = new Voucher_Entry();
        $temp_manage_accounts_voucher_entry = new Voucher_Entry();

        $count = 0;
        foreach($rawTrips as $record){
            $count++;

            //setting the parent details
            if($record->trip_id != $previous_trip_id)
            {
                $previous_trip_id = $record->trip_id;

                //$previous_trip_obj = $temp_trip;
                $temp_trip = new trip();

                //setting data in the parent object
                $temp_trip->trip_id = $record->trip_id;
                $temp_trip->type = $record->trip_type;
                $temp_trip->customer = new Customer($record->customer_id, $record->customerName, (100 - $record->contractor_commission) );
                $temp_trip->contractor = new Contractor($record->contractor_id, $record->contractorName, $record->contractor_commission);
                $temp_trip->company = new Company($record->company_id, $record->companyName, $record->company_commission_1, $record->wht);
                $temp_trip->driver_1 = new Driver($record->driver_id_1, $record->driver_1_name);
                $temp_trip->driver_2 = new Driver($record->driver_id_2, $record->driver_2_name);
                $temp_trip->driver_3 = new Driver($record->driver_id_3, $record->driver_3_name);

                $temp_trip->tanker = new Tanker($record->tanker_id, $record->tanker_number, $record->capacity);

                //setting trip dates
                $temp_trip->dates = new TripDates(
                    $record->email_date,
                    $record->filling_date,
                    $record->receiving_date,
                    $record->stn_receiving_date,
                    $record->decanding_date,
                    $record->invoice_date,
                    $record->entryDate
                );

                $temp_trip->invoice_number = $record->invoice_number;

            }/////////////////////////////////////////////////

            /////////////////////////////////////////////////
            if($record->trips_details_id != $previous_trip_product_detail_id)
            {
                $previous_trip_product_detail_id = $record->trips_details_id;

                $temp_trip_product_detail = new Trip_Product_Detail($temp_trip);

                //setting data in the Trip_Product_Data object
                $temp_trip_product_detail->product_detail_id = $record->trips_details_id;
                $temp_trip_product_detail->product = new Product($record->productId, $record->productName, $record->product_type);
                $temp_trip_product_detail->source = new City($record->source_id, $record->sourceCityName);
                $temp_trip_product_detail->destination = new City($record->destination_id, $record->destinationCityName);

                $temp_trip_product_detail->product_quantity = $record->product_quantity;
                $temp_trip_product_detail->quantity_at_destination = $record->qty_at_destination;
                $temp_trip_product_detail->quantity_after_decanding = $record->qty_after_decanding;
                $temp_trip_product_detail->price_unit = $record->price_unit;
                $temp_trip_product_detail->customer_freight_unit = $record->freight_unit;
                $temp_trip_product_detail->company_freight_unit = $record->company_freight_unit;
                $temp_trip_product_detail->stn_number = $record->stn_number;
                $temp_trip_product_detail->shortage_voucher_dest = $record->shortage_voucher_dest;
                $temp_trip_product_detail->shortage_voucher_decnd = $record->shortage_voucher_decnd;
                //setting bill data
                $bill = new Bill();
                $bill->id = $record->bill_id;
                $bill->date_time = $record->billed_date_time;
                $temp_trip_product_detail->bill = $bill;
            }/////////////////////////////////////////////////

            //gathring data according to aimed for
            switch($aimed_for)
            {
                case "contractor_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids))
                    {
                        $temp_contractor_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_contractor_accounts_voucher_entry->active = $record->contractor_accounts_voucher_active;
                        $temp_contractor_accounts_voucher_entry->setAc_type($record->contractor_accounts_voucher_entry_ac_type);
                        $temp_contractor_accounts_voucher_entry->setTitle($record->contractor_accounts_title);
                        $temp_contractor_accounts_voucher_entry->setAccount_title_id($record->contractor_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->contractor_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->contractor_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->contractor_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->contractor_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->contractor_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_contractor_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_contractor_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_contractor_accounts_voucher_entry->person_tid = $record->contractor_accounts_voucher_person_tid;
                        $temp_contractor_accounts_voucher_entry->setCredit($record->contractor_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;
                case "customer_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids))
                    {
                        $temp_customer_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_customer_accounts_voucher_entry->active = $record->customer_accounts_voucher_active;
                        $temp_customer_accounts_voucher_entry->setAc_type($record->customer_accounts_voucher_entry_ac_type);
                        $temp_customer_accounts_voucher_entry->setTitle($record->customer_accounts_title);
                        $temp_customer_accounts_voucher_entry->setAccount_title_id($record->customer_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->customer_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->customer_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->customer_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->customer_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->customer_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_customer_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_customer_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_customer_accounts_voucher_entry->person_tid = $record->customer_accounts_voucher_person_tid;
                        $temp_customer_accounts_voucher_entry->setCredit($record->customer_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                case "company_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids))
                    {
                        $temp_company_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                        $temp_company_accounts_voucher_entry->active = $record->company_accounts_voucher_active;
                        $temp_company_accounts_voucher_entry->setAc_type($record->company_accounts_voucher_entry_ac_type);
                        $temp_company_accounts_voucher_entry->setTitle($record->company_accounts_title);
                        $temp_company_accounts_voucher_entry->setAccount_title_id($record->company_accounts_title_id);
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->company_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->company_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->company_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->company_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->company_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_company_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_company_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_company_accounts_voucher_entry->person_tid = $record->company_accounts_voucher_person_tid;
                        $temp_company_accounts_voucher_entry->setCredit($record->company_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    }/////////////////////////////////////////////////
                    break;

                case "manage_accounts":

                    ///************Setting contractor commission credit Accounts**********///
                    //if(!in_array($record->manage_accounts_voucher_entry_id, $trip_detail_voucher_relation_for_manage_accounts_ids))
                    //{
                        $temp_manage_accounts_voucher_entry = new Voucher_Entry();

                        //setting data in the parent object
                    $temp_manage_accounts_voucher_entry->journal_voucher_id = $record->manage_accounts_voucher_id;
                    $temp_manage_accounts_voucher_entry->voucher_date = $record->manage_accounts_voucher_date;
                    $temp_manage_accounts_voucher_entry->active = $record->manage_accounts_voucher_active;
                    $temp_manage_accounts_voucher_entry->setAc_type($record->manage_accounts_voucher_entry_ac_type);
                        $temp_manage_accounts_voucher_entry->setTitle($record->manage_accounts_title);
                        $temp_manage_accounts_voucher_entry->setAccount_title_id($record->manage_accounts_title_id);
                        $temp_manage_accounts_voucher_entry->dr_cr = $record->manage_accounts_voucher_entry_dr_cr;
                        $agent_type = "";
                        $agent_id = 0;
                        if($record->manage_accounts_voucher_entry_related_other_agent != 0){
                            $agent_id = $record->manage_accounts_voucher_entry_related_other_agent;
                            $agent_type = "other_agent";
                        }else if($record->manage_accounts_voucher_entry_related_customer != 0){
                            $agent_id = $record->manage_accounts_voucher_entry_related_customer;
                            $agent_type = "customer";
                        }else if($record->manage_accounts_voucher_entry_related_contractor != 0){
                            $agent_id = $record->manage_accounts_voucher_entry_related_contractor;
                            $agent_type = "contractor";
                        }else if($record->manage_accounts_voucher_entry_related_company != 0){
                            $agent_id = $record->manage_accounts_voucher_entry_related_company;
                            $agent_type = "company";
                        }
                        $temp_manage_accounts_voucher_entry->setRelated_agent($agent_type);
                        $temp_manage_accounts_voucher_entry->setRelated_agent_id($agent_id);
                        $temp_manage_accounts_voucher_entry->person_tid = $record->manage_accounts_voucher_person_tid;
                        $temp_manage_accounts_voucher_entry->setCredit($record->manage_accounts_voucher_entry_credit_amount);

                        //$temp_contractor_accounts_voucher_entry->setRelated_person_tid($record->contractor_accounts_voucher_person_tid);

                    //}/////////////////////////////////////////////////
                    break;

                default:
                    break;
            }
            ///////////////////////////////////////////



            //pushing particals
            if($count != sizeof($rawTrips)){
                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                            //pushing the object id
                            array_push($contractor_accounts_voucher_entry_ids, $record->contractor_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////

                        break;

                    case "customer_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                            //pushing the object id
                            array_push($customer_accounts_voucher_entry_ids, $record->customer_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    case "company_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                            //pushing the object id
                            array_push($company_accounts_voucher_entry_ids, $record->company_accounts_voucher_entry_id);
                        }
                        /////////////////////////////////////////////////
                        break;

                    case "manage_accounts":
                        ///************Pushing contractor commission credit Accounts**********///
                        //if(!in_array($record->manage_accounts_voucher_entry_id, $trip_detail_voucher_relation_for_manage_accounts_ids)){
                            array_push($temp_trip_product_detail->user_accounts_entries, $temp_manage_accounts_voucher_entry);
                            //pushing the object id
                            array_push($trip_detail_voucher_relation_for_manage_accounts_ids, $record->manage_accounts_voucher_entry_id);
                        //}
                        /////////////////////////////////////////////////
                        break;

                    default:
                        break;
                }
                ///////////////////////////////////////////

                if($rawTrips[$count]->trips_details_id != $record->trips_details_id){
                    array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                }
                if($rawTrips[$count]->trip_id != $record->trip_id){
                    array_push($final_trips_array, $temp_trip);
                }
            }else{

                //pushing data according to aimed for
                switch($aimed_for)
                {
                    case "contractor_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->contractor_accounts_voucher_entry_id, $contractor_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->contractor_accounts_entries, $temp_contractor_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "customer_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->customer_accounts_voucher_entry_id, $customer_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_customer_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "company_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        if(!in_array($record->company_accounts_voucher_entry_id, $company_accounts_voucher_entry_ids)){
                            array_push($temp_trip_product_detail->customer_accounts_credit_entries, $temp_company_accounts_voucher_entry);
                        }
                        /////////////////////////////////////////////////

                        break;
                    case "manage_accounts":

                        ///************Pushing contractor commission credit Accounts**********///
                        //if(!in_array($record->manage_accounts_voucher_entry_id, $trip_detail_voucher_relation_for_manage_accounts_ids)){
                            array_push($temp_trip_product_detail->user_accounts_entries, $temp_manage_accounts_voucher_entry);
                        //}
                        /////////////////////////////////////////////////

                        break;
                    default:
                        break;
                }
                ///////////////////////////////////////////

                array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
                array_push($final_trips_array, $temp_trip);
            }
        }

        return $final_trips_array;
    }

    public function limited_trips($limit, $start) {

        $this->db->limit($limit, $start);
        $query = $this->db->get("trips");
        $trips = array();
            foreach ($query->result() as $row) {
                array_push($trips, $this->trip_details($row->id));
            }
            return $trips;
    }

    public function trips_by_month($month, $limit, $start){
        $this->db->select('trips.id as trip_id');
        $this->db->limit($limit, $start);
        $this->db->order_by('trips.id','desc');
        $this->db->from('trips');
        $this->db->where('active',1);
        $this->db->like('trips.entryDate', $month."-", 'after');
        $trips = $this->db->get()->result();
        $trips_details = array();
        foreach($trips as $trip){
            array_push($trips_details, $this->trip_details($trip->trip_id));
        }
        return $trips_details;
    }

    public function save_shortage_voucher_completely()
    {
        $this->db->trans_start();
        $this->trips_model->save_shortage_expense();

        if(isset($_POST['product_type']) && strtolower($_POST['product_type']) == strtolower('black oil'))
        {

            $this->db->select('trips_details.company_freight_unit');
            $result = $this->db->get_where('trips_details',array('id'=>$_POST['trip_detail_id']))->result();
            $company_freight_per_unit = $result[0]->company_freight_unit;
            $amount = ($_POST['shortage_quantity'] * $company_freight_per_unit)+($_POST['shortage_quantity'] * $_POST['shortage_rate']);

            $this->trips_model->shortage_deduction_voucher_for_black_oil($amount);

            /**
             * ignore destination deduction voucher if
             * there is decanding deduction voucher exists
             */
            if($_POST['shortage_type'] == 2)
            {
                $where = array(
                    'voucher_type'=>'dest_shortage_deduction',
                    'trip_product_detail_id'=>$_POST['trip_detail_id'],
                    'active'=>1,
                );
                $this->helper_model->ignore_voucher_where($where);
            }
            else if($_POST['shortage_type'] == 1)
            {
                /**
                 * Check if decanding deduction already made
                 * Than ignore destination deduction voucher
                 */
                $where = array(
                    'voucher_type'=>'decnd_shortage_deduction',
                    'trip_product_detail_id'=>$_POST['trip_detail_id'],
                    'active'=>1,
                );
                if($this->helper_model->is_there_any_voucher_where($where) == true)
                {
                    //ignore destination deduction voucher
                    $where = array(
                        'voucher_type'=>'dest_shortage_deduction',
                        'trip_product_detail_id'=>$_POST['trip_detail_id'],
                        'active'=>1,
                    );
                    $this->helper_model->ignore_voucher_where($where);
                }
            }
            /*---------------------------------------------------*/
        }

        return $this->db->trans_complete();

    }
    public function save_shortage_voucher()
    {
        $voucher = array(
            'voucher_date' =>$this->input->post('voucher_date'),
            'detail' => $this->input->post('voucher_details'),
            'person_tid' => 'users.1',
            'trip_id' => $this->input->post('trip_id'),
            'trip_product_detail_id'=> ((isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0),
            'tanker_id' => $this->input->post('tankers'),
            'shortage_quantity'=>(isset($_POST['shortage_quantity']))?$_POST['shortage_quantity']:0,
            'shortage_rate'=>(isset($_POST['shortage_rate']))?$_POST['shortage_rate']:0,
            'price_unit'=>(isset($_POST['price_unit']))?$_POST['price_unit']:0,
            'voucher_type'=>($_POST['shortage_type'] == 1)?'shortage_voucher_dest':'shortage_voucher_decnd',
        );
        $result = $this->db->insert('voucher_journal', $voucher);
        $inserted_voucher_id = $this->db->insert_id();

        $entries = array();
        $entries_counter = $this->input->post('pannel_count');
        for($counter = 1; $counter < $entries_counter; $counter++){
            $entry = array();
            $entry['ac_type'] = $this->input->post('tr_type_'.$counter);
            $entry['account_title_id'] = $this->input->post('tr_title_'.$counter);
            $entry['description'] = $this->input->post('description_'.$counter);
            $related_other_agent = ($this->input->post('agent_type_'.$counter) == 'other_agents')?$this->input->post('agent_id_'.$counter):0;
            $related_customer = ($this->input->post('agent_type_'.$counter) == 'customers')?$this->input->post('agent_id_'.$counter):0;
            $related_contractor = ($this->input->post('agent_type_'.$counter) == 'carriage_contractors')?$this->input->post('agent_id_'.$counter):0;
            $related_company = ($this->input->post('agent_type_'.$counter) == 'companies')?$this->input->post('agent_id_'.$counter):0;

            $entry['related_company'] = $related_company;
            $entry['related_other_agent'] = $related_other_agent;
            $entry['related_customer'] = $related_customer;
            $entry['related_contractor'] = $related_contractor;
            $entry['debit_amount'] = ($this->input->post('payment_type_'.$counter) == 0)?0.00:$this->input->post('amount_'.$counter);
            $entry['credit_amount'] = ($this->input->post('payment_type_'.$counter) == 1)?0.00:$this->input->post('amount_'.$counter);
            $entry['dr_cr'] = $this->input->post('payment_type_'.$counter);
            $entry['journal_voucher_id'] = $inserted_voucher_id;
            array_push($entries, $entry);

        }

        return ($this->db->insert_batch('voucher_entry', $entries)); die();
    }

    public function shortage_deduction_voucher_for_black_oil($total_amount)
    {
        $trip_id = $_POST['trip_id'];
        $this->db->select('trips.company_id, trips.contractor_id');
        $this->db->where('trips.id',$trip_id);
        $result = $this->db->get('trips')->result();
        $company_id = $result[0]->company_id;
        $contractor_id = $result[0]->contractor_id;
        $credit_title_id = 54;
        $debit_title_id = 49;
        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$_POST['voucher_date'],
            'detail' => 'shortage deduction voucher for black oil',
            'person_tid' => "users.1",
            'trip_id' => $_POST['trip_id'],
            'trip_product_detail_id'=>(isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0,
            'tanker_id' => $_POST['tankers'],
            'voucher_type'=>($_POST['shortage_type'] == 1)?'dest_shortage_deduction':'decnd_shortage_deduction',
        );
        $result = $this->db->insert('voucher_journal', $journal_voucher_data);
        $inserted_voucher_id = $this->db->insert_id();

        $voucher_entries = array();
        $entry = array();
        $entry['ac_type'] = '';
        $entry['account_title_id'] = $debit_title_id;
        $entry['description'] = 'shortage deduction black oil';

        $entry['related_company'] = 0;
        $entry['related_other_agent'] = 0;
        $entry['related_customer'] = 0;
        $entry['related_contractor'] = $contractor_id;
        $entry['debit_amount'] = $total_amount;
        $entry['credit_amount'] = 0;
        $entry['dr_cr'] = 1;
        $entry['journal_voucher_id'] = $inserted_voucher_id;
        array_push($voucher_entries, $entry);

        $entry = array();
        $entry['ac_type'] = '';
        $entry['account_title_id'] = $credit_title_id;
        $entry['description'] = 'shortage deduction black oil';

        $entry['related_company'] = $company_id;
        $entry['related_other_agent'] = 0;
        $entry['related_customer'] = 0;
        $entry['related_contractor'] = 0;
        $entry['debit_amount'] = 0;
        $entry['credit_amount'] = $total_amount;
        $entry['dr_cr'] = 0;
        $entry['journal_voucher_id'] = $inserted_voucher_id;
        array_push($voucher_entries, $entry);
        $this->db->insert_batch('voucher_entry', $voucher_entries);
    }

    public function save_shortage_expense()
    {
        if($this->input->post('shortage_type') == '1'){

            $this->db->trans_start();
            if($this->trips_model->save_shortage_voucher() == true){

                $this->db->select("journal_voucher_id");
                $result = $this->db->get_where('voucher_entry',array('id'=>mysql_insert_id()))->result();
                $voucher_id = $result[0]->journal_voucher_id;

                $data = array(
                    'shortage_voucher_dest'=>$voucher_id,
                );
                $this->db->where('trips_details.id',$this->input->post('trip_detail_id'));
                $this->db->update('trips_details',$data);

                //fetching decanding voucher id
                $destination_voucher = $voucher_id;
                $this->db->select("shortage_voucher_decnd");
                $result = $this->db->get_where('trips_details',array('shortage_voucher_dest'=>$destination_voucher))->result();
                $decanding_voucher = $result[0]->shortage_voucher_decnd;
                //////////////////////////////////////////////////////////

                //if decanding voucher is already given than ignore the destination voucher
                if($decanding_voucher != 0){
                    $this->helper_model->ignore_voucher($destination_voucher);

                }
                ///////////////////////////////////////////////////////////////////////////
                $this->db->trans_complete();
                if($this->db->trans_status() == true)
                {
                    return true;
                }
                return false;
            }
        }else if($this->input->post('shortage_type') == '2'){

            //ignoring the destinatino voucher
            $destination_voucher = $this->input->post('destination_voucher');
            if($destination_voucher != 0){
               $this->helper_model->ignore_voucher($destination_voucher);
            }

            //saving the decanding voucher
            if($this->trips_model->save_shortage_voucher() == true){
                $this->db->select("journal_voucher_id");
                $result = $this->db->get_where('voucher_entry',array('id'=>mysql_insert_id()))->result();
                $voucher_id = $result[0]->journal_voucher_id;

                $data = array(
                    'shortage_voucher_decnd'=>$voucher_id,
                );
                $this->db->where('trips_details.id',$this->input->post('trip_detail_id'));
                if($this->db->update('trips_details',$data) == true){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function delete_dest_shortage_voucher($voucher_id)
    {
        $this->db->trans_start();

        $product_type = 'white_oil';
        $detail_id = 0;
        /*----------- Knowing product type ------------*/
        $this->db->select('products.type as product_type, trips_details.id as detail_id');
        $this->db->from('trips_details');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->where('trips_details.shortage_voucher_dest',$voucher_id);
        $result = $this->db->get()->result();
        if(sizeof($result) > 0)
        {
            if($result[0] != null)
            {
                if($result[0]->product_type == 'black oil')
                {
                    $product_type = 'black_oil';
                    $detail_id = $result[0]->detail_id;
                }
            }
        }
        /*---------------------------------------------*/

        $data = array(
            'shortage_voucher_dest'=>0,
        );
        $this->db->where('trips_details.shortage_voucher_dest',$voucher_id);
        $this->db->update('trips_details',$data);

        $this->helper_model->safe_delete('voucher_journal',$voucher_id);

        if($product_type == 'black_oil')
        {
            $where = array(
                'voucher_type'=>'dest_shortage_deduction',
                'trip_product_detail_id'=>$detail_id,
            );
            $this->helper_model->safe_delete_where('voucher_journal', $where);
        }

        return $this->db->trans_complete();
    }

    public function delete_decnd_shortage_voucher($decanding_voucher)
    {
        $this->db->trans_start();

        $product_type = 'white_oil';
        $detail_id = 0;
        /*----------- Knowing product type ------------*/
        $this->db->select('products.type as product_type, trips_details.id as detail_id');
        $this->db->from('trips_details');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->where('trips_details.shortage_voucher_decnd',$decanding_voucher);
        $result = $this->db->get()->result();
        if(sizeof($result) > 0)
        {
            if($result[0] != null)
            {
                if($result[0]->product_type == 'black oil')
                {
                    $product_type = 'black_oil';
                    $detail_id = $result[0]->detail_id;
                }
            }
        }
        /*---------------------------------------------*/

        $this->db->select("trips_details.shortage_voucher_dest");
        $result = $this->db->get_where('trips_details',array('shortage_voucher_decnd'=>$decanding_voucher))->result();
        if($result != null){
            $destination_voucher = $result[0]->shortage_voucher_dest;
        }else{
            $destination_voucher = 0;
            return;
        }

        $data = array(
            'shortage_voucher_decnd'=>0,
        );
        $this->db->where('trips_details.shortage_voucher_decnd',$decanding_voucher);
        $this->db->update('trips_details',$data);

        //delete decanding voucher
        $this->helper_model->safe_delete('voucher_journal',$decanding_voucher);

        //dont ignore destination voucher
        $this->helper_model->dont_ignore_voucher($destination_voucher);

        /**
         * If product is black_oil than
         * -- Remove decanding deduction vcuoher
         * -- Don't ignore destination deduction voucher
         **/
        if($product_type == 'black_oil')
        {
            $where = array(
                'voucher_type'=>'decnd_shortage_deduction',
                'trip_product_detail_id'=>$detail_id,
            );
            $this->helper_model->safe_delete_where('voucher_journal', $where);

            $where = array(
                'voucher_type'=>'dest_shortage_deduction',
                'trip_product_detail_id'=>$detail_id,
            );
            $this->helper_model->dont_ignore_voucher_where($where);
        }
        /**-----------------------------------------------------**/

        return $this->db->trans_complete();
    }


    public function search_trips($keys, $limit, $start, $sort){
        //applying keys....
        include_once(APPPATH."serviceProviders/Sort.php");
        $sorting_info = Sort::columns($keys['module']);

        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 2){
                $this->db->where('stn_number !=','');
            }
            if($keys['trip_status'] == 1)
            {
                $this->db->where('stn_number','');
            }
        }
        if($keys['from'] != ''){
            $this->db->where('entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where_in('product_id',$keys['product']);
        }
        if($keys['trips_routes'] != '')
        {
            $where = "(";
            foreach($keys['trips_routes'] as $route)
            {
                $route_parts = explode('_',$route);
                $where.="(source_id = ".$route_parts[0]." AND destination_id = ".$route_parts[1].") OR ";
            }
            $where.=")";
            $where_parts = explode(') OR )',$where);
            $where = $where_parts[0];
            $where.="))";
            $this->db->where($where);
        }
        else
        {
            if($keys['source'] != ''){
                $this->db->where_in('source_id',$keys['source']);
            }
            if($keys['destination'] != ''){
                $this->db->where_in('destination_id',$keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id',$keys['company']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id',$keys['contractor']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id',$keys['customer']);
        }
        if($keys['tanker'] != '' ){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['stn_number'] != '' ){
            $this->db->like('stn_number', $keys['stn_number']);
        }

        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }


        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4 OR trip_type_id = 1 OR trip_type_id = 5)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 3)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary_local'){
                $where = "(trip_type_id = 6)";
                $this->db->where($where);
            }
        }

        if($keys['trip_master_types'] != '' ){
            $trip_types = array();
            foreach($keys['trip_master_types'] as $type)
            {
                switch($type)
                {
                    case "primary":
                        $trip_types = [1,2,4,5];
                        break;
                    case "secondary":
                        array_push($trip_types,3);
                        break;
                    case "secondary_local":
                        array_push($trip_types,6);
                        break;
                }
            }
            $this->db->where_in('trip_type_id',$trip_types);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('*');
        $this->db->limit($limit, $start);
        foreach($sorting_info as $sort)
        {
            $this->db->order_by($sort['sort_by'],$sort['order_by']);
        }
        $trips = $this->db->get('trips_view')->result();

        return $trips;

    }

    public function count_searched_trips($keys)
    {
        //Those trips ids which are open
        if($keys['trip_status'] != '')
        {
            if($keys['trip_status'] == 2){
                $this->db->where('stn_number !=','');
            }
            if($keys['trip_status'] == 1)
            {
                $this->db->where('stn_number','');
            }
        }
        if($keys['from'] != ''){
            $this->db->where('entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trip_id',$keys['trip_id']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where_in('product_id',$keys['product']);
        }
        if($keys['trips_routes'] != '')
        {
            $where = "(";
            foreach($keys['trips_routes'] as $route)
            {
                $route_parts = explode('_',$route);
                $where.="(source_id = ".$route_parts[0]." AND destination_id = ".$route_parts[1].") OR ";
            }
            $where.=")";
            $where_parts = explode(') OR )',$where);
            $where = $where_parts[0];
            $where.="))";
            $this->db->where($where);
        }
        else
        {
            if($keys['source'] != ''){
                $this->db->where_in('source_id',$keys['source']);
            }
            if($keys['destination'] != ''){
                $this->db->where_in('destination_id',$keys['destination']);
            }
        }
        if($keys['company'] != '' ){
            $this->db->where_in('company_id',$keys['company']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where_in('contractor_id',$keys['contractor']);
        }
        if($keys['customer'] != '' ){
            $this->db->where_in('customer_id',$keys['customer']);
        }
        if($keys['tanker'] != '' ){
            $this->db->where_in('tanker_id',$keys['tanker']);
        }
        if($keys['stn_number'] != '' ){
            $this->db->like('stn_number', $keys['stn_number']);
        }

        if($keys['trip_type'] != '' ){
            $this->db->where_in('trip_type_id', $keys['trip_type']);
        }


        if($keys['trip_master_type'] != '' ){
            if($keys['trip_master_type'] == 'primary'){
                $where = "(trip_type_id = 2 OR trip_type_id = 4 OR trip_type_id = 1 OR trip_type_id = 5)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary'){
                $where = "(trip_type_id = 3)";
                $this->db->where($where);
            }else if($keys['trip_master_type'] == 'secondary_local'){
                $where = "(trip_type_id = 6)";
                $this->db->where($where);
            }
        }

        if($keys['trip_master_types'] != '' ){
            $trip_types = array();
            foreach($keys['trip_master_types'] as $type)
            {
                switch($type)
                {
                    case "primary":
                        $trip_types = [1,2,4,5];
                        break;
                    case "secondary":
                        array_push($trip_types,3);
                        break;
                    case "secondary_local":
                        array_push($trip_types,6);
                        break;
                }
            }
            $this->db->where_in('trip_type_id',$trip_types);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('*');
        $this->db->from('trips_view');

        $trips = $this->db->get()->num_rows();
        return $trips;
    }

    public function separate_trips_by_products($trips)
    {
        $separate_trips = array();
        foreach($trips as $trip)
        {
            $temp_trip = $trip;
            foreach($trip->trip_related_details as $detail)
            {
                $temp_details = array();
                array_push($temp_details, $detail);
                $temp_trip->trip_related_details = $temp_details;
                array_push($separate_trips, $temp_trip);
            }
        }
        return $separate_trips;
    }

    public function trip($id){
        $this->db->select('trips.id as trip_id, trips.customer_id,
         trips.contractor_id, trips.company_id,
         trips.type,
          trips.tanker_id, trips.contractor_commission,
           trips.company_commission_1, trips.company_commission_2,
           trips.start_meter, trips.end_meter, trips.fuel_consumed,
            trips.driver_id_1, trips.driver_id_2, trips.driver_id_3,
             trips.filling_date, trips.decanding_date, trips.email_date,
              trips.stn_receiving_date, trips.receiving_date, trips.invoice_date,
               trips.invoice_number, trips.entryDate, trips_details.id as trips_details_id,
                trips_details.product_quantity, trips_details.qty_at_destination,
                 trips_details.qty_after_decanding, trips_details.price_unit,
                  freight_unit, trips_details.stn_number, trips_details.source,
                   trips_details.destination, trips_details.product, trips_details.company_freight_unit
                   ');
        $this->db->from('trips');
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id');
        $this->db->where('trips.id',$id);
        $this->db->where('active',1);
        $result = $this->db->get()->result();
        if($result){
            return $result;
        }else{
            return null;
        }
    }

    public function trip_details($id){
        include_once(APPPATH."models/helperClasses/Trip_Details.php");
        $this->db->select('trips.id as trip_id');
        $this->db->from('trips');
        $this->db->where('trips.id',$id);
        $this->db->where('active',1);
        $result = $this->db->get()->result();
        if($result){
            $trip = $result[0];
            $trip_details = new Trip_Details($trip);

            return $trip_details;
        }else{
            return null;
        }
    }

    public function save_new_trip_completely()
    {
        $this->db->trans_begin();

        //saving trip_info...
        $this->trips_model->insert_trip();
        //fetching the last saved trip id
        $last_saved_trip_id = 0;
        $this->db->select('trip_id');
        $result = $this->db->get_where('trips_details',array('id'=>$this->db->insert_id(),))->result();
        $last_saved_trip_id = $result[0]->trip_id;
        //saving the vouchers related to this trip
        $this->trips_model->do_auto_accounting_on_trip_save($last_saved_trip_id);

        //checking weather the vouchers are saved correctly
        $vouchers_are_even = $this->trips_model->ensure_auto_generated_trips_vouchers_are_even($last_saved_trip_id);

        if ($this->db->trans_status() === FALSE || $vouchers_are_even == false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }

    public function save_other_new_trip_completely($trip_id= '', $trip_type)
    {
        $this->db->trans_start();

        $this->trips_model->insert_other_trip($trip_id, $trip_type);

        $this->db->select('trip_id');
        $result = $this->db->get_where('trips_details',array('id'=>$this->db->insert_id(),))->result();
        $last_saved_trip_id = $result[0]->trip_id;
        //saving the vouchers related to this trip
        $this->trips_model->do_auto_accounting_on_trip_save($last_saved_trip_id);

        //checking weather the vouchers are saved correctly
        $vouchers_are_even = $this->trips_model->ensure_auto_generated_trips_vouchers_are_even($last_saved_trip_id);

        if ($this->db->trans_status() === FALSE || $vouchers_are_even == false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }

    public function ensure_auto_generated_trips_vouchers_are_even($trip_id)
    {
        $this->db->select("voucher_journal.id as voucher_id");
        $this->db->from('voucher_journal');
        $this->db->where(array(
            'active'=>1,
            'auto_generated'=>1,
            'trip_id'=>$trip_id,
        ));
        $result = $this->db->get()->result();
        if(sizeof($result) < 1)
        {
            return false;
        }
        $voucher_ids = array(0,);
        foreach($result as $record)
        {
            array_push($voucher_ids, $record->voucher_id);
        }
        $journal = $this->accounts_model->journal("users","1",$voucher_ids, "");
        foreach($journal as $voucher)
        {
            if($voucher->balance() != 0)
            {
                return false;
            }
        }
        return true;
    }


    public function save_existing_trip_completely($trip_id)
    {
        $this->db->trans_start();

        //saving trip_info...
        $this->trips_model->update_trip($trip_id);

        /*
         * -------------------------------------
         * adding vouchers of those trips which
         * have extra product detail on edit.
         * ------------------------------------
         */
         $this->trips_model->inserted_vouchers_for_newly_added_products($trip_id);
         /*----------------------------------------------*/

        //saving the vouchers related to this trip
        $this->trips_model->automatic_transactions_on_trip_edit($trip_id);

        //saving expense vouchers related to this trip
        $this->trips_model->update_expense_vouchers_on_trip_edit($trip_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function update_expense_vouchers_on_trip_edit($trip_id)
    {
        $trip_ids = array(
            $trip_id,
        );
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'');
        $trip = $trips[0];

        /*Fetching expense vouchers of this trip*/
        $this->db->select('voucher_journal.id as voucher_id' );
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $this->db->join('account_titles', 'account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where(array(
            'voucher_journal.active'=>1,
            'account_titles.type'=>'expense',
            'voucher_journal.trip_id'=>$trip->trip_id,
        ));
        $result = $this->db->get()->result();
        $voucher_ids = array(0,);
        foreach($result as $record)
        {
            array_push($voucher_ids, $record->voucher_id);
        }
        $journal = $this->accounts_model->journal("users","1",$voucher_ids,"");
        $voucher_data_array = array();
        $entry_data_array = array();
        foreach($journal as $voucher)
        {
            $voucher_data = array(
                'id'=>$voucher->voucher_id,
                'tanker_id'=>$trip->tanker->id,
            );
            array_push($voucher_data_array, $voucher_data);

            foreach($voucher->entries as $entry)
            {
                if($entry->ac_type == 'expense')
                {
                    $related_customer = 0;
                    $related_contractor = 0;
                    $related_company = 0;
                    $related_customer = ($entry->related_agent == 'customers')?$trip->customer->id:0;
                    $related_contractor = ($entry->related_agent == 'carriage_contractors')?$trip->contractor->id:0;
                    $related_company = ($entry->related_agent == 'company')?$trip->company->id:0;
                    $entry_data = array(
                        'id'=>$entry->id,
                        'related_customer'=>$related_customer,
                        'related_company'=>$related_company,
                        'related_contractor'=>$related_contractor,
                    );
                    array_push($entry_data_array, $entry_data);
                }
            }
        }
        if(sizeof($voucher_data_array) > 0)
        {
            $this->db->update_batch('voucher_journal',$voucher_data_array, 'id');
            $this->db->update_batch('voucher_entry',$entry_data_array, 'id');
        }
    }

    public function save_other_existing_trip_completely($trip_id= '', $trip_type)
    {
        $this->db->trans_start();

        $this->trips_model->update_other_trip($trip_id, $trip_type);

        /*
         * -------------------------------------
         * adding vouchers of those trips which
         * have extra product detail on edit.
         * ------------------------------------
         */
        $this->trips_model->inserted_vouchers_for_newly_added_products($trip_id);
        /*----------------------------------------------*/

        //saving the vouchers related to this trip
        $this->trips_model->automatic_transactions_on_trip_edit($trip_id);

        //saving expense vouchers related to this trip
        $this->trips_model->update_expense_vouchers_on_trip_edit($trip_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function update_trip($trip_id= ''){

        $details_counter = $this->input->post('pannel_count');
        //setting dates
        $entry_date = (easyDate($this->input->post('entry_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('entry_date'));
        $email_date = (easyDate($this->input->post('email_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('email_date'));
        $filling_date = (easyDate($this->input->post('filling_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('filling_date'));
        $receiving_date = (easyDate($this->input->post('receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('receiving_date'));
        $stn_receiving_date = (easyDate($this->input->post('stn_receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('stn_receiving_date'));
        $decanding_date = (easyDate($this->input->post('decanding_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('decanding_date'));
        //echo "<h1>".$decanding_date."</h1>";

        $invoice_date = (easyDate($this->input->post('invoice_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('invoice_date'));


        $trips_data = array(
            'customer_id'=>$this->input->post('customers'),
            'contractor_id'=>$this->input->post('contractors'),
            'company_id'=>$this->input->post('companies'),
            'tanker_id'=>$this->input->post('tankers'),
            //commissions
            'contractor_commission'=>$this->input->post('contractor_commission'),
            'company_commission_1'=>$this->input->post('company_commission_1'),
            'company_commission_2'=>$this->input->post('company_commission_2'),

            'entryDate'=>$entry_date,
            'email_date'=>$email_date,
            'filling_date'=>$filling_date,
            'receiving_date'=>$receiving_date,
            'stn_receiving_date'=>$stn_receiving_date,
            'decanding_date'=>$decanding_date,
            'invoice_date'=>$invoice_date,

            'driver_id_1'=>$this->input->post('drivers_1'),
            'driver_id_2'=>$this->input->post('drivers_2'),
            'driver_id_3'=>$this->input->post('drivers_3'),
            'invoice_number'=>$this->input->post('invoice_number'),
            'start_meter'=>$this->input->post('start_meter_reading'),
            'end_meter'=>$this->input->post('end_meter_reading'),
            'fuel_consumed'=>$this->input->post('fuel_consumed'),
            'type'=>$this->input->post('trip_type'),

            //'stn_number'=>$this->input->post('stn_number'),
            //'final_quantity' => $this->input->post('final_quantity'),
        );

        //deciding weather to update or insert?
        $db_error = true;

        $this->db->where('trips.id',$trip_id);
        $this->db->update('trips',$trips_data);

        $trips_details_data = array();
        $trip_details_ids = array();
        for($counter = 1; $counter < $details_counter; $counter++){
            //calculating qty_at_destination adn after_decanding
            $initial_quantity = ($this->input->post('initial_product_quantity_'.$counter) != '')?$this->input->post('initial_product_quantity_'.$counter):0;
            $shortage_at_destination = ($this->input->post('shortage_at_destination_'.$counter))?$this->input->post('shortage_at_destination_'.$counter):0;
            $shortage_after_decanding = ($this->input->post('shortage_after_decanding_'.$counter))?$this->input->post('shortage_after_decanding_'.$counter):0;
            $quantity_at_destination = $initial_quantity - $shortage_at_destination;
            $quantity_after_decanding = $quantity_at_destination - $shortage_after_decanding;

            $company_freight_unit = ($this->input->post('company_freight_unit_'.$counter) == '')?$this->input->post('freight_unit_'.$counter):$this->input->post('company_freight_unit_'.$counter);

            $arr = array(
                'trip_id'=> $trip_id,
                'source'=>$this->input->post('sourceCity_'.$counter),
                'destination'=>$this->input->post('destinationCity_'.$counter),
                'product'=>$this->input->post('product_'.$counter),
                'product_quantity'=>$this->input->post('initial_product_quantity_'.$counter),
                'qty_at_destination'=>$quantity_at_destination,
                'qty_after_decanding'=>$quantity_after_decanding,
                'price_unit'=>$this->input->post('price_unit_'.$counter),
                'freight_unit'=>$this->input->post('freight_unit_'.$counter),
                'company_freight_unit'=>$company_freight_unit,
                'stn_number'=>$this->input->post('stn_number_'.$counter),
            );

            $trips_details_data_for_insertion_at_updation_time = array();

            //below segment will execute when trip was edited..
            if($trip_id != ''){
                //below we check weather this record should b updated or inserted...
                if($counter > $this->input->post('num_saved_trips_details')){
                    array_unshift($trips_details_data_for_insertion_at_updation_time,$arr);
                }
                $arr['id']=$this->input->post('trips_details_id_'.$counter);
                array_push($trip_details_ids, $this->input->post('trips_details_id_'.$counter));

            }
            array_unshift($trips_details_data,$arr);
        }


        /*
         * -----------------------------------
         * 4/9/2015 | Delete Trip Details
         * -----------------------------------
         * deleting those details which user
         * wants to be deleted.
         * */

        //deleting details

        $this->db->where_not_in('trips_details.id',$trip_details_ids);
        $this->db->where('trips_details.trip_id',$trip_id);
        $this->db->from('trips_details');
        $this->db->delete();

        //deleting vouchers
        $this->db->where_not_in('voucher_journal.trip_product_detail_id',$trip_details_ids);
        $this->db->where('voucher_journal.trip_id',$trip_id);
        $this->db->where('voucher_journal.auto_generated',1);
        $voucher_data = array(
            'active'=>0,
        );
        $this->db->update('voucher_journal',$voucher_data);
        /*---------------------~Ends~------------------------------*/

        //deciding weather to update or insert?
        //var_dump($trips_details_data_for_insertion_at_updation_time);
        if(sizeof($trips_details_data_for_insertion_at_updation_time) >=1){
            $result = $this->db->insert_batch('trips_details', $trips_details_data_for_insertion_at_updation_time);
        }
        //var_dump($trips_details_data);die();
        $result = $this->db->update_batch('trips_details',$trips_details_data, 'id');

        return true;

    }

    public function insert_trip($trip_id= ''){
        $details_counter = $this->input->post('pannel_count');
        //setting dates
        $entry_date = (easyDate($this->input->post('entry_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('entry_date'));
        $email_date = (easyDate($this->input->post('email_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('email_date'));
        $filling_date = (easyDate($this->input->post('filling_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('filling_date'));
        $receiving_date = (easyDate($this->input->post('receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('receiving_date'));
        $stn_receiving_date = (easyDate($this->input->post('stn_receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('stn_receiving_date'));
        $decanding_date = (easyDate($this->input->post('decanding_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('decanding_date'));
        //echo "<h1>".$decanding_date."</h1>";

        $invoice_date = (easyDate($this->input->post('invoice_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('invoice_date'));


        $trips_data = array(
            'customer_id'=>$this->input->post('customers'),
            'contractor_id'=>$this->input->post('contractors'),
            'company_id'=>$this->input->post('companies'),
            'tanker_id'=>$this->input->post('tankers'),
            //commissions
            'contractor_commission'=>$this->input->post('contractor_commission'),
            'company_commission_1'=>$this->input->post('company_commission_1'),
            'company_commission_2'=>$this->input->post('company_commission_2'),

            'entryDate'=>$entry_date,
            'email_date'=>$email_date,
            'filling_date'=>$filling_date,
            'receiving_date'=>$receiving_date,
            'stn_receiving_date'=>$stn_receiving_date,
            'decanding_date'=>$decanding_date,
            'invoice_date'=>$invoice_date,

            'driver_id_1'=>$this->input->post('drivers_1'),
            'driver_id_2'=>$this->input->post('drivers_2'),
            'driver_id_3'=>$this->input->post('drivers_3'),
            'invoice_number'=>$this->input->post('invoice_number'),
            'start_meter'=>$this->input->post('start_meter_reading'),
            'end_meter'=>$this->input->post('end_meter_reading'),
            'fuel_consumed'=>$this->input->post('fuel_consumed'),
            'type'=>$this->input->post('trip_type'),

            //'stn_number'=>$this->input->post('stn_number'),
            //'final_quantity' => $this->input->post('final_quantity'),
        );

        //deciding weather to update or insert?
        $db_error = true;
        if($trip_id == ''){
            $trips_insert_result = $this->db->insert('trips', $trips_data);
            if($trips_insert_result == true){
                $db_error = false;
            }
        }else{
            $this->db->where('trips.id',$trip_id);
            $this->db->update('trips',$trips_data);
        }
        $trips_details_data = array();
        for($counter = 1; $counter < $details_counter; $counter++){
            //calculating qty_at_destination adn after_decanding
            $initial_quantity = ($this->input->post('initial_product_quantity_'.$counter) != '')?$this->input->post('initial_product_quantity_'.$counter):0;
            $shortage_at_destination = ($this->input->post('shortage_at_destination_'.$counter))?$this->input->post('shortage_at_destination_'.$counter):0;
            $shortage_after_decanding = ($this->input->post('shortage_after_decanding_'.$counter))?$this->input->post('shortage_after_decanding_'.$counter):0;
            $quantity_at_destination = $initial_quantity - $shortage_at_destination;
            $quantity_after_decanding = $quantity_at_destination - $shortage_after_decanding;

            $company_freight_unit = ($this->input->post('company_freight_unit_'.$counter) == '')?$this->input->post('freight_unit_'.$counter):$this->input->post('company_freight_unit_'.$counter);

            $arr = array(
                'trip_id'=>($trip_id == '')?mysql_insert_id():$trip_id,
                'source'=>$this->input->post('sourceCity_'.$counter),
                'destination'=>$this->input->post('destinationCity_'.$counter),
                'product'=>$this->input->post('product_'.$counter),
                'product_quantity'=>$this->input->post('initial_product_quantity_'.$counter),
                'qty_at_destination'=>$quantity_at_destination,
                'qty_after_decanding'=>$quantity_after_decanding,
                'price_unit'=>$this->input->post('price_unit_'.$counter),
                'freight_unit'=>$this->input->post('freight_unit_'.$counter),
                'company_freight_unit'=>$company_freight_unit,
                'stn_number'=>$this->input->post('stn_number_'.$counter),
            );

            $trips_details_data_for_insertion_at_updation_time = array();

            //below segment will execute when trip was edited..
            if($trip_id != ''){
                //below we check weather this record should b updated or inserted...
                if($counter > $this->input->post('num_saved_trips_details')){
                    array_unshift($trips_details_data_for_insertion_at_updation_time,$arr);
                }
                $arr['id']=$this->input->post('trips_details_id_'.$counter);
            }

            array_unshift($trips_details_data,$arr);
        }

        //deciding weather to update or insert?
        if($trip_id == ''){
            if($db_error == false){
                $trips_details_insert_result = $this->db->insert_batch('trips_details', $trips_details_data);
                if($trips_details_insert_result == true){
                    return true;
                }
                return false;
            }else{
                return false;
            }
        }else{
            if(sizeof($trips_details_data_for_insertion_at_updation_time) >=1){
                $result = $this->db->insert_batch('trips_details', $trips_details_data_for_insertion_at_updation_time);
            }

            $result = $this->db->update_batch('trips_details',$trips_details_data, 'id');
            return true;
            //update your trip here.
        }

    }

    public function do_auto_accounting_on_trip_save($trip_id)
    {
        $trip_ids = array(
            $trip_id,
        );
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'');
        $trip = $trips[0];

        /*
         * fetching helping material
         */
        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C From Company',
        ))->result();
        $contractor_freight_ac_from_company_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Freight A/c',
        ))->result();
        $company_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Income A/c',
        ))->result();
        $income_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C To Customer',
        ))->result();
        $contractor_freight_ac_to_customer_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Commission A/C',
        ))->result();
        $contractor_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Service Charges',
        ))->result();
        $contractor_service_charges_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Customer Freight A/c',
        ))->result();
        $customer_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Commission A/c',
        ))->result();
        $company_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company W.h.t A/c',
        ))->result();
        $company_wht_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Commission To Company',
        ))->result();
        $contractor_commission_to_company_id = $result[0]->id;

        //fetching agents ids
        $company_id = $trip->company->id;
        $customer_id = $trip->customer->id;
        $contractor_id = $trip->contractor->id;
        $tanker_id = $trip->tanker->id;

        /************************************************/


        $entries_array = array();
        $voucher_trip_relation_array = array();

        //$this->db->trans_start();
        foreach($trip->trip_related_details as $detail)
        {
            /**************fetching necessary data*******************/
            $contractor_freight_amount = 0;
            $contractor_freight_amount = round($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()),3);
            $customer_freight_amount = round($detail->get_customer_freight_amount($trip->customer->freight), 3);
            $company_commission_amount = round($detail->get_company_commission_amount($trip->company->commission_1), 3);

            /***********************************************************************************************************/

            /********************************Contractor freight from company****************************/
            $voucher_data = array(
                'voucher_date'=>$trip->dates->filling_date,
                'detail'=>'System Generated Trip Voucher',
                'person_tid'=>'users.1',
                'trip_id'=>$trip->trip_id,
                'tanker_id'=>$trip->tanker->id,
                'trip_product_detail_id'=>$detail->product_detail_id,
                'transaction_column'=>'contractor_freight',
                'auto_generated'=>1,
            );
            //here voucher_inserted
            $inserted_voucher_id = 0;
            $this->db->insert('voucher_journal',$voucher_data);
            $voucher_data = null;
            $inserted_voucher_id = $this->db->insert_id();

            /********Computing voucher and trip relation data*/
            $voucher_trip_relation_data = array(
                'voucher_id'=>$inserted_voucher_id,
                'trip_detail_id'=>$detail->product_detail_id,
            );
            array_push($voucher_trip_relation_array, $voucher_trip_relation_data);
            /************************************************************************/

            //debit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$company_freight_ac_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>0,
                'related_company'=>$company_id,
                'debit_amount'=>$contractor_freight_amount,
                'credit_amount'=>0,
                'dr_cr'=>1,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);

            //credit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$contractor_freight_ac_from_company_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>$contractor_id,
                'related_company'=>0,
                'debit_amount'=>0,
                'credit_amount'=>$contractor_freight_amount,
                'dr_cr'=>0,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);
            /*******************************contractor freight from company completed**********************************************/


            /********************************Customer Freight entry starts****************************/
            $voucher_data = array(
                'voucher_date'=>$trip->dates->filling_date,
                'detail'=>'System Generated Trip Voucher',
                'person_tid'=>'users.1',
                'trip_id'=>$trip->trip_id,
                'tanker_id'=>$trip->tanker->id,
                'trip_product_detail_id'=>$detail->product_detail_id,
                'transaction_column'=>'customer_freight',
                'auto_generated'=>1,
            );
            //here voucher_inserted
            $inserted_voucher_id = 0;
            $this->db->insert('voucher_journal',$voucher_data);
            $voucher_data = null;
            $inserted_voucher_id = $this->db->insert_id();

            /********Computing voucher and trip relation data*/
            $voucher_trip_relation_data = array(
                'voucher_id'=>$inserted_voucher_id,
                'trip_detail_id'=>$detail->product_detail_id,
            );
            array_push($voucher_trip_relation_array, $voucher_trip_relation_data);
            /************************************************************************/

            //debit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$contractor_freight_ac_to_customer_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>$contractor_id,
                'related_company'=>0,
                'debit_amount'=>$customer_freight_amount,
                'credit_amount'=>0,
                'dr_cr'=>1,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);

            //credit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$customer_freight_ac_id,
                'related_other_agent'=>0,
                'related_customer'=>$customer_id,
                'related_contractor'=>0,
                'related_company'=>0,
                'debit_amount'=>0,
                'credit_amount'=>$customer_freight_amount,
                'dr_cr'=>0,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);
            /*******************************Customer freight from contractor completed**********************************************/


            /********************************Company Commission entry starts****************************/
//            $voucher_data = array(
//                'voucher_date'=>$trip->dates->filling_date,
//                'detail'=>'System Generated Trip Voucher',
//                'person_tid'=>'users.1',
//                'trip_id'=>$trip->trip_id,
//                'tanker_id'=>$trip->tanker->id,
//                'trip_product_detail_id'=>$detail->product_detail_id,
//                'transaction_column'=>'company_commission',
//                'auto_generated'=>1,
//            );
//            //here voucher_inserted
//            $inserted_voucher_id = 0;
//            $this->db->insert('voucher_journal',$voucher_data);
//            $voucher_data = null;
//            $inserted_voucher_id = $this->db->insert_id();
//
//            /********Computing voucher and trip relation data*/
//            $voucher_trip_relation_data = array(
//                'voucher_id'=>$inserted_voucher_id,
//                'trip_detail_id'=>$detail->product_detail_id,
//            );
//            array_push($voucher_trip_relation_array, $voucher_trip_relation_data);
//            /************************************************************************/
//
//            //debit entry
//            $voucher_entry = array(
//                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
//                'account_title_id'=>$contractor_commission_to_company_id,
//                'related_other_agent'=>0,
//                'related_customer'=>0,
//                'related_contractor'=>$contractor_id,
//                'related_company'=>0,
//                'debit_amount'=>$company_commission_amount,
//                'credit_amount'=>0,
//                'dr_cr'=>1,
//                'journal_voucher_id'=>$inserted_voucher_id,
//            );
//            array_push($entries_array, $voucher_entry);
//
//            //credit entry
//            $voucher_entry = array(
//                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
//                'account_title_id'=>$company_commission_ac_id,
//                'related_other_agent'=>0,
//                'related_customer'=>0,
//                'related_contractor'=>0,
//                'related_company'=>$company_id,
//                'debit_amount'=>0,
//                'credit_amount'=>$company_commission_amount,
//                'dr_cr'=>0,
//                'journal_voucher_id'=>$inserted_voucher_id,
//            );
//            array_push($entries_array, $voucher_entry);
            /*******************************contractor freight from company completed**********************************************/

        }

        //inserting the entries
        $this->db->insert_batch('voucher_entry',$entries_array);
        $this->db->insert_batch('trip_detail_voucher_relation', $voucher_trip_relation_array);

        //$this->db->trans_complete();

    }

    public function automatic_transactions_on_trip_edit($trip_id)
    {

        $trip_ids = array(
            $trip_id,
        );
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'');
        $trip = $trips[0];

        /*
         * fetching helping material
         */
        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C From Company',
        ))->result();
        $contractor_freight_ac_from_company_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Freight A/c',
        ))->result();
        $company_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Income A/c',
        ))->result();
        $income_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C To Customer',
        ))->result();
        $contractor_freight_ac_to_customer_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Commission A/C',
        ))->result();
        $contractor_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Service Charges',
        ))->result();
        $contractor_service_charges_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Customer Freight A/c',
        ))->result();
        $customer_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Commission A/c',
        ))->result();
        $company_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company W.h.t A/c',
        ))->result();
        $company_wht_ac_id = $result[0]->id;

        //fetching agents ids
        $company_id = $trip->company->id;
        $customer_id = $trip->customer->id;
        $contractor_id = $trip->contractor->id;
        $tanker_id = $trip->tanker->id;


        /***********************************************************************************************
         ***************************************Auto Voucher Editing Portion****************************/
        /***Hleper Arrays***/
        $voucher_entries_update_array = array();
        $vouchers_to_update_array = array();
        /*******************/

        foreach($trip->trip_related_details as $detail)
        {
            //here we will claculate the amounts needed to update
            $customer_freight_amount = round($detail->get_customer_freight_amount($trip->customer->freight), 3);
            //$total_customer_freight += $customer_freight_amount;
            $contractor_freight_amount = round($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()),3);

            /*************Getting voucher ids associated with this detail id***************/
            $this->db->select('trip_detail_voucher_relation.voucher_id');
            $this->db->from('trip_detail_voucher_relation');
            $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
            $this->db->where('voucher_journal.active',1);
            $this->db->where('trip_detail_voucher_relation.trip_detail_id',$detail->product_detail_id);

            /*
             * test section
             */
            $this->db->where('voucher_journal.auto_generated',1);
            /*******************************************************/

            $result = $this->db->get()->result();
            /***Hleper Arrays***/
            $voucher_ids_related_detail_id = array();
            /*******************/
            foreach($result as $record)
            {
                array_push($voucher_ids_related_detail_id, $record->voucher_id);
            }
            /******************************************************************************/

            /**************loop On Voucher Ids for updating records****************/
            foreach($voucher_ids_related_detail_id as $voucher_id)
            {
                /**********Lets fetch the voucher to update************/
                $temp_voucher_ids = array($voucher_id,);
                $temp_vouchers = $this->accounts_model->journal('users', 1, $temp_voucher_ids, "");
                $temp_voucher = $temp_vouchers[0];
                /******************************************************/

                //deciding the voucher date to save
                if($temp_voucher->auto_generated == 1){
                    $voucher_date = $trip->dates->filling_date;
                }else{
                    $voucher_date = $temp_voucher->voucher_date;
                }
                /////////////////////

                $voucher_data_to_update = array(
                    'id'=>$temp_voucher->voucher_id,
                    'voucher_date'=>$voucher_date,
                    'tanker_id'=>$tanker_id,
                );
                array_push($vouchers_to_update_array, $voucher_data_to_update);
                /*************************************************************/

                switch($temp_voucher->transaction_column)
                {
                    case 'customer_freight':
                        $dr_cr_amount = $customer_freight_amount;
                        break;
                    case 'contractor_freight':
                        $dr_cr_amount = $contractor_freight_amount;
                        break;
                    default:
                        $dr_cr_amount = "unknown";
                }
                /*************making the voucher_entries to update***********/
                foreach($temp_voucher->entries as $entry)
                {
                    //setting description
                    if($temp_voucher->auto_generated == 1)
                    {
                        $entry_description = "Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name;
                    }else{
                        $entry_description = $entry->description;
                    }

                    if($dr_cr_amount == "unknown"){
                        $dr_cr_amount = ($entry->dr_cr == 0)?$entry->credit:$entry->debit;
                    }

                    if($temp_voucher->auto_generated == 1){
                        $related_customer = $customer_id;
                        $related_contractor = $contractor_id;
                        $related_company = $company_id;
                    }else{
                        $related_customer = $entry->related_agent_id;
                        $related_contractor = $entry->related_agent_id;
                        $related_company = $entry->related_agent_id;
                    }

                    $temp_entry = array(
                        'id'=>$entry->id,
                        'description'=>$entry_description,
                        'related_customer'=>(($entry->related_agent == 'customers')?$related_customer:0),
                        'related_company'=>(($entry->related_agent == 'companies')?$related_company:0),
                        'related_contractor'=>(($entry->related_agent == 'carriage_contractors')?$related_contractor:0),
                        'credit_amount'=>(($entry->dr_cr == 'credit')?$dr_cr_amount:0),
                        'debit_amount'=>(($entry->dr_cr == 'debit')?$dr_cr_amount:0),
                    );
                    array_push($voucher_entries_update_array, $temp_entry);
                }
                /********************************/

                /********************************************/
            }
            /**********************************************************************/
        }

        if(sizeof($voucher_entries_update_array) > 0){
            $this->db->update_batch('voucher_journal',$vouchers_to_update_array,'id');
            $this->db->update_batch('voucher_entry',$voucher_entries_update_array,'id');
        }

        /***********************************************************************************************
        ***************************************Mass Editing Portion*************************************/

        /***Hleper Arrays***/
        $voucher_entries_update_array = array();
        $vouchers_to_update_array = array();
        /*******************/

        foreach($trip->trip_related_details as $detail)
        {
            /*************Getting voucher ids associated with this detail id***************/
            $this->db->select('trip_detail_voucher_relation.voucher_id');
            $this->db->from('trip_detail_voucher_relation');
            $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
            $this->db->where('voucher_journal.active',1);
            $this->db->where('trip_detail_voucher_relation.trip_detail_id',$detail->product_detail_id);

            /*
             * test section
             */
            $this->db->where('voucher_journal.auto_generated',0);
            /*******************************************************/

            $result = $this->db->get()->result();
            /***Hleper Arrays***/
            $voucher_ids_related_detail_id = array();
            /*******************/
            foreach($result as $record)
            {
                array_push($voucher_ids_related_detail_id, $record->voucher_id);
            }
            /******************************************************************************/

            /**************loop On Voucher Ids for updating records****************/
            foreach($voucher_ids_related_detail_id as $voucher_id)
            {
                //echo $voucher_id." / ";
                /**********selecting trip ids against the voucher id**********/
                $this->db->select('trip_detail_voucher_relation.trip_detail_id');
                $this->db->from('trip_detail_voucher_relation');
                $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
                $this->db->where('voucher_journal.active',1);
                $this->db->where('trip_detail_voucher_relation.voucher_id',$voucher_id);
                $result = $this->db->get()->result();
                /***Hleper Arrays***/
                $trip_detail_ids_related_to_voucher_id = array();
                /*******************/
                foreach($result as $record)
                {
                    array_push($trip_detail_ids_related_to_voucher_id, $record->trip_detail_id);
                }
                /*************************************************************/

                /********fetching trip_ids by trip_detail_ids***********/
                $this->db->select('trip_id');
                $this->db->distinct();
                $this->db->from('trips_details');
                $this->db->where_in('trips_details.id',$trip_detail_ids_related_to_voucher_id);
                $result_3 = $this->db->get()->result();
                $trip_ids_related_to_voucher_id = array();
                foreach($result_3 as $record_3)
                {
                    array_push($trip_ids_related_to_voucher_id, $record_3->trip_id);
                }
                /********************************************/

                /*declaring variables of amounts to update*/
                $total_customer_freight = 0;
                $total_customer_freight_without_shortage =0;
                $total_shortage_amount  = 0;
                $total_contractor_freight = 0;
                $total_contractor_freight_without_shortage = 0;
                $total_company_commission = 0;
                $total_company_wht = 0;
                $total_service_charges = 0;
                $total_contractor_commission = 0;
                $grand_total_freight = 0;
                /******************************************/
                $trips_2 = $this->trips_model->parametrized_trips_engine($trip_ids_related_to_voucher_id,'');
                foreach($trips_2 as $trip_2)
                {
                    foreach($trip_2->trip_related_details as $detail_2)
                    {
                        //here we will claculate the amounts needed to update
                        $customer_freight_amount = round($detail_2->get_customer_freight_amount($trip_2->customer->freight), 3);
                        $total_customer_freight += $customer_freight_amount;

                        $customer_freight_amount_without_shortage = round($customer_freight_amount - $detail_2->getShortageAmount(), 3);
                        $total_customer_freight_without_shortage += $customer_freight_amount_without_shortage;

                        $shortage_amount = $detail_2->getShortageAmount();
                        $total_shortage_amount += $shortage_amount;

                        $contractor_freight_amount = round($detail_2->get_contractor_freight_amount_according_to_company($trip_2->get_contractor_freight_according_to_company()),3);
                        $total_contractor_freight += $contractor_freight_amount;

                        $contractor_freight_amount_without_shortage = round($contractor_freight_amount - $detail_2->getShortageAmount() ,3);
                        $total_contractor_freight_without_shortage += $contractor_freight_amount_without_shortage;

                        $contractor_commission = $trip_2->contractor->commission_1 - $trip_2->company->wht - $trip_2->company->commission_1;
                        $contractor_commission_amount = $detail_2->get_contractor_commission_amount($contractor_commission);
                        $total_contractor_commission += $contractor_commission_amount;

                        $company_commission_amount = round($detail_2->get_company_commission_amount($trip_2->company->commission_1), 3);
                        $total_company_commission += $company_commission_amount;

                        $wht_amount = $detail_2->get_wht_amount($trip_2->company->wht);
                        $total_company_wht += $wht_amount;

                        $service_charges = round($detail_2->contractor_benefits(), 4);
                        $total_service_charges += $service_charges;

                        $total_freight_for_company = $detail_2->get_total_freight_for_company();
                        $grand_total_freight += $total_freight_for_company;
                    }
                }
                /**********Lets fetch the voucher to update************/
                $temp_voucher_ids = array($voucher_id,);
                $temp_vouchers = $this->accounts_model->journal('users', 1, $temp_voucher_ids, "");
                $temp_voucher = $temp_vouchers[0];
                /******************************************************/

                /*
                 * here we will update what ever we want to do
                 */

                //deciding the voucher date to save
                if($temp_voucher->auto_generated == 1){
                    $voucher_date = $trip->dates->filling_date;
                }else{
                    $voucher_date = $temp_voucher->voucher_date;
                }
                /////////////////////

                $voucher_data_to_update = array(
                    'id'=>$temp_voucher->voucher_id,
                    'voucher_date'=>$voucher_date,
                );
                array_push($vouchers_to_update_array, $voucher_data_to_update);
                /*************************************************************/

                switch($temp_voucher->transaction_column)
                {
                    case 'customer_freight':
                        $dr_cr_amount = $total_customer_freight;
                        break;
                    case 'customer_freight_without_shortage':
                        $dr_cr_amount = $total_customer_freight_without_shortage;
                        break;
                    case 'shortage_amount':
                        $dr_cr_amount = $total_shortage_amount;
                        break;
                    case 'contractor_freight':
                        $dr_cr_amount = $total_contractor_freight;
                        break;
                    case 'contractor_freight_without_shortage':
                        $dr_cr_amount = $total_contractor_freight_without_shortage;
                        break;
                    case 'company_total_freight':
                        $dr_cr_amount = $grand_total_freight;
                        break;
                    case 'company_wht':
                        $dr_cr_amount = $total_company_wht;
                        break;
                    case 'company_commission':
                        $dr_cr_amount = $total_company_commission;
                        break;
                    case 'contractor_commission':
                        $dr_cr_amount = $total_contractor_commission;
                        break;
                    case 'service_charges':
                        $dr_cr_amount = $total_service_charges;
                        break;
                    default:
                        $dr_cr_amount = "unknown";
                }

                /*************making the voucher_entries to update***********/
                foreach($temp_voucher->entries as $entry)
                {
                    if($dr_cr_amount == "unknown"){
                        $dr_cr_amount = ($entry->dr_cr == 0)?$entry->credit:$entry->debit;
                    }

                    if($temp_voucher->auto_generated == 1){
                        $related_customer = $customer_id;
                        $related_contractor = $contractor_id;
                        $related_company = $company_id;
                    }else{
                        $related_customer = $entry->related_agent_id;
                        $related_contractor = $entry->related_agent_id;
                        $related_company = $entry->related_agent_id;
                    }

                    $temp_entry = array(
                        'id'=>$entry->id,
                        'related_customer'=>(($entry->related_agent == 'customers')?$related_customer:0),
                        'related_company'=>(($entry->related_agent == 'companies')?$related_company:0),
                        'related_contractor'=>(($entry->related_agent == 'carriage_contractors')?$related_contractor:0),
                        'credit_amount'=>(($entry->dr_cr == 'credit')?$dr_cr_amount:0),
                        'debit_amount'=>(($entry->dr_cr == 'debit')?$dr_cr_amount:0),
                    );
                    array_push($voucher_entries_update_array, $temp_entry);
                }
                /********************************/

                /********************************************/
            }

            /**********************************************************************/
        }

        if(sizeof($voucher_entries_update_array) > 0){
            $this->db->update_batch('voucher_journal',$vouchers_to_update_array,'id');
            $this->db->update_batch('voucher_entry',$voucher_entries_update_array,'id');
        }

    }

    public function inserted_vouchers_for_newly_added_products($trip_id)
    {
        /*
         * ------------------------------------
         *  Product Ids which are saved with
         *  auto generated vouchers.
         * ------------------------------------
         */
        $this->db->select('trip_detail_voucher_relation.trip_detail_id');
        $this->db->from('trip_detail_voucher_relation');
        $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id');
        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.trip_id'=>$trip_id,
            'voucher_journal.auto_generated'=>1,
        ));
        $result = $this->db->get()->result();
        $detail_ids = array();
        foreach($result as $record)
        {
            array_push($detail_ids, $record->trip_detail_id);
        }
        /*------------------------------------------*/

        $trip_ids = array(
            $trip_id,
        );
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'');
        $trip = $trips[0];

        /*
         * fetching helping material
         */
        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C From Company',
        ))->result();
        $contractor_freight_ac_from_company_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Freight A/c',
        ))->result();
        $company_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Income A/c',
        ))->result();
        $income_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Freight A/C To Customer',
        ))->result();
        $contractor_freight_ac_to_customer_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Commission A/C',
        ))->result();
        $contractor_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Service Charges',
        ))->result();
        $contractor_service_charges_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Customer Freight A/c',
        ))->result();
        $customer_freight_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company Commission A/c',
        ))->result();
        $company_commission_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Company W.h.t A/c',
        ))->result();
        $company_wht_ac_id = $result[0]->id;

        $this->db->select("id");
        $result = $this->db->get_where('account_titles',array(
            'title'=>'Contractor Commission To Company',
        ))->result();
        $contractor_commission_to_company_id = $result[0]->id;

        //fetching agents ids
        $company_id = $trip->company->id;
        $customer_id = $trip->customer->id;
        $contractor_id = $trip->contractor->id;
        $tanker_id = $trip->tanker->id;

        /************************************************/


        $entries_array = array();
        $voucher_trip_relation_array = array();

        //$this->db->trans_start();
        foreach($trip->trip_related_details as $detail)
        {
            /*
             * ------------------------------------
             * Skipping loop iteration
             * ------------------------------------
             * if auto voucher already saved than
             * skip the current iteration.
             */
               if(in_array($detail->product_detail_id, $detail_ids)){
                   continue;
               }
            /*--------------------------------------*/

            /**************fetching necessary data*******************/
            $contractor_freight_amount = 0;
            $contractor_freight_amount = round($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()),3);
            $customer_freight_amount = round($detail->get_customer_freight_amount($trip->customer->freight), 3);
            $company_commission_amount = round($detail->get_company_commission_amount($trip->company->commission_1), 3);

            /***********************************************************************************************************/

            /********************************Contractor freight from company****************************/
            $voucher_data = array(
                'voucher_date'=>$trip->dates->filling_date,
                'detail'=>'System Generated Trip Voucher',
                'person_tid'=>'users.1',
                'trip_id'=>$trip->trip_id,
                'tanker_id'=>$trip->tanker->id,
                'trip_product_detail_id'=>$detail->product_detail_id,
                'transaction_column'=>'contractor_freight',
                'auto_generated'=>1,
            );
            //here voucher_inserted
            $inserted_voucher_id = 0;
            $this->db->insert('voucher_journal',$voucher_data);
            $voucher_data = null;
            $inserted_voucher_id = $this->db->insert_id();

            /********Computing voucher and trip relation data*/
            $voucher_trip_relation_data = array(
                'voucher_id'=>$inserted_voucher_id,
                'trip_detail_id'=>$detail->product_detail_id,
            );
            array_push($voucher_trip_relation_array, $voucher_trip_relation_data);
            /************************************************************************/

            //debit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$company_freight_ac_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>0,
                'related_company'=>$company_id,
                'debit_amount'=>$contractor_freight_amount,
                'credit_amount'=>0,
                'dr_cr'=>1,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);

            //credit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$contractor_freight_ac_from_company_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>$contractor_id,
                'related_company'=>0,
                'debit_amount'=>0,
                'credit_amount'=>$contractor_freight_amount,
                'dr_cr'=>0,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);
            /*******************************contractor freight from company completed**********************************************/


            /********************************Customer Freight entry starts****************************/
            $voucher_data = array(
                'voucher_date'=>$trip->dates->filling_date,
                'detail'=>'System Generated Trip Voucher',
                'person_tid'=>'users.1',
                'trip_id'=>$trip->trip_id,
                'tanker_id'=>$trip->tanker->id,
                'trip_product_detail_id'=>$detail->product_detail_id,
                'transaction_column'=>'customer_freight',
                'auto_generated'=>1,
            );
            //here voucher_inserted
            $inserted_voucher_id = 0;
            $this->db->insert('voucher_journal',$voucher_data);
            $voucher_data = null;
            $inserted_voucher_id = $this->db->insert_id();

            /********Computing voucher and trip relation data*/
            $voucher_trip_relation_data = array(
                'voucher_id'=>$inserted_voucher_id,
                'trip_detail_id'=>$detail->product_detail_id,
            );
            array_push($voucher_trip_relation_array, $voucher_trip_relation_data);
            /************************************************************************/

            //debit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$contractor_freight_ac_to_customer_id,
                'related_other_agent'=>0,
                'related_customer'=>0,
                'related_contractor'=>$contractor_id,
                'related_company'=>0,
                'debit_amount'=>$customer_freight_amount,
                'credit_amount'=>0,
                'dr_cr'=>1,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);

            //credit entry
            $voucher_entry = array(
                'description'=>"Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name,
                'account_title_id'=>$customer_freight_ac_id,
                'related_other_agent'=>0,
                'related_customer'=>$customer_id,
                'related_contractor'=>0,
                'related_company'=>0,
                'debit_amount'=>0,
                'credit_amount'=>$customer_freight_amount,
                'dr_cr'=>0,
                'journal_voucher_id'=>$inserted_voucher_id,
            );
            array_push($entries_array, $voucher_entry);
            /*******************************Customer freight from contractor completed**********************************************/

        }

        //inserting the entries

        if(sizeof($entries_array) > 0)
        {
            $this->db->insert_batch('voucher_entry',$entries_array);
            $this->db->insert_batch('trip_detail_voucher_relation', $voucher_trip_relation_array);
        }

    }

    public function insert_other_trip($trip_id= '', $trip_type){
        $details_counter = $this->input->post('pannel_count');
        //setting dates
        $entry_date = (easyDate($this->input->post('entry_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('entry_date'));
        $email_date = (easyDate($this->input->post('email_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('email_date'));
        $filling_date = (easyDate($this->input->post('filling_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('filling_date'));
        $receiving_date = (easyDate($this->input->post('receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('receiving_date'));
        $stn_receiving_date = (easyDate($this->input->post('stn_receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('stn_receiving_date'));
        $decanding_date = (easyDate($this->input->post('decanding_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('decanding_date'));
        //echo "<h1>".$decanding_date."</h1>";

        $invoice_date = (easyDate($this->input->post('invoice_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('invoice_date'));


        $trips_data = array(
            'customer_id'=>$this->input->post('customers'),
            'contractor_id'=>$this->input->post('contractors'),
            'company_id'=>$this->input->post('companies'),
            'tanker_id'=>$this->input->post('tankers'),
            //commissions
            'contractor_commission'=>$this->input->post('contractor_commission'),
            'company_commission_1'=>$this->input->post('company_commission_1'),
            'company_commission_2'=>$this->input->post('company_commission_2'),

            'entryDate'=>$entry_date,
            'email_date'=>$email_date,
            'filling_date'=>$filling_date,
            'receiving_date'=>$receiving_date,
            'stn_receiving_date'=>$stn_receiving_date,
            'decanding_date'=>$decanding_date,
            'invoice_date'=>$invoice_date,

            'driver_id_1'=>$this->input->post('drivers_1'),
            'driver_id_2'=>$this->input->post('drivers_2'),
            'driver_id_3'=>$this->input->post('drivers_3'),
            'invoice_number'=>$this->input->post('invoice_number'),
            'start_meter'=>$this->input->post('start_meter_reading'),
            'end_meter'=>$this->input->post('end_meter_reading'),
            'fuel_consumed'=>$this->input->post('fuel_consumed'),
            'type'=>$this->input->post('trip_type'),
            //'stn_number'=>$this->input->post('stn_number'),
            //'final_quantity' => $this->input->post('final_quantity'),
        );
        //deciding weather to update or insert?
        $db_error = true;
        $inserted_trip_id = 0;
        if($trip_id == ''){
            $trips_insert_result = $this->db->insert('trips', $trips_data);
            if($trips_insert_result == true){
                $inserted_trip_id = $this->db->insert_id();
                $db_error = false;
            }
        }else{
            $this->db->where('trips.id',$trip_id);
            $this->db->update('trips',$trips_data);
        }
        $trips_details_data = array();
        for($counter = 1; $counter < $details_counter; $counter++){
            //calculating qty_at_destination adn after_decanding
            $initial_quantity = ($this->input->post('initial_product_quantity_'.$counter) != '')?$this->input->post('initial_product_quantity_'.$counter):0;
            $shortage_at_destination = ($this->input->post('shortage_at_destination_'.$counter))?$this->input->post('shortage_at_destination_'.$counter):0;
            $shortage_after_decanding = ($this->input->post('shortage_after_decanding_'.$counter))?$this->input->post('shortage_after_decanding_'.$counter):0;
            $quantity_at_destination = $initial_quantity - $shortage_at_destination;
            $quantity_after_decanding = $quantity_at_destination - $shortage_after_decanding;

            $company_freight_unit = ($this->input->post('company_freight_unit_'.$counter) == '')?$this->input->post('freight_unit_'.$counter):$this->input->post('company_freight_unit_'.$counter);

            //finding necessary reqs //
            switch($trip_type)
            {
                case "local_cmp":
                    $route_input = $this->input->post('route_'.$counter);
                    $route_input_parts = explode('_',$route_input);

                    $source_id = $route_input_parts[0];
                    $destination_id = $route_input_parts[1];
                    $product_id = $route_input_parts[2];

                    break;
                case "local_self":
                    $route_input = $this->input->post('route_'.$counter);
                    $route_input_parts = explode('_',$route_input);

                    $source_id = $route_input_parts[0];
                    $destination_id = $route_input_parts[1];
                    $product_id = $route_input_parts[2];

                    break;

            }

            /////////////////////////
            $arr = array(
                'trip_id'=>($trip_id == '')?$inserted_trip_id:$trip_id,
                'source'=>$source_id,
                'destination'=>$destination_id,
                'product'=>$product_id,
                'product_quantity'=>$this->input->post('initial_product_quantity_'.$counter),
                'qty_at_destination'=>$quantity_at_destination,
                'qty_after_decanding'=>$quantity_after_decanding,
                'price_unit'=>$this->input->post('price_unit_'.$counter),
                'freight_unit'=>$this->input->post('freight_unit_'.$counter),
                'company_freight_unit'=>$company_freight_unit,
                'stn_number'=>$this->input->post('stn_number_'.$counter),
            );

            $trips_details_data_for_insertion_at_updation_time = array();

            //below segment will execute when trip was edited..
            if($trip_id != ''){
                //below we check weather this record should b updated or inserted...
                if($counter > $this->input->post('num_saved_trips_details')){
                    array_unshift($trips_details_data_for_insertion_at_updation_time,$arr);
                }
                $arr['id']=$this->input->post('trips_details_id_'.$counter);
            }

            array_unshift($trips_details_data,$arr);
        }

        //deciding weather to update or insert?
        if($trip_id == ''){
            if($db_error == false){
                $trips_details_insert_result = $this->db->insert_batch('trips_details', $trips_details_data);
                if($trips_details_insert_result == true){
                    return true;
                }
                return false;
            }else{
                return false;
            }
        }else{
            if(sizeof($trips_details_data_for_insertion_at_updation_time) >=1){
                $result = $this->db->insert_batch('trips_details', $trips_details_data_for_insertion_at_updation_time);
            }

            $result = $this->db->update_batch('trips_details',$trips_details_data, 'id');
            return true;
            //update your trip here.
        }

    }

    public function update_other_trip($trip_id= '', $trip_type){
        $details_counter = $this->input->post('pannel_count');
        //setting dates
        $entry_date = (easyDate($this->input->post('entry_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('entry_date'));
        $email_date = (easyDate($this->input->post('email_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('email_date'));
        $filling_date = (easyDate($this->input->post('filling_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('filling_date'));
        $receiving_date = (easyDate($this->input->post('receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('receiving_date'));
        $stn_receiving_date = (easyDate($this->input->post('stn_receiving_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('stn_receiving_date'));
        $decanding_date = (easyDate($this->input->post('decanding_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('decanding_date'));
        //echo "<h1>".$decanding_date."</h1>";

        $invoice_date = (easyDate($this->input->post('invoice_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('invoice_date'));


        $trips_data = array(
            'customer_id'=>$this->input->post('customers'),
            'contractor_id'=>$this->input->post('contractors'),
            'company_id'=>$this->input->post('companies'),
            'tanker_id'=>$this->input->post('tankers'),
            //commissions
            'contractor_commission'=>$this->input->post('contractor_commission'),
            'company_commission_1'=>$this->input->post('company_commission_1'),
            'company_commission_2'=>$this->input->post('company_commission_2'),

            'entryDate'=>$entry_date,
            'email_date'=>$email_date,
            'filling_date'=>$filling_date,
            'receiving_date'=>$receiving_date,
            'stn_receiving_date'=>$stn_receiving_date,
            'decanding_date'=>$decanding_date,
            'invoice_date'=>$invoice_date,

            'driver_id_1'=>$this->input->post('drivers_1'),
            'driver_id_2'=>$this->input->post('drivers_2'),
            'driver_id_3'=>$this->input->post('drivers_3'),
            'invoice_number'=>$this->input->post('invoice_number'),
            'start_meter'=>$this->input->post('start_meter_reading'),
            'end_meter'=>$this->input->post('end_meter_reading'),
            'fuel_consumed'=>$this->input->post('fuel_consumed'),
            'type'=>$this->input->post('trip_type'),
            //'stn_number'=>$this->input->post('stn_number'),
            //'final_quantity' => $this->input->post('final_quantity'),
        );
        //deciding weather to update or insert?
        $db_error = true;
        $inserted_trip_id = 0;
        if($trip_id == ''){
            $trips_insert_result = $this->db->insert('trips', $trips_data);
            if($trips_insert_result == true){
                $inserted_trip_id = $this->db->insert_id();
                $db_error = false;
            }
        }else{
            $this->db->where('trips.id',$trip_id);
            $this->db->update('trips',$trips_data);
        }
        $trips_details_data = array();
        $trip_details_ids = array();
        for($counter = 1; $counter < $details_counter; $counter++){
            //calculating qty_at_destination adn after_decanding
            $initial_quantity = ($this->input->post('initial_product_quantity_'.$counter) != '')?$this->input->post('initial_product_quantity_'.$counter):0;
            $shortage_at_destination = ($this->input->post('shortage_at_destination_'.$counter))?$this->input->post('shortage_at_destination_'.$counter):0;
            $shortage_after_decanding = ($this->input->post('shortage_after_decanding_'.$counter))?$this->input->post('shortage_after_decanding_'.$counter):0;
            $quantity_at_destination = $initial_quantity - $shortage_at_destination;
            $quantity_after_decanding = $quantity_at_destination - $shortage_after_decanding;

            $company_freight_unit = ($this->input->post('company_freight_unit_'.$counter) == '')?$this->input->post('freight_unit_'.$counter):$this->input->post('company_freight_unit_'.$counter);

            //finding necessary reqs //
            switch($trip_type)
            {
                case "local_cmp":
                    $route_id = $this->input->post('route_'.$counter);
                    $route = $this->routes_model->route($route_id);
                    $source_id = $route->sourceId;
                    $destination_id = $route->destinationId;
                    $product_id = $route->productId;

                    break;
                case "local_self":
                    $route_id = $this->input->post('route_'.$counter);
                    $route = $this->routes_model->route($route_id);
                    $source_id = $route->sourceId;
                    $destination_id = $route->destinationId;
                    $product_id = $route->productId;

                    break;

            }

            /////////////////////////
            $arr = array(
                'trip_id'=>($trip_id == '')?$inserted_trip_id:$trip_id,
                'source'=>$source_id,
                'destination'=>$destination_id,
                'product'=>$product_id,
                'product_quantity'=>$this->input->post('initial_product_quantity_'.$counter),
                'qty_at_destination'=>$quantity_at_destination,
                'qty_after_decanding'=>$quantity_after_decanding,
                'price_unit'=>$this->input->post('price_unit_'.$counter),
                'freight_unit'=>$this->input->post('freight_unit_'.$counter),
                'company_freight_unit'=>$company_freight_unit,
                'stn_number'=>$this->input->post('stn_number_'.$counter),
            );

            $trips_details_data_for_insertion_at_updation_time = array();

            //below segment will execute when trip was edited..
            if($trip_id != ''){
                //below we check weather this record should b updated or inserted...
                if($counter > $this->input->post('num_saved_trips_details')){
                    array_unshift($trips_details_data_for_insertion_at_updation_time,$arr);
                }
                $arr['id']=$this->input->post('trips_details_id_'.$counter);
                array_push($trip_details_ids, $this->input->post('trips_details_id_'.$counter));
            }

            array_unshift($trips_details_data,$arr);
        }

        /*
         * -----------------------------------
         * 4/9/2015 | Delete Trip Details
         * -----------------------------------
         * deleting those details which user
         * wants to be deleted.
         * */

        //deleting details
        $this->db->where_not_in('trips_details.id',$trip_details_ids);
        $this->db->where('trips_details.trip_id',$trip_id);
        $this->db->from('trips_details');
        $this->db->delete();

        //deleting vouchers
        $this->db->where_not_in('voucher_journal.trip_product_detail_id',$trip_details_ids);
        $this->db->where('voucher_journal.trip_id',$trip_id);
        $this->db->where('voucher_journal.auto_generated',1);
        $voucher_data = array(
            'active'=>0,
        );
        $this->db->update('voucher_journal',$voucher_data);
        /*---------------------~Ends~------------------------------*/

        //deciding weather to update or insert?
        if(sizeof($trips_details_data_for_insertion_at_updation_time) >=1){
             $result = $this->db->insert_batch('trips_details', $trips_details_data_for_insertion_at_updation_time);
        }
        $result = $this->db->update_batch('trips_details',$trips_details_data, 'id');
        return true;

    }

    public function meter_reading($tanker_id)
    {
        $meter_reading['start'] = 0;
        $meter_reading['end'] = 0;

        $this->db->select('trips.start_meter, trips.end_meter');
        $this->db->limit(1,0);
        $this->db->order_by('trips.id','desc');
        $this->db->where('trips.tanker_id',$tanker_id);
        $this->db->where('trips.active',1);
        $trips = $this->db->get('trips')->result();
        if(sizeof($trips) >= 1){
            $trip = $trips[0];
            $meter_reading['start'] = $trip->start_meter;
            $meter_reading['end'] = $trip->end_meter;
        }
        return $meter_reading;
    }

    public function trip_with_mass_payment($trip_id)
    {
        $this->db->select('id');
        $result = $this->db->get_where('trips_details', array(
            'trip_id'=>$trip_id,
        ))->result();

        $trip_detail_ids = array();
        foreach($result as $record)
        {
            array_push($trip_detail_ids, $record->id);
        }

        //selecting mass vouchers
        $this->db->select('voucher_journal.id');
        $this->db->distinct();
        $this->db->from('trip_detail_voucher_relation');
        $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
        $this->db->where('voucher_journal.active',1);
        $this->db->where(array(
            'voucher_journal.auto_generated'=>0,
            'voucher_journal.transaction_column !='=>'',
        ));
        $this->db->where_in('trip_detail_voucher_relation.trip_detail_id', $trip_detail_ids);

        $result = $this->db->get()->num_rows();
        if($result > 0)
        {
            return true;
        }
        return false;

    }

    public function expenses($trip_id)
    {
        $this->db->select('account_titles.title, voucher_journal.id as voucher_id,
            voucher_entry.debit_amount , voucher_entry.credit_amount, voucher_entry.dr_cr,
        ');
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $this->db->join('account_titles', 'account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.trip_id'=>$trip_id,
            'account_titles.type'=>'expense',
        ));
        $result = $this->db->get()->result();
        return $result;
    }

    /*
     * ----------------------------------------------------
     * Safely and completely remove the trip
     * ---------------------------------------------------
     */
    public function safely_remove_trip($trip_id)
    {
        $this->db->trans_start();
        $this->helper_model->safe_delete('trips',$trip_id);
        $this->db->where(array(
            'voucher_journal.trip_id'=>$trip_id,
            'voucher_journal.auto_generated'=>1,
        ));
        $this->db->delete('voucher_journal');
        $this->db->trans_complete();
        if($this->db->trans_status() == true)
        {
            return true;
        }
        return false;
    }
}