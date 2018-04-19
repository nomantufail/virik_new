<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Admin extends ParentController {

    //public variables...

    public function __construct()
    {
        parent::__construct();

    }

    public function test()
    {
        $this->load->view('components/header');
        $this->load->view('admin/test');
        $this->load->view('components/footer');
    }
    /* The default function that gets called when visiting the page */
    public function index()
    {
        if($this->login == true){

            //redirecting to the trips area
            //because home is not completed yet
            redirect(base_url()."trips/show/primary");

            $headerData = array(
                'title' => 'Petroleum Test Project',
                'page' => 'home',
            );

           //generating dashboard
            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();

            $agent = "customers";
            $agent_id = 1;

            //setting keys for searchings
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';;
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';;
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year['from'];
            $keys['accounting_year_to'] = $accounting_year['to'];

            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
            );


            //saving the voucher
            if(isset($_POST['save_voucher'])){
                if( $this->accounts_model->save_tanker_expense_voucher() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            $customer_balance = $this->accounts_model->customer_balance($agent_id, $accounting_year);

            //calculating net profit
            $income_statement = $this->accounts_model->income_statement($agent, $agent_id, $keys);
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
            $total_expense = $total_expense + $customer_balance['shortage_expense_at_destination'] + $customer_balance['shortage_expense_after_decanding'];

            $bodyData['total_revenue'] = $total_revenue;
            $bodyData['total_expense'] = $total_expense;
            $bodyData['balance_sheet'] = $this->accounts_model->balance_sheet($agent, $agent_id, $keys);
            $bodyData['net_profit'] = $customer_balance['revenue']+$total_revenue - $total_expense;
            $bodyData['customer_balance'] = $customer_balance;
            $bodyData['bank_cash_summery'] = $this->accounts_model->bank_cash_summery($agent, $agent_id, $accounting_year);
            //$bodyData['titles'] = $this->accounts_model->account_titles();

            //name of account holder
            $this->db->select('name');
            $account_holder = $this->db->get_where($agent, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;

            ///////////////////////////////////////////////////////////////

            $this->load->view('components/header', $headerData);
            $this->load->view('admin/welcome', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }
    }

    /*this script is setting the tanker expense voucher problem(same agent in both entry)*/
//    public function pre_scripting_1()
//    {
//        //die();
//        $voucher_entries = array();
//        //fetching voucher id;
//        $voucher_ids = array();
//        $this->db->select('voucher_journal.id');
//        $result = $this->db->get('voucher_journal')->result();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//        //var_dump($voucher_ids);
//        $journal = $this->helper_model->journal('','',$voucher_ids,'');
//
//        $voucher_entries = array();
//        foreach($journal as $voucher)
//        {
//            if($voucher->entries[0]->related_agent == $voucher->entries[1]->related_agent && $voucher->entries[0]->related_agent_id == $voucher->entries[1]->related_agent_id){
//                foreach($voucher->entries as $entry){
//                    //fill the entry array
//                    if($entry->dr_cr == 'debit'){
//                        $voucher_entry['id'] = $entry->id;
//                        $voucher_entry['related_customer'] = 0;
//                        $voucher_entry['related_contractor'] = 0;
//                        $voucher_entry['related_company'] = 0;
//                        $voucher_entry['related_other_agent'] = 0;
//                        //push the array
//                        array_push($voucher_entries, $voucher_entry);
//                    }
//                }
//            }
//        }
//        //var_dump($voucher_entries);
//        $this->db->update_batch('voucher_entry', $voucher_entries, 'id');
//    }
//
//    public function transfer_tanker_trip_expenses()
//    {
//        //die();
//
//        //fetching trips_expense title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'trip expense'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $trip_expense_title_id = $result[0]->id;
//
//        //fetching trips_expense title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'other expense'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $other_expense_title_id = $result[0]->id;
//
//        //fetching credit_account title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'credit a/c'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $credit_ac_title_id = $result[0]->id;
//
//        //fetching other agent id
//        $this->db->select('id');
//        $result = $this->db->get_where('other_agents', array('name'=>'haji mushtaq'))->result();
//        $other_agent_id = $result[0]->id;
//
//
//        //fetching the tankers_expenses
//        $this->db->select('trips_tankers_expenses.expense_date, trips_tankers_expenses.trip_id,
//        trips_tankers_expenses.tanker_id, trips_tankers_expenses.description, trips_tankers_expenses.amount, trips.customer_id');
//        $this->db->from('trips_tankers_expenses');
//        $this->db->join('trips','trips.id = trips_tankers_expenses.trip_id','left');
//        $result = $this->db->get()->result();
//
//        $voucher_entries = array();
//        foreach($result as $record)
//        {
//            //insert into voucher_journal
//            $voucher_data = array(
//                'voucher_date' =>$record->expense_date,
//                'detail' => 'System Generated Voucher',
//                'person_tid' => "users.1",
//                'trip_id' => $record->trip_id,
//                'trip_product_detail_id'=>'',
//                'tanker_id' => $record->tanker_id,
//            );
//            $this->db->insert('voucher_journal',$voucher_data);
//
//            $inserted_voucher_id = $this->db->insert_id();
//
//            //fill the entry array
//            $entry['account_title_id'] = $trip_expense_title_id/*what title?*/;
//            $entry['description'] = $record->description/*what description*/;
//            $entry['related_customer'] = $record->customer_id/*related customer to be debited*/;
//            $entry['related_other_agent'] = 0/*related customer to be debited*/;
//            $entry['debit_amount'] = $record->amount;
//            $entry['credit_amount'] = 0;
//            $entry['dr_cr'] = 1;
//            $entry['journal_voucher_id'] = $inserted_voucher_id;
//            //push the array
//            array_push($voucher_entries, $entry);
//
//            $entry['account_title_id'] = $credit_ac_title_id/*what title?*/;
//            $entry['description'] = $record->description/*what description*/;
//            $entry['related_other_agent'] = $other_agent_id/*related customer to be debited*/;
//            $entry['related_customer'] = 0/*related customer to be debited*/;
//
//            $entry['debit_amount'] = 0;
//            $entry['credit_amount'] = $record->amount;
//            $entry['dr_cr'] = 0;
//            $entry['journal_voucher_id'] = $inserted_voucher_id;
//            //push the array
//            array_push($voucher_entries, $entry);
//        }
//
//        $this->db->insert_batch('voucher_entry', $voucher_entries);
//        //var_dump($voucher_entries);
//
//    }
//
//    public function transfer_tanker_other_expenses()
//    {
//        //die();
//
//        //fetching trips_expense title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'trip expense'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $trip_expense_title_id = $result[0]->id;
//
//        //fetching trips_expense title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'other expense'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $other_expense_title_id = $result[0]->id;
//
//        //fetching credit_account title id;
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'credit a/c'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $credit_ac_title_id = $result[0]->id;
//
//        //fetching other agent id
//        $this->db->select('id');
//        $result = $this->db->get_where('other_agents', array('name'=>'haji mushtaq'))->result();
//        $other_agent_id = $result[0]->id;
//
//
//        //fetching the tankers_expenses
//        $this->db->select('other_tankers_expenses.expense_date, other_tankers_expenses.tanker_id, other_tankers_expenses.description, other_tankers_expenses.amount, tankers.customerId as customer_id');
//        $this->db->from('other_tankers_expenses');
//        $this->db->join('tankers','tankers.id = other_tankers_expenses.tanker_id','left');
//        $result = $this->db->get()->result();
//
//        $voucher_entries = array();
//        foreach($result as $record)
//        {
//            //insert into voucher_journal
//            $voucher_data = array(
//                'voucher_date' =>$record->expense_date,
//                'detail' => 'System Generated Voucher',
//                'person_tid' => "users.1",
//                'trip_id' => 0,
//                'trip_product_detail_id'=>'',
//                'tanker_id' => $record->tanker_id,
//            );
//            $this->db->insert('voucher_journal',$voucher_data);
//
//            $inserted_voucher_id = $this->db->insert_id();
//
//            //fill the entry array
//            $entry['account_title_id'] = $other_expense_title_id/*what title?*/;
//            $entry['description'] = $record->description/*what description*/;
//            $entry['related_customer'] = $record->customer_id/*related customer to be debited*/;
//            $entry['related_other_agent'] = 0/*related customer to be debited*/;
//            $entry['debit_amount'] = $record->amount;
//            $entry['credit_amount'] = 0;
//            $entry['dr_cr'] = 1;
//            $entry['journal_voucher_id'] = $inserted_voucher_id;
//            //push the array
//            array_push($voucher_entries, $entry);
//
//            $entry['account_title_id'] = $credit_ac_title_id/*what title?*/;
//            $entry['description'] = $record->description/*what description*/;
//            $entry['related_other_agent'] = $other_agent_id/*related customer to be debited*/;
//            $entry['related_customer'] = 0/*related customer to be debited*/;
//
//            $entry['debit_amount'] = 0;
//            $entry['credit_amount'] = $record->amount;
//            $entry['dr_cr'] = 0;
//            $entry['journal_voucher_id'] = $inserted_voucher_id;
//            //push the array
//            array_push($voucher_entries, $entry);
//        }
//
//        $this->db->insert_batch('voucher_entry', $voucher_entries);
//        //var_dump($voucher_entries);
//
//    }
//
//    public function transfer_vouchers_to_user()
//    {
//       //die();
//        $voucher_entries = array();
//        //fetching voucher id;
//        $voucher_ids = array();
//        $this->db->select('voucher_journal.id');
//        $this->db->where(array(
//            'voucher_journal.person_tid !='=>'users.1',
//            'voucher_journal.detail !='=>'system generated voucher',
//        ));
//        $result = $this->db->get('voucher_journal')->result();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//        //var_dump($voucher_ids);
//        $journal = $this->helper_model->journal('','',$voucher_ids,'');
//
//        foreach($journal as $voucher)
//        {
//            $voucher_data = array(
//                'voucher_date' =>$voucher->voucher_date,
//                'detail' => $voucher->voucher_details,
//                'person_tid' => "users.1",
//                'trip_id' => $voucher->trip_id,
//                'trip_product_detail_id'=>$voucher->trip_detail_id,
//                'tanker_id' => $voucher->tanker_id,
//                'ignored'=>$voucher->ignore,
//            );
//            $this->db->insert('voucher_journal',$voucher_data);
//            $inserted_voucher_id = $this->db->insert_id();
//
//            foreach($voucher->entries as $entry)
//            {
//                //fill the entry array
//                $voucher_entry['account_title_id'] = $entry->account_title_id/*what title?*/;
//                $voucher_entry['description'] = $entry->description/*what description*/;
//                if($entry->related_agent == 'self'){
//                    $voucher_entry['related_customer'] = ($voucher->person == 'customers')?$voucher->person_id:0;
//                    $voucher_entry['related_contractor'] = ($voucher->person == 'contractors')?$voucher->person_id:0;
//                    $voucher_entry['related_company'] = ($voucher->person == 'companies')?$voucher->person_id:0;
//                    $voucher_entry['related_other_agent'] = ($voucher->person == 'other_agents')?$voucher->person_id:0;
//                }
//                else
//                {
//                    $voucher_entry['related_customer'] = ($entry->related_agent == 'customers')?$entry->related_agent_id:0;
//                    $voucher_entry['related_contractor'] = ($entry->related_agent == 'contractors')?$entry->related_agent_id:0;
//                    $voucher_entry['related_company'] = ($entry->related_agent == 'companies')?$entry->related_agent_id:0;
//                    $voucher_entry['related_other_agent'] = ($entry->related_agent == 'other_agents')?$entry->related_agent_id:0;
//                }
//                $voucher_entry['debit_amount'] = $entry->debit;
//                $voucher_entry['credit_amount'] = $entry->credit;
//                $voucher_entry['dr_cr'] = ($entry->dr_cr == 'debit')? 1 : 0;
//                $voucher_entry['journal_voucher_id'] = $inserted_voucher_id;
//                //push the array
//                array_push($voucher_entries, $voucher_entry);
//            }
//        }
//
//        $this->db->insert_batch('voucher_entry',$voucher_entries);
//
//    }
//
//    public function remove_vouchers_with_zero_amount()
//    {
//        //die();
//        $this->db->select("voucher_journal.id");
//        $this->db->distinct();
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where('voucher_entry.debit_amount','0');
//        $this->db->where('voucher_entry.credit_amount','0');
//        $result = $this->db->get()->result();
//
//        $voucher_ids = array();
//        foreach($result as $id)
//        {
//            array_push($voucher_ids, $id->id);
//        }
//
//        //deleting the records
//        $this->db->where_in('voucher_journal.id',$voucher_ids );
//        $this->db->delete('voucher_journal');
//    }
//
//    public function vouchers_with_no_title()
//    {
//
//        $this->db->select("voucher_journal.id");
//        $this->db->distinct();
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where('voucher_entry.account_title_id','0');
//        $this->db->where('voucher_journal.person_tid','users.1');
//        $result = $this->db->get()->result();
//
//        $voucher_ids = array();
//        foreach($result as $id)
//        {
//            array_push($voucher_ids, $id->id);
//        }
//
//        var_dump($voucher_ids);
//
//        //deleting the records
//        /*$this->db->where_in('voucher_journal.id',$voucher_ids );
//        $this->db->delete('voucher_journal');*/
//    }
//
//    public function shortage()
//    {
//        //die();
//
//        //fetch required titles
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'destination shortage',
//        ))->result();
//        $destination_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'decanding shortage',
//        ))->result();
//        $decanding_title_id = $result[0]->id;
//        ///////////////////////////
//
//
//        //fetching credit a/c title id
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'credit a/c',
//        ))->result();
//        $credit_ac_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('other_agents',array(
//            'name'=>'haji mushtaq',
//        ))->result();
//        $haji_id = $result[0]->id;
//
//
//        $this->db->select("(trips_details.product_quantity - trips_details.qty_at_destination) as destination_shortage, (trips_details.product_quantity - trips_details.qty_after_decanding) as decanding_shortage, trips_details.price_unit,
//        trips_details.id as trips_details_id, trips_details.trip_id, trips.customer_id, products.productName,
//        trips.entryDate,
//        ");
//        $this->db->from('trips');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id', 'left');
//        $this->db->join('products', 'products.id = trips_details.product','left');
//        $this->db->where('trips.active',1);
//        $where = "((trips_details.product_quantity - trips_details.qty_at_destination) != 0 OR (trips_details.product_quantity - trips_details.qty_after_decanding) != 0)";
//        $this->db->where($where);
//        $result = $this->db->get()->result();
//
//        $voucher_entries = array();
//        $trips_details = array();
//        foreach($result as $record)
//        {
//            if($record->destination_shortage != 0 || $record->decanding_shortage != 0){
//                //insert into voucher_journal
//
//
//                //fill the entry array
//                if($record->destination_shortage != 0)
//                {
//                    $voucher_data = array(
//                        'voucher_date' =>$record->entryDate/*what date*/,
//                        'detail' => 'System Generated Voucher (shortage)'/*what detail*/,
//                        'person_tid' => "users.1",
//                        'trip_id' => $record->trip_id,
//                        'trip_product_detail_id'=>$record->trips_details_id,
//                        'tanker_id' => '',
//                    );
//                    $this->db->insert('voucher_journal',$voucher_data);
//
//                    $inserted_voucher_id = $this->db->insert_id()/*222222222222*/;
//
//                    $entry['account_title_id'] = $destination_title_id/*what title?*/;
//                    $entry['description'] = "Shortage_quantity => ".$record->destination_shortage." Price/Unit => ".$record->price_unit." Product =>".$record->productName/*what description*/;
//                    $entry['related_customer'] = $record->customer_id/*related customer to be debited*/;
//                    $entry['related_other_agent'] = 0;
//                    $entry['debit_amount'] = $record->decanding_shortage * $record->price_unit;
//                    $entry['credit_amount'] = 0;
//                    $entry['dr_cr'] = 1;
//                    $entry['journal_voucher_id'] = $inserted_voucher_id;
//                    //push the array
//                    array_push($voucher_entries, $entry);
//
//                    $entry['account_title_id'] = $credit_ac_title_id/*what title?*/;
//                    $entry['description'] = "Shortage_quantity => ".$record->destination_shortage." Price/Unit => ".$record->price_unit." Product =>".$record->productName/*what description*/;
//                    $entry['related_customer'] = 0/*related customer to be debited*/;
//                    $entry['related_other_agent'] = $haji_id;
//
//                    $entry['debit_amount'] = 0;
//                    $entry['credit_amount'] = $record->decanding_shortage * $record->price_unit;;
//                    $entry['dr_cr'] = 0;
//                    $entry['journal_voucher_id'] = $inserted_voucher_id;
//                    //push the array
//                    array_push($voucher_entries, $entry);
//
//                    $detail = array(
//                        'trips_details.id'=>$record->trips_details_id,
//                        'shortage_voucher_dest'=>$inserted_voucher_id,
//                        'shortage_voucher_decnd'=>0,
//                    );
//                    //push the array
//                    array_push($trips_details, $detail);
//                }
//
//                if($record->decanding_shortage != 0)
//                {
//                    $voucher_data = array(
//                        'voucher_date' =>$record->entryDate/*what date*/,
//                        'detail' => 'System Generated Voucher (shortage)'/*what detail*/,
//                        'person_tid' => "users.1",
//                        'trip_id' => $record->trip_id,
//                        'trip_product_detail_id'=>$record->trips_details_id,
//                        'tanker_id' => '',
//                    );
//                    $this->db->insert('voucher_journal',$voucher_data);
//
//                    $inserted_voucher_id = $this->db->insert_id()/*222222222222*/;
//
//                    $entry['account_title_id'] = $decanding_title_id/*what title?*/;
//                    $entry['description'] = "Shortage_quantity => ".$record->destination_shortage." Price/Unit => ".$record->price_unit." Product =>".$record->productName/*what description*/;
//                    $entry['related_customer'] = $record->customer_id/*related customer to be debited*/;
//                    $entry['related_other_agent'] = 0;
//                    $entry['debit_amount'] = $record->decanding_shortage * $record->price_unit;
//                    $entry['credit_amount'] = 0;
//                    $entry['dr_cr'] = 1;
//                    $entry['journal_voucher_id'] = $inserted_voucher_id;
//                    //push the array
//                    array_push($voucher_entries, $entry);
//
//                    $entry['account_title_id'] = $credit_ac_title_id/*what title?*/;
//                    $entry['description'] = "Shortage_quantity => ".$record->destination_shortage." Price/Unit => ".$record->price_unit." Product =>".$record->productName/*what description*/;
//                    $entry['related_customer'] = 0/*related customer to be debited*/;
//                    $entry['related_other_agent'] = $haji_id;
//                    $entry['debit_amount'] = 0;
//                    $entry['credit_amount'] = $record->decanding_shortage * $record->price_unit;;
//                    $entry['dr_cr'] = 0;
//                    $entry['journal_voucher_id'] = $inserted_voucher_id;
//                    //push the array
//                    array_push($voucher_entries, $entry);
//
//                    //fill the trips_details array
//                    $detail = array(
//                        'trips_details.id'=>$record->trips_details_id,
//                        'shortage_voucher_dest'=>0,
//                        'shortage_voucher_decnd'=>$inserted_voucher_id,
//                    );
//                    //push the array
//                    array_push($trips_details, $detail);
//                }
//
//            }
//
//        }
//        //var_dump($result);
//        //inserting the voucher entries
//        $this->db->insert_batch('voucher_entry', $voucher_entries);
//
//        //updating the trips_details
//        $this->db->update_batch('trips_details',$trips_details, 'trips_details.id');
//    }
//
//    //below script is setting shortage vouchers titles
//    public function post_scripting_1()
//    {
//        //die();
//        //fetching destination and decanding shortage title id
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'destination shortage',
//        ))->result();
//        $destination_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'decanding shortage',
//        ))->result();
//        $decanding_title_id = $result[0]->id;
//        ///////////////////////////
//
//
//        //fetching credit a/c title id
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'credit a/c',
//        ))->result();
//        $credit_ac_title_id = $result[0]->id;
//        ////////////////////////////
//
//        $where = "(account_title_id = ".$destination_title_id." OR account_title_id = ".$decanding_title_id.")";
//        $this->db->where($where);
//        $this->db->where(array(
//            'voucher_entry.dr_cr'=>0,
//        ));
//        //fetching credit a/c title id
//
//        /*$result = $this->db->get('voucher_entry')->result();
//        var_dump($result[0]);*/
//
//        $data = array(
//            'account_title_id'=>$credit_ac_title_id,
//        );
//        $this->db->update('voucher_entry',$data);
//
//        /**********************************************************
//         * Now updating those shortage vouchers which have no title
//         **********************************************************/
//
//        $where = "(description LIKE '%at destination%')";
//        $this->db->where($where);
//        $this->db->where('dr_cr','1');
//        /*$result = $this->db->get('voucher_entry')->result();
//        var_dump($result);*/
//        $data = array(
//            'account_title_id'=>$destination_title_id,
//        );
//        $this->db->update('voucher_entry',$data);
//
//        $where = "(description LIKE '%at destination%')";
//        $this->db->where($where);
//        $this->db->where('dr_cr','0');
//        $data = array(
//            'account_title_id'=>$credit_ac_title_id,
//        );
//        $this->db->update('voucher_entry',$data);
//
//    }
//
//    public function post_scripting_2_dest_shortage()
//    {
//        //fetch required titles
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'destination shortage',
//        ))->result();
//        $destination_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'decanding shortage',
//        ))->result();
//        $decanding_title_id = $result[0]->id;
//        ///////////////////////////
//
//        $this->db->select('trips_details.trip_id');
//        $this->db->where('trips_details.shortage_voucher_dest >', 0);
//        $this->db->where('trips_details.shortage_voucher_dest <',458);
//        $result = $this->db->get('trips_details')->result();
//        $trip_ids = array();
//        foreach($result as $r)
//        {
//            array_push($trip_ids, $r->trip_id);
//        }
//
//        var_dump($trip_ids);
//
//        $this->db->select('voucher_journal.id, voucher_journal.trip_product_detail_id, voucher_journal.trip_id,');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where_in('voucher_journal.trip_id',$trip_ids);
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>'users.1',
//            'voucher_entry.account_title_id'=>$destination_title_id,
//            'voucher_journal.trip_product_detail_id'=>0,
//            'voucher_journal.active'=>1,
//        ));
//        $result = $this->db->get()->result();
//        foreach($result as $record){
//            //var_dump($record);
//            $data = array(
//                'shortage_voucher_dest'=>$record->id,
//            );
//            //var_dump($data);
//            $this->db->where(array(
//                'shortage_voucher_dest <'=>458,
//                'trip_id'=>$record->trip_id,
//            ));
//             $this->db->update('trips_details',$data);
//        }
//
//        $this->db->select('voucher_journal.id, voucher_journal.trip_product_detail_id,');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>'users.1',
//            'voucher_entry.account_title_id'=>$destination_title_id,
//            'voucher_journal.trip_product_detail_id !='=>0,
//            'voucher_journal.active'=>1,
//        ));
//        $result = $this->db->get()->result();
//        $update_data = array();
//        foreach($result as $record){
//            $data = array(
//                'trips_details.id'=>$record->trip_product_detail_id,
//                'shortage_voucher_dest'=>$record->id,
//            );
//            array_push($update_data, $data);
//        }
//        var_dump($update_data);
//        $this->db->update_batch('trips_details',$update_data, 'trips_details.id');
//
//
//
//        //fetch voucher_ids here...
//    }
//
//
//    public function post_scripting_2_decnd_shortage()
//    {
//        //fetch required titles
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'destination shortage',
//        ))->result();
//        $destination_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'decanding shortage',
//        ))->result();
//        $decanding_title_id = $result[0]->id;
//        ///////////////////////////
//
//
//        $this->db->select('trips_details.trip_id');
//        $this->db->where('trips_details.shortage_voucher_decnd >', 0);
//        $this->db->where('trips_details.shortage_voucher_decnd <',458);
//        $result = $this->db->get('trips_details')->result();
//        $trip_ids = array();
//        foreach($result as $r)
//        {
//            array_push($trip_ids, $r->trip_id);
//        }
//
//        var_dump($trip_ids);
//
//
//        $this->db->select('voucher_journal.id, voucher_journal.trip_product_detail_id,');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>'users.1',
//            'voucher_entry.account_title_id'=>$decanding_title_id,
//            'voucher_journal.trip_product_detail_id !='=>0,
//            'voucher_journal.active'=>1,
//        ));
//        $result = $this->db->get()->result();
//        $update_data = array();
//        foreach($result as $record){
//            $data = array(
//                'trips_details.id'=>$record->trip_product_detail_id,
//                'shortage_voucher_decnd'=>$record->id,
//            );
//            array_push($update_data, $data);
//        }
//       var_dump($update_data);
//        $this->db->update_batch('trips_details',$update_data, 'trips_details.id');
//
//
//
//        //fetch voucher_ids here...
//    }
//
//    public function post_scription_2_ignore_vouchers()
//    {
//        $this->db->select('trips_details.shortage_voucher_dest');
//        $this->db->where('trips_details.shortage_voucher_decnd !=',0);
//        $result = $this->db->get('trips_details')->result();
//
//        $update_arary = array();
//        foreach($result as $record){
//            $data = array(
//                'voucher_journal.id'=>$record->shortage_voucher_dest,
//                'ignored'=>1,
//            );
//            array_push($update_arary, $data);
//        }
//        var_dump($update_arary);
//        $this->db->update_batch('voucher_journal',$update_arary, 'voucher_journal.id');
//    }
//
//    public function deactive_with_no_title_and_related_agent()
//    {
//        die();
//        $this->db->select('voucher_journal.id, voucher_entry.account_title_id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>'users.1',
////            'voucher_entry.account_title_id'=>0,
//            'voucher_entry.related_other_agent'=>0,
//            'voucher_entry.related_customer'=>0,
//            'voucher_entry.related_company'=>0,
//            'voucher_entry.related_contractor'=>0,
//            'voucher_journal.active'=>1,
//        ));
//        $result = $this->db->get()->result();
//        var_dump($result);
//        $voucher_ids = array();
//        foreach($result as $r)
//        {
//            array_push($voucher_ids, $r->id);
//        }
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $data = array(
//            'active'=>0,
//        );
//        $this->db->update('voucher_journal',$data);
//    }
//
//    public function duplicate_vouchers()
//    {
//        //fetch required titles
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'destination shortage',
//        ))->result();
//        $destination_title_id = $result[0]->id;
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles',array(
//            'title'=>'decanding shortage',
//        ))->result();
//        $decanding_title_id = $result[0]->id;
//        ///////////////////////////
//
//        $deletable_vouchers = array();
//
//        $this->db->select('trips_details.id, trips_details.shortage_voucher_dest');
//        $this->db->where('trips_details.shortage_voucher_dest !=',0);
//        $result = $this->db->get('trips_details')->result();
//        foreach($result as $r)
//        {
//            $this->db->select('voucher_journal.id, voucher_journal.trip_product_detail_id');
//            $this->db->from('voucher_journal');
//            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//            $this->db->where(array(
//                'voucher_journal.person_tid'=>'users.1',
//                'voucher_journal.trip_product_detail_id'=>$r->id,
//                'voucher_entry.account_title_id'=>$destination_title_id,
//                'voucher_journal.id !='=>$r->shortage_voucher_dest,
//                'voucher_journal.active'=>1,
//            ));
//            $result2 = $this->db->get()->result();
//            foreach($result2 as $r2){
//                array_push($deletable_vouchers, $r2->id);
//            }
//        }
//        var_dump($deletable_vouchers);
//
//        $data = array(
//            'active'=>0,
//        );
//        $this->db->where_in('voucher_journal.id',$deletable_vouchers);
//        $this->db->update('voucher_journal',$data);
//
//
//
//    }
//
//    public function duplicate_voucher_2()
//    {
//        //die();
//        $this->db->select('voucher_journal.id');
//        $result = $this->db->get_where('voucher_journal', array(
//            'active'=>1,
//            'person_tid'=>'users.1',
//        ))->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//        $vouchers = $this->accounts_model->journal("users","1",$voucher_ids,"");
//        $deletable_vouchers = array();
//        for($count = 0; $count < sizeof($vouchers); $count++)
//        {
//            for($count_2 = ($count+1); $count_2 < sizeof($vouchers); $count_2++)
//            {
//                if($this->is_same($vouchers[$count], $vouchers[$count_2]) == true)
//                {
//                    $same_voucher_ids = $vouchers[$count]->voucher_id."_".$vouchers[$count_2]->voucher_id;
//                    array_push($deletable_vouchers, $same_voucher_ids);
//                }
//            }
//        }
//        var_dump($deletable_vouchers);die();
//        $update_vouchers_array = array();
//        foreach($deletable_vouchers as $id){
//            $id_parts = explode('_',$id);
//            $update_data = array(
//                'id'=>$id_parts[1],
//                'detail'=>'system deleted voucher ('.$id_parts[0].')',
//                'active'=>0,
//            );
//            array_push($update_vouchers_array, $update_data);
//        }
//
//        $this->db->update_batch('voucher_journal',$update_vouchers_array,'id');
//
//    }
//    public function test_same($id1, $id2)
//    {
//        $voucher_ids_1 = array();
//        array_push($voucher_ids_1, $id1);
//        $voucher_ids_2 = array();
//        array_push($voucher_ids_2, $id2);
//        $vouchers_1 = $this->accounts_model->journal("users","1",$voucher_ids_1,"");
//        $vouchers_2 = $this->accounts_model->journal("users","1",$voucher_ids_2,"");
//        $voucher_1 = $vouchers_1[0];
//        $voucher_2 = $vouchers_2[0];
//
//        var_dump($voucher_1->voucher_date);
//        var_dump($voucher_2->voucher_date);
//        if($this->is_same($voucher_1, $voucher_2) == true)
//        {
//            echo "same";
//        }else{
//            echo "not same";
//        }
//    }
//    public function is_same($voucher_1, $voucher_2)
//    {
//
//        if($voucher_1->trip_id != $voucher_2->trip_id){
//            return false;
//        }
//        if($voucher_1->trip_detail_id != $voucher_2->trip_detail_id){
//            return false;
//        }
//        if($voucher_1->person_id != $voucher_2->person_id){
//            return false;
//        }
//
//        if($voucher_1->person_name != $voucher_2->person_name){
//            return false;
//        }
//        if($voucher_1->person != $voucher_2->person){
//            return false;
//        }
//        if($voucher_1->tanker_id != $voucher_2->tanker_id){
//            return false;
//        }
//        if($voucher_1->voucher_date != $voucher_2->voucher_date){
//            return false;
//        }
//        if($voucher_1->ignore != $voucher_2->ignore){
//            return false;
//        }
//
//
//        if($voucher_1->entries[0]->title != $voucher_2->entries[0]->title){
//            return false;
//        }
//        if($voucher_1->entries[0]->account_title_id != $voucher_2->entries[0]->account_title_id){
//            return false;
//        }
//        if($voucher_1->entries[0]->related_agent != $voucher_2->entries[0]->related_agent){
//            return false;
//        }
//        if($voucher_1->entries[0]->related_agent_id != $voucher_2->entries[0]->related_agent_id){
//            return false;
//        }
//        if($voucher_1->entries[0]->description != $voucher_2->entries[0]->description){
//            return false;
//        }
//        if($voucher_1->entries[0]->debit != $voucher_2->entries[0]->debit){
//            return false;
//        }
//        if($voucher_1->entries[0]->credit != $voucher_2->entries[0]->credit){
//            return false;
//        }
//        if($voucher_1->entries[0]->dr_cr != $voucher_2->entries[0]->dr_cr){
//            return false;
//        }
//                                 /*************/
//
//        if($voucher_1->entries[1]->title != $voucher_2->entries[1]->title){
//            return false;
//        }
//        if($voucher_1->entries[1]->account_title_id != $voucher_2->entries[1]->account_title_id){
//            return false;
//        }
//        if($voucher_1->entries[1]->related_agent != $voucher_2->entries[1]->related_agent){
//            return false;
//        }
//        if($voucher_1->entries[1]->related_agent_id != $voucher_2->entries[1]->related_agent_id){
//            return false;
//        }
//        if($voucher_1->entries[1]->description != $voucher_2->entries[1]->description){
//            return false;
//        }
//        if($voucher_1->entries[1]->debit != $voucher_2->entries[1]->debit){
//            return false;
//        }
//        if($voucher_1->entries[1]->credit != $voucher_2->entries[1]->credit){
//            return false;
//        }
//        if($voucher_1->entries[1]->dr_cr != $voucher_2->entries[1]->dr_cr){
//            return false;
//        }
//        /******************************/
//
//        return true;
//    }
//
//
//    public function check_opening_balance()
//    {
//        $this->db->select("voucher_date, id");
//        $result = $this->db->get_where('voucher_journal',array(
//            'voucher_date <'=>'2014-10-12',
//        ))->result();
//
//        $update_array = array();
//        foreach($result as $record)
//        {
//            var_dump($record->voucher_date);
//            $date_parts = explode('-',$record->voucher_date);
//            $modified_date = "2014-".$date_parts[1]."-".$date_parts[2];
//            var_dump($modified_date);
//            $data = array(
//                'id'=>$record->id,
//                'voucher_date'=>$modified_date,
//            );
//            array_push($update_array, $data);
//        }
//        $this->db->update_batch('voucher_journal',$update_array, 'id');
//    }
//
//
//    public function auto_voucher_script()
//    {
//        $this->db->select('id');
//        $resutl = $this->db->get_where('trips',array(
//            'active'=>1,
//        ))->result();
//
//        $this->db->trans_start();
//        foreach($resutl as $record)
//        {
//            $this->trips_model->do_auto_accounting_on_trip_save($record->id);
//        }
//        $this->db->trans_complete();
//
//    }
//
//    public function insert_extra_info_in_vouchers()
//    {
//        $this->db->select('id');
//        $result = $this->db->get_where('voucher_journal', array(
//            'voucher_journal.active'=>1,
//            'auto_generated'=>1,
//        ))->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//        }
//        $vouchers = $this->accounts_model->journal("users","1",$voucher_ids,"");
//        $voucher_data_array = array();
//        $entry_data_array = array();
//        foreach($vouchers as $voucher)
//        {
//            //making voucher_data
//            $trip_ids = array($voucher->trip_id,);
//            $trips = $this->trips_model->parametrized_trips_engine($trip_ids,'');
//            $tanker_id = $trips[0]->tanker->id;
//            $voucher_data = array(
//                'id'=>$voucher->voucher_id,
//                'tanker_id'=>$tanker_id,
//            );
//            array_push($voucher_data_array, $voucher_data);
//            ////////////////////
//
//            //making voucher_entries
//            foreach($voucher->entries as $entry)
//            {
//                $trip = $trips[0];
//                /*computing trip detail data*/
//                foreach($trip->trip_related_details as $detail)
//                {
//                    if($detail->product_detail_id == $voucher->trip_detail_id)
//                    {
//                        $description = "Capacity=> ".$trip->tanker->capacity." | product=> ".$detail->product->name." <br> Route=> ".$detail->source->name." to ".$detail->destination->name;
//                    }
//                }
//                /*****************************/
//                $entry_data = array(
//                    'id'=>$entry->id,
//                    'description'=>$description,
//                );
//                array_push($entry_data_array, $entry_data);
//            }
//            /**************************/
//        }
//        $this->db->trans_start();
//
//        $this->db->update_batch('voucher_journal',$voucher_data_array, 'id');
//        $this->db->update_batch('voucher_entry',$entry_data_array,'id');
//
//        $this->db->trans_complete();
//    }
//
//    public function set_tanker_number_in_vouchers()
//    {
//        $this->db->select('voucher_journal.id, voucher_journal.trip_id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>'users.1',
//            'voucher_journal.active'=>1,
//            'voucher_journal.trip_id !='=>0,
//            'voucher_journal.auto_generated'=>0,
//
//            'voucher_entry.related_customer !='=>0,
//        ));
//        $result = $this->db->get()->result();
//        $voucher_data_array = array();
//        foreach($result as $record)
//        {
//            /*****fetching the tanker number**********/
//            $this->db->select('tanker_id');
//            $trips = $this->db->get_where('trips',array(
//                'id'=>$record->trip_id,
//            ))->result();
//            $tanker_id= $trips[0]->tanker_id;
//            /*****************************************/
//
//            $voucher_data = array(
//                'id'=>$record->id,
//                'tanker_id'=>$tanker_id,
//            );
//            array_push($voucher_data_array, $voucher_data);
//        }
//
//        $this->db->update_batch('voucher_journal',$voucher_data_array,'id');
//    }
//
//    public function delete_compony_account_title_vouchers()
//    {
//
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'company commission a/c'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $account_title_id = $result[0]->id;
//
//        $this->db->select('voucher_journal.id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.active'=>1,
//            'voucher_journal.auto_generated'=>1,
//            'voucher_entry.account_title_id'=>$account_title_id,
//        ));
//        $result = $this->db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//        }
//
//        $this->db->trans_start();
//
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->delete('voucher_journal');
//
//        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
//        $this->db->delete('voucher_entry');
//
//        $this->db->where_in('trip_detail_voucher_relation.voucher_id',$voucher_ids);
//        $this->db->delete('trip_detail_voucher_relation');
//
//        $this->db->trans_complete();
//
//    }
//
//    public function delete_auto_generated_vouchers()
//    {
//        $this->db->select('voucher_journal.id');
//        $this->db->where(array(
//            'voucher_journal.auto_generated'=>1,
//        ));
//        $result = $this->db->get('voucher_journal')->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//        }
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->delete('voucher_journal');
//
//        $this->db->where_in('trip_detail_voucher_relation.voucher_id', $voucher_ids);
//        $this->db->delete('trip_detail_voucher_relation');
//    }
//
//    public function change_cash_to_bank()
//    {
//        $this->db->select('id');
//        $raw_titile = $this->db->get_where('account_titles',array(
//            'title'=>'Bank A/c',
//        ))->result();
//        $bank_ac_title_id = $raw_titile[0]->id;
//
//        $this->db->select('id');
//        $raw_titile = $this->db->get_where('account_titles',array(
//            'title'=>'cash',
//        ))->result();
//        $cash_ac_title_id = $raw_titile[0]->id;
//
//        $update_data = array(
//            'account_title_id'=>$bank_ac_title_id,
//        );
//        $this->db->where('account_title_id',$cash_ac_title_id);
//        $this->db->update('voucher_entry',$update_data);
//    }
//
//    public function settle_accounts()
//    {
//        $this->db->select('trips.id');
//        $raw_trips = $this->db->get_where('trips',array(
//            'active'=>1,
//        ))->result();
//        foreach($raw_trips as $trip)
//        {
//            $this->db->trans_start();
//            $this->trips_model->inserted_vouchers_for_newly_added_products($trip->id);
//            $this->trips_model->automatic_transactions_on_trip_edit($trip->id);
//            $this->db->trans_complete();
//        }
//    }
//
//    /*
//     * ---------------------------------------------------
//     * deleting those vouchers which have deleted trips
//     * ---------------------------------------------------
//     */
//    public function delete_vouchers_with_deleted_trips()
//    {
//        $this->db->select('trips.id');
//        $result = $this->db->get_where('trips',array(
//            'active'=>0,
//        ))->result();
//        $deleted_trips_ids = array();
//        foreach($result as $record)
//        {
//            array_push($deleted_trips_ids, $record->id);
//        }
//
//        $this->db->where_in('voucher_journal.trip_id',$deleted_trips_ids);
//        $this->db->update('voucher_journal',array(
//            'active'=>0,
//        ));
//    }
//
//    public function correct_decanding_shortage_vouchers()
//    {
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'decanding shortage'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $title_id = $result[0]->id;
//
//        $this->db->select('trips_details.id, (qty_at_destination - qty_after_decanding) as shortage_at_decanding,
//            price_unit, products.productName,
//        ');
//        $this->db->from('trips_details');
//        $this->db->join('products','products.id = trips_details.product');
//        $this->db->where(array(
//            '(qty_at_destination - qty_after_decanding) >'=>0,
//            'product_quantity !='=>0,
//        ));
//        $raw_detail_ids = $this->db->get()->result();
//        //var_dump($raw_detail_ids); die();
//        foreach($raw_detail_ids as $record)
//        {
//            $this->db->select('voucher_journal.id');
//            $this->db->from('voucher_journal');
//            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id');
//            $this->db->where(array(
//                'trip_product_detail_id'=>$record->id,
//                'voucher_entry.account_title_id'=>$title_id,
//                'voucher_journal.active'=>1,
//            ));
//            $this->db->like('voucher_journal.detail','(shortage)');
//            $result = $this->db->get()->result();
//            if($result != null){
//                $voucher_id = $result[0]->id;
//                $entry_data = array(
//                    'credit_amount'=>($record->shortage_at_decanding * $record->price_unit),
//                    'description'=>"Shortage_quantity => ".$record->shortage_at_decanding." Price/Unit => ".$record->price_unit." Product =>".$record->productName."",
//                );
//                $this->db->where(array(
//                    'voucher_entry.dr_cr'=>0,
//                    'voucher_entry.journal_voucher_id'=>$voucher_id,
//                ));
//                $this->db->update('voucher_entry',$entry_data);
//                //var_dump($entry_data);
//
//                $entry_data = array(
//                    'debit_amount'=>round(($record->shortage_at_decanding * $record->price_unit), 3),
//                    'description'=>"Shortage_quantity => ".$record->shortage_at_decanding." Price/Unit => ".$record->price_unit." Product =>".$record->productName."",
//                );
//                $this->db->where(array(
//                    'voucher_entry.dr_cr'=>1,
//                    'voucher_entry.journal_voucher_id'=>$voucher_id,
//                ));
//                $this->db->update('voucher_entry',$entry_data);
//                //var_dump($entry_data);
//            }
//        }
//    }
//
//
//    public function correct_destination_shortage_vouchers()
//    {
//        $this->db->select('id');
//        $result = $this->db->get_where('account_titles', array('title'=>'destination shortage'))->result();
//        if($result == null){ echo "titles are not set"; die();}
//        $title_id = $result[0]->id;
//
//        $this->db->select('trips_details.id, (product_quantity - qty_at_destination) as shortage_at_destination,
//            price_unit, products.productName,
//        ');
//        $this->db->from('trips_details');
//        $this->db->join('products','products.id = trips_details.product');
//        $this->db->where(array(
//            '(product_quantity - qty_at_destination) !='=>0,
//            'product_quantity !='=>0,
//        ));
//        $raw_detail_ids = $this->db->get()->result();
//        foreach($raw_detail_ids as $record)
//        {
//            $this->db->select('voucher_journal.id');
//            $this->db->from('voucher_journal');
//            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id');
//            $this->db->where(array(
//                'trip_product_detail_id'=>$record->id,
//                'voucher_entry.account_title_id'=>$title_id,
//                'voucher_journal.active'=>1,
//            ));
//            $this->db->like('voucher_journal.detail','(shortage)');
//            $result = $this->db->get()->result();
//            if($result != null){
//                $voucher_id = $result[0]->id;
//                $entry_data = array(
//                    'credit_amount'=>($record->shortage_at_destination * $record->price_unit),
//                    'description'=>"Shortage_quantity => ".$record->shortage_at_destination." Price/Unit => ".$record->price_unit." Product =>".$record->productName."",
//                );
//                $this->db->where(array(
//                    'voucher_entry.dr_cr'=>0,
//                    'voucher_entry.journal_voucher_id'=>$voucher_id,
//                ));
//                $this->db->update('voucher_entry',$entry_data);
//                //var_dump($entry_data);
//
//                $entry_data = array(
//                    'debit_amount'=>round(($record->shortage_at_destination * $record->price_unit), 3),
//                    'description'=>"Shortage_quantity => ".$record->shortage_at_destination." Price/Unit => ".$record->price_unit." Product =>".$record->productName."",
//                );
//                $this->db->where(array(
//                    'voucher_entry.dr_cr'=>1,
//                    'voucher_entry.journal_voucher_id'=>$voucher_id,
//                ));
//                $this->db->update('voucher_entry',$entry_data);
//                //var_dump($entry_data);
//            }
//        }
//    }
//
//    public function foo()
//    {
//        $this->db->select('trips_details.id');
//        $result = $this->db->get_where('trips_details', array(
//            'trip_id >'=>50,
//        ))->result();
//        $details_ids = array();
//        foreach($result as $record)
//        {
//            array_push($details_ids, $record->id);
//        }
//        var_dump($details_ids);
//        $this->db->where('trip_detail_voucher_relation.trip_detail_id >', 50);
//        $this->db->delete('trip_detail_voucher_relation');
//
//        $this->db->select('voucher_journal.id');
//        $result = $this->db->get_where('voucher_journal',array('trip_id >'=>50))->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//        }
//        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
//        $this->db->delete('voucher_entry');
//    }
//
//    public function  set_wrong_tankers()
//    {
//        $data = array(
//            'customerId'=>5,
//        );
//        $where = "(id = 109 OR id = 107)";
//        $this->db->where($where);
//        $this->db->update('tankers',$data);
//    }
//
//    public function trial_balance_testing()
//    {
//        $this->db->select('voucher_journal.id as voucher_id, (SUM(voucher_entry.debit_amount) - SUM(voucher_entry.credit_amount)) as balance');
//        $this->db->from('voucher_journal');
//        $this->db->where(array(
//            'voucher_journal.active'=>1,
//            'voucher_journal.person_tid'=>'users.1',
//            'voucher_journal.id <'=>2865
//        ));
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $result = $this->db->get()->result();
//        var_dump($result);
//        /*$this->db->select('voucher_journal.id');
//        $this->db->where('person_tid',"users.1");
//        $result = $this->db->get_where('voucher_journal',array('active'=>1))->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//        }
//        $vouchers = $this->accounts_model->journal("users","1",$voucher_ids,"");
//        foreach($vouchers as $voucher)
//        {
//
//        }*/
//    }
//
//
//    public function unbalance_vouchers()
//    {
//        $this->db->select("SUM(voucher_entry.debit_amount) as total_debit, SUM(voucher_entry.credit_amount) as total_credit,
//        (SUM(voucher_entry.debit_amount) - SUM(voucher_entry.credit_amount)) as balance, voucher_journal.trip_id,
//        ");
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where(array(
//            'voucher_journal.active'=>1,
//        ));
//        $this->db->group_by('voucher_journal.id');
//        $result = $this->db->get()->result();
//        foreach($result as $record)
//        {
//            if(round($record->balance) != 0)
//            {
//                var_dump($record->trip_id);
//            }
//        }
//    }
//
//    public function deleted_vouchers()
//    {
//        $this->db->select("voucher_journal.id as voucher_id");
//        $this->db->distinct();
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
//        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>"users.1",
//            'voucher_journal.active'=>0,
//        ));
//        $this->db->where(array(
//            'voucher_entry.related_other_agent'=>2,
//            'account_titles.type'=>'liability',
//            'voucher_entry.dr_cr'=>0,
//        ));
//        $result = $this->db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            if($record->voucher_id != 457)
//            {
//                array_push($voucher_ids, $record->voucher_id);
//            }
//        }
//
//        /*$this->db->select("voucher_journal.id as voucher_id");
//        $this->db->distinct();
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
//        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>"users.1",
//        ));
//        $where = "(voucher_entry.account_title_id = 37 OR voucher_entry.account_title_id = 38)";
//        $this->db->where($where);
//        $result = $this->db->get()->result();*/
//        var_dump($voucher_ids);
//        $this->db->where_in('voucher_journal.id', $voucher_ids);
//        $this->db->update('voucher_journal',array(
//            'active'=>1,
//        ));
//    }
//    public function deleted_vouchers_trips()
//    {
//        $this->db->select("voucher_journal.id as voucher_id");
//        $this->db->distinct();
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id', 'left');
//        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $this->db->where(array(
//            'voucher_journal.person_tid'=>"users.1",
//            'voucher_journal.active'=>0,
//        ));
//        $this->db->where(array(
//            'voucher_entry.related_other_agent'=>2,
//            'account_titles.type'=>'liability',
//            'voucher_entry.dr_cr'=>0,
//            'voucher_journal.trip_id !='=>0,
//        ));
//        $result = $this->db->get()->result();
//        foreach($result as $record)
//        {
//            echo $record->voucher_id."<br>";
//        }
//    }
//    public function excel()
//    {
//        $file="test.xls";
//        $tankers = $this->tankers_model->tankers();
//        $test = "";
//        $test.="<table style='width: 500px;'>
//        <thead>
//        <tr>
//            <th>ID</th>
//            <th>Tanker#</th>
//            <th>Engine Number</th>
//        </tr>
//        </thead>";
//        foreach($tankers as $tanker)
//        {
//            $test.="<tr>
//                <td style='text-align:center;'>".$tanker->id."</td>
//                <td style='text-align:center;'>".$tanker->truck_number."</td>
//                <td style='text-align:center;'>".$tanker->engine_number."</td>
//            </tr>";
//        }
//        $test.="</table>";
//        header("Content-type: application/vnd.ms-excel");
//        header("Content-Disposition: attachment; filename=$file");
//        echo $test;
//    }

//    public function activate_expense_vouchers()
//    {
//        $this->db->select('voucher_journal.id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $this->db->distinct();
//        $this->db->where(array(
//                'voucher_journal.trip_id !='=>0,
//                'voucher_journal.active'=>0,
//                'voucher_entry.account_title_id !='=>38,
//                'voucher_entry.account_title_id !='=>37,
//            )
//        );
//        $this->db->where(array(
//            'person_tid'=>'users.1',
//        ));
//        $result = $this->db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//
//        $this->db->select('voucher_entry.journal_voucher_id');
//        $this->db->distinct();
//        $this->db->where(array(
//            'voucher_entry.related_other_agent !='=>0,
//            'voucher_entry.dr_cr'=>0,
//            'voucher_entry.account_title_id'=>42,
//        ));
//        $this->db->where_in('voucher_entry.journal_voucher_id', $voucher_ids);
//        $result = $this->db->get('voucher_entry')->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//           // echo $record->journal_voucher_id."<br>";
//            array_push($voucher_ids, $record->journal_voucher_id);
//        }
//       // var_dump($voucher_ids); die();
//        $this->db->where_in('voucher_journal.id', $voucher_ids);
//        $update_data = array(
//            'active'=>1,
//        );
//        $this->db->update('voucher_journal',$update_data);
//
//
//    }
//    public function is_expense_vouchers_same($voucher_1, $voucher_2)
//    {
//
//        if($voucher_1->trip_id != $voucher_2->trip_id){
//            return false;
//        }
//
//        if($voucher_1->tanker_id != $voucher_2->tanker_id){
//            return false;
//        }
//        if($voucher_1->voucher_date != $voucher_2->voucher_date){
//            return false;
//        }
//
//        if($voucher_1->entries[0]->account_title_id != $voucher_2->entries[0]->account_title_id){
//            return false;
//        }
//        if($voucher_1->entries[0]->related_agent != $voucher_2->entries[0]->related_agent){
//            return false;
//        }
//        if($voucher_1->entries[0]->related_agent_id != $voucher_2->entries[0]->related_agent_id){
//            return false;
//        }
//        /*if($voucher_1->entries[0]->description != $voucher_2->entries[0]->description){
//            return false;
//        }*/
//
//        if($voucher_1->entries[0]->debit != $voucher_2->entries[0]->debit){
//            return false;
//        }
//        if($voucher_1->entries[0]->credit != $voucher_2->entries[0]->credit){
//            return false;
//        }
//        if($voucher_1->entries[0]->dr_cr != $voucher_2->entries[0]->dr_cr){
//            return false;
//        }
//        /*************/
//
//
//        if($voucher_1->entries[1]->account_title_id != $voucher_2->entries[1]->account_title_id){
//            return false;
//        }
//        if($voucher_1->entries[1]->related_agent != $voucher_2->entries[1]->related_agent){
//            return false;
//        }
//        if($voucher_1->entries[1]->related_agent_id != $voucher_2->entries[1]->related_agent_id){
//            return false;
//        }
//        /*if($voucher_1->entries[1]->description != $voucher_2->entries[1]->description){
//            return false;
//        }*/
//
//        if($voucher_1->entries[1]->debit != $voucher_2->entries[1]->debit){
//            return false;
//        }
//        if($voucher_1->entries[1]->credit != $voucher_2->entries[1]->credit){
//            return false;
//        }
//        if($voucher_1->entries[1]->dr_cr != $voucher_2->entries[1]->dr_cr){
//            return false;
//        }
//        /******************************/
//
//        return true;
//    }
//    function duplicate_expense_vouchers()
//    {
//
//        $this->db->select('voucher_journal.id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $this->db->distinct();
//        $this->db->where(array(
//                'voucher_journal.trip_id !='=>0,
//                'voucher_journal.active'=>1,
//                'voucher_entry.account_title_id !='=>38,
//                'voucher_entry.account_title_id !='=>37,
//            )
//        );
//        $this->db->where(array(
//            'person_tid'=>'users.1',
//        ));
//        $result = $this->db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//
//        $this->db->select('voucher_entry.journal_voucher_id');
//        $this->db->distinct();
//        $this->db->where(array(
//            'voucher_entry.related_other_agent !='=>0,
//            'voucher_entry.dr_cr'=>0,
//            'voucher_entry.account_title_id'=>42,
//        ));
//        $this->db->where_in('voucher_entry.journal_voucher_id', $voucher_ids);
//        $result = $this->db->get('voucher_entry')->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->journal_voucher_id);
//        }
//
//
//
//        $this->db->select('voucher_journal.trip_id, voucher_entry.account_title_id, voucher_entry.debit_amount, voucher_entry.journal_voucher_id as voucher_id,');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->where('voucher_entry.dr_cr',1);
//        $result = $this->db->get()->result();
//        $deletable_voucher_ids = array();
//        $small_ids = array();
//        foreach($result as $entry_1)
//        {
//            foreach($result as $entry_2)
//            {
//                if($entry_1->voucher_id != $entry_2->voucher_id)
//                {
//                    if(
//                        $entry_1->trip_id == $entry_2->trip_id && $entry_1->debit_amount == $entry_2->debit_amount
//                        && $entry_1->account_title_id == $entry_2->account_title_id
//                    )
//                    {
//                        if($entry_1->voucher_id < $entry_2->voucher_id)
//                        {
//                            array_push($small_ids, $entry_1->voucher_id);
//                        }else{
//                            array_push($small_ids, $entry_2->voucher_id);
//                        }
//                        array_push($deletable_voucher_ids, $entry_1->voucher_id);
//                        array_push($deletable_voucher_ids, $entry_2->voucher_id);
//                    }
//                }
//            }
//        }
//        var_dump($small_ids);
//        //die();
//        /*$vouchers = $this->accounts_model->journal("users","1",$voucher_ids,"");
//        $deletable_vouchers = array();
//        for($count = 0; $count < sizeof($vouchers); $count++)
//        {
//            for($count_2 = ($count+1); $count_2 < sizeof($vouchers); $count_2++)
//            {
//                if($this->is_expense_vouchers_same($vouchers[$count], $vouchers[$count_2]) == true)
//                {
//                    $same_voucher_ids = $vouchers[$count]->voucher_id."_".$vouchers[$count_2]->voucher_id;
//                    array_push($deletable_vouchers, $same_voucher_ids);
//                }
//            }
//        }*/
//
//       /* $deletable_voucher_ids = array();
//        foreach($deletable_vouchers as $id){
//            $id_parts = explode('_',$id);
//            array_push($deletable_voucher_ids, $id_parts[0]);
//        }
//        var_dump($deletable_voucher_ids);*/
//        $this->db->trans_start();
//        $this->db->where_in('voucher_journal.id', $small_ids);
//        $this->db->delete('voucher_journal');
//
//        $this->db->where_in('voucher_entry.journal_voucher_id', $small_ids);
//        $this->db->delete('voucher_entry');
//
//        $this->db->trans_complete();
//    }
//
//    function delete_unwanted_vouchers()
//    {
//        $this->db->select('voucher_entry.journal_voucher_id');
//        $this->db->distinct();
//
//        $where = "(voucher_entry.related_other_agent > 2) OR (voucher_entry.related_customer = 0 && voucher_entry.related_contractor = 0 && voucher_entry.related_company = 0 && voucher_entry.related_other_agent = 0)";
//        $this->db->where($where);
//        $result = $this->db->get('voucher_entry')->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->journal_voucher_id);
//        }
//        var_dump($voucher_ids);
//        $this->db->trans_start();
//        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
//        $this->db->delete('voucher_entry');
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->delete('voucher_journal');
//        $this->db->trans_complete();
//
//        $this->db->select('voucher_journal.id');
//        $this->db->distinct();
//        $this->db->where(array(
//            'voucher_journal.person_tid !='=>'users.1',
//        ));
//        $result = $this->db->get('voucher_journal')->result();
//        $voucher_ids = array();
//        foreach($result as $record){
//            array_push($voucher_ids, $record->id);
//        }
//        var_dump($voucher_ids);
//        $this->db->trans_start();
//        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
//        $this->db->delete('voucher_entry');
//        $this->db->where_in('voucher_journal.id',$voucher_ids);
//        $this->db->delete('voucher_journal');
//        $this->db->trans_complete();
//    }
//
//    public function add_routes()
//    {
//        $this->db->select('routes.id');
//        $this->db->where('routes.type !=',1);
//        $result = $this->db->get('routes')->result();
//        $routes_ids = array();
//        foreach($result as $record)
//        {
//            array_push($routes_ids, $record->id);
//        }
//        if(sizeof($routes_ids) > 0)
//        {
//            $this->db->where_in('routes.id',$routes_ids);
//            $this->db->delete('routes');
//            $this->db->where_in('freights.route_id',$routes_ids);
//            $this->db->delete('freights');
//        }
//
//        $this->db->select('trips.type, trips_details.source, trips_details.destination, trips_details.product, trips.entryDate,
//            trips_details.company_freight_unit,
//        ');
//        $this->db->from('trips');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
//        $this->db->where('trips.type','2');
//        $this->db->group_by('trips_details.source, trips_details.destination, trips_details.product');
//        $general_trips_routes = $this->db->get()->result();
//
//        $this->db->select('trips.type, trips_details.source, trips_details.destination, trips_details.product, trips.entryDate,
//            trips_details.company_freight_unit,
//        ');
//        $this->db->from('trips');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
//        $this->db->where('trips.type','3');
//        $this->db->group_by('trips_details.source, trips_details.destination, trips_details.product');
//        $local_cmp_routes = $this->db->get()->result();
//
//        $this->db->select('trips.type, trips.id, trips_details.source, trips_details.destination, trips_details.product, trips.entryDate,
//            trips_details.company_freight_unit,
//        ');
//        $this->db->from('trips');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
//        $this->db->where('trips.type','4');
//        $this->db->where('trips.active',1);
//        $this->db->group_by('trips_details.source, trips_details.destination, trips_details.product');
//        $local_self_routes = $this->db->get()->result();
//
//        $this->db->trans_start();
//        foreach($general_trips_routes as $route)
//        {
//            $data_routes = array(
//                'source'=>$route->source,
//                'destination'=>$route->destination,
//                'product'=>$route->product,
//                'type'=>2,
//                'entryDate' => $route->entryDate,
//            );
//            $this->db->insert('routes', $data_routes);
//            $inserted_id = $this->db->insert_id();
//            $data_freights = array(
//                'route_id'=>$inserted_id,
//                'freight'=>round($route->company_freight_unit, 3),
//                'startDate'=>$route->entryDate,
//                'endDate'=>Carbon::now()->addDays(4)->toDateString(),
//            );
//            $this->db->insert('freights', $data_freights);
//        }
//
//        foreach($local_cmp_routes as $route)
//        {
//            $data_routes = array(
//                'source'=>$route->source,
//                'destination'=>$route->destination,
//                'product'=>$route->product,
//                'type'=>3,
//                'entryDate' => $route->entryDate,
//            );
//            $this->db->insert('routes', $data_routes);
//            $inserted_id = $this->db->insert_id();
//            $data_freights = array(
//                'route_id'=>$inserted_id,
//                'freight'=>round($route->company_freight_unit, 3),
//                'startDate'=>$route->entryDate,
//                'endDate'=>Carbon::now()->addDays(4)->toDateString(),
//            );
//            $this->db->insert('freights', $data_freights);
//        }
//
//        foreach($local_self_routes as $route)
//        {
//            $data_routes = array(
//                'source'=>$route->source,
//                'destination'=>$route->destination,
//                'product'=>$route->product,
//                'type'=>4,
//                'entryDate' => $route->entryDate,
//            );
//            $this->db->insert('routes', $data_routes);
//            $inserted_id = $this->db->insert_id();
//            $data_freights = array(
//                'route_id'=>$inserted_id,
//                'freight'=>round($route->company_freight_unit, 3),
//                'startDate'=>$route->entryDate,
//                'endDate'=>Carbon::now()->addDays(4)->toDateString(),
//            );
//            $this->db->insert('freights', $data_freights);
//        }
//
//        var_dump($this->db->trans_complete());
//
//        die();
//    }
//
//
//    public function shortages_by_keys()
//    {
//        $this->db->select('trips.id as trip_id, trips_details.id as detail_id,
//            trips_details.shortage_voucher_dest, trips_details.shortage_voucher_decnd,
//        ');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
//        $this->db->from('trips');
//        $this->db->where('trips.active',1);
//        $this->db->where(array(
//            'trips.contractor_id'=>1,
//            'trips.company_id'=>1,
//        ));
//        $where = "(trips_details.shortage_voucher_dest != 0 OR trips_details.shortage_voucher_decnd != 0)";
//        $this->db->where($where);
//        $this->db->where(array(
//            'trips.invoice_date >='=>'2015-03-01',
//            'trips.invoice_date <='=>'2015-03-31',
//        ));
//        $result = $this->db->get()->result();
//        foreach($result as $record)
//        {
//            echo $record->detail_id;
//            echo "<br><br>";
//        }
//    }
//
//    public function add_detail_ids_in_shortage_vouchers()
//    {
//        $this->db->select('voucher_journal.id, voucher_journal.trip_id');
//        $this->db->from('voucher_journal');
//        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $this->db->where('voucher_journal.active',1);
//        $this->db->where('voucher_entry.dr_cr',1);
//        $where = "(voucher_entry.account_title_id = 37 OR voucher_entry.account_title_id = 38)";
//        $this->db->where($where);
//        $this->db->where('voucher_journal.trip_product_detail_id',0);
//        $result = $this->db->get()->result();
//        $voucher_ids = array();
//        $trip_ids = array();
//        $update_data = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->id);
//            array_push($trip_ids, $record->trip_id);
//
//            $this->db->select('trips_details.id');
//            $this->db->where('trips_details.trip_id',$record->trip_id);
//            $details_id = $this->db->get('trips_details')->result();
//            if($details_id != null)
//            {
//                $detail_id = $details_id[0]->id;
//                $data = array(
//                    'id'=>$record->id,
//                    'trip_product_detail_id'=>$detail_id,
//                );
//                array_push($update_data, $data);
//            }
//        }
//        var_dump($update_data);
//        $this->db->update_batch('voucher_journal',$update_data,'id');
//
//    }
//
//    public function creating_test_databases()
//    {
//        $this->load->dbforge();
//        $this->dbforge->create_database('viriklogistics_test_4_5_april');
//        $this->dbforge->create_database('viriklogistics_test_5_6_april');
//        $this->dbforge->create_database('viriklogistics_test_6_9_april');
//        $this->dbforge->create_database('viriklogistics_test_7_16_april');
//        $this->dbforge->create_database('viriklogistics_test_8_20_april');
//        $this->dbforge->create_database('viriklogistics_test_9_22_april');
//        $this->dbforge->create_database('viriklogistics_test_10_22_april_backup');
//        $this->dbforge->create_database('viriklogistics_test_11_22_april');
//        $this->dbforge->create_database('viriklogistics_test_12_23_april');
//        $this->dbforge->create_database('viriklogistics_test_13_23_april_backup_with_duplicate_entries');
//        $this->dbforge->create_database('viriklogistics_test_14_26_april');
//    }
//
//    public function other_haider_fs_vouchers()
//    {
//
//        $latest_db = $this->load->database('default', true);
//        $latest_db->select('voucher_journal.id as voucher_id');
//        $latest_db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $latest_db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $latest_db->from('voucher_journal');
//        $latest_db->where('voucher_journal.person_tid','users.1');
//        $latest_db->where('voucher_journal.voucher_date <=','2015-03-31');
//        $latest_db->where('account_titles.type','expense');
//        $result = $latest_db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($voucher_ids, $record->voucher_id);
//        }
//
//        $latest_db->select('voucher_journal.id as voucher_id');
//        $latest_db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $latest_db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $latest_db->from('voucher_journal');
//        $latest_db->where_not_in('voucher_journal.id',$voucher_ids);
//        $latest_db->where('voucher_journal.voucher_date <=','2015-03-31');
//        $latest_db->where('voucher_entry.related_other_agent','2');
//        $result = $latest_db->get()->result();
//        $voucher_ids = array();
//        foreach($result as $record)
//        {
//            var_dump($record);
//            array_push($voucher_ids, $record->voucher_id);
//        }
//
//
//    }
//    public function test_db()
//    {
//
//        $_16_april = $this->load->database('_16_april', true);
//
//        $_16_april->trans_start();
//        $_16_april->select('voucher_journal.id as voucher_id');
//        $_16_april->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $_16_april->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $_16_april->from('voucher_journal');
//        $_16_april->where('voucher_journal.person_tid','users.1');
//        $_16_april->where('voucher_journal.id <=','5320');
//        $_16_april->where(array(
//            'voucher_entry.account_title_id'=>42,
//            'voucher_entry.related_other_agent !='=>0,
//            'voucher_entry.related_other_agent <='=>2,
//            'voucher_entry.dr_cr'=>0,
//        ));
//        $result = $_16_april->get()->result();
//        $_16_april_voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($_16_april_voucher_ids, $record->voucher_id);
//        }
//
//        $_16_april->select('*');
//        $_16_april->where_in('voucher_journal.id',$_16_april_voucher_ids);
//        $result = $_16_april->get('voucher_journal')->result();
//        $_16_april_vouchers = array();
//        foreach($result as $record)
//        {
//            $data =  (array) $record;
//            array_push($_16_april_vouchers ,$data);
//        }
//
//
//        $_16_april->select('*');
//        $_16_april->where_in('voucher_entry.journal_voucher_id',$_16_april_voucher_ids);
//        $result = $_16_april->get('voucher_entry')->result();
//        $_16_april_entries = array();
//        foreach($result as $record)
//        {
//            $data =  (array) $record;
//            array_push($_16_april_entries ,$data);
//        }
//
//        $_16_april->trans_complete();
//
//        //updating in latest database
//        $latest_db = $this->load->database('default', true);
//        $latest_db->trans_start();
//        $latest_db->select('voucher_journal.id as voucher_id');
//        $latest_db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
//        $latest_db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
//        $latest_db->from('voucher_journal');
//        $latest_db->where('voucher_journal.person_tid','users.1');
//        $latest_db->where('voucher_journal.id <=','5320');
//        $latest_db->where(array(
//            'voucher_entry.account_title_id'=>42,
//            'voucher_entry.related_other_agent !='=>0,
//            'voucher_entry.related_other_agent <='=>2,
//            'voucher_entry.dr_cr'=>0,
//        ));
//        $result = $latest_db->get()->result();
//        $latest_db_voucher_ids = array();
//        foreach($result as $record)
//        {
//            array_push($latest_db_voucher_ids, $record->voucher_id);
//        }
//
//        $latest_db->where_in('voucher_journal.id',$latest_db_voucher_ids);
//        $latest_db->delete('voucher_journal');
//
//        $latest_db->where_in('voucher_entry.journal_voucher_id',$latest_db_voucher_ids);
//        $latest_db->delete('voucher_entry');
//
//        $latest_db->insert_batch('voucher_journal',$_16_april_vouchers);
//        $latest_db->insert_batch('voucher_entry',$_16_april_entries);
//        var_dump($latest_db->trans_complete());
//
//    }


    public function make_routes_of_two_types()
    {
        $this->db->trans_start();
        $this->db->select('source, destination, product, COUNT(id) as num_of_records');
        $this->db->group_by('source,destination,product');
        $result = $this->db->get('routes')->result();
        $multiple_routes = array();
        foreach($result as $record)
        {
            if($record->num_of_records > 1)
            {
                array_push($multiple_routes, $record);
            }
        }

        $latest_routes = array();
        $duplicate_ids = array();
        foreach($multiple_routes as $route)
        {
            $this->db->select('routes.id');
            $this->db->where(array(
                'source'=>$route->source,
                'destination'=>$route->destination,
                'product'=>$route->product,
            ));
            $result = $this->db->get('routes')->result();
            $ids = array();
            foreach($result as $record)
            {
                array_push($ids, $record->id);
                array_push($duplicate_ids, $record->id);
            }
            $latest_id = max($ids);

            array_push($latest_routes, $latest_id);
        }
        $deleteable_routes = array();
        foreach($duplicate_ids as $id)
        {
            if(!in_array($id, $latest_routes))
            {
                array_push($deleteable_routes, $id);
            }
        }

        $this->db->where_in('routes.id',$deleteable_routes);
        $this->db->delete('routes');

        $this->db->where_in('routes.type',array('1','2','4'));
        $data = array('type'=>1);
        $this->db->update('routes',$data);


        $this->db->trans_complete();
    }


    public function comparing_9_april_with_latest()
    {


        $_9_april = $this->load->database('_9_april', true);

        $_9_april->trans_start();
        $_9_april->select('voucher_journal.id as voucher_id, voucher_journal.active');
        $_9_april->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $_9_april->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $_9_april->from('voucher_journal');
        $_9_april->where('voucher_journal.person_tid','users.1');
        $_9_april->where(array(
            'voucher_entry.dr_cr'=>1,
            'account_titles.type'=>'expense',
        ));
        $_9_april_vouchers = $_9_april->get()->result();

        /*foreach($_9_april_vouchers as $_9_april_voucher)
        {
            var_dump($_9_april_voucher->voucher_id."__".$_9_april_voucher->active);
        }*/

        //updating in latest database
        $latest_db = $this->load->database('default', true);
        $latest_db->trans_start();
        $latest_db->select('voucher_journal.id as voucher_id, voucher_journal.active');
        $latest_db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $latest_db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $latest_db->from('voucher_journal');
        $latest_db->where('voucher_journal.person_tid','users.1');
        $latest_db->where(array(
            'voucher_entry.dr_cr'=>1,
            'account_titles.type'=>'expense',
        ));
        $latest_db_vouchers = $latest_db->get()->result();

        /*foreach($latest_db_vouchers as $latest_db_voucher)
        {
            var_dump($latest_db_voucher->voucher_id."__".$latest_db_voucher->active);
        }*/

        $faulted_vouchers = array();
        foreach($_9_april_vouchers as $_9_april_voucher)
        {
            foreach($latest_db_vouchers as $latest_db_voucher)
            {
                if($_9_april_voucher->voucher_id == $latest_db_voucher->voucher_id)
                {
                    if($_9_april_voucher->active != $latest_db_voucher->active)
                    {
                        $str = $latest_db_voucher->voucher_id;
                        array_push($faulted_vouchers, $str);

                    }
                    break;
                }
            }
        }

        /*$faulted_vouchers = array_unique($faulted_vouchers);
        $arr = array(
            'active'=>1,
        );
        $latest_db->where_in('voucher_journal.id',$faulted_vouchers);
        $latest_db->update('voucher_journal',$arr);*/

        $latest_db->trans_start();
        $latest_db->select('voucher_journal.id as voucher_id');
        $latest_db->distinct();
        $latest_db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $latest_db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $latest_db->from('voucher_journal');
        $latest_db->where('voucher_journal.person_tid','users.1');
        $latest_db->where(array(
            'voucher_entry.dr_cr'=>1,
            'account_titles.type'=>'expense',
            'voucher_journal.id >'=>5099,
            'voucher_journal.active'=>0,
        ));
        $this->db->order_by('voucher_journal.id');
        $latest_db_vouchers = $latest_db->get()->result();
        var_dump($latest_db_vouchers);

        /*$activate_vouchers = array();
        foreach($latest_db_vouchers as $latest_db_voucher)
        {
            array_push($activate_vouchers, $latest_db_voucher->voucher_id);
        }
        $arr = array(
            'active'=>1,
        );
        $latest_db->where_in('voucher_journal.id',$activate_vouchers);
        $latest_db->update('voucher_journal',$arr);*/


        var_dump($latest_db->trans_complete());


    }

    function set_contractor_company_commission()
    {
        /*---- Setting commissions for Attock and Viriklogistics-------*/
        $this->db->select('trips.id');
        $this->db->where(array(
            'company_id'=>1,
            'contractor_id'=>1,
            'company_commission_2'=>0,
            'trips.id >'=>800,
            'active'=>1,
        ));
        $result = $this->db->get('trips')->result();
        $trip_ids = array();
        foreach($result as $record)
        {
            array_push($trip_ids,$record->id);
        }
        var_dump($trip_ids);
        $data = array(
            'company_commission_1'=>6,
            'company_commission_2'=>2,
        );
        $this->db->where_in('trips.id',$trip_ids);
        $this->db->update('trips',$data);

        $this->db->trans_start();
        foreach($trip_ids as $id)
        {
            $this->trips_model->save_existing_trip_completely($id);
        }
        var_dump($this->db->trans_complete());
        /*------------------------------------------------------------*/
        die();
    }

    function check_income_statement()
    {



        $second = $_SESSION['second_income_statement'];
        $first = $_SESSION['first_income_statement'];
        //var_dump($first);die();
        $data = array();
        foreach($second as $second_record)
        {
            foreach($first as $first_record)
            {
                if($second_record->tanker_id == $first_record->tanker_id)
                {
                   if($second_record->total_income != $first_record->total_income)
                   {
                       var_dump($second_record->tanker_number);
                   }
                }
            }
        }
    }

    function trips_with_zero_freight()
    {
        $this->db->select('trips.id as trip_id, trips_details.freight_unit');
        $this->db->join('trips_details','trips_details.trip_id = trips.id', 'left');
        $this->db->where('trips_details.freight_unit',0);
        $this->db->where('trips.active',1);
        /*$this->db->where(array(
            'entryDate >='=>'2015-05-01',
            'entryDate <'=>'2015-06-01',
        ));*/
        $this->db->order_by('trips.id');
        $result = $this->db->get('trips')->result();
        //var_dump($result);die();
        foreach($result as $record)
        {
            echo($record->trip_id."<br>");
        }

    }

    function trips_with_different_company_freight()
    {
        $this->db->select('trips.id as trip_id, trips_details.company_freight_unit as freight, trips_details.source, trips_details.destination, trips_details.product, trips.entryDate');
        $this->db->join('trips_details','trips_details.trip_id = trips.id', 'left');
        $this->db->where('trips.active',1);
        $this->db->where(array(
            'entryDate >='=>'2015-04-01',
            'entryDate <'=>'2015-06-01',
        ));
        $this->db->order_by('trips.id');
        $result = $this->db->get('trips')->result();
        //var_dump($result);die();
        $trips_with_different_freight = array();
        foreach($result as $record)
        {
            $freight = $this->freight($record->source, $record->destination, $record->product,$record->entryDate);
            if($freight != $record->freight)
            {
                array_push($trips_with_different_freight, $record->trip_id);
                echo $freight."__".$record->freight."<br>";
            }
        }
        var_dump(sizeof($trips_with_different_freight));
    }

    //this function might be called by client with an ajax request...
    public function freight($source_id, $destination_id, $product_id, $date='', $route_type='1'){

        /*fething freight for a given duration*/
        $this->db->select('freights.freight, routes.id as route_id, freights.startDate, freights.endDate');
        $this->db->order_by("freights.id","desc");
        $this->db->from('routes');
        $this->db->join('freights', 'freights.route_id = routes.id');
        $this->db->where(array(
            'routes.source'=>$source_id,
            'routes.destination'=>$destination_id,
            'routes.product'=>$product_id,
            'freights.startDate <=' => ($date == '')?date('Y-m-d'):$date,
            'freights.endDate >=' => ($date == '')?date('Y-m-d'):$date,
            'routes.type'=>$route_type,
        ));
        $result = $this->db->get()->result();

        /*fetching latest freight if not given*/
        if($result == null)
        {
            $this->db->select('freights.freight, routes.id as route_id, freights.startDate, freights.endDate');
            $this->db->order_by("freights.id","desc");
            $this->db->from('routes');
            $this->db->join('freights', 'freights.route_id = routes.id');
            $this->db->where(array(
                'routes.source'=>$source_id,
                'routes.destination'=>$destination_id,
                'routes.product'=>$product_id,
                'routes.type'=>$route_type,
            ));
            $result = $this->db->get()->result();
        }
        $freight = (!$result)?null :$result[0]->freight ;
       return $freight;

    }
    /*---------------------------------------------------*/
    /*5/25/2015*/
    /*---------------------------------------------------*/
    public function correct_route_type()
    {
        $data = array(
            'type'=>1,
        );
        $where = "(routes.type = 2 OR routes.type = 4)";
        $this->db->where($where);
        $this->db->update('routes',$data);
    }


    /*---------------------------------------------------*/
    /*5/26/2015*/
    /*---------------------------------------------------*/
    public function get_shortage_voucher_ids()
    {

        $voucher_ids = array();

        //getting decanding vouchers
        $this->db->select("trips_details.shortage_voucher_decnd as decnd_voucher");
        $this->db->from('trips_details');
        $this->db->join('trips','trips.id = trips_details.trip_id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_decnd','left');
        $this->db->where('trips_details.shortage_voucher_decnd !=',0);
        $this->db->where('products.type','black oil');
        $this->db->where('voucher_journal.shortage_rate',0);
        $this->db->where(array(
            'trips.entryDate >='=>'2015-04-01',
            'trips.entryDate <'=>'2015-06-01'
        ));
        $result = $this->db->get()->result();

        foreach($result as $record)
        {
            array_push($voucher_ids, $record->decnd_voucher);
        }

        //getting destination vouchers
        $this->db->select("trips_details.shortage_voucher_dest as dest_voucher");
        $this->db->from('trips_details');
        $this->db->join('trips','trips.id = trips_details.trip_id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_dest','left');
        $this->db->where('voucher_journal.shortage_rate',0);
        $this->db->where(array(
            'trips_details.shortage_voucher_dest !='=>0,
            'trips_details.shortage_voucher_decnd'=>0,
        ));
        $this->db->where('products.type','black oil');
        $this->db->where(array(
            'trips.entryDate >='=>'2015-04-01',
            'trips.entryDate <'=>'2015-06-01'
        ));
        $result = $this->db->get()->result();

        foreach($result as $record)
        {
            array_push($voucher_ids, $record->dest_voucher);
        }

        return $voucher_ids;
    }

    public function get_updatable_aray_for_shortage_vouchers($voucher_ids)
    {
        $updatable_array = array();
        $this->db->select("voucher_entry.journal_voucher_id as voucher_id, voucher_entry.description");
        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
        $result = $this->db->get('voucher_entry')->result();
        foreach($result as $record)
        {
            $data = array();
            $data['id']=$record->voucher_id;
            $data['price_unit'] = round($this->getShortagePricePerUnit($record->description), 3);
            $data['shortage_rate'] = round($this->getShortagePricePerUnit($record->description), 3);
            $data['shortage_quantity'] = $this->getShortageQuantity($record->description);

            array_push($updatable_array, $data);
        }

        return $updatable_array;
    }

    public function getShortagePricePerUnit($shortage_detail)
    {
        $shortage_details = str_replace(' ','',$shortage_detail);
        $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
        $shortage_details_parts = explode('_&&_', $shortage_details);
        $shortage_details = array(
            'qty'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[1]:0,
            'price_unit'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[2]:0,
        );

        return $shortage_details['price_unit'];
    }


    public function getShortageQuantity($shortage_detail)
    {
        $shortage_details = str_replace(' ','',$shortage_detail);
        $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
        $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
        $shortage_details_parts = explode('_&&_', $shortage_details);
        $shortage_details = array(
            'qty'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[1]:0,
            'price_unit'=>(sizeof($shortage_details_parts) > 2)?$shortage_details_parts[2]:0,
        );

        return ( is_double(doubleval($shortage_details['qty'])) == true)?doubleval($shortage_details['qty']):0;
    }

    public function shortage_deduction_voucher_for_black_oil($total_amount, $voucher_date, $trip_id, $trip_detail_id)
    {
        $this->db->select('trips.company_id, trips.contractor_id, trips.tanker_id');
        $this->db->where('trips.id',$trip_id);
        $result = $this->db->get('trips')->result();
        $company_id = $result[0]->company_id;
        $contractor_id = $result[0]->contractor_id;
        $tanker_id = $result[0]->tanker_id;
        $credit_title_id = 54;
        $debit_title_id = 49;
        //now its time to insert this voucher in database...
        $journal_voucher_data = array(
            'voucher_date' =>$voucher_date,
            'detail' => 'shortage deduction voucher for black oil',
            'person_tid' => "users.1",
            'trip_id' => $trip_id,
            'trip_product_detail_id'=>$trip_detail_id,
            'tanker_id' => $tanker_id,
            'voucher_type'=>'dest_shortage_deduction',
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

    public function get_voucher_data_for_deduction_voucher($voucher_ids)
    {
        $this->db->select('(voucher_journal.shortage_rate * voucher_journal.shortage_quantity) as amount, voucher_journal.voucher_date, voucher_journal.trip_id, voucher_journal.trip_product_detail_id');
        $this->db->where_in('voucher_journal.id',$voucher_ids);
        $result = $this->db->get('voucher_journal')->result();
        return $result;
    }
    public function update_shortage_vouchers_april_may()
    {
        $voucher_ids = $this->get_shortage_voucher_ids();
        $data = $this->get_updatable_aray_for_shortage_vouchers($voucher_ids);
        $this->db->trans_start();
        $this->db->update_batch('voucher_journal',$data,'id');
        $deduction_data = $this->get_voucher_data_for_deduction_voucher($voucher_ids);

        //deleting all the shortage_deduction vouchers
        $this->db->where('voucher_journal.voucher_type','shortage_deduction');
        $this->db->delete('voucher_journal');

        //adding deduction vouchers to db
        foreach($deduction_data as $data)
        {
            $this->shortage_deduction_voucher_for_black_oil($data->amount, $data->voucher_date, $data->trip_id, $data->trip_product_detail_id);
        }

        var_dump($this->db->trans_complete());
    }

    public function correct_routes_problem()
    {
        $old_db = $this->load->database('old_db', true);

        $old_db->select('source, destination, product, COUNT(id) as num_of_records');
        $old_db->group_by('source,destination,product');
        $where = "(routes.type = 1 OR routes.type = 2 OR routes.type = 4)";
        $old_db->where($where);
        $result = $old_db->get('routes')->result();
        $multiple_routes = array();
        foreach($result as $record)
        {
            if($record->num_of_records > 1)
            {
                array_push($multiple_routes, $record);
            }
        }

        $updatable_routes_data = array();
        foreach($multiple_routes as $route)
        {
            $old_db->select('freights.id as history_id, freights.freight, routes.id as route_id');
            $old_db->join('routes','routes.id = freights.route_id','left');
            $old_db->where(array(
                'routes.source'=>$route->source,
                'routes.destination'=>$route->destination,
                'routes.product'=>$route->product,
            ));
            $where = "(routes.type = 1 OR routes.type = 2 OR routes.type = 4)";
            $this->db->where($where);
            $old_db->order_by('freights.id','desc');
            $old_db->limit(1);
            $result = $old_db->get('freights')->result();
            $new_route = $result[0];
            array_push($updatable_routes_data, array(
                'source'=>$route->source,
                'destination'=>$route->destination,
                'product'=>$route->product,
                'new_route_id'=>$new_route->route_id,
            ));
        }

        var_dump($updatable_routes_data);

        $latest_db = $this->load->database('default', true);

        $latest_db->trans_start();
        foreach($updatable_routes_data as $data)
        {
            $latest_db->where(array(
                'source'=>$data['source'],
                'destination'=>$data['destination'],
                'product'=>$data['product'],
            ));
            $where = "(routes.type = 1 OR routes.type = 2 OR routes.type = 4)";
            $latest_db->where($where);
            $update = array('id'=>$data['new_route_id']);
            $latest_db->update('routes',$update);
        }

        var_dump($latest_db->trans_complete());
    }

    public function update_new_freight_in_primary_trips()
    {
        $this->db->select('trips_details.source, trips_details.destination, trips_details.product, trips.filling_date, trips.id as trip_id, trips_details.id as detail_id,

        ');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->from('trips');
        $where = "(trips_details.freight_unit - trips_details.company_freight_unit) = 0";
        $this->db->where($where);
        $where = "(trips.type = 1 OR trips.type = 2 OR trips.type = 4 OR trips.type = 5)";
        $this->db->where($where);
        $this->db->where('trips.active',1);
        $this->db->where(array(
            'trips.filling_date >='=>'2015-04-01',
            'trips.filling_date <='=>'2015-06-01',
        ));
        $result = $this->db->get()->result();

        $updatable_trips_data = array();
        foreach($result as $record)
        {
            array_push($updatable_trips_data, $record);
        }

        $freight_units = array();
        foreach($updatable_trips_data as $record)
        {
            $freight = $this->freight($record->source, $record->destination, $record->product, $record->filling_date);

            if($freight != null)
            {
                array_push($freight_units, array(
                    'id'=>$record->detail_id,
                    'freight_unit'=>$freight,
                    'company_freight_unit'=>$freight,
                ));
            }
        }

        //updating customer and company freight per unit in trips_details
        $this->db->trans_start();
        $this->db->update_batch('trips_details',$freight_units,'id');

        //updating trips vouchers
        foreach($updatable_trips_data as $record)
        {
            $this->trips_model->save_existing_trip_completely($record->trip_id);
        }
        var_dump($this->db->trans_complete());
    }

    public function trips_with_different_company_customer_freight()
    {
        $this->db->select('trips.id as trip_id, trips_details.id as detail_id,
            trips_details.source, trips_details.destination, trips_details.product,
            trips.filling_date,
            trips_details.company_freight_unit, trips_details.freight_unit,
        ');
        $this->db->from('trips');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $where = "(trips_details.freight_unit - trips_details.company_freight_unit) != 0";
        $this->db->where($where);
        $this->db->where('trips.active','1');
        $where = "(trips.type = 1 OR trips.type = 2 OR trips.type = 4 OR trips.type = 5)";
        $this->db->where($where);
        $this->db->where(array(
            'trips.filling_date >='=>'2015-04-01',
            'trips.filling_date <'=>'2015-06-01',
        ));
        $result = $this->db->get()->result();

        $trips_data = array();
        foreach($result as $record)
        {
            array_push($trips_data, $record);
        }

        //var_dump($trips_data);

        foreach($trips_data as $record)
        {
            $freight = $this->freight($record->source, $record->destination, $record->product, $record->filling_date);

            if($record->company_freight_unit != $freight)
            {
                echo $record->detail_id."<br>";
            }
            else
            {
                //echo $record->company_freight_unit."____".$freight."<br>";
            }
        }


        // trips with zero customer freight unit
        $trips_with_zero_freight = array();
        foreach($trips_data as $record)
        {
            if($record->freight_unit == 0 || $record->company_freight_unit == 0)
            {
                array_push($trips_with_zero_freight, $record);
            }
        }

        var_dump($trips_with_zero_freight);
    }

    /*----------------------------------*/
    /*           5/28/2015              */
    /*----------------------------------*/

    public function correct_deduction_vouchers_type()
    {

        $decanding_trip_detail_ids = array();

        //getting decanding vouchers
        $this->db->select("trips_details.id");
        $this->db->from('trips_details');
        $this->db->join('trips','trips.id = trips_details.trip_id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_decnd','left');
        $this->db->where('trips_details.shortage_voucher_decnd !=',0);
        $this->db->where('products.type','black oil');
        $this->db->where(array(
            'trips.entryDate >='=>'2015-04-01',
            'trips.entryDate <'=>'2015-06-01'
        ));
        $result = $this->db->get()->result();
        foreach($result as $record)
        {
            array_push($decanding_trip_detail_ids, $record->id);
        }

        /* Correcting for decanding vouchers */

        foreach($decanding_trip_detail_ids as $record)
        {
            $this->db->where(array(
                'trip_product_detail_id'=>$record,
                'voucher_type'=>'shortage_deduction',
            ));
            $this->db->update('voucher_journal',array('voucher_type'=>'decnd_shortage_deduction'));
        }

        /*-------------------------------------*/

        //getting destination vouchers
        $destination_trip_detail_ids = array();
        $this->db->select("trips_details.id");
        $this->db->from('trips_details');
        $this->db->join('trips','trips.id = trips_details.trip_id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_dest','left');

        $this->db->where(array(
            'trips_details.shortage_voucher_dest !='=>0,
            'trips_details.shortage_voucher_decnd'=>0,
        ));
        $this->db->where('products.type','black oil');
        $this->db->where(array(
            'trips.entryDate >='=>'2015-04-01',
            'trips.entryDate <'=>'2015-06-01'
        ));
        $result = $this->db->get()->result();

        foreach($result as $record)
        {
            array_push($destination_trip_detail_ids, $record->id);
        }

        /* Correcting for decanding vouchers */

        foreach($destination_trip_detail_ids as $record)
        {
            $this->db->where(array(
                'trip_product_detail_id'=>$record,
                'voucher_type'=>'shortage_deduction',
            ));
            $this->db->update('voucher_journal',array('voucher_type'=>'dest_shortage_deduction'));
        }

        /*-------------------------------------*/
    }

    public function correct_shortage_voucher_deduction_problem()
    {
        $this->db->trans_start();
        $this->shortage_deduction_voucher_for_black_oil(5836.7964501, '2015-05-23', '1071', '1349');
        $this->shortage_deduction_voucher_for_black_oil(7133.8623279, '2015-05-23', '1070', '1348');
        $this->shortage_deduction_voucher_for_black_oil(7055.580389, '2015-05-23', '1060', '1331');
        var_dump($this->db->trans_complete());
    }


    /**
     * 6/62015
     **/

    public function account_difference()
    {
        $this->db->select('trips_details.id');
        $this->db->join('trips','trips.id = trips_details.trip_id','left');
        $this->db->where('trips.active',1);
        $this->db->where(array(
            'entryDate >='=>'2015-01-01',
            'entryDate <'=>'2015-02-01',
        ));
        $result = $this->db->get('trips_details')->result();

        $detail_ids = property_to_array('id',$result);

        $final_trips = $this->trips_model->parametrized_trips_engine_by_detail_ids($detail_ids, "trips_welcome");

        foreach($final_trips as $trip)
        {
            $detail = $trip->trip_related_details[0];
            $total_freight = $detail->get_total_freight_for_company();
            $net_freight = $total_freight - ($detail->get_wht_amount($trip->company->wht));


            $this->db->select('voucher_journal.id, voucher_entry.debit_amount as amount');
            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
            $this->db->from('voucher_journal');
            $this->db->where('voucher_journal.active',1);
            $this->db->where(array(
                'voucher_journal.transaction_column'=>'contractor_freight',
                'voucher_journal.auto_generated'=>1,
                'voucher_journal.trip_product_detail_id'=>$detail->product_detail_id,
            ));
            $this->db->where('voucher_entry.dr_cr',1);
            $result = $this->db->get()->result();

            if(round($net_freight, 3) != round($result[0]->amount, 3))
            {
                echo round($net_freight, 3)."____".round($result[0]->amount, 3)." trip: ".$trip->trip_id."<br>";
            }
        }

    }


    /**
     * 6/8/2015
     * Transfer shortage description to seperate columns
     **/
    public function transfer_shortage_description_to_seperate_columns()
    {

        $objects = array();

        // selecting shortage description from voucher entries
        $this->db->select('voucher_entry.description, voucher_entry.journal_voucher_id as voucher_id');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_dest','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->where('trips_details.shortage_voucher_dest !=',0);
        $this->db->where(array(
            'voucher_journal.shortage_quantity'=>0,
            'voucher_journal.price_unit'=>0,
        ));
        $this->db->group_by('voucher_entry.journal_voucher_id');
        $result = $this->db->get('trips_details')->result();
        foreach($result as $record)
        {
            array_push($objects, $record);
        }


        // selecting shortage description from voucher entries
        $this->db->select('voucher_entry.description, voucher_entry.journal_voucher_id as voucher_id');
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_decnd','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->where('trips_details.shortage_voucher_decnd !=',0);
        $this->db->where(array(
            'voucher_journal.shortage_quantity'=>0,
            'voucher_journal.price_unit'=>0,
        ));
        $this->db->group_by('voucher_entry.journal_voucher_id');
        $result = $this->db->get('trips_details')->result();
        foreach($result as $record)
        {
            array_push($objects, $record);
        }

        $updateable_data = array();
        foreach($objects as $obj)
        {
            $shortage_details = $obj->description;
            $shortage_details = str_replace(' ','',$shortage_details);
            $shortage_details = str_replace('Shortage_quantity=>','_&&_',$shortage_details);
            $shortage_details = str_replace('Price/Unit=>','_&&_',$shortage_details);
            $shortage_details = str_replace('Product=>','_&&_',$shortage_details);
            $shortage_details_parts = explode('_&&_', $shortage_details);
            $qty = (sizeof($shortage_details_parts) > 3)?$shortage_details_parts[1]:0;
            if(is_numeric($qty))
            {
                $price_unit = (sizeof($shortage_details_parts) > 3)?$shortage_details_parts[2]:0;
                $shortage_rate = $price_unit;

                $data = array(
                    'id'=>$obj->voucher_id,
                    'shortage_quantity'=>$qty,
                    'price_unit'=>$qty,
                    'shortage_rate'=>$shortage_rate,
                );

                array_push($updateable_data, $data);
            }
        }

        $this->db->update_batch('voucher_journal',$updateable_data,'id');
    }

    /*function freight_test()
    {
        $groups = $this->routes_model->all_routes_freights();
        var_dump($groups);
    }
    function get_freight($group, $date = '')
    {
        $date = ($date == '')?date('Y-m-d'):$date;
        $freight = null;
        if(sizeof($group) == 0)
        {
            return null;
        }

        foreach($group as $record)
        {
            if(is_date_btw($date, $record->startDate, $record->endDate) == true)
            {
                $freight = $record->freight;
            }
        }

        if($freight == null)
        {
            $record = $group[0];
            $freight = $record->freight;
        }
    }*/

    /*public function freights_history()
    {
        $this->db->select('freights.*');
        $this->db->order_by('freights.id');
        $this->db->where('route_id',1);
        $result = $this->db->get('freights')->result();
        var_dump($result);
    }*/


    /*
     * 7/2/2015 m/d/y
     * */
    function correct_contractor_commission()
    {
        $this->db->select('customer_id, contractor_id, freight_commission');
        $result = $this->db->get('contractor_customer_commissions')->result();

        $this->db->trans_start();
        foreach($result as $record)
        {
            if($record->freight_commission > 0)
            {
                $this->db->select('id');
                $this->db->where(array(
                    'customer_id'=>$record->customer_id,
                    'contractor_id'=>$record->contractor_id,
                    'contractor_commission'=>0,
                ));
                $this->db->update('trips',array('contractor_commission'=>$record->freight_commission));
            }
        }
        var_dump($this->db->trans_complete());

        die();
    }


    public function add_voucher_type_for_shortage_vouchers()
    {
        $this->db->select('shortage_voucher_dest, shortage_voucher_decnd');
        $result = $this->db->get('trips_details')->result();
        $dest_ids = property_to_array('shortage_voucher_dest', $result);
        $decnd_ids = property_to_array('shortage_voucher_decnd', $result);
        /*$ids = array_merge($dest_ids, $decnd_ids);
        $voucher_ids = array();
        foreach($ids as $id)
        {
            if($id != 0)
                array_push($voucher_ids, $id);
        }*/
        $this->db->trans_start();
        $this->db->where_in('voucher_journal.id', $dest_ids);
        $this->db->update('voucher_journal',['voucher_type'=>'shortage_voucher_dest']);

        $this->db->where_in('voucher_journal.id', $decnd_ids);
        $this->db->update('voucher_journal',['voucher_type'=>'shortage_voucher_decnd']);
        var_dump($this->db->trans_complete());
    }

    public function create_query_shortage()
    {
        $this->db->select('trips.id as trip_id, trips_details.id as detail_id,
         destination_shortages_view.shortage_id as dest_shrt_id,
          decanding_shortages_view.shortage_id as decnd_shrt_id,
          source_city.cityName as source, destination_city.cityName as destination,
          products.productName as product_name, tankers.truck_number, customers.name as customer,
          companies.name as company, carriage_contractors.name as contractor,

          ');
        $this->db->from('trip');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('destination_shortages_view','destination_shortages_view.trip_detail_id = trips_details.id','left');
        $this->db->join('decanding_shortages_view','decanding_shortages_view.trip_detail_id = trips_details.id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','left');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','left');
        $this->db->join('customers','customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies','companies.id = trips.company_id','left');
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->where('trips.active',1);
        $where = "(destination_shortages_view.shortage_id IS NULL OR decanding_shortages_view.shortage_id IS NULL)";
        $this->db->where($where);

        $result = $this->db->get()->result();
        var_dump($result);
    }

    public function trips_with_no_shortage_given_view()
    {
        $this->db->select("*");
        $result = $this->db->get('trips_with_no_shortage_given_view')->result();
        var_dump($result); die();
    }

    public function transfer_old_shortages()
    {
        //transfer destination shortages
        $this->db->select('
            trips_details.shortage_voucher_dest as voucher_id,
            trips_details.trip_id, trips_details.id as trip_detail_id,
            voucher_journal.voucher_date as shortage_date, voucher_journal.shortage_rate,
            voucher_journal.price_unit, voucher_journal.shortage_quantity,
        ');
        $this->db->where('trips_details.shortage_voucher_dest !=', 0);
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_dest','left');
        $result = $this->db->get('trips_details')->result();

        $shortages = [];
        foreach($result as $record){
            $shortage = [
                'trip_detail_id'=>$record->trip_detail_id,
                'rate' => round(($record->shortage_rate == 0)?$record->price_unit:$record->shortage_rate, 3),
                'quantity' => $record->shortage_quantity,
                'date' => $record->shortage_date,
                'type' => 1,
            ];

            $shortages[] = $shortage;
        }

        $this->db->insert_batch('shortages',$shortages);

        $this->db->select('trips_details.shortage_voucher_dest as voucher_id, shortages.id as shortage_id');
        $this->db->where('shortages.type',1);
        $this->db->where('trips_details.shortage_voucher_dest !=', 0);
        $this->db->join('trips_details','trips_details.id = shortages.trip_detail_id','inner');
        $result = $this->db->get('shortages')->result();

        $vouchers_data = [];
        foreach($result as $record)
        {
            $vouchers_data[] = [
                'id'=>$record->voucher_id,
                'shortage_id'=>$record->shortage_id,
            ];
        }
        $this->db->update_batch('voucher_journal',$vouchers_data,'id');



        //decanding transfer
        $this->db->select('
            trips_details.shortage_voucher_decnd as voucher_id,
            trips_details.trip_id, trips_details.id as trip_detail_id,
            voucher_journal.voucher_date as shortage_date, voucher_journal.shortage_rate,
            voucher_journal.price_unit, voucher_journal.shortage_quantity,
        ');
        $this->db->where('trips_details.shortage_voucher_decnd !=', 0);
        $this->db->join('voucher_journal','voucher_journal.id = trips_details.shortage_voucher_decnd','left');
        $result = $this->db->get('trips_details')->result();

        $shortages = array();
        foreach($result as $record){
            $shortage = [
                'trip_detail_id'=>$record->trip_detail_id,
                'rate' => round(($record->shortage_rate == 0)?$record->price_unit:$record->shortage_rate, 3),
                'quantity' => $record->shortage_quantity,
                'date' => $record->shortage_date,
                'type' => 2,
            ];

            $shortages[] = $shortage;
        }
        $this->db->insert_batch('shortages',$shortages);

        $this->db->select('trips_details.shortage_voucher_decnd as voucher_id, shortages.id as shortage_id');
        $this->db->where('shortages.type',2);
        $this->db->where('trips_details.shortage_voucher_decnd !=', 0);
        $this->db->join('trips_details','trips_details.id = shortages.trip_detail_id','inner');
        $result = $this->db->get('shortages')->result();

        $vouchers_data = [];
        foreach($result as $record)
        {
            $vouchers_data[] = [
                'id'=>$record->voucher_id,
                'shortage_id'=>$record->shortage_id,
            ];
        }
        //var_dump($vouchers_data);
        $this->db->update_batch('voucher_journal',$vouchers_data,'id');
    }

    function delete_dangling_voucher_entries()
    {
        $query = 'SELECT DISTINCT journal_voucher_id FROM voucher_entry LEFT JOIN voucher_journal ON voucher_entry.journal_voucher_id=voucher_journal.id WHERE voucher_journal.id IS NULL';
        $result = $this->db->query($query)->result();

        $voucher_ids = property_to_array('journal_voucher_id',$result);
        $this->db->where_in('voucher_entry.journal_voucher_id',$voucher_ids);
        $this->db->delete('voucher_entry');
    }



    /**
     *      WARNING WARNING WARNING WARNING
     * PLEASE DO NOT CALL THIS FUNCTION ITS WRONG
     *
     **/
    /*function update_shortage_id_in_deduction_vouchers()
    {
        $this->db->trans_start();
        //updating destination
        $this->db->select('id, trip_detail_id');
        $this->db->where('type',1);
        $result = $this->db->get('shortages')->result();
        foreach($result as $record)
        {
            $data['shortage_id'] = $record->id;
            //var_dump($data);
            $this->db->where('trip_product_detail_id', $record->trip_detail_id);
            $this->db->where('voucher_type','dest_shortage_deduction');
            $this->db->update('voucher_journal',$data);
        }


        //updating decanding
        $this->db->select('id, trip_detail_id');
        $this->db->where('type',2);
        $result = $this->db->get('shortages')->result();
        foreach($result as $record)
        {
            $data['shortage_id'] = $record->id;
            //var_dump($data);
            $this->db->where('trip_product_detail_id', $record->trip_detail_id);
            $this->db->where('voucher_type','decnd_shortage_deduction');
            $this->db->update('voucher_journal',$data);
        }

        var_dump($this->db->trans_complete());

    }*/

    /*
        please check syntax error for ROUND
    */

    function destination_shortages_temp_view()
    {
        $select = "trips.id";
        $this->db->select($select);
        $this->db->from('shortage');
        $this->db->join('voucher_journal','voucher_journal.shortage_id = shortages.id','left');
        $this->db->join('trips_details','trips_details.id = shortages.trip_detail_id','inner');
        $this->db->join('trips','trips.id = trips_details.trip_id','inner');
        $this->db->join('products','products.id = trips_details.product','inner');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','inner');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','inner');
        $this->db->join('customers','customers.id = trips.customer_id','inner');
        $this->db->join('carriage_contractors','carriage_contractors.id = trips.contractor_id','inner');
        $this->db->join('companies','companies.id = trips.company_id','inner');
        $this->db->join('tankers','tankers.id = trips.tanker_id','inner');
        $this->db->where('trips.active',1);
        $this->db->where('shortages.type', 1);

        $result = $this->db->get()->result();

    }

    function decanding_shortages_view()
    {

        $str = "
        trips.id AS trip_id,trips.entryDate AS trip_entry_date,
        shortages.id AS shortage_id,shortages.trip_detail_id AS trip_detail_id,
        shortages.quantity AS quantity,shortages.rate AS rate,
        (shortages.rate * shortages.quantity) AS shortage_amount,
        shortages.date AS date,source_city.cityName AS source,
        destination_city.cityName AS destination,products.productName AS product_name,
        customers.id as customer_id, customers.name as customer_name,
        companies.id as companies_id, companies.name as company_name,
        carriage_contractors.id as contractor_id, carriage_contractors.name as contractor_name,";


        $select = "trips.id";
        $this->db->select($select);
        $this->db->from('shortages');
        $this->db->join('voucher_journal','voucher_journal.shortage_id = shortages.id','left');
        $this->db->join('trips_details','trips_details.id = shortages.trip_detail_id','inner');
        $this->db->join('trips','trips.id = trips_details.trip_id','inner');
        $this->db->join('products','products.id = trips_details.product','inner');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','inner');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','inner');
        $this->db->join('customers','customers.id = trips.customer_id','inner');
        $this->db->join('carriage_contractors','carriage_contractors.id = trips.contractor_id','inner');
        $this->db->join('companies','companies.id = trips.company_id','inner');
        $this->db->join('tankers','tankers.id = trips.tanker_id','inner');
        $this->db->where('trips.active',1);
        $this->db->where('shortages.type', 2);

        $result = $this->db->get()->result();
        var_dump($result);

    }

    
//    function bze_tankers_view()
//    {
//        $this->db->select("
//        tankers.*,
//        ( CASE
//            WHEN count(trips_details.id) > 0 THEN '1'
//            ELSE 0
//          END
//        ) as bze
//
//        ");
//        $this->db->join('trips','trips.tanker_id = tankers.id','left');
//        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
//        $having = "count(trips_details.id) > 0";
//        $this->db->having($having);
//        $where = "(trips_details.stn_number='')";
//        $this->db->where($where);
//        $this->db->where('trips.active',1);
//        $this->db->group_by('tankers.id');
//        $result = $this->db->get('tanker')->result();
//        var_dump($result);
//
//
//    }
//    function free_tankers_view()
//    {
//        $this->db->select("tankers.*,
//        ( CASE
//            WHEN bze_tankers_view.id IS NULL THEN '0'
//            ELSE 1
//          END
//        ) as bze
//        ");
//        $this->db->join('bze_tankers_view','bze_tankers_view.id = tankers.id','left');
//        $this->db->where('bze_tankers_view.id',null);
//        $this->db->having('bze',0);
//        $result = $this->db->get('tanker')->result();
//        var_dump($result);
//    }

    function incomplete_trips_view()
    {
        $this->db->select('trips.id as trip_id, trips.tanker_id, trips.entryDate,
		source_city.id as source_id, source_city.cityName as source,
		destination_city.id as destination_id, destination_city.cityName as destination,
		products.id as product_id, products.productName as product,
		trips.customer_id, trips.contractor_id, trips.company_id,
		customers.name as customer_name,
	');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','inner');
        $this->db->join('customers','customers.id = trips.customer_id','left');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','inner');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','inner');
        $this->db->join('products','products.id = trips_details.product');
        $this->db->where('trips_details.stn_number','');
        $this->db->where('trips.active',1);
        $result = $this->db->get('trip')->result();
        var_dump($result);

    }

    function tankers_status_view()
    {
        $this->db->select("
            tankers.id, tankers.truck_number, tankers.engine_number, tankers.chase_number,
            (
              CASE
                WHEN tankers.fitness_certificate = 0 THEN
                    'no'
                ELSE
                    'yes'
              END
            ) as fitness_certificate,
             tankers.capacity,
            (
              CASE
                WHEN trips.trip_id IS NULL THEN
                    tankers.customerId
                ELSE
                    trips.customer_id
              END
            ) as customerId,
            trips.source, trips.destination, trips.product,
            (
              CASE
                WHEN trips.trip_id IS NULL THEN
                    customers.name
                ELSE
                    trips.customer_name
              END
            ) as customer,
            trips.trip_id as trip_id, trips.entryDate as trip_entry_date, trips.product_id,
            ( CASE
                WHEN trips.trip_id IS NULL THEN 'free'
                ELSE 'on_move'
              END
            ) as status,
        ");
        $this->db->join('incomplete_trips_view as trips','trips.tanker_id = tankers.id','left');
        $this->db->join('customers','customers.id = tankers.customerId','left');
        $result = $this->db->get('tanker')->result();
        var_dump($result);
    }

    function calculation_sheet_view()
    {
        $this->db->select('
	*,product_id as product, trip_detail_id as detail_id, wht as tax,

	');
        $result = $this->db->get('trips_details_upper_layer_vie')->result();
        var_dump($result);
    }

    function trips_view()
    {
        $this->db->select('
            tul.trip_id, tul.entryDate, tul.tanker_id, tul.tanker_number, tankers.capacity,
            tul.customer_name, tul.company_name, tul.contractor_name,
            tul.customer_id, tul.company_id, tul.contractor_id,
            tul.source_city_name as source, tul.destination_city_name as destination,
            tul.productName as product,
            tul.source_id, tul.destination_id, tul.product_id,
            tul.dis_qty as product_quantity,
            tul.stn_number,
            tul.type as trip_type_id, trip_types.trip_type, trip_types.trip_sub_type
        ');
        $this->db->from('trips_details_upper_layer_vie as tul');
        $this->db->join('trip_types','trip_types.id = tul.type','left');
        $this->db->join('tankers','tankers.id = tul.tanker_id','left');
        $result = $this->db->get()->result();
        var_dump($result);
    }

    function committed_decanding_shortages_view()
    {
        $this->db->select('decnd_v.*');
        $this->db->from('decanding_shortages_view as decnd_v');
        $this->db->join('voucher_journal','voucher_journal.shortage_id = decnd_v.shortage_id','inner');
        $result = $this->db->get()->result();

        var_dump($result);
    }


    function trips_details_upper_layer_view()
    {
        $shortage_quantity = "
            (
                case
                    when (t_d_ids.decnd_shortage_id is null) then
                        (
                            case
                                when (t_d_ids.dest_quantity is null) then
                                    0
                                else
                                    t_d_ids.dest_quantity
                            end
                        )
                    else
                        t_d_ids.decnd_quantity
                end
            )
        ";
        $shortage_rate = "
        (
                case
                    when (t_d_ids.decnd_shortage_id is null) then
                        (
                            case
                                when (t_d_ids.dest_rate is null) then
                                    0
                                else
                                    t_d_ids.dest_rate
                            end
                        )
                    else
                        t_d_ids.decnd_rate
                end
            )
        ";
        $shortage_id = "
            (
                case
                    when (t_d_ids.decnd_shortage_id is null) then
                        (
                            case
                                when (t_d_ids.dest_shortage_id is null) then
                                    0
                                else
                                    t_d_ids.dest_shortage_id
                            end
                        )
                    else
                        t_d_ids.decnd_shortage_id
                end
            )
        ";
        $this->db->select('
            t_d_ids.trip_id, t_d_ids.trip_detail_id,

            '.$shortage_quantity.' AS shortage_quantity,
            '.$shortage_rate.' AS shortage_rate,
            '.$shortage_id.' AS shortage_id,

             trips_details.product_quantity as dis_qty,
             (trips_details.product_quantity - '.$shortage_quantity.')as rec_qty,

            source_cities.cityName as source_city_name, trips_details.source as source_id,
            destination_cities.cityName as destination_city_name, trips_details.destination as destination_id,

            trips.invoice_date, trips.invoice_number, trips.company_id,
            trips.customer_id, trips.contractor_id, trips_details.product as product_id,
            tankers.truck_number as tanker_number, carriage_contractors.name as contractor_name,
            customers.name as customer_name,
            companies.name as company_name, trips_details.freight_unit as customer_freight_unit,
            trips_details.company_freight_unit, trips.company_commission_2 as wht,
            products.type as product_type, products.productName,
            trips.entryDate as entryDate, trips.tanker_id,

            trips_details.stn_number, trips_details.bill_id, trips.type,
            trip_types.trip_type, trip_types.trip_sub_type,
            trips.company_commission_1 as company_commission,
            trips.contractor_commission,
            bills.billed_date_time

        ');
        $this->db->from('trips_details_and_dest_decnd_id as t_d_ids');
        $this->db->join('trips_details','trips_details.id = t_d_ids.trip_detail_id','left');
        $this->db->join('bills','bills.id = trips_details.bill_id','left');
        //$this->db->join('destination_shortages_view as dest_v','dest_v.shortage_id = t_d_ids.dest_shortage_id','left');
        //$this->db->join('decanding_shortages_view as decnd_v','decnd_v.shortage_id = t_d_ids.decnd_shortage_id','left');
        $this->db->join('cities as source_cities','source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities','destination_cities.id = trips_details.destination','left');
        $this->db->join('trips','trips.id = t_d_ids.trip_id','left');
        $this->db->join('trip_types','trip_types.id = trips.type','left');
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->join('customers','customers.id = trips.customer_id','left');
        $this->db->join('carriage_contractors','carriage_contractors.id = trips.contractor_id','left');
        $this->db->join('companies','companies.id = trips.company_id','left');

        $result = $this->db->get()->result();
        var_dump(($result));
    }

    public function manage_accounts_black_oil_view()
    {
        $shortage_amount = "(shortage_quantity * shortage_rate)";
        $freight_on_shortage_qty_cmp = "(company_freight_unit * shortage_quantity)";
        $freight_on_shortage_qty_cst = "(customer_freight_unit * shortage_quantity)";
        $total_freight_cmp = "(dis_qty * company_freight_unit)";
        $total_freight_cst = "(dis_qty * customer_freight_unit)";
        $freight_amount_cmp = "(".$total_freight_cmp." - ".$freight_on_shortage_qty_cmp.")";
        $freight_amount_cst = "(".$total_freight_cst." - ".$freight_on_shortage_qty_cst.")";
        $payable_before_tax = "(".$freight_amount_cmp." - ".$shortage_amount.")";
        $wht_amount = "(wht * ".$freight_amount_cmp." / 100)";
        $net_payables = "(".$payable_before_tax."-".$wht_amount.")";
        $company_commission_amount = "(company_commission * ".$freight_amount_cmp."/100)";
        $contractor_net_freight = "(".$net_payables." - ".$company_commission_amount.")";
        $contractor_commission = "(contractor_commission - wht - company_commission)";
        $contractor_commission_amount = "(".$contractor_commission." * ".$freight_amount_cst."/100)";
        $customer_freight_amount = "(".$total_freight_cst." - (contractor_commission * ".$freight_amount_cst."/100) - (".$freight_on_shortage_qty_cst." + ".$shortage_amount."))";

        $select = '
                trip_id, trip_detail_id, trip_sub_type, type as trip_type_id, td.stn_number
                trip_type, entryDate as trip_date, source_city_name as source,
                source_id, destination_city_name as destination,
                destination_id, invoice_date, invoice_number, tanker_id, tanker_number,
                productName as product, product_id, dis_qty, rec_qty, shortage_quantity,
                '.$total_freight_cmp.' as total_freight_cmp,
                shortage_rate, '.$shortage_amount.' as shortage_amount,
                '.$freight_on_shortage_qty_cmp.' as freight_on_shortage_qty_cmp,
                '.$freight_on_shortage_qty_cst.' as freight_on_shortage_qty_cst,
                customer_freight_unit, company_freight_unit, company_name as company, company_id,
                '.$payable_before_tax.' as payable_before_tax, wht,
                '.$wht_amount.' as wht_amount, '.$freight_amount_cmp.' as freight_amount_cmp,
                '.$net_payables.' as net_payables, '.$company_commission_amount.' as company_commission_amount,
                '.$contractor_net_freight.' as contractor_net_freight, company_commission,
                '.$contractor_commission.' as contractor_commission,
                '.$contractor_commission_amount.' as contractor_commission_amount,
                contractor_name as contractor, contractor_id,
                '.$total_freight_cst.' as total_freight_cst, '.$freight_amount_cst.' as freight_amount_cst,
                '.$customer_freight_amount.' as customer_freight, customer_name as customer,
                customer_id, bill_id, trip_id as service_charges, billed_date_time,
                 ( CASE
                        WHEN bill_id = "0" THEN "Not Billed"
                        ELSE "Billed"
                    END
                 ) AS billed
            ';
        $this->db->select($select);
        $this->db->where('product_type','black oil');
        $this->db->order_by('trip_id','desc');
        $result = $this->db->get('trips_details_upper_layer_vie as td')->result();
        var_dump($result);
    }

    function _create_captcha(){

        /*$words = array( '2', '3', '4', '5', '6','7', '8', '9','0', 'a', 'b','z', 'n', 'b','x', 'y', 'v');
        $count = 1;
        $word = "";
        while($count < 3){
            $word = $word.$words[mt_rand(0, 16)];
            $count++;
        }
        $vals = array(
            'word'      => strtolower($word),
            'img_path'	=> './captcha/',
            'img_url'	=> base_url().'captcha/',
            'font_path'	=> 'fonts/DENMARK.ttf',
            'img_width'	=> '210',
            'img_height' => 40,
            'expiration' => 20
        );
        $cap = create_captcha($vals);
        return $cap;*/
    }

    function _check_credentials($str, $data){
        /*list($table, $userField, $passField)=explode('.', $data);
        //You have to change this line below
        if($this->input->post('username') != "" && $this->input->post('password') != "" && $this->input->post('confirmCaptcha') != "" && $this->form_validation->captcha_check($this->input->post('confirmCaptcha'), 'captcha') == true){
            //////////////////////////////////////////////////////////////////////////////////////////////////
            $userName = $userField.".".$this->input->post('username');
            $password = $passField.".".$this->input->post('password');
            $credentials = $this->admin_model->check_credentials($table, $userName, $password);
            if($credentials == false){
                $this->form_validation->set_message('_check_credentials','Invalid Username/Password. Please try again');
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }*/
    }

}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */