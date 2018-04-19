<?php

class
Accounts_model extends Parent_Model {

    public function __construct(){
        parent::__construct();
    }

    public function driver($id){
        $this->db->order_by("entryDate", "desc");
        $accounts = $this->db->get_where('drivers_expenses', array('driver_id'=> $id))->result();
        return $accounts;
    }


    /*

     * below is about customers accounts and payments

     */

    public function customer_payments($trip_detail_id='', $customer_id){
        if($trip_detail_id == ''){
            $query = $this->db->get_where('customer_accounts', array('customer_id', $customer_id));
        }else{
            $query = $this->db->get_where('customer_accounts', array('trip_detail_id'=>$trip_detail_id, 'customer_id'=>$customer_id));
        }
        $result = $query->result();
        return $result;
    }

    public function company_payments($trip_detail_id='', $company_id){
        if($trip_detail_id == ''){
            $query = $this->db->get_where('company_accounts', array('company_id', $company_id));
        }else{
            $query = $this->db->get_where('company_accounts', array('trip_detail_id'=>$trip_detail_id, 'company_id'=>$company_id));
        }
        $result = $query->result();
        return $result;
    }

    public function contractor_payments($trip_detail_id='', $contractor_id){
        if($trip_detail_id == ''){
            $query = $this->db->get_where('contractor_accounts', array('contractor_id', $contractor_id));
        }else{
            $query = $this->db->get_where('contractor_accounts', array('trip_detail_id'=>$trip_detail_id, 'contractor_id'=>$contractor_id));
        }
        $result = $query->result();
        return $result;
    }

    public function add_customer_payment(){
        $payment_date = (easyDate($this->input->post('payment_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('payment_date'));
        $data = array(
            'trip_detail_id'=>$this->input->post('trip_detail_id'),
            'customer_id'=>$this->input->post('customer_id'),
            'payment_date'=>$payment_date,
            'amount'=>$this->input->post('amount'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('customer_accounts', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function add_company_payment(){
        $payment_date = (easyDate($this->input->post('payment_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('payment_date'));
        $data = array(
            'trip_detail_id'=>$this->input->post('trip_detail_id'),
            'company_id'=>$this->input->post('company_id'),
            'payment_date'=>$payment_date,
            'amount'=>$this->input->post('amount'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('company_accounts', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function delete_company_payment($payment_id)
    {
        return $this->helper_model->delete_record('company_accounts', $payment_id);
    }

    public function company_mass_payment($trip_ids)
    {
        $payments = array();

        $trip_ids = explode('_',$trip_ids);
        $trips = $this->trips_model->test_trips_details($trip_ids);
        foreach($trips as $trip)
        {
            foreach($trip->trip_related_details as $detail){
                $remaining = ($detail->get_company_commission_amount($trip->company->commission_1) - $detail->get_paid_to_company());
                if($remaining != 0){
                    $entry = array(
                        'company_id'=>$trip->company->id,
                        'trip_detail_id'=>$detail->product_detail_id,
                        'amount'=>$remaining,
                        'payment_date'=>$this->input->post('payment_date'),
                    );
                    array_push($payments, $entry);
                }
            }
        }
        $this->db->insert_batch('company_accounts', $payments);

        return true;

    }

    public function delete_customer_payment($payment_id)
    {
        return $this->helper_model->delete_record('customer_accounts', $payment_id);
    }
    public function customer_mass_payment($trip_ids)
    {
        $payments = array();

        $trip_ids = explode('_',$trip_ids);
        $trips = $this->trips_model->test_trips_details($trip_ids);
        foreach($trips as $trip)
        {
            foreach($trip->trip_related_details as $detail){
                $remaining = ($detail->get_customer_freight_amount($trip->customer->freight) - $detail->get_paid_to_customer());
                if($remaining != 0){
                    $entry = array(
                        'customer_id'=>$trip->customer->id,
                        'trip_detail_id'=>$detail->product_detail_id,
                        'amount'=>$remaining,
                        'payment_date'=>$this->input->post('payment_date'),
                    );
                    array_push($payments, $entry);
                }
            }
        }
        $this->db->insert_batch('customer_accounts', $payments);

        return true;
    }

    public function delete_contractor_payment($payment_id)
    {
        return $this->helper_model->delete_record('contractor_accounts', $payment_id);
    }
    public function contractor_mass_payment($trip_ids)
    {
        $payments = array();

        $trip_ids = explode('_',$trip_ids);
        $trips = $this->trips_model->test_trips_details($trip_ids);
        foreach($trips as $trip)
        {
            foreach($trip->trip_related_details as $detail){
                $remaining = ($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()) - $detail->get_paid_to_contractor());
                if($remaining != 0){
                    $entry = array(
                        'contractor_id'=>$trip->contractor->id,
                        'trip_detail_id'=>$detail->product_detail_id,
                        'amount'=>$remaining,
                        'payment_date'=>$this->input->post('payment_date'),
                    );
                    array_push($payments, $entry);
                }
            }
        }
        $this->db->insert_batch('contractor_accounts', $payments);

        return true;

    }

    public function add_contractor_payment(){
        $payment_date = (easyDate($this->input->post('payment_date')) == '1970-01-01')? '0000-00-00': easyDate($this->input->post('payment_date'));
        $data = array(
            'trip_detail_id'=>$this->input->post('trip_detail_id'),
            'contractor_id'=>$this->input->post('contractor_id'),
            'payment_date'=>$payment_date,
            'amount'=>$this->input->post('amount'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('contractor_accounts', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function contractor_services_charges($trip_id, $trip_detail_id, $product_type='white_oil')
    {
        $trip_ids = array();
        array_push($trip_ids, $trip_id);
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'trips_welcome');
        if(sizeof($trips >= 1)){
            $trip = $trips[0];
            foreach($trip->trip_related_details as $detail){
                if($detail->product_detail_id == $trip_detail_id){
                    $data = array();
                    if($product_type == 'black_oil')
                    {
                        $data['total_freight_for_company'] = $detail->get_total_freight_cmp_for_black_oil();
                        $data['company_commission'] = $trip->company->commission_1;
                        $data['company_commission_amount'] = $detail->get_company_commission_amount_for_black_oil();
                        $data['wht'] = $trip->company->wht;
                        $data['wht_amount'] = $detail->get_wht_amount_for_black_oil();
                        $data['contractor_commission'] = $detail->get_contractor_commission_percentage();
                        $data['contractor_commission_amount'] = $detail->get_contractor_commission_amount_for_black_oil();
                        $data['total_freight_for_customer'] = $detail->get_total_freight_cst_for_black_oil();
                        $data['customer_freight'] = $trip->customer->freight;
                        $data['customer_freight_amount'] = $detail->get_customer_freight_amount_for_black_oil();
                    }
                    else
                    {
                        $data['total_freight_for_company'] = $detail->get_total_freight_for_company();
                        $data['company_commission'] = $trip->company->commission_1;
                        $data['company_commission_amount'] = $detail->get_company_commission_amount($trip->company->commission_1);
                        $data['wht'] = $trip->company->wht;
                        $data['wht_amount'] = $detail->get_wht_amount($trip->company->wht);
                        $data['contractor_commission'] = $trip->contractor->commission_1 - $trip->company->commission_1 - $trip->company->wht;
                        $data['contractor_commission_amount'] = $detail->get_contractor_commission_amount($data['contractor_commission']);
                        $data['total_freight_for_customer'] = $detail->get_total_freight_for_customer();
                        $data['customer_freight'] = $trip->customer->freight;
                        $data['customer_freight_amount'] = $detail->get_customer_freight_amount($trip->customer->freight);
                    }

                    return $data;
                }
            }
        }
        return null;
    }

    public function customer($c_id, $keys, $limit, $start){

        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['company'] != '' ){
            $this->db->where('trips.company_id', $keys['company']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where('trips.contractor_id', $keys['contractor']);
        }
        if($keys['customer_freight_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['customer_freight_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        $trips_ids = array();
        foreach($trips as $trip){
            array_push($trips_ids, $trip->trip_id);
        }

        $final_trips = $this->trips_model->parametrized_trips_engine($trips_ids, "customer_accounts");
        usort($final_trips, array("Sorting_Model", "sort_customer_accounts"));
        $start = ($start == '')?0:$start;
        return array_slice($final_trips, $start,($limit));
    }

    public function count_searched_customer_accounts($c_id, $keys)
    {

        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['company'] != '' ){
            $this->db->where('trips.company_id', $keys['company']);
        }
        if($keys['contractor'] != '' ){
            $this->db->where('trips.contractor_id', $keys['contractor']);
        }
        if($keys['customer_freight_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['customer_freight_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        return sizeof($trips);
    }

    public function company($c_id, $keys, $limit, $start){

        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['freight_unit'] != ''){
            $this->db->where('trips_details.company_freight_unit',$keys['freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('trips.company_commission_2',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('trips.company_commission_1',$keys['company_commission']);
        }
        if($keys['company_commission_status'] == 'unpaid'){
            $this->db->where('company_accounts.amount',null);
        }
        if($keys['company_commission_status'] == 'paid' ){
            $this->db->where('company_accounts.amount !=', '');
        }
        if($keys['contractor'] != '' ){
            $this->db->where('trips.contractor_id', $keys['contractor']);
        }
        if($keys['contractor_freight_status'] == 'unpaid' ){
            $this->db->where('contractor_accounts.amount', null);
        }
        if($keys['contractor_freight_status'] == 'paid' ){
            $this->db->where('contractor_accounts.amount !=', '');
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.company_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        $trips_ids = array();
        foreach($trips as $trip){
            array_push($trips_ids, $trip->trip_id);
        }

        $final_trips = $this->trips_model->parametrized_trips_engine($trips_ids, "company_accounts");
        usort($final_trips, array("Sorting_Model", "sort_company_accounts"));
        $start = ($start == '')?0:$start;
        return array_slice($final_trips, $start,($limit));
    }

    public function count_searched_company_accounts($c_id, $keys)
    {
        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['freight_unit'] != ''){
            $this->db->where('trips_details.company_freight_unit',$keys['freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('trips.company_commission_2',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('trips.company_commission_1',$keys['company_commission']);
        }
        if($keys['company_commission_status'] == 'unpaid'){
            $this->db->where('company_accounts.amount',null);
        }
        if($keys['company_commission_status'] == 'paid' ){
            $this->db->where('company_accounts.amount !=', '');
        }
        if($keys['contractor'] != '' ){
            $this->db->where('trips.contractor_id', $keys['contractor']);
        }
        if($keys['contractor_freight_status'] == 'unpaid' ){
            $this->db->where('contractor_accounts.amount', null);
        }
        if($keys['contractor_freight_status'] == 'paid' ){
            $this->db->where('contractor_accounts.amount !=', '');
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.company_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        return sizeof($trips);
    }


    public function contractor($c_id, $keys, $limit, $start){

        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['company'] != '' ){
            $this->db->where('trips.company_id', $keys['company']);
        }
        if($keys['cmp_freight_unit'] != ''){
            $this->db->where('trips_details.company_freight_unit',$keys['cmp_freight_unit']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('trips.company_commission_2',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('trips.company_commission_1',$keys['company_commission']);
        }
        if($keys['company_commission_status'] == 'unpaid'){
            $this->db->where('company_accounts.amount',null);
        }
        if($keys['company_commission_status'] == 'paid' ){
            $this->db->where('company_accounts.amount !=', '');
        }
        if($keys['contractor_freight_status'] == 'unpaid' ){
            $this->db->where('contractor_accounts.amount', null);
        }
        if($keys['contractor_freight_status'] == 'paid' ){
            $this->db->where('contractor_accounts.amount !=', '');
        }
        if($keys['contractor_commission'] != '' ){
            $this->db->where('trips.contractor_commission', $keys['contractor_commission']);
        }
        if($keys['contractor_commission_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['contractor_commission_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['customer'] != '' ){
            $this->db->where('trips.customer_id', $keys['customer']);
        }
        if($keys['customer_freight_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['customer_freight_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.contractor_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        $trips_ids = array();
        foreach($trips as $trip){
            array_push($trips_ids, $trip->trip_id);
        }

        $final_trips = $this->trips_model->parametrized_trips_engine($trips_ids,"contractor_accounts");
        usort($final_trips, array("Sorting_Model", "sort_contractor_accounts"));
        $start = ($start == '')?0:$start;
        return array_slice($final_trips, $start,($limit));
    }
    public function count_searched_contractor_accounts($c_id, $keys)
    {
        //applying keys....
        if($keys['from'] != ''){
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != ''){
            $this->db->where('trips.entryDate <=',$keys['to']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['trip_type'] != '' ){
            if($keys['trip_type'] == 1){
                $this->db->where('trips.type', '1');
            }else if($keys['trip_type'] == 2){
                $this->db->where('trips.type', '2');
            }else if($keys['trip_type'] == 3){
                $this->db->where('trips.type', '3');
            }else if($keys['trip_type'] == 4){
                $this->db->where('trips.type', '4');
            }
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        if($keys['entryDate'] != ''){
            $this->db->where('trips.entryDate',$keys['entryDate']);
        }
        if($keys['product'] != ''){
            $this->db->where('trips_details.product',$keys['product']);
        }
        if($keys['source'] != ''){
            $this->db->where('trips_details.source',$keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('trips_details.destination',$keys['destination']);
        }
        if($keys['company'] != '' ){
            $this->db->where('trips.company_id', $keys['company']);
        }
        if($keys['cmp_freight_unit'] != ''){
            $this->db->where('trips_details.company_freight_unit',$keys['cmp_freight_unit']);
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        if($keys['wht'] != ''){
            $this->db->where('trips.company_commission_2',$keys['wht']);
        }
        if($keys['company_commission'] != ''){
            $this->db->where('trips.company_commission_1',$keys['company_commission']);
        }
        if($keys['company_commission_status'] == 'unpaid'){
            $this->db->where('company_accounts.amount',null);
        }
        if($keys['company_commission_status'] == 'paid' ){
            $this->db->where('company_accounts.amount !=', '');
        }
        if($keys['contractor_freight_status'] == 'unpaid' ){
            $this->db->where('contractor_accounts.amount', null);
        }
        if($keys['contractor_freight_status'] == 'paid' ){
            $this->db->where('contractor_accounts.amount !=', '');
        }
        if($keys['contractor_commission'] != '' ){
            $this->db->where('trips.contractor_commission', $keys['contractor_commission']);
        }
        if($keys['contractor_commission_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['contractor_commission_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['customer'] != '' ){
            $this->db->where('trips.customer_id', $keys['customer']);
        }
        if($keys['customer_freight_status'] == 'unpaid' ){
            $this->db->where('customer_accounts.amount', null);
        }
        if($keys['customer_freight_status'] == 'paid' ){
            $this->db->where('customer_accounts.amount !=', '');
        }
        if($keys['cst_freight_unit'] != ''){
            $this->db->where('trips_details.freight_unit',$keys['cst_freight_unit']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.contractor_id'=>$c_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');

        //joining customers, contractors and companies
        $this->db->join('customers', 'customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors', 'carriage_contractors.id = trips.contractor_id','left');

        //joining accounts
        $this->db->join('contractor_accounts', 'contractor_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('company_accounts', 'company_accounts.trip_detail_id = trips_details.id','left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id','left');

        //joining cites and routes etc..
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->join('products', 'products.id = trips_details.product','left');

        /*--**********************joining ends*********************--*/

        $trips = $this->db->get()->result();
        return sizeof($trips);
    }

    public function voucher($voucher_id)
    {
        $voucher = $this->journal("","","","","voucher_journal.id = $voucher_id");
        if(sizeof($voucher) >= 1){
            return $voucher[0];
        }
        return null;
    }

    public function delete_voucher($id)
    {
        $this->db->trans_start();
        $data = array(
            'voucher_journal.active' => 0,
        );
        $this->db->where('voucher_journal.id',$id);
        $this->db->update('voucher_journal',$data);

        //deleting destination shortage voucher
        $data = array(
            'shortage_voucher_dest'=>0,
        );
        $this->db->where('trips_details.shortage_voucher_dest',$id);
        $this->db->update('trips_details',$data);

        //deleting decanding shortage voucher
        $this->trips_model->delete_decnd_shortage_voucher($id);

        $this->db->trans_complete();
        if($this->db->trans_status() == true){
            return true;
        }
        return false;
    }

    public function save_voucher($account_holder = "users", $account_holder_id = '')
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        $voucher = new Universal_Voucher();

        //setting values to the object
        $voucher->trip_detail_id = (isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0;
        $voucher->trip_id = $this->input->post('trip_id');
        $voucher->person_id = ($account_holder_id != '')?$account_holder_id:$this->input->post('account_holders');
        $voucher->person = $account_holder;
        $voucher->tanker_id = $this->input->post('tankers');
        $voucher->voucher_date = $this->input->post('voucher_date');
        $voucher->price_unit = (isset($_POST['price_unit']))?$_POST['price_unit']:0;
        $voucher->shortage_rate = (isset($_POST['shortage_rate']))?$_POST['shortage_rate']:0;
        $voucher->shortage_quantity = (isset($_POST['shortage_quantity']))?$_POST['shortage_quantity']:0;
        $voucher->voucher_details = $this->input->post('voucher_details');
        $voucher->voucher_type = (isset($_POST['voucher_type']))?$_POST['voucher_type']:'';


        $entries_counter = $this->input->post('pannel_count');
        for($counter = 1; $counter < $entries_counter; $counter++){
            $entry = array(
                'ac_type'=> '',
                'account_title_id'=> '',
                'description'=> '',
                'related_other_agent'=> '',
                'debit_amount'=> '',
                'credit_amount'=> '',
                'journal_voucher_id'=> '',
            );

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
            $entry['journal_voucher_id'] = '';

            array_unshift($voucher->entries, $entry);
        }

        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$voucher->voucher_date,
            'detail' => $voucher->voucher_details,
            'person_tid' => $voucher->person.".".$voucher->person_id,
            'trip_id' => $voucher->trip_id,
            'trip_product_detail_id'=>$voucher->trip_detail_id,
            'tanker_id' => $voucher->tanker_id,
            'shortage_quantity'=>$voucher->shortage_quantity,
            'shortage_rate'=>$voucher->shortage_rate,
            'price_unit'=>$voucher->price_unit,
        );
        $result = $this->db->insert('voucher_journal', $journal_voucher_data);
        if($result == true){
            $voucher_entries = array();
            foreach($voucher->entries as $entry)
            {
                $entry['journal_voucher_id'] = mysql_insert_id();
                array_unshift($voucher_entries , $entry);
            }
            if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
                return true;
            }
        }
    }

    public function save_tanker_expense_voucher()
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        $voucher = new Universal_Voucher();

        //setting values to the object
        $voucher->trip_detail_id = (isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0;
        $voucher->trip_id = $this->input->post('trip_id');
        $voucher->person_id = $this->input->post('account_holders');
        $voucher->person = "users";
        $voucher->tanker_id = $this->input->post('tankers');
        $voucher->voucher_date = $this->input->post('voucher_date');
        $voucher->voucher_details = $this->input->post('voucher_details');

        $entries_counter = $this->input->post('pannel_count');
        for($counter = 1; $counter < $entries_counter; $counter++){
            $entry = array(
                'ac_type'=> '',
                'account_title_id'=> '',
                'description'=> '',
                'related_other_agent'=> '',
                'debit_amount'=> '',
                'credit_amount'=> '',
                'journal_voucher_id'=> '',
            );

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
            $entry['journal_voucher_id'] = '';

            array_unshift($voucher->entries, $entry);
        }

        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$voucher->voucher_date,
            'detail' => $voucher->voucher_details,
            'person_tid' => $voucher->person.".1",
            'trip_id' => $voucher->trip_id,
            'trip_product_detail_id' => $voucher->trip_detail_id,
            'tanker_id' => $voucher->tanker_id,
        );
        $result = $this->db->insert('voucher_journal', $journal_voucher_data);
        if($result == true){
            $voucher_entries = array();
            foreach($voucher->entries as $entry)
            {
                $entry['journal_voucher_id'] = mysql_insert_id();
                array_unshift($voucher_entries , $entry);
            }
            if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
                return true;
            }
        }
    }

    public function update_journal_voucher()
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        $voucher = new Universal_Voucher();

        //setting values to the object
        $voucher->trip_id = $this->input->post('trip_id');
        $voucher->trip_detail_id = (isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0;
        $voucher->person_id = 1;
        $voucher->person = "users";
        $voucher->tanker_id = $this->input->post('tankers');
        $voucher->voucher_date = $this->input->post('voucher_date');
        $voucher->voucher_details = $this->input->post('voucher_details');

        $entries_counter = $this->input->post('pannel_count');
        $voucher_entries_ids = array();
        for($counter = 1; $counter < $entries_counter; $counter++){
            $entry = array(
                'id'=>'',
                'account_title_id'=> '',
                'description'=> '',
                'related_other_agent'=> '',
                'debit_amount'=> '',
                'credit_amount'=> '',
                'journal_voucher_id'=> '',
            );
            $entry['id'] = $this->input->post('voucher_entry_id_'.$counter);
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
            $entry['journal_voucher_id'] = '';

            //calucalating those entry ids which were saved before.
            array_unshift($voucher_entries_ids, $entry['id']);

            array_unshift($voucher->entries, $entry);
        }
        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$voucher->voucher_date,
            'detail' => $voucher->voucher_details,
            'person_tid' => $voucher->person.".".$voucher->person_id,
            'trip_id' => $voucher->trip_id,
            'trip_product_detail_id' => $voucher->trip_detail_id,
            'tanker_id' => $voucher->tanker_id,
        );


        $this->db->where('voucher_journal.id',$this->input->post('voucher_id'));
        $result = $this->db->update('voucher_journal', $journal_voucher_data);
        if($result == true){
            $voucher_entries_for_update = array();
            $voucher_entries_for_insert = array();

            foreach($voucher->entries as $entry)
            {
                $entry['journal_voucher_id'] = $this->input->post('voucher_id');
                if($entry['id'] == 0){
                    array_unshift($voucher_entries_for_insert , $entry);
                }else{
                    array_unshift($voucher_entries_for_update , $entry);
                }
            }

            //deleting those entries which were removed from the voucher

            $this->db->where('voucher_entry.journal_voucher_id',$this->input->post('voucher_id'));
            $this->db->where_not_in('voucher_entry.id',$voucher_entries_ids);
            $this->db->from('voucher_entry');
            $this->db->delete();

            //updating
            $this->db->update_batch('voucher_entry', $voucher_entries_for_update,'id');
            if(sizeof($voucher_entries_for_insert) >= 1){
                $this->db->insert_batch('voucher_entry', $voucher_entries_for_insert);
            }

            //echo "data inserted";die();
            return true;
        }
    }

    public function update_tanker_expense_voucher()
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        $voucher = new Universal_Voucher();

        //setting values to the object
        $voucher->trip_id = $this->input->post('trip_id');
        $voucher->trip_detail_id = (isset($_POST['trip_detail_id']))?$_POST['trip_detail_id']:0;
        $voucher->person_id = $this->input->post('account_holders');
        $voucher->person = $this->input->post('account_holder_type');
        $voucher->tanker_id = $this->input->post('tankers');
        $voucher->voucher_date = $this->input->post('voucher_date');
        $voucher->voucher_details = $this->input->post('voucher_details');

        $entries_counter = $this->input->post('pannel_count');
        $voucher_entries_ids = array();
        for($counter = 1; $counter < $entries_counter; $counter++){
            $entry = array(
                'id'=>'',
                'ac_type'=> '',
                'account_title_id'=> '',
                'description'=> '',
                'related_other_agent'=> '',
                'debit_amount'=> '',
                'credit_amount'=> '',
                'journal_voucher_id'=> '',
            );
            $entry['id'] = $this->input->post('voucher_entry_id_'.$counter);
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
            $entry['journal_voucher_id'] = '';

            //calucalating those entry ids which were saved before.
            array_unshift($voucher_entries_ids, $entry['id']);

            array_unshift($voucher->entries, $entry);
        }
        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$voucher->voucher_date,
            'detail' => $voucher->voucher_details,
            'person_tid' => $voucher->person.".".$voucher->person_id,
            'trip_id' => $voucher->trip_id,
            'trip_product_detail_id' => $voucher->trip_detail_id,
            'tanker_id' => $voucher->tanker_id,
        );
        /*echo $voucher->person_id."<br>";
        echo $journal_voucher_data['person_tid']."<br>"; die();*/

        $this->db->where('voucher_journal.id',$this->input->post('voucher_id'));
        $result = $this->db->update('voucher_journal', $journal_voucher_data);
        if($result == true){
            $voucher_entries_for_update = array();
            $voucher_entries_for_insert = array();

            foreach($voucher->entries as $entry)
            {
                $entry['journal_voucher_id'] = $this->input->post('voucher_id');
                if($entry['id'] == 0){
                    array_unshift($voucher_entries_for_insert , $entry);
                }else{
                    array_unshift($voucher_entries_for_update , $entry);
                }
            }

            //deleting those entries which were removed from the voucher

            $this->db->where('voucher_entry.journal_voucher_id',$this->input->post('voucher_id'));
            $this->db->where_not_in('voucher_entry.id',$voucher_entries_ids);
            $this->db->from('voucher_entry');
            $this->db->delete();

            //updating
            $this->db->update_batch('voucher_entry', $voucher_entries_for_update,'id');
            if(sizeof($voucher_entries_for_insert) >= 1){
                $this->db->insert_batch('voucher_entry', $voucher_entries_for_insert);
            }

            //echo "data inserted";die();
            return true;
        }
    }

    public function searched_voucher_ids($agent, $agent_id,$keys)
    {
        if($keys['voucher_id'] != ''){
            $this->db->where('voucher_journal.id', $keys['voucher_id']);
        }
        else
        {
            if($keys['custom_from'] != '' && $keys['custom_to']){
                $this->db->where('voucher_journal.voucher_date >=',$keys['custom_from']);
                $this->db->where('voucher_journal.voucher_date <=',$keys['custom_to']);
            }else{
                /*
                 * ----------------------------------------------
                 * Below area is commented because we are hiding
                 * the accounting year functionality just for
                 * now.
                 * it might be activated again on some day.
                 * ----------------------------------------------
                 */
                    //if($keys['accounting_year_from'] != ''){
                    //$this->db->where('voucher_journal.voucher_date >=', $keys['accounting_year_from']);
                    //}
                    //if($keys['accounting_year_to'] != ''){
                    //$this->db->where('voucher_journal.voucher_date <=', $keys['accounting_year_to']);
                    //}
                /*--------------------------------------------------*/

            }
        }
        if($keys['voucher_type'] != ''){
            if($keys['voucher_type'] == 1){
                $this->db->where('voucher_journal.ignored', 1);
            }
        }else{
            $this->db->where('voucher_journal.ignored', 0);
        }

        if($keys['title'] != ''){
            $this->db->where('voucher_entry.account_title_id',$keys['title']);
        }
        if($keys['expense_type'] != ''){
            $this->db->where('account_titles.secondary_type',$keys['expense_type']);
        }
        if($keys['ac_type'] != ''){
            $this->db->where('account_titles.type',$keys['ac_type']);
        }
        if($keys['agent_type'] != ''){
            if($keys['agent_type'] == 'customers'){
                $agent_type = 'related_customer';
            }
            if($keys['agent_type'] == 'other_agents'){
                $agent_type = 'related_other_agent';
            }
            if($keys['agent_type'] == 'carriage_contractors'){
                $agent_type = 'related_contractor';
            }
            if($keys['agent_type'] == 'companies'){
                $agent_type = 'related_company';
            }

            if($keys['agent_id'] != ''){
                $this->db->where('voucher_entry.'.$agent_type.'', $keys['agent_id']);
            }else{
                $this->db->where('voucher_entry.'.$agent_type.' !=', 0);
            }
        }

        if($keys['tanker'] != ''){
            $this->db->where('tankers.id',$keys['tanker']);
        }
        if($keys['voucher_detail'] != ''){
            $this->db->like('voucher_journal.detail',$keys['voucher_detail']);
        }
        if($keys['summery'] != ''){
            $this->db->like('voucher_entry.description',$keys['summery']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('voucher_journal.trip_id',$keys['trip_id']);
        }
        if($keys['trip_detail_id'] != ''){
            $this->db->where('voucher_journal.trip_product_detail_id',$keys['trip_detail_id']);
        }

        //checking weather user demands pagination or not
        if(isset($keys['limit']) && isset($keys['start'])){
            $this->db->limit($keys['limit'], $keys['start']);
        }
        /////////////////////////////////////////////////////

        //checking weather user demands Sorting or not
        if(isset($keys['sort']) && $keys['sort'] != ''){
            $this->db->order_by($keys['sort']['sort_by'],$keys['sort']['order']);
            //$this->db->order_by('voucher_journal.id',$keys['sort']['order']);
        }else{
            $this->db->order_by('voucher_journal.id','asc');
        }
        /////////////////////////////////////////////////////

        $this->db->select("voucher_journal.id as voucher_id");
        $this->db->distinct();
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'inner');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->where(array(
            'voucher_journal.person_tid'=>$agent.".".$agent_id,
            'voucher_journal.active'=>1,
        ));

        $result = $this->db->get()->result();
        $voucher_ids[0] = 0;
        foreach($result as $r){
            array_unshift($voucher_ids, $r->voucher_id);
        }
        return $voucher_ids;
    }


    public function search_journal($agent, $agent_id, $limit, $start, $keys, $sort)
    {
        $keys['limit'] = $limit;
        $keys['start'] = $start;
        $keys['sort'] = $sort;

        $voucher_ids = $this->searched_voucher_ids($agent, $agent_id, $keys);
        $journal = $this->journal($agent, $agent_id, $voucher_ids, $sort);

        if($keys['sort']['sort_by'] != 'voucher_journal.id')
        {
            usort($journal, array("Sorting_Model", "sort_journal"));
        }

        return $journal;
    }

    public function count_searched_journal($agent, $agent_id, $keys)
    {
        if($keys['voucher_id'] != ''){
            $this->db->where('voucher_journal.id', $keys['voucher_id']);
        }
        else
        {
            if($keys['custom_from'] != '' && $keys['custom_to']){
                $this->db->where('voucher_journal.voucher_date >=',$keys['custom_from']);
                $this->db->where('voucher_journal.voucher_date <=',$keys['custom_to']);
            }else{
                if($keys['accounting_year_from'] != ''){
                    $this->db->where('voucher_journal.voucher_date >=', $keys['accounting_year_from']);
                }
                if($keys['accounting_year_to'] != ''){
                    $this->db->where('voucher_journal.voucher_date <=', $keys['accounting_year_to']);
                }
            }
        }
        if($keys['voucher_type'] != ''){
            if($keys['voucher_type'] == 1){
                $this->db->where('voucher_journal.ignored', 1);
            }
        }else{
            $this->db->where('voucher_journal.ignored', 0);
        }
        if($keys['title'] != ''){
            $this->db->where('voucher_entry.account_title_id',$keys['title']);
        }
        if($keys['expense_type'] != ''){
            $this->db->where('account_titles.secondary_type',$keys['expense_type']);
        }
        if($keys['ac_type'] != ''){
            $this->db->where('voucher_entry.ac_type',$keys['ac_type']);
        }
        if($keys['agent_type'] != ''){
            if($keys['agent_type'] == 'customers'){
                $agent_type = 'related_customer';
            }
            if($keys['agent_type'] == 'other_agents'){
                $agent_type = 'related_other_agent';
            }
            if($keys['agent_type'] == 'carriage_contractors'){
                $agent_type = 'related_contractor';
            }
            if($keys['agent_type'] == 'companies'){
                $agent_type = 'related_company';
            }

            if($keys['agent_id'] != ''){
                $this->db->where('voucher_entry.'.$agent_type.'', $keys['agent_id']);
            }else{
                $this->db->where('voucher_entry.'.$agent_type.' !=', 0);
            }
        }

        if($keys['tanker'] != ''){
            $this->db->like('tankers.id',$keys['tanker']);
        }
        if($keys['voucher_detail'] != ''){
            $this->db->like('voucher_journal.detail',$keys['voucher_detail']);
        }
        if($keys['summery'] != ''){
            $this->db->like('voucher_entry.description',$keys['summery']);
        }
        if($keys['trip_id'] != ''){
            $this->db->where('voucher_journal.trip_id',$keys['trip_id']);
        }
        if($keys['trip_detail_id'] != ''){
            $this->db->like('voucher_journal.trip_product_detail_id',$keys['trip_detail_id']);
        }

        $this->db->select("voucher_journal.id as voucher_id");
        $this->db->distinct();
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'inner');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->where('voucher_journal.active',1);
        $this->db->where('voucher_journal.person_tid',$agent.".".$agent_id);
        $result = $this->db->get()->result();

        return sizeof($result);
    }


    public function bank_cash_summery($agent, $agent_id, $accounting_year){
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");


        $this->db->select("voucher_entry.related_other_agent,
                            SUM(voucher_entry.debit_amount) AS debit,SUM(voucher_entry.credit_amount) AS credit,
                            (SUM(voucher_entry.debit_amount)-SUM(voucher_entry.credit_amount)) AS balance,
                            other_agents.name as other_agent_name, customers.name as customer_name,voucher_entry.related_customer, carriage_contractors.name as contractor_name,
                            companies.name as company_name, voucher_entry.related_company,
                             voucher_entry.related_contractor,
        ");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->where(array(
            'voucher_journal.person_tid'=>$agent.".".$agent_id,
            'voucher_journal.active'=>1,
        ));
        //$this->db->like('voucher_date', $month."-", 'after');
        $this->db->group_by(array("related_other_agent","related_customer","related_contractor"));
        $this->db->where(array(
            'voucher_journal.voucher_date >='=>$accounting_year['from'],
            'voucher_journal.voucher_date <='=>$accounting_year['to'],
        ));
        $this->db->where(array(
            'account_titles.title'=>'cash',
            'voucher_entry.ac_type' => 'bank',
        ));
        $this->db->order_by('voucher_entry.dr_cr','asc');
        $result = $this->db->get()->result();

        return $result;
    }

    public function journal($agent, $agent_id, $voucher_ids, $sort, $where='')
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");

        $accounting_year = $this->accounting_year();

        $this->db->select("voucher_journal.id as voucher_id, voucher_journal.ignored, voucher_entry.id as voucher_entry_id,
                            voucher_journal.voucher_date, voucher_journal.detail, voucher_journal.person_tid,
                            voucher_journal.trip_id,voucher_journal.trip_product_detail_id, voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            voucher_journal.entryDate, voucher_journal.transaction_column, voucher_journal.auto_generated,
                            voucher_journal.shortage_quantity, voucher_journal.shortage_rate, voucher_journal.price_unit,
                            voucher_entry.id as voucher_entry_id, voucher_entry.related_other_agent,
                             voucher_entry.related_customer, voucher_entry.related_contractor,
                            voucher_entry.description, account_titles.title, account_titles.id as account_title_id,
                             account_titles.type as ac_type, voucher_entry.debit_amount,
                            voucher_entry.credit_amount, voucher_entry.dr_cr,
                            companies.name as company_name, voucher_entry.related_company,
                            other_agents.name as related_other_agent_name, customers.name as related_customer_name, carriage_contractors.name as related_contractor_name, companies.name as related_company_name,
        ");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'inner');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');

        if($where == '')
        {
            $this->db->where(array(
                'voucher_journal.person_tid'=>$agent.".".$agent_id,
                'voucher_journal.active'=>1,
            ));
            if($voucher_ids == ""){
                $this->db->where(array(
                    'voucher_date >='=>$accounting_year['from'],
                    'voucher_date <='=>$accounting_year['to'],
                ));
            }
        }
        else
        {
            $this->db->where($where);
        }

        if($voucher_ids != ""){
            $this->db->where_in('voucher_journal.id',$voucher_ids);
        }

        //sorting
        if($sort != ''){
            $this->db->order_by('voucher_journal.id',$sort['order']);
        }else{
            $this->db->order_by('voucher_journal.id','asc');
        }

        $this->db->order_by('voucher_entry.dr_cr','asc');
        $result = $this->db->get()->result();

        $previous_voucher_id = -1;
        $temp_voucher = new Universal_Voucher();
        $final_journal = array();
        $count = 0;
        foreach($result as $voucher){
            $count++;

            if($voucher->voucher_id != $previous_voucher_id)
            {
                if($previous_voucher_id != -1){
                    array_push($final_journal, $temp_voucher);
                }

                $previous_voucher_id = $voucher->voucher_id;
                $temp_voucher = new Universal_Voucher();

                //setting data in the parent object
                $temp_voucher->voucher_id = $voucher->voucher_id;
                $temp_voucher->ignore = $voucher->ignored;
                $temp_voucher->voucher_details = $voucher->detail;
                $temp_voucher->voucher_date = $voucher->voucher_date;
                $temp_voucher->tanker_id = $voucher->tanker_id;
                $temp_voucher->tanker_number = $voucher->tanker_number;
                $temp_voucher->trip_id = $voucher->trip_id;
                $temp_voucher->trip_detail_id = $voucher->trip_product_detail_id;
                $person = explode('.',$voucher->person_tid);
                $temp_voucher->person_id = $person[1];
                $temp_voucher->person = $person[0];
                $temp_voucher->transaction_column = $voucher->transaction_column;
                $temp_voucher->auto_generated = $voucher->auto_generated;
                $temp_voucher->shortage_quantity = $voucher->shortage_quantity;
                $temp_voucher->shortage_rate = $voucher->shortage_rate;
            }

            //making a voucher Entry
            $temp_voucher_entry = new Voucher_Entry();

            //setting data in temp_voucher_entry
            $temp_voucher_entry->setId($voucher->voucher_entry_id);
            $temp_voucher_entry->setAc_type($voucher->ac_type);
            $temp_voucher_entry->setTitle($voucher->title);
            $temp_voucher_entry->setAccount_title_id($voucher->account_title_id);
            $temp_voucher_entry->setDescription($voucher->description);
            //finding the related agent
            $related_agent = ''; $related_agent_id=''; $related_agent_name='';
            if($voucher->related_other_agent != 0){
                $related_agent = "other_agents";
                $related_agent_id = $voucher->related_other_agent;
                $related_agent_name = $voucher->related_other_agent_name;
            }else if($voucher->related_customer != 0){
                $related_agent = "customers";
                $related_agent_id = $voucher->related_customer;
                $related_agent_name = $voucher->related_customer_name;
            }else if($voucher->related_contractor != 0){
                $related_agent = "carriage_contractors";
                $related_agent_id = $voucher->related_contractor;
                $related_agent_name = $voucher->related_contractor_name;
            }else if($voucher->related_company != 0){
                $related_agent = "companies";
                $related_agent_id = $voucher->related_company;
                $related_agent_name = $voucher->related_company_name;
            }else{
                $related_agent = "self";
                $related_agent_id = 0;
                $related_agent_name = '';
            }
            $temp_voucher_entry->setRelated_agent($related_agent);
            $temp_voucher_entry->setRelated_agent_id($related_agent_id);
            $temp_voucher_entry->setRelated_agent_name($related_agent_name);
            $temp_voucher_entry->setDebit($voucher->debit_amount);
            $temp_voucher_entry->setCredit($voucher->credit_amount);
            $dr_cr = ($voucher->dr_cr == 0)?'credit':'debit';
            $temp_voucher_entry->setDr_cr($dr_cr);
            $temp_voucher_entry->setJournal_voucher_id($voucher->voucher_id);
            //insert voucher entry into the voucher
            array_unshift($temp_voucher->entries,$temp_voucher_entry);

            //checking if the record is final
            if($count == sizeof($result))
            {
                array_push($final_journal, $temp_voucher);
            }
        }

        return $final_journal;
    }

    public function ledger($agent, $agent_id, $keys)
    {
        //fetching the bank a/c id
        $this->db->select('id');
        $raw_titile = $this->db->get_where('account_titles',array(
            'title'=>'Bank A/c',
        ))->result();
        $bank_ac_title_id = $raw_titile[0]->id;

        $trial_balance_settings = $this->accounts_model->fetch_trial_balance_settings();
        $group_by = $trial_balance_settings;
        /*if(in_array('ac_type',$trial_balance_settings))
        {
            array_push($group_by, 'account_titles.type');
        }
        if(in_array('title',$trial_balance_settings))
        {
            array_push($group_by, 'account_titles.title');
        }
        if(in_array('related_other_agent',$trial_balance_settings))
        {
            array_push($group_by, 'voucher_entry.related_other_agent');
            array_push($group_by, 'voucher_entry.related_customer');
            array_push($group_by, 'voucher_entry.related_company');
            array_push($group_by, 'voucher_entry.related_contractor');
        }*/
        //fetching voucher ids
        $voucher_ids = $this->searched_voucher_ids($agent, $agent_id, $keys);
        /////////////////////////////////////////////////////////////////////

        $ac_type = (isset($_GET['ledger_ac_type']))?$_GET['ledger_ac_type']:'';
        $ac_title = (isset($_GET['ledger_account_title_id']))?$_GET['ledger_account_title_id']:'';
        $related_other_agent = (isset($_GET['related_other_agent']))?$_GET['related_other_agent']:'';
        $related_customer = (isset($_GET['related_customer']))?$_GET['related_customer']:'';
        $related_contractor = (isset($_GET['related_contractor']))?$_GET['related_contractor']:'';
        $related_company = (isset($_GET['related_company']))?$_GET['related_company']:'';
        if($ac_type == '' || $related_contractor == '' || $related_customer == '' || $related_other_agent == '' || $related_company == '' ){
            die("Sorry!\nInvalid request detected. please try again");
        }

        $extra_query = (($ac_type == '*')? "voucher_entry.ac_type,":"") .
            (($ac_title == '*')?"account_titles.title as title_str,":"").
            (($related_other_agent == '*')?"other_agents.name as related_other_agent_name,":"").
            (($related_customer == '*')?"customers.name as related_customer_name,":"").
            (($related_contractor == '*')?"carriage_contractors.name as related_contractor_name,":"").
            (($related_company == '*')?"companies.name as related_company_name,":"");

        $this->db->select("voucher_journal.id as voucher_id, voucher_entry.id as voucher_entry_id,
                            voucher_journal.voucher_date,

                            voucher_journal.trip_id, voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            voucher_entry.description, voucher_entry.dr_cr, voucher_entry.debit_amount as debit, voucher_entry.credit_amount as credit,
        ".$extra_query);
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        //static where.....
        $this->db->where(array(
            'voucher_journal.person_tid'=>$agent.".".$agent_id,
            'voucher_journal.active'=>1,
        ));
        $this->db->where_in('voucher_journal.id',$voucher_ids);
        /*
         * deciding weather to exclude bank acount or not?
         */
        if(! in_array('title', $group_by))
        {
            if(strtolower($_GET['ledger_title']) == 'bank a/c'){
                $this->db->where('voucher_entry.account_title_id',$bank_ac_title_id);
            }else{
                $this->db->where('voucher_entry.account_title_id !=',$bank_ac_title_id);
            }
        }else{
            if($ac_title != '*'){
                $this->db->where('voucher_entry.account_title_id',$ac_title);
            }
        }
        /*$this->db->where(array(
            'voucher_journal.voucher_date >='=>$accounting_year['from'],
            'voucher_journal.voucher_date <='=>$accounting_year['to'],
        ));*/
        ///////////////////////////////////////////

        //dynamic where..............
        if($ac_type != '*'){
            $this->db->where('account_titles.type',$ac_type);
        }
        if($related_other_agent != '*'){
            $this->db->where('related_other_agent',$related_other_agent);
        }
        if($related_customer != '*'){
            $this->db->where('related_customer',$related_customer);
        }
        if($related_contractor != '*'){
            $this->db->where('related_contractor',$related_contractor);
        }
        if($related_company != '*'){
            $this->db->where('related_company',$related_company);
        }

        /*$this->db->where(array(
            'voucher_entry.ac_type'=>$ac_type,
            'voucher_entry.account_title_id'=>$ac_title,
            'related_other_agent'=>$related_other_agent,
            'related_customer'=>$related_customer,
            'related_contractor'=>$related_contractor,
        ));*/
        ////////////////////////////////////////
        //$this->db->like('voucher_date', $month."-", 'after');
        $this->db->order_by('voucher_journal.voucher_date','asc');
        $this->db->order_by('voucher_journal.entryDate','asc');
        $result = $this->db->get()->result();
        return $result;
    }

    public function tankers_ledger($keys)
    {
        $this->db->select("voucher_journal.id as voucher_id, voucher_entry.id as voucher_entry_id,
                            voucher_journal.voucher_date, account_titles.type as ac_type, account_titles.title,
                            voucher_journal.trip_id, voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            voucher_entry.description, voucher_entry.dr_cr, voucher_entry.debit_amount as debit, voucher_entry.credit_amount as credit,
                            voucher_entry.related_other_agent as other_agent_id, voucher_entry.related_customer as customer_id,
                            voucher_entry.related_contractor as contractor_id, voucher_entry.related_company as company_id,
                            other_agents.name as other_agent_name, customers.name as customer_name, carriage_contractors.name as contractor_name,
                            companies.name as company_name,
        ");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');

        $this->db->order_by('voucher_journal.voucher_date');
        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.ignored !='=>1,
            'voucher_journal.person_tid'=>'users.1',
        ));
        if($keys['from'] != '')
        {
            $this->db->where('voucher_journal.voucher_date >=',$keys['from']);
        }
        if($keys['to'] != '')
        {
            $this->db->where('voucher_journal.voucher_date <=',$keys['to']);
        }
        if($keys['tanker_id'] != '')
            $this->db->where_in('voucher_journal.tanker_id',$keys['tanker_id']);
        /*applying conditions*/

        //below where was the previous where now we are changing it for some pupose
        /*$where = "((voucher_entry.dr_cr = 1 AND account_titles.type = 'expense') OR (voucher_entry.dr_cr = 0 AND account_titles.type = 'income'))";*/

        //new where...
//        $where = "((account_titles.type = 'expense') OR (voucher_entry.dr_cr = 0 AND account_titles.type = 'income'))";
//        /*--------------------------------------------------*/
//
//        //comented just for test. can b uncommented if needed
//        $this->db->where($where);

        $this->db->where('voucher_entry.related_customer !=',0);

        $result = $this->db->get()->result();

        //var_dump($result); die();
        return $result;
    }


    public function tankers_income_statement($keys)
    {
        include_once(APPPATH."models/helperClasses/tankers_routes.php");
        include_once(APPPATH."models/helperClasses/income_statement_row.php");

        $this->db->select("voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            SUM(voucher_entry.debit_amount) as total_expense,
                            SUM(voucher_entry.credit_amount) as total_income,
                            (SUM(voucher_entry.credit_amount) - SUM(voucher_entry.debit_amount)) as profit,
                            account_titles.secondary_type,
        ");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');

        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.ignored !='=>1,
            'voucher_journal.person_tid'=>'users.1',
            'voucher_journal.tanker_id !='=>0,
        ));
        $where = "(account_titles.type = 'expense' OR account_titles.type = 'income')";
        $this->db->where($where);
        $this->db->group_by('voucher_journal.tanker_id, account_titles.secondary_type');

        if($keys['from'] != '')
        {
            $this->db->where('voucher_journal.voucher_date >=',$keys['from']);
        }
        if($keys['to'] != '')
        {
            $this->db->where('voucher_journal.voucher_date <=',$keys['to']);
        }
        if($keys['tanker_id'] != '')
        {
            $this->db->where_in('voucher_journal.tanker_id',$keys['tanker_id']);
        }
        if($keys['customer_id'] != '')
        {
            $this->db->where('voucher_entry.related_customer',$keys['customer_id']);
        }
        //checking weather user demands Sorting or not
        /*if(isset($keys['sort']) && $keys['sort'] != ''){
            //$this->db->order_by($keys['sort']['sort_by'],$keys['sort']['order']);
            //$this->db->order_by('voucher_journal.id',$keys['sort']['order']);
        }else{
            $this->db->order_by('voucher_journal.tanker_id','asc');
        }*/
        /////////////////////////////////////////////////////
        $result = $this->db->get()->result();
        $grouped = Arrays::groupBy($result, Functions::extractField('tanker_id'));
        $income_statement = array();
        foreach($grouped as $group)
        {
            $income_statement_row = new Income_Statement_Row();
            foreach($group as $record)
            {
                $income_statement_row->tanker_id = $record->tanker_id;
                $income_statement_row->tanker_number = $record->tanker_number;
                if($record->secondary_type == 'other_expense')
                {
                    $income_statement_row->other_expense = $record->total_expense;
                }
                if($record->secondary_type == 'shortage_expense')
                {
                    $income_statement_row->shortage_expense = $record->total_expense;
                }

                    $income_statement_row->total_income += $record->total_income;

                $income_statement_row->secondary_type = $record->secondary_type;

            }
            $income_statement_row->profit = $income_statement_row->total_income - ($income_statement_row->shortage_expense + $income_statement_row->other_expense);
            array_push($income_statement, $income_statement_row);
        }

        usort($income_statement, array("Sorting_Model", "sort_tanker_income_statement"));

        /*Getting the routes information*/
        $this->db->select("trips.tanker_id, source_cities.cityName as source, destination_cities.cityName as destination,
                            COUNT(trips_details.source) as route_counter,
        ");
        $this->db->from('trips');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('cities as source_cities', 'source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities', 'destination_cities.id = trips_details.destination','left');
        $this->db->group_by(array(
            'trips.tanker_id',
            'trips_details.source',
            'trips_details.destination',
        ));
        $this->db->where(array(
            'trips.active'=>1,
        ));
        if($keys['from'] != '')
        {
            $this->db->where('trips.entryDate >=',$keys['from']);
        }
        if($keys['to'] != '')
        {
            $this->db->where('trips.entryDate <=',$keys['to']);
        }

        if($keys['tanker_id'] != '')
        {
            $this->db->where_in('trips.tanker_id',$keys['tanker_id']);
        }
        if($keys['customer_id'] != '')
        {
            $this->db->where('trips.customer_id',$keys['customer_id']);
        }
        $result = $this->db->get()->result();
        $tankers_routes = new Tankers_Routes($result);
        /**********************************/

        return array(
            'tankers_routes'=>$tankers_routes,
            'income_statement'=>$income_statement,
        );
    }

    public function trial_balance($agent, $agent_id, $keys, $given_trial_settings = null){
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher_ids = $this->searched_voucher_ids($agent, $agent_id, $keys);

        $trial_balance_settings = ($given_trial_settings != null)?$given_trial_settings:$this->accounts_model->fetch_trial_balance_settings();
        $group_by = array();
        if(in_array('ac_type',$trial_balance_settings))
        {
            array_push($group_by, 'account_titles.type');
        }
        if(in_array('title',$trial_balance_settings))
        {
            array_push($group_by, 'account_titles.title');
        }
        if(in_array('related_other_agent',$trial_balance_settings))
        {
            array_push($group_by, 'voucher_entry.related_other_agent');
            array_push($group_by, 'voucher_entry.related_customer');
            array_push($group_by, 'voucher_entry.related_company');
            array_push($group_by, 'voucher_entry.related_contractor');
        }

        //fetching the bank a/c id
        $this->db->select('id');
        $raw_titile = $this->db->get_where('account_titles',array(
            'title'=>'Bank A/c',
        ))->result();
        $bank_ac_title_id = $raw_titile[0]->id;

        $select = "voucher_journal.id as voucher_id, voucher_entry.id as voucher_entry_id,
                            voucher_journal.voucher_date, voucher_journal.detail, voucher_journal.person_tid,
                            voucher_journal.trip_id, voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            voucher_journal.entryDate,
                            voucher_entry.id as voucher_entry_id, voucher_entry.related_other_agent,
                            voucher_entry.description,  account_titles.title, account_titles.id as account_title_id,
                            account_titles.type as ac_type,
                            SUM(voucher_entry.debit_amount) AS debit,SUM(voucher_entry.credit_amount) AS credit,
                            (SUM(voucher_entry.debit_amount)-SUM(voucher_entry.credit_amount)) AS balance,
                            other_agents.name as other_agent_name, customers.name as customer_name,voucher_entry.related_customer, carriage_contractors.name as contractor_name,
                             voucher_entry.related_contractor, voucher_entry.related_company, companies.name as company_name,";
        $this->db->select($select);
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('companies','companies.id = voucher_entry.related_company','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->where(array(
            'voucher_journal.person_tid'=>$agent.".".$agent_id,
            'voucher_journal.active'=>1,
        ));
        $this->db->where_in('voucher_journal.id',$voucher_ids);
        if(sizeof($group_by >= 1)){
            $this->db->group_by($group_by);
        }
        $this->db->order_by('voucher_entry.dr_cr','asc');

        /*
         * deciding weather to exclude bank ac entries or not?
         */
        if(! in_array('title',$group_by)){
            $this->db->where('voucher_entry.account_title_id !=',$bank_ac_title_id);
        }
        /*******************************************************/
        $result = $this->db->get()->result();
        $trial_balance['other_entries'] = $result;
        $trial_balance['bank_ac_entries'] = array();

        /*
         * Deciding weather to exclude bank acount entries or not?
         */
        if(! in_array('title',$group_by)){

            $bank_ac_entries = array();

            $this->db->select($select);
            $this->db->from('voucher_journal');
            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
            $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
            $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
            $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
            $this->db->join('companies','companies.id = voucher_entry.related_company','left');
            $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
            $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
            $this->db->where(array(
                'voucher_journal.person_tid'=>$agent.".".$agent_id,
                'voucher_journal.active'=>1,
            ));
            $this->db->where_in('voucher_journal.id',$voucher_ids);
            //if(sizeof($group_by >= 1)){
                $group_by_array = array(
                    'title','related_customer','related_contractor','related_company','related_other_agent',
                );
                $this->db->group_by($group_by_array);
            //}
            $this->db->order_by('voucher_entry.dr_cr','asc');
            $where = "(voucher_entry.account_title_id = ".$bank_ac_title_id.")";
            $this->db->where($where);
            $raw_entries = $this->db->get()->result();
            $bank_ac_entries = array();
            $cash_ac_entries = array();
            foreach($raw_entries as $record)
            {
                if($record->account_title_id == $bank_ac_title_id){
                    array_push($bank_ac_entries , $record);
                }else{
                    array_push($cash_ac_entries, $record);
                }
            }
            //var_dump($cash_ac_entries); die();
            $trial_balance['bank_ac_entries'] = $bank_ac_entries;
        }
        /********************************************************/

        return $trial_balance;

        /*
         * below section of code will be activated when we need unbalanced
         * trial balance for client ease of use.
         */
        /*if($keys['voucher_id'] != ''){
            $this->db->where('voucher_journal.id', $keys['voucher_id']);
        }
        if($keys['custom_from'] != '' && $keys['custom_to']){
            $this->db->where('voucher_journal.voucher_date >=',$keys['custom_from']);
            $this->db->where('voucher_journal.voucher_date <=',$keys['custom_to']);
        }else{
            if($keys['accounting_year_from'] != ''){
                $this->db->where('voucher_journal.voucher_date >=', $keys['accounting_year_from']);
            }
            if($keys['accounting_year_to'] != ''){
                $this->db->where('voucher_journal.voucher_date <=', $keys['accounting_year_to']);
            }
        }
        if($keys['title'] != ''){
            $this->db->where('voucher_entry.account_title_id',$keys['title']);
        }
        if($keys['ac_type'] != ''){
            $this->db->where('voucher_entry.ac_type',$keys['ac_type']);
        }
        if($keys['agent_type'] != ''){
            if($keys['agent_type'] == 'customers'){
                $agent_type = 'related_customer';
            }
            if($keys['agent_type'] == 'other_agents'){
                $agent_type = 'related_other_agent';
            }
            if($keys['agent_type'] == 'carriage_contractors'){
                $agent_type = 'related_contractor';
            }

            if($keys['agent_id'] != ''){
                $this->db->where('voucher_entry.'.$agent_type.'', $keys['agent_id']);
            }else{
                $this->db->where('voucher_entry.'.$agent_type.' !=', 0);
            }
        }
        if($keys['tanker_number'] != ''){
            $this->db->like('tankers.truck_number',$keys['tanker_number']);
        }
        if($keys['voucher_detail'] != ''){
            $this->db->like('voucher_journal.detail',$keys['voucher_detail']);
        }
        if($keys['trip_id'] != ''){
            $this->db->like('voucher_journal.trip_id',$keys['trip_id']);
        }

        $this->db->select("voucher_journal.id as voucher_id, voucher_entry.id as voucher_entry_id,
                            voucher_journal.voucher_date, voucher_journal.detail, voucher_journal.person_tid,
                            voucher_journal.trip_id, voucher_journal.tanker_id, tankers.truck_number as tanker_number,
                            voucher_journal.entryDate,
                            voucher_entry.id as voucher_entry_id, voucher_entry.ac_type, voucher_entry.related_other_agent,
                            voucher_entry.description,  account_titles.title, account_titles.id as account_title_id,
                            SUM(voucher_entry.debit_amount) AS debit,SUM(voucher_entry.credit_amount) AS credit,
                            (SUM(voucher_entry.debit_amount)-SUM(voucher_entry.credit_amount)) AS balance,
                            other_agents.name as other_agent_name, customers.name as customer_name,voucher_entry.related_customer, carriage_contractors.name as contractor_name,
                             voucher_entry.related_contractor,
        ");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('other_agents','other_agents.id = voucher_entry.related_other_agent','left');
        $this->db->join('customers','customers.id = voucher_entry.related_customer','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = voucher_entry.related_contractor','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->where(array(
            'voucher_journal.person_tid'=>$agent.".".$agent_id,
            'voucher_journal.active'=>1,
        ));
        //$this->db->like('voucher_date', $month."-", 'after');
        $this->db->group_by(array("title", "ac_type", "related_other_agent","related_customer","related_contractor"));
        $this->db->order_by('voucher_entry.dr_cr','asc');
        $result = $this->db->get()->result();

        return $result;*/
    }

    public function income_statement($agent, $agent_id, $keys){

        $trial_balance_settings = $this->accounts_model->fetch_trial_balance_settings();
        if(!in_array('ac_type', $trial_balance_settings))
        {
            array_push($trial_balance_settings, "ac_type");
        }

        $trial_balance = $this->trial_balance($agent, $agent_id, $keys, $trial_balance_settings);
        $trial_balance = $trial_balance['other_entries'];
        $revenues = array();
        $expenses = array();

        foreach($trial_balance as $record)
        {
            if($record->ac_type == 'income'){
                array_unshift($revenues, $record);
            }else if($record->ac_type == 'expense'){
                array_unshift($expenses, $record);
            }
        }
        $income_statement = array();
        $income_statement['revenues'] = $revenues;
        $income_statement['expenses'] = $expenses;
        return $income_statement;
    }

    public function balance_sheet($agent, $agent_id, $keys){

        $trial_balance_settings = $this->accounts_model->fetch_trial_balance_settings();
        if(!in_array('ac_type', $trial_balance_settings))
        {
            array_push($trial_balance_settings, "ac_type");
        }

        $trial_balance = $this->trial_balance($agent, $agent_id, $keys, $trial_balance_settings);
        $trial_balance = $trial_balance['other_entries'];
        $balance_sheet = array();

        //below we are calculating equity, assets and Liabilities and expense and income
        $expenses = array();
        $income = array();
        $equity = array();
        $liabilities = array();
        $assets = array();
        foreach($trial_balance as $record){
            if($record->ac_type == "expense"){
                array_unshift($expenses,$record);
            }else if($record->ac_type == 'income'){
                array_unshift($income, $record);
            }else if($record->ac_type == 'assets'){
                array_unshift($assets, $record);
            }else if($record->ac_type == 'liability'){
                array_unshift($liabilities, $record);
            }else if($record->ac_type == 'owner equity'){
                array_unshift($equity, $record);
            }
        }
        /*-----------------------------------------------------*/

        /*---------------CALCULATING NET PROFIT----------------*/
        $total_income = 0;
        $total_expenses = 0;
        foreach($income as $record)
        {
            $total_income += ($record->credit - $record->debit);
        }
        foreach($expenses as $record)
        {
            $total_expenses += ($record->debit - $record->credit);
        }
        $net_profit = $total_income - $total_expenses;
        /*-----------------------------------------------------*/

        $balance_sheet = array(
            'assets'=>$assets,
            'liabilities'=>$liabilities,
            'equity'=>$equity,
            'net_profit'=>$net_profit,
        );
        return $balance_sheet;

    }

    public function customer_balance($c_id, $accounting_year)
    {
        //$accounting_year = $this->accounting_year();

        /*if((isset($_GET['custom_from']) && $_GET['custom_from'] != '') && (isset($_GET['custom_to']) && $_GET['custom_to'] != '')){
            $accounting_year['from'] = $_GET['custom_from'];
            $accounting_year['to'] = $_GET['custom_to'];
        }*/


        $customer_balance = array(
            'revenue'=>0,
            'cash'=>0,
            'receivable'=>0,
            'shortage_expense_at_destination'=>0,
            'shortage_expense_liability_at_destination'=>0,
            'shortage_expense_after_decanding'=>0,
            'shortage_expense_liability_after_decanding'=>0,
        );

        //calculating revenue;
        $this->db->select("
            SUM((trips_details.freight_unit * trips_details.product_quantity)*(100-contractor_commission)/100) as revenue,
            SUM( (( product_quantity - qty_at_destination )* price_unit) ) as expense_at_destination,
            SUM( (( qty_at_destination - qty_after_decanding )* price_unit) ) as expense_after_decanding
          ");
        $this->db->from('trips');
        $this->db->where('trips.active',1);
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $this->db->where(array(
            'trips.entryDate >='=>$accounting_year['from'],
            'trips.entryDate <='=>$accounting_year['to'],
        ));
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));

        $tanker_id = (isset($_GET['tanker']))?$_GET['tanker']:'';
        if($tanker_id != ''){
            $this->db->where('trips.tanker_id',$tanker_id);
        }

        $result = $this->db->get()->result();
        if(sizeof($result) >= 1){
            $customer_balance['revenue'] = round($result[0]->revenue, 3);
            $customer_balance['shortage_expense_at_destination'] = round($result[0]->expense_at_destination, 3);
            $customer_balance['shortage_expense_after_decanding'] = round($result[0]->expense_after_decanding, 3);
            $customer_balance['shortage_expense_liability_at_destination'] = round($result[0]->expense_at_destination, 3);
            $customer_balance['shortage_expense_liability_after_decanding'] = round($result[0]->expense_after_decanding, 3);
        }

        $this->db->select("SUM(customer_accounts.amount) as cash");
        $this->db->from('trips');
        $this->db->where('trips.active',1);
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id', 'left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id', 'left');
        $this->db->where(array(
            'trips.entryDate >='=>$accounting_year['from'],
            'trips.entryDate <='=>$accounting_year['to'],
        ));
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));

        $tanker_id = (isset($_GET['tanker']))?$_GET['tanker']:'';
        if($tanker_id != ''){
            $this->db->where('trips.tanker_id',$tanker_id);
        }

        $result = $this->db->get()->result();
        if(sizeof($result) >= 1){
            $customer_balance['cash'] = round($result[0]->cash, 3);
        }

        $customer_balance['receivable'] = $customer_balance['revenue'] - $customer_balance['cash'];

        /////////////Applying the shortage formula////////////////
        if($customer_balance['shortage_expense_after_decanding'] != 0){
            $customer_balance['shortage_expense_at_destination'] = 0;
            $customer_balance['shortage_expense_liability_at_destination'] = 0;
        }
        /////////////////////////////////////////////
        return $customer_balance;
    }

    public function net_profit($agent, $agent_id, $keys)
    {
        return null;
        //calculating accounting year
        /*$accounting_year['from'] = $keys['accounting_year_from'];
        $accounting_year['to'] = $keys['accounting_year_to'];

        //calculating net profit
        $income_statement = $this->income_statement($agent, $agent_id, $keys);
        $revenues = $income_statement[0];
        $expenses = $income_statement[1];

        $total_revenue = 0;
        foreach($revenues as $revenue){
            $total_revenue += $revenue->credit - $revenue->debit;
        }
        $total_expense = 0;
        foreach($expenses as $expense){
            $total_expense += $expense->debit - $expense->credit;
        }

        $net_profit = 0;
        //fetching the accounts related data of the account holder
        switch($agent){
            case "customers":
                $bodyData['customer_balance'] = $this->customer_balance($agent_id, $accounting_year);
                $net_profit = $bodyData['customer_balance']['revenue']+$total_revenue - $total_expense;
                break;
            case "contractors":
                $contractor_accounts = $this->accounts_model->contractor_accounts_for_trial_balance($agent_id, $keys);
                $net_profit = $total_revenue + ($contractor_accounts['commission_received'] + $contractor_accounts['commission_pending'] + $contractor_accounts['service_charges_received'] + $contractor_accounts['service_charges_pending']) - $total_expense;

                break;
            case "companies":
                $bodyData['companies_accounts'] = $this->accounts_model->company_accounts_for_trial_balance($agent_id, $keys);
                break;
            case "other_agents":
                $bodyData['other_agents_accounts'] = $this->accounts_model->other_agent_accounts_for_trial_balance($agent_id, $keys);
                break;
            case "users":
                $net_profit = $total_revenue - $total_expense;
                break;
        }//////////////////////


        return $net_profit;*/
    }

    public function customer_balance_for_opening_balance($c_id, $accounting_year)
    {
        $accounting_year = $accounting_year;
        $customer_balance = array(
            'revenue'=>0,
            'cash'=>0,
            'receivable'=>0,
            'shortage_expense_at_destination'=>0,
            'shortage_expense_liability_at_destination'=>0,
            'shortage_expense_after_decanding'=>0,
            'shortage_expense_liability_after_decanding'=>0,
        );

        //calculating revenue;
        $this->db->select("SUM((trips_details.freight_unit * trips_details.product_quantity)*(100-contractor_commission)/100) as revenue, SUM( (( product_quantity - qty_at_destination )* price_unit) ) as expense_at_destination, SUM( (( qty_at_destination - qty_after_decanding )* price_unit) ) as expense_after_decanding");
        $this->db->from('trips');
        $this->db->where('trips.active',1);
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $this->db->where(array(
            'trips.entryDate >='=>$accounting_year['from'],
            'trips.entryDate <='=>$accounting_year['to'],
        ));
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));

        $result = $this->db->get()->result();
        if(sizeof($result) >= 1){
            $customer_balance['revenue'] = round($result[0]->revenue, 3);
            $customer_balance['shortage_expense_at_destination'] = round($result[0]->expense_at_destination, 3);
            $customer_balance['shortage_expense_after_decanding'] = round($result[0]->expense_after_decanding, 3);
            $customer_balance['shortage_expense_liability_at_destination'] = round($result[0]->expense_at_destination, 3);
            $customer_balance['shortage_expense_liability_after_decanding'] = round($result[0]->expense_after_decanding, 3);
        }


        $this->db->select("SUM(customer_accounts.amount) as cash");
        $this->db->from('trips');
        $this->db->where('trips.active',1);
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id', 'left');
        $this->db->join('customer_accounts', 'customer_accounts.trip_detail_id = trips_details.id', 'left');
        $this->db->where(array(
            'trips.entryDate >='=>$accounting_year['from'],
            'trips.entryDate <='=>$accounting_year['to'],
        ));
        $this->db->where(array(
            'trips.customer_id'=>$c_id,
        ));
        $result = $this->db->get()->result();
        if(sizeof($result) >= 1){
            $customer_balance['cash'] = round($result[0]->cash, 3);
        }

        $customer_balance['receivable'] = $customer_balance['revenue'] - $customer_balance['cash'];

        /////////////Applying the shortage formula////////////////
        if($customer_balance['shortage_expense_after_decanding'] != 0){
            $customer_balance['shortage_expense_at_destination'] = 0;
            $customer_balance['shortage_expense_liability_at_destination'] = 0;
        }
        /////////////////////////////////////////////

        return $customer_balance;
    }

    public function opening_balance($agent, $agent_id)
    {
        $custom_from = (isset($_GET['custom_from']))?$_GET['custom_from']:'';
        $custom_to = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
        $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:'';
        $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:'';
        if($custom_from != ''){
            $accounting_year_from = $custom_from;
        }
        if($custom_to != ''){
            $accounting_year_to = $custom_to;
        }

        //calculating accounting year
        if($accounting_year_from == '' || $accounting_year_to == ''){
            $accounting_year = $this->accounts_model->accounting_year();
        }else{
            $accounting_year['from'] = $accounting_year_from;
            $accounting_year['to'] = $accounting_year_to;
        }

        //setting keys for searchings
        $keys_1['voucher_id'] = '';
        $keys_1['voucher_type']='';
        $keys_1['custom_from'] = '';;
        $keys_1['custom_to'] = '';;
        $keys_1['title'] = '';
        $keys_1['ac_type'] = '';
        $keys_1['agent_type'] = '';
        $keys_1['agent_id'] = '';
        $keys_1['voucher_detail'] = '';
        $keys_1['summery'] = '';
        $keys_1['tanker'] = '';
        $keys_1['trip_id'] = '';
        $keys_1['trip_detail_id'] = '';
        $keys_1['accounting_year_from'] = "1947-01-01";
        $keys_1['accounting_year_to'] = $this->helper_model->change_date($accounting_year['from'],1,'sub');

        $net_profit = $this->net_profit($agent, $agent_id, $keys_1);

        //calculating revenue;
        $this->db->select("SUM(voucher_entry.credit_amount)-SUM(voucher_entry.debit_amount) as equity");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $where = "(voucher_entry.ac_type='owner_equity' OR voucher_entry.ac_type='retain_earning' OR  voucher_entry.ac_type='dividend')";
        $this->db->where($where);
        $this->db->where('voucher_journal.person_tid',$agent.".".$agent_id);
        $this->db->where('voucher_journal.active',1);

        $this->db->where(array(
            'voucher_journal.voucher_date >='=>$keys_1['accounting_year_from'],
            'voucher_journal.voucher_date <='=> $keys_1['accounting_year_to'],
        ));

        $result = $this->db->get()->result();

        $equity = 0;
        if($result){
            $equity = $result[0]->equity;
        }

        $opening_balance = $equity + $net_profit;

        return $opening_balance;
    }


    public function closing_balance($agent, $agent_id)
    {
        $custom_from = (isset($_GET['custom_from']))?$_GET['custom_from']:'';
        $custom_to = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
        $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:'';
        $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:'';
        if($custom_from != ''){
            $accounting_year_from = $custom_from;
        }
        if($custom_to != ''){
            $accounting_year_to = $custom_to;
        }

        //calculating accounting year
        if($accounting_year_from == '' || $accounting_year_to == ''){
            $accounting_year = $this->accounts_model->accounting_year();
        }else{
            $accounting_year['from'] = $accounting_year_from;
            $accounting_year['to'] = $accounting_year_to;
        }

        //setting keys for searchings
        $keys_1['voucher_id'] = '';
        $keys_1['voucher_type']='';
        $keys_1['custom_from'] = '';;
        $keys_1['custom_to'] = '';;
        $keys_1['title'] = '';
        $keys_1['ac_type'] = '';
        $keys_1['agent_type'] = '';
        $keys_1['agent_id'] = '';
        $keys_1['voucher_detail'] = '';
        $keys_1['summery'] = 0;
        $keys_1['tanker'] = '';
        $keys_1['trip_id'] = '';
        $keys_1['trip_detail_id'] = '';
        $keys_1['accounting_year_from'] = "1947-01-01";
        $keys_1['accounting_year_to'] = $accounting_year['to'];

        $net_profit = $this->net_profit($agent, $agent_id, $keys_1);

        //calculating revenue;
        $this->db->select("SUM(voucher_entry.credit_amount)-SUM(voucher_entry.debit_amount) as equity");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $where = "(voucher_entry.ac_type='owner_equity' OR voucher_entry.ac_type='retain_earning' OR  voucher_entry.ac_type='dividend')";
        $this->db->where($where);
        $this->db->where('voucher_journal.person_tid',$agent.".".$agent_id);
        $this->db->where('voucher_journal.active',1);

        $this->db->where(array(
            'voucher_journal.voucher_date >='=>$keys_1['accounting_year_from'],
            'voucher_journal.voucher_date <='=> $keys_1['accounting_year_to'],
        ));

        $result = $this->db->get()->result();

        $equity = 0;
        if($result){
            $equity = $result[0]->equity;
        }

        $closing_balance = $equity + $net_profit;
        return $closing_balance;
    }

    public function opening_balance_for_ledger($agent, $agent_id)
    {
        $custom_from = (isset($_GET['custom_from']))?$_GET['custom_from']:'';
        $custom_to = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
        $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:'';
        $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:'';
        if($custom_from != ''){
            $accounting_year_from = $custom_from;
        }
        if($custom_to != ''){
            $accounting_year_to = $custom_to;
        }

        /*
         * ---------------------------------------------------------
         * Below area was commented to hide accounting year
         * functionality, so that we can activate it again when
         * needed.
         * --------------------------------------------------------
         */
            //calculating accounting year
            //if($accounting_year_from == '' || $accounting_year_to == ''){
            //$accounting_year = $this->accounts_model->accounting_year();
            //}else{
            //$accounting_year['from'] = $accounting_year_from;
            //$accounting_year['to'] = $accounting_year_to;
            //}
            //$this->db->where('voucher_journal.voucher_date <',$accounting_year['from']);
        /*-------------------------------------------------------------------------------------*/
        /*
         * -----------------------------------------------------
         * This area was activated when accounting year
         * functionality was being hidden.
         * we can coment this area to activate the upper
         * functionality
         * -----------------------------------------------------
         */
        if($custom_from != '')
        {
            $this->db->where('voucher_journal.voucher_date <',$custom_from);
        }else
        {
            $this->db->where('voucher_journal.voucher_date <','0000-00-00');
        }
        /*---------------------------------------------------------*/
        /*
         * computing account title
         */
        $requested_account_title_id = $_GET['ledger_account_title_id'];
        /*****************************/

        //calculating opening balance;
        $this->db->select("SUM(voucher_entry.debit_amount)-SUM(voucher_entry.credit_amount) as balance");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id');
        //$where = "(voucher_entry.ac_type='owner_equity' OR voucher_entry.ac_type='retain_earning' OR  voucher_entry.ac_type='dividend')";
        //$this->db->where($where);
        $this->db->where('voucher_journal.person_tid',$agent.".".$agent_id);
        $this->db->where('voucher_journal.active',1);
        $this->db->where('voucher_journal.ignored',0);
        if($requested_account_title_id != '*'){
            $this->db->where(array(
                'account_titles.id'=>$requested_account_title_id,
            ));
            $this->db->where(array(
                'voucher_entry.account_title_id'=>$requested_account_title_id,
            ));
        }
        $this->db->where(array(
            'voucher_entry.related_other_agent'=>$_GET['related_other_agent'],
            'voucher_entry.related_customer'=>$_GET['related_customer'],
            'voucher_entry.related_contractor'=>$_GET['related_contractor'],
            'voucher_entry.related_company'=>$_GET['related_company'],
        ));
        if(isset($_GET['tanker']) && $_GET['tanker'] != '')
        {
            $this->db->where('voucher_journal.tanker_id', $_GET['tanker']);
        }
        if(isset($_GET['voucher_detail']) && $_GET['voucher_detail'] != '')
        {
            $this->db->like('voucher_journal.detail',$_GET['voucher_detail']);
        }
        if(isset($_GET['summery']) && $_GET['summery'] != '')
        {
            $this->db->like('voucher_entry.description',$_GET['summery']);
        }

        $result = $this->db->get()->result();

        $opening_balance = 0;
        if($result){
            $opening_balance = $result[0]->balance;
        }

        return round($opening_balance, 3);
    }

    public function opening_balance_for_tankers_ledger($keys)
    {
        $this->db->select("SUM(voucher_entry.debit_amount)-SUM(voucher_entry.credit_amount) as balance");
        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');

        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.ignored !='=>1,
            'voucher_journal.person_tid'=>'users.1',
        ));
        if($keys['from'] != '')
        {
            $this->db->where('voucher_journal.voucher_date <',$keys['from']);
        }else{ return 0; }
        if($keys['tanker_id'] != '')
            $this->db->where_in('voucher_journal.tanker_id',$keys['tanker_id']);
        /*applying conditions*/
        $where = "((voucher_entry.dr_cr = 1 AND account_titles.type = 'expense') OR (voucher_entry.dr_cr = 0 AND account_titles.type = 'income'))";
        $this->db->where($where);
        $result = $this->db->get()->result();
        $balance = round($result[0]->balance, 3);
        return $balance;

    }

    public function account_titles()
    {
        $this->db->select("*");
        $this->db->order_by('title','asc');
        $result = $this->db->get('account_titles')->result();
        return $result;
    }
    public function account_title($id)
    {
        $this->db->select("*");
        $this->db->order_by('title','asc');
        $this->db->where('id',$id);
        $result = $this->db->get('account_titles')->result();
        if($result)
        {
            return $result[0];
        }else{
            null;
        }
    }

    public function add_account_title()
    {
        $data['title'] = $this->input->post('title');
        $data['type'] = $this->input->post('ac_types');
        if($data['type'] == 'expense')
        {
            $data['secondary_type'] = "other_expense";
        }
        $this->db->insert('account_titles', $data);
    }

    public function accounting_year()
    {
        $today = date('Y-m-d');

        $this->db->from('accounting_year');
        $result = $this->db->get()->result();
        //var_dump($result); die();
        $from = $result[0]->from;
        $from_parts = explode('-',$from);
        $from = date('Y')."-".$from_parts[1]."-".$from_parts[2];
        if($this->helper_model->bigger_date($from, $today) == true){
            $from = (intval(date('Y')) - 1)."-".$from_parts[1]."-".$from_parts[2];

            $from_parts = explode('-',$from);
            $to = (intval($from_parts[0]) + 1)."-".$from_parts[1]."-".$from_parts[2];
            /*$date = Carbon::createFromFormat('Y-m-d',$from);
            $to = $date->addYear()->toDateString();*/
        }else{
            /*$date = Carbon::createFromFormat('Y-m-d',$from);
            $to = $date->addYear()->toDateString();*/

            $from_parts = explode('-',$from);
            $to = (intval($from_parts[0]) + 1)."-".$from_parts[1]."-".$from_parts[2];
        }

        //subtracting one day from to date
        $date = Carbon::createFromFormat('Y-m-d',$to);
        $to = $date->subDay()->toDateString();

        $accounting_year['from'] = $from;
        $accounting_year['to'] =$to;

        //var_dump($accounting_year); die();
        return $accounting_year;
    }

    public function fetch_trial_balance_settings()
    {
        $group_by = array();
        $this->db->select('group_by');
        $result = $this->db->get_where('trial_balance_settings', array('status'=>1))->result();
        foreach($result as $r){
            array_push($group_by, $r->group_by);
        }

        return $group_by;
    }

    public function save_trial_balance_settings()
    {
        $title = (isset($_POST['title']))?$_POST['title']:false;
        $ac_type = (isset($_POST['ac_type']))?$_POST['ac_type']:false;
        $related_agent = (isset($_POST['related_agent']))?$_POST['related_agent']:false;

        $batch_data = array();
        if($title == true){
            $data = array(
                'group_by'=>'title',
                'status'=>1,
            );
            array_push($batch_data, $data);
        }
        if($ac_type == true){
            $data = array(
                'group_by'=>'ac_type',
                'status'=>1,
            );
            array_push($batch_data, $data);
        }
        if($related_agent == true){
            $data = array(
                'group_by'=>'related_other_agent',
                'status'=>1,
            );
            array_push($batch_data, $data);
            $data = array(
                'group_by'=>'related_customer',
                'status'=>1,
            );
            array_push($batch_data, $data);
            $data = array(
                'group_by'=>'related_contractor',
                'status'=>1,
            );
            array_push($batch_data, $data);
            $data = array(
                'group_by'=>'related_company',
                'status'=>1,
            );
            array_push($batch_data, $data);
        }
        $data = array(
            'status'=>0,
        );
        $this->db->update('trial_balance_settings', $data);

        if(sizeof($batch_data) != 0){
            $this->db->update_batch('trial_balance_settings',$batch_data, 'group_by');
        }
        return true;
    }

    public function contractor_accounts_for_trial_balance($contractor_id, $keys)
    {
        $contractor_accounts = array(
            'freight_receivable'=>0,
            'freight_received'=>0,
            'freight_payable'=>0,
            'freight_paid'=>0,
            'company_commission_payable'=>0,
            'company_commission_paid'=>0,
            'commission_received'=>0,
            'commission_pending'=>0,
            'service_charges_received'=>0,
            'service_charges_pending'=>0,
        );

        ///var_dump($keys);
        //calculating accounting year for customer balance
        $trip_duration = array();
        if($keys['custom_from'] != '' && $keys['custom_to'] != ''){
            $trip_duration['from'] = $keys['custom_from'];
            $trip_duration['to'] = $keys['custom_to'];
        }else{
            $trip_duration['from'] = $keys['accounting_year_from'];
            $trip_duration['to'] = $keys['accounting_year_to'];
        }


        //applying keys....
        $this->db->where('trips.entryDate >=',$trip_duration['from']);
        $this->db->where('trips.entryDate <=',$trip_duration['to']);

        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.contractor_id'=>$contractor_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $trips = $this->db->get()->result();
        $trips_ids = array();
        foreach($trips as $trip){
            array_push($trips_ids, $trip->trip_id);
        }

        $final_trips = $this->trips_model->test_trips_details($trips_ids);

        $total_contractor_freight = 0;
        $total_contractor_freight_received = 0;
        $total_payable_to_customer = 0;
        $total_paid_to_customer = 0;
        $total_contractor_commission = 0;
        $total_contractor_commission_received = 0;
        $total_service_charges = 0;
        $total_service_charges_received = 0;
        $total_company_commission_amount = 0;
        $total_paid_to_company = 0;

        foreach($final_trips as $trip){
            foreach($trip->trip_related_details as $detail){
                $total_contractor_freight += $detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company());
                $total_contractor_freight_received += $detail->get_paid_to_contractor();
                $total_payable_to_customer += round($detail->get_customer_freight_amount($trip->customer->freight), 3);
                $total_paid_to_customer += round($detail->get_paid_to_customer(),3);

                $contractor_commission = $trip->contractor->commission_1 - $trip->company->wht - $trip->company->commission_1;
                $contractor_commission_amount = $detail->get_contractor_commission_amount($contractor_commission);

                $total_contractor_commission += $contractor_commission_amount;
                if($detail->is_contractor_commission_paid($trip->customer->freight) == true){
                    $total_contractor_commission_received += $contractor_commission_amount;
                    $total_service_charges_received += $detail->contractor_benefits();
                }

                $total_service_charges += $detail->contractor_benefits();

                $company_commission_amount = round($detail->get_company_commission_amount($trip->company->commission_1), 3);
                $total_company_commission_amount += $company_commission_amount;
                $total_paid_to_company += round($detail->get_paid_to_company(), 3);
            }
        }

        $contractor_accounts['freight_receivable'] = round(($total_contractor_freight - $total_contractor_freight_received), 3);
        $contractor_accounts['freight_received'] = round($total_contractor_freight_received, 3);
        $contractor_accounts['freight_payable'] = round(($total_payable_to_customer- $total_paid_to_customer), 3);
        $contractor_accounts['freight_paid'] = round($total_paid_to_customer, 3);
        $contractor_accounts['commission_received'] = round($total_contractor_commission_received, 3);
        $contractor_accounts['commission_pending'] = round(($total_contractor_commission - $total_contractor_commission_received), 3);
        $contractor_accounts['company_commission_payable'] = round(($total_company_commission_amount- $total_paid_to_company), 3);
        $contractor_accounts['company_commission_paid'] = round($total_paid_to_company, 3);
        $contractor_accounts['service_charges_received'] = round($total_service_charges_received, 3);
        $contractor_accounts['service_charges_pending'] = round(($total_service_charges - $total_service_charges_received) , 3);

        return $contractor_accounts;
    }


    public function company_accounts_for_trial_balance($company_id, $keys)
    {
        $company_accounts = array(
            'total_freight'=>0,
            'contractor_freight_paid'=>0,
            'contractor_freight_pending'=>0,
            'wht_revenue'=>0,
            'company_commission_revenue'=>0,
            'company_commission_received'=>0,
            'company_commission_remaining'=>0,
        );

        ///var_dump($keys);
        //calculating accounting year for customer balance
        $trip_duration = array();
        if($keys['custom_from'] != '' && $keys['custom_to'] != ''){
            $trip_duration['from'] = $keys['custom_from'];
            $trip_duration['to'] = $keys['custom_to'];
        }else{
            $trip_duration['from'] = $keys['accounting_year_from'];
            $trip_duration['to'] = $keys['accounting_year_to'];
        }


        //applying keys....
        $this->db->where('trips.entryDate >=',$trip_duration['from']);
        $this->db->where('trips.entryDate <=',$trip_duration['to']);

        if($keys['trip_id'] != ''){
            $this->db->where('trips.id',$keys['trip_id']);
        }
        if($keys['tanker'] != ''){
            $this->db->where('trips.tanker_id',$keys['tanker']);
        }
        ///////////////////////////////////////////////////////

        $this->db->select('trips.id as trip_id');
        //$this->db->limit($limit, $start);
        $this->db->distinct('trips.id');
        $this->db->where(array(
            'trips.company_id'=>$company_id,
        ));

        $this->db->from('trips');
        $this->db->where('trips.active',1);
        //join starts..
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $trips = $this->db->get()->result();
        $trips_ids = array();
        foreach($trips as $trip){
            array_push($trips_ids, $trip->trip_id);
        }

        $final_trips = $this->trips_model->test_trips_details($trips_ids);

        $total_freight = 0;
        $total_contractor_freight = 0;
        $total_contractor_freight_paid = 0;
        $total_wht_revenue = 0;
        $total_company_commission_amount = 0;
        $total_company_commission_received = 0;


        foreach($final_trips as $trip){
            foreach($trip->trip_related_details as $detail){
                $total_freight += $detail->get_total_freight_for_company();
                $total_contractor_freight += $detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company());
                $total_contractor_freight_paid += $detail->get_paid_to_contractor();
                $total_wht_revenue += $detail->get_wht_amount($trip->company->wht);
                $company_commission_amount = round($detail->get_company_commission_amount($trip->company->commission_1), 3);
                $total_company_commission_amount += $company_commission_amount;
                $total_company_commission_received += round($detail->get_paid_to_company(), 3);
            }
        }

        $company_accounts['total_freight'] = round(($total_freight), 3);
        $company_accounts['contractor_freight_paid'] = round($total_contractor_freight_paid, 3);
        $company_accounts['contractor_freight_pending'] = round(($total_contractor_freight- $total_contractor_freight_paid), 3);
        $company_accounts['wht_revenue'] = round($total_wht_revenue, 3);
        $company_accounts['company_commission_revenue'] = round($total_company_commission_amount, 3);
        $company_accounts['company_commission_received'] = round(($total_company_commission_received), 3);
        $company_accounts['company_commission_pending'] = round(($total_company_commission_amount- $total_company_commission_received), 3);

        return $company_accounts;
    }


    public function global_balance_sheet($keys)
    {
        $this->db->select("*");
        $this->db->from("voucher_journal");
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where('voucher_journal.active',1);
        $where = "(account_titles.type = 'assets' OR account_titles.type = 'liability' OR account_titles.type = 'owner equity')";
        $this->db->where($where);
        if($keys['agent_type'] != ''){
            if($keys['agent_type'] == 'customers'){
                $agent_type = 'related_customer';
            }
            if($keys['agent_type'] == 'other_agents'){
                $agent_type = 'related_other_agent';
            }
            if($keys['agent_type'] == 'carriage_contractors'){
                $agent_type = 'related_contractor';
            }

            if($keys['agent_id'] != ''){
                $where = "(voucher_entry.$agent_type = ".$keys['agent_id']." OR (voucher_journal.person_tid = '".$keys['agent_type'].".".$keys['agent_id']." AND related_customer = 0 AND related_contractor = 0 AND related_company = 0 AND related_other_agent = 0'))";
                $this->db->where($where);
            }else{
                $this->db->where('voucher_entry.'.$agent_type.' !=', 0);
            }
        }

        if($keys['from'] != ""){
            $this->db->where('voucher_journal.voucher_date >=',$keys['from']);
        }
        if($keys['to'] != ""){
            $this->db->where('voucher_journal.voucher_date <=', $keys['to']);
        }

        $result = $this->db->get()->result();
        return $result;
    }
    public function global_balance_sheet_opening_balance($keys)
    {
        $this->db->select("(SUM(voucher_entry.debit_amount) - (SUM(voucher_entry.credit_amount))) as balance");
        $this->db->from("voucher_journal");
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where('voucher_journal.active',1);
        $where = "(voucher_entry.ac_type = 'bank' OR voucher_entry.ac_type = 'payable' OR voucher_entry.ac_type = 'liability' OR voucher_entry.ac_type = 'assets' OR voucher_entry.ac_type = 'receivables')";
        $this->db->where($where);
        if($keys['agent_type'] != ''){
            if($keys['agent_type'] == 'customers'){
                $agent_type = 'related_customer';
            }
            if($keys['agent_type'] == 'other_agents'){
                $agent_type = 'related_other_agent';
            }
            if($keys['agent_type'] == 'carriage_contractors'){
                $agent_type = 'related_contractor';
            }
            if($keys['agent_type'] == 'companies'){
                $agent_type = 'related_company';
            }

            if($keys['agent_id'] != ''){
                $where = "(voucher_entry.$agent_type = ".$keys['agent_id']." OR (voucher_journal.person_tid = '".$keys['agent_type'].".".$keys['agent_id']." AND related_customer = 0 AND related_contractor = 0 AND related_company = 0 AND related_other_agent = 0'))";
                $this->db->where($where);
            }else{
                $this->db->where('voucher_entry.'.$agent_type.' !=', 0);
            }
        }

        if($keys['from'] != ""){
            $this->db->where('voucher_journal.voucher_date <',$keys['from']);
        }else{
            return 0;
        }

        $result = $this->db->get()->result();
        return $result[0]->balance;
    }

    public function credit_mass_payment_contractor($account_holder_type, $account_holder_id)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        //computing related agent
        $related_other_agent = ($this->input->post('agent_type') == 'other_agents')?$this->input->post('agent_id'):0;
        $related_customer = ($this->input->post('agent_type') == 'customers')?$this->input->post('agent_id'):0;
        $related_contractor = ($this->input->post('agent_type') == 'contractors')?$this->input->post('agent_id'):0;
        $related_company = ($this->input->post('agent_type') == 'companies')?$this->input->post('agent_id'):0;
        ///////////////////////////////

        //computing account titile id
        $voucher_type = $this->input->post('voucher_type');
        $this->db->select('id');
        if($voucher_type == '1'){
            $result = $this->db->get_where('account_titles', array('title'=>'freight commission'))->result();
        }else{
            $result = $this->db->get_where('account_titles', array('title'=>'service charges'))->result();
        }
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        ///////////////////////////////////////////////////////
        $trips_ids = explode('_',$this->input->post('trip_ids'));
        $trips = $this->trips_model->parametrized_trips_engine($trips_ids, "contractor_accounts");

        $voucher_entries = array();

        //inserting voucher journals and making entries...
        foreach($trips as $trip){
            foreach($trip->trip_related_details as $detail){

                if($voucher_type == 1){
                    $contractor_commission = $trip->contractor->commission_1 - $trip->company->wht - $trip->company->commission_1;
                    $contractor_commission_amount = $detail->get_contractor_commission_amount($contractor_commission);
                    $contractor_commission_credit_amount = $detail->get_total_contractor_commission_credit_amount();
                    $creditable_amount = $contractor_commission_amount - $contractor_commission_credit_amount;
                }else{
                    $service_charges = round($detail->contractor_benefits(), 3);
                    $contractor_service_charges_credit_amount = $detail->get_total_contractor_service_charges_credit_amount();
                    $creditable_amount = $service_charges - $contractor_service_charges_credit_amount;
                }
                if($creditable_amount != 0){
                    //saving the voucher journal
                    $journal_voucher_data = array(
                        'voucher_date' =>($this->input->post('voucher_date') == '')?date('Y-m-d'):$this->input->post('voucher_date'),
                        'detail' => $this->input->post('voucher_details'),
                        'person_tid' => $account_holder_type.".".$account_holder_id,
                        'trip_id' => $trip->trip_id,
                        'trip_product_detail_id'=>$detail->product_detail_id,
                        'tanker_id' => $trip->tanker->id,
                    );
                    $result = $this->db->insert('voucher_journal',$journal_voucher_data);
                    if($result == true){
                        $last_insert_id = $this->db->insert_id();
                        //first entry
                        $voucher_entry = array(
                            'ac_type'=>'dividend',
                            'account_title_id'=>$account_title_id,
                            'description'=>'Mass Payment',
                            'related_other_agent'=>0,
                            'related_customer'=>0,
                            'related_contractor'=>0,
                            'related_company'=>'0',
                            'debit_amount'=>$creditable_amount,
                            'credit_amount'=>0,
                            'dr_cr'=>1,
                            'journal_voucher_id'=>$last_insert_id,
                        );
                        array_push($voucher_entries, $voucher_entry);
                        //second entry
                        $voucher_entry = array(
                            'ac_type'=>'payable',
                            'account_title_id'=>$account_title_id,
                            'description'=>'Mass Payment',
                            'related_other_agent'=>$related_other_agent,
                            'related_customer'=>$related_customer,
                            'related_contractor'=>$related_contractor,
                            'related_company'=>0,
                            'debit_amount'=>0,
                            'credit_amount'=>$creditable_amount,
                            'dr_cr'=>0,
                            'journal_voucher_id'=>$last_insert_id,
                        );
                        array_push($voucher_entries, $voucher_entry);
                    }
                }
            }
        }

        //now its time to insert this voucher in database...
        if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
            return true;
        }else{
            return false;
        }
    }


    public function mass_credit_customer_freight($account_holder_type, $account_holder_id)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");

        //computing related agent
        $related_other_agent = ($this->input->post('agent_type') == 'other_agents')?$this->input->post('agent_id'):0;
        $related_customer = ($this->input->post('agent_type') == 'customers')?$this->input->post('agent_id'):0;
        $related_contractor = ($this->input->post('agent_type') == 'contractors')?$this->input->post('agent_id'):0;
        $related_company = ($this->input->post('agent_type') == 'companies')?$this->input->post('agent_id'):0;
        ///////////////////////////////

        //computing account titile id
        $voucher_type = $this->input->post('voucher_type');
        $this->db->select('id');
        $result = $this->db->get_where('account_titles', array('title'=>'freight'))->result();

        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        ///////////////////////////////////////////////////////
        $trips_ids = explode('_',$this->input->post('trip_ids'));
        $trips = $this->trips_model->parametrized_trips_engine($trips_ids, "customer_accounts");

        $voucher_entries = array();

        //inserting voucher journals and making entries...
        foreach($trips as $trip){
            foreach($trip->trip_related_details as $detail){

                $customer_freight_amount = round($detail->get_customer_freight_amount($trip->customer->freight), 3);
                $customer_freight_credit_amount = $detail->get_total_customer_freight_credit_amount();
                $creditable_amount = $customer_freight_amount - $customer_freight_credit_amount;

                if($creditable_amount != 0){
                    //saving the voucher journal
                    $journal_voucher_data = array(
                        'voucher_date' =>($this->input->post('voucher_date') == '')?date('Y-m-d'):$this->input->post('voucher_date'),
                        'detail' => $this->input->post('voucher_details'),
                        'person_tid' => $account_holder_type.".".$account_holder_id,
                        'trip_id' => $trip->trip_id,
                        'trip_product_detail_id'=>$detail->product_detail_id,
                        'tanker_id' => $trip->tanker->id,
                    );
                    $result = $this->db->insert('voucher_journal',$journal_voucher_data);
                    if($result == true){
                        $last_insert_id = $this->db->insert_id();
                        //first entry
                        $voucher_entry = array(
                            'ac_type'=>'dividend',
                            'account_title_id'=>$account_title_id,
                            'description'=>'Mass Payment',
                            'related_other_agent'=>0,
                            'related_customer'=>0,
                            'related_contractor'=>0,
                            'related_company'=>'0',
                            'debit_amount'=>$creditable_amount,
                            'credit_amount'=>0,
                            'dr_cr'=>1,
                            'journal_voucher_id'=>$last_insert_id,
                        );
                        array_push($voucher_entries, $voucher_entry);
                        //second entry
                        $voucher_entry = array(
                            'ac_type'=>'payable',
                            'account_title_id'=>$account_title_id,
                            'description'=>'Mass Payment',
                            'related_other_agent'=>$related_other_agent,
                            'related_customer'=>$related_customer,
                            'related_contractor'=>$related_contractor,
                            'related_company'=>0,
                            'debit_amount'=>0,
                            'credit_amount'=>$creditable_amount,
                            'dr_cr'=>0,
                            'journal_voucher_id'=>$last_insert_id,
                        );
                        array_push($voucher_entries, $voucher_entry);
                    }
                }
            }
        }

        //now its time to insert this voucher in database...
        if($this->db->insert_batch('voucher_entry', $voucher_entries) == true){
            return true;
        }else{
            return false;
        }
    }


    public function opening_balance_for_voucher($agent_type , $agent_id, $title_id, $voucher_id='')
    {
        $this->db->select("(SUM(voucher_entry.debit_amount) - (SUM(voucher_entry.credit_amount))) as balance");
        $this->db->from("voucher_journal");
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where('voucher_journal.active',1);
        $this->db->where('voucher_journal.ignored',0);
        switch($agent_type)
        {
            case "customers":
                $agent_type = 'related_customer';
                break;
            case "carriage_contractors":
                $agent_type = 'related_contractor';
                break;
            case "other_agents":
                $agent_type = 'related_other_agent';
                break;
            case "companies":
                $agent_type = 'related_company';
                break;
        }

        $where = "voucher_entry.$agent_type = ".$agent_id;
        $this->db->where($where);
        if($voucher_id != '')
        {
            $this->db->where('voucher_journal.id !=',$voucher_id);
        }
        $this->db->where('voucher_entry.account_title_id',$title_id);

        $result = $this->db->get()->result();
        return round($result[0]->balance, 3);
    }

    public function fetch_shortage_details_by_given_voucher_ids($voucher_ids)
    {
        //push 0 if voucher_ids is empty
        array_push($voucher_ids, 0);
        $this->db->select('voucher_journal.trip_id, voucher_journal.trip_product_detail_id,
                voucher_entry.description as shortage_detail, voucher_journal.shortage_rate,
                voucher_journal.shortage_quantity,
        ');
        $this->db->from('voucher_journal');
        $this->join_vouchers();
        $this->db->where('voucher_journal.active',1);
        $this->db->where_in('voucher_journal.id',$voucher_ids);
        $result = $this->db->get()->result();
        return $result;
    }
}
