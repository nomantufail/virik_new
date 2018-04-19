<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Accounts extends ParentController {

    //public variables...

    public function __construct()
    {
        parent::__construct();

    }

    /* The default function that gets called when visiting the page */
    public function index()
    {
        if($this->login == true){
            //redirecting to the required location
            redirect(base_url().'accounts/journal/users/1');
            /***********************************/
            $name = (isset($_GET['name']))?$_GET['name']:'';
            $keys = array(
                'name'=>$name,
            );

            //defining the sorting column
            $sort = array(
                'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'entryDate',
                'order' => (isset($_GET['order']))?$_GET['order']:'asc',
            );
            ///////////////////////////////////////////////////////////////

            //counting total agents
            $num_of_records = $this->customers_model->count_searched_customers($keys);
            $num_of_records = ($num_of_records == 0)?1:$num_of_records;
            $config = $this->helper_model->pagination_configs("otherAgents/index/?", "other_agents", $num_of_records);
            $this->pagination->initialize($config);

            $pageNumber = 0;

            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
                if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
            }
            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'pages' => $this->pagination->create_links(),
                'someMessage'=>'',
            );

            //saving the voucher
            if($this->form_validation->run('save_tanker_expense_voucher') == true){
                if( $this->accounts_model->save_tanker_expense_voucher() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            $bodyData['customers'] = $this->customers_model->search_limited_customers($config["per_page"], $pageNumber, $keys, $sort);

            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/customers', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }

    }

    public function customers()
    {
        if($this->login == true){
            $name = (isset($_GET['name']))?$_GET['name']:'';
            $keys = array(
                'name'=>$name,
            );

            //defining the sorting column
            $sort = array(
                'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'entryDate',
                'order' => (isset($_GET['order']))?$_GET['order']:'asc',
            );
            ///////////////////////////////////////////////////////////////

            //counting total agents
            $num_of_records = $this->customers_model->count_searched_customers($keys);
            $num_of_records = ($num_of_records == 0)?1:$num_of_records;
            $config = $this->helper_model->pagination_configs("otherAgents/index/?", "other_agents", $num_of_records);
            $this->pagination->initialize($config);

            $pageNumber = 0;

            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
                if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
            }
            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'pages' => $this->pagination->create_links(),
                'someMessage'=>'',
            );

            //saving the voucher
            if($this->form_validation->run('save_tanker_expense_voucher') == true){
                if( $this->accounts_model->save_tanker_expense_voucher() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            $bodyData['customers'] = $this->customers_model->search_limited_customers($config["per_page"], $pageNumber, $keys, $sort);

            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/customers', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }
    }
    public function contractors()
    {
        if($this->login == true){
            $name = (isset($_GET['name']))?$_GET['name']:'';
            $keys = array(
                'name'=>$name,
            );

            //defining the sorting column
            $sort = array(
                'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'entryDate',
                'order' => (isset($_GET['order']))?$_GET['order']:'asc',
            );
            ///////////////////////////////////////////////////////////////

            //counting total agents
            $num_of_records = $this->customers_model->count_searched_customers($keys);
            $num_of_records = ($num_of_records == 0)?1:$num_of_records;
            $config = $this->helper_model->pagination_configs("otherAgents/index/?", "other_agents", $num_of_records);
            $this->pagination->initialize($config);

            $pageNumber = 0;

            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
                if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
            }
            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'pages' => $this->pagination->create_links(),
                'someMessage'=>'',
            );


            //saving the voucher
            if($this->form_validation->run('save_tanker_expense_voucher') == true){
                if( $this->accounts_model->save_voucher("contractors") == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            $bodyData['contractors'] = $this->carriageContractors_model->carriageContractors();

            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/contractors', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }
    }

    public function settings()
    {
        if($this->login == true){

            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'someMessage'=>'',
            );

            //saving the title
            if(isset($_POST['add_title'])){
                $this->accounts_model->add_account_title();
                $bodyData['someMessage'] = array('message'=>'Title Saved Successfully!', 'type'=>'alert-success');
            }

            $bodyData['titles'] = $this->accounts_model->account_titles();

            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/settings', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }
    }

    public function close_balance($agent, $agent_id)
    {
        var_dump($this->accounts_model->closing_balance($agent, $agent_id));
    }

    public function get_global_balance_sheet()
    {
        $agent_type = (isset($_GET['agent_type']))?$_GET['agent_type']:'other_agents';
        $agent_id = (isset($_GET['agent_id']))?$_GET['agent_id']:1;
        $from_date = (isset($_GET['from']))?$_GET['from']:"1947-01-01";
        $to_date = (isset($_GET['to']))?$_GET['to']:"";
        $keys['agent_type'] = $agent_type;
        $keys['agent_id'] = $agent_id;
        $keys['from'] = $from_date;
        $keys['to'] = $to_date;
        $bodyData['records'] = $this->accounts_model->global_balance_sheet($keys);

        $bodyData['agent_type'] = $agent_type;
        $bodyData['from_date'] = $from_date;
        $bodyData['to_date'] = $to_date;
        $bodyData['account_holder_type'] = $agent_type;
        //name of account holder
        $this->db->select('name');
        $table_name = ($agent_type == "contractors")?"carriage_contractors":$agent_type;
        $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
        if(sizeof($account_holder) >= 1){
            $account_holder = $account_holder[0]->name;
        }else {$account_holder = '';}
        $bodyData['account_holder'] = $account_holder;
        $bodyData['opening_balance'] = $this->accounts_model->global_balance_sheet_opening_balance($keys);

        $this->load->view("accounts/global_balance_sheet", $bodyData);
    }

    public function global_test_accounts()
    {
        $headerData = array(
            'title' => 'Virik Logistics | Accounts',
            'page' => 'accounts',
        );

        $this->load->view('components/header', $headerData);
        $this->load->view("accounts/global_test_accounts");
        $this->load->view('components/footer');
    }

    public function dash_board($agent, $agent_id){
        if($this->login == true){

            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();

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

            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
                'pages' => $this->pagination->create_links(),
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
            $bodyData['account_holder_type'] = $agent;

            //fething the required acount holders list
            switch($agent)
            {
                case "contractors":
                    $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                    break;
                case "customers":
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
                case "companies":
                    $bodyData['account_holders'] = $this->companies_model->companies();
                    break;
                case "other_agents":
                    $bodyData['account_holders'] = $this->other_agents_model->other_agents();
                    break;
                case "users":
                    $bodyData['account_holders'] = $this->admin_model->admins();
                    break;
                default:
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
            }
            //////////////////////////////////////////////

            if(isset($_GET['print'])){
                $this->load->view('accounts/components/print_dash_board', $bodyData);
            }else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/dash_board', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->load->view('admin/login');
        }
    }

    public function journal($agent, $agent_id)
    {
        if($this->login == true){

            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();
            $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:$accounting_year['from'];
            $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:$accounting_year['to'];
            ///////////////////////////////////////////////////////////////////

            //setting keys for searchings
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';;
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['expense_type'] = (isset($_GET['expense_type']))?$_GET['expense_type']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year_from;
            $keys['accounting_year_to'] = $accounting_year_to;

            //defining the sorting column
            $sort = array(
                'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'voucher_journal.id',
                'order' => (isset($_GET['order']))?$_GET['order']:'asc',
            );
            ///////////////////////////////////////////////////////////////

            $total_rows = $this->accounts_model->count_searched_journal($agent, $agent_id, $keys);
            $total_rows = ($total_rows == 0)?1:$total_rows;

            /////////////////
            //computing the url for page number
            $query_string = explode('&page',$_SERVER['QUERY_STRING']);
            $query_string = $query_string[0];
            //////////////////////////////////
            $config = $this->helper_model->pagination_configs("accounts/journal/$agent/$agent_id/?".$query_string, "", $total_rows);
            $this->pagination->initialize($config);

            $pageNumber = 0;
            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
                if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
            }
            //////////////////////////////////////////////////////////////////////////////////
            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => array(
                    'from'=>$accounting_year_from,
                    'to'=>$accounting_year_to,
                ),
                'pages' => $this->pagination->create_links(),
            );

            //saving the voucher
            if(isset($_POST['save_voucher'])){
                if( $this->accounts_model->save_tanker_expense_voucher() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            //updating the voucher
            if(isset($_POST['save_journal_voucher'])){
                if( $this->accounts_model->update_journal_voucher() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            //deleting the voucher
            if(isset($_GET['delete_voucher'])){
                if( $this->accounts_model->delete_voucher($_GET['delete_voucher']) == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }////////////////////////////////////////////////////////

            $bodyData['journal'] = $this->accounts_model->search_journal($agent, $agent_id, $config["per_page"], $pageNumber, $keys, $sort);

            /*----------Testing-----------------------------*/
            /*$this->db->select('voucher_journal.id');
            $this->db->from('voucher_journal');
            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
            $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
            $this->db->distinct();
            $this->db->where(array(
                    'voucher_journal.trip_id !='=>0,
                    'voucher_journal.active'=>1,
                    'voucher_entry.account_title_id !='=>38,
                    'voucher_entry.account_title_id !='=>37,
                )
            );
            $this->db->where(array(
                'person_tid'=>'users.1',
            ));
            $result = $this->db->get()->result();
            $voucher_ids = array();
            foreach($result as $record){
                array_push($voucher_ids, $record->id);
            }

            $this->db->select('voucher_entry.journal_voucher_id');
            $this->db->distinct();
            $this->db->where(array(
                'voucher_entry.related_other_agent !='=>0,
                'voucher_entry.dr_cr'=>0,
                'voucher_entry.account_title_id'=>42,
            ));
            $this->db->where_in('voucher_entry.journal_voucher_id', $voucher_ids);
            $result = $this->db->get('voucher_entry')->result();
            $voucher_ids = array();
            foreach($result as $record){
                array_push($voucher_ids, $record->journal_voucher_id);
            }

            $this->db->select('voucher_journal.trip_id, voucher_entry.account_title_id, voucher_entry.debit_amount, voucher_entry.journal_voucher_id as voucher_id,');
            $this->db->from('voucher_journal');
            $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
            $this->db->where_in('voucher_journal.id',$voucher_ids);
            $this->db->where('voucher_entry.dr_cr',1);
            $result = $this->db->get()->result();
            $deletable_voucher_ids = array();
            foreach($result as $entry_1)
            {
                foreach($result as $entry_2)
                {
                    if($entry_1->voucher_id != $entry_2->voucher_id)
                    {
                        if(
                            $entry_1->trip_id == $entry_2->trip_id && $entry_1->debit_amount == $entry_2->debit_amount
                            && $entry_1->account_title_id == $entry_2->account_title_id
                        )
                        {
                            array_push($deletable_voucher_ids, $entry_1->voucher_id);
                            array_push($deletable_voucher_ids, $entry_2->voucher_id);
                        }
                    }
                }
            }
            $journal = array();
            foreach($deletable_voucher_ids as $id)
            {
                $ar = array($id,);
                $vouchers = $this->accounts_model->journal("users","1",$ar,"");
                array_push($journal, $vouchers[0]);

            }
            $bodyData['journal'] = $journal;*/
            /*-------------------------------------------------*/


            $bodyData['titles'] = $this->accounts_model->account_titles();
            $bodyData['tankers'] = ($agent == "users")?$this->tankers_model->tankers():$this->tankers_model->tankers($agent_id);

            //name of account holder
            $this->db->select('name');
            $table_name = ($agent == "contractors")?"carriage_contractors":$agent;
            $table_name = ($agent == "users")?"admin":$agent;
            $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;
            $bodyData['account_holder_type'] = $agent;

            //fething the required acount holders list
            switch($agent)
            {
                case "contractors":
                    $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                    break;
                case "customers":
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
                case "companies":
                    $bodyData['account_holders'] = $this->companies_model->companies();
                    break;
                case "other_agents":
                    $bodyData['account_holders'] = $this->other_agents_model->other_agents();
                    break;
                case "users":
                    $bodyData['account_holders'] = $this->admin_model->admins();
                    break;
                default:
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
            }
            //////////////////////////////////////////////

            if(isset($_GET['print'])){
                $this->load->view('accounts/components/print_journal', $bodyData);
            }
            else if(isset($_GET['export']))
            {
                $this->load->view('accounts/components/export/export_journal', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/journal', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->load->view('admin/login');
        }
    }

    public function ledger($agent, $agent_id)
    {
        if($this->login == true){

            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();
            $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:$accounting_year['from'];
            $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:$accounting_year['to'];
            ///////////////////////////////////////////////////////////////////

            //setting keys
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';;
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';;
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['expense_type'] = (isset($_GET['expense_type']))?$_GET['expense_type']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year_from;
            $keys['accounting_year_to'] = $accounting_year_to;
            ////////////////////////////////



            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
            );

            $bodyData['ledger'] = $this->accounts_model->ledger($agent, $agent_id, $keys);

            /*findin opening balance*/
            $bodyData['opening_balance'] = $this->accounts_model->opening_balance_for_ledger($agent, $agent_id);
            /****************************/
            //name of account holder
            $this->db->select('name');
            $table_name = ($agent == "contractors")?"carriage_contractors":$agent;
            $table_name = ($agent == "users")?"admin":$agent;
            $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;
            $bodyData['font_size'] = $this->settings_model->system_settings('printing font size');

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    $bodyData['ledger'] = $this->helper_model->filter_records($bodyData['ledger'], $_POST['check'],"voucher_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('accounts/components/print_ledger', $bodyData);
            }
            else if(isset($_GET['export']))
            {
                if(isset($_POST['check'])){
                    $bodyData['ledger'] = $this->helper_model->filter_records($bodyData['ledger'], $_POST['check'],"voucher_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('accounts/components/export/export_ledger', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/ledger', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->load->view('admin/login');
        }
    }
    public function trial_balance($agent, $agent_id)
    {
        if($this->login == true){
            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();
            $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:$accounting_year['from'];
            $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:$accounting_year['to'];
            $accounting_year['from'] = $accounting_year_from;
            $accounting_year['to'] = $accounting_year_to;
            ///////////////////////////////////////////////////////////////////

            //setting keys for searchings
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';;
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';;
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['expense_type'] = (isset($_GET['expense_type']))?$_GET['expense_type']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year_from;
            $keys['accounting_year_to'] = $accounting_year_to;

            //calculating accounting year for customer balance
            $accounting_year_for_customer_balance = array();
            if($keys['custom_from'] != '' && $keys['custom_to'] != ''){
                $accounting_year_for_customer_balance['from'] = $keys['custom_from'];
                $accounting_year_for_customer_balance['to'] = $keys['custom_to'];
            }else{
                $accounting_year_for_customer_balance['from'] = $keys['accounting_year_from'];
                $accounting_year_for_customer_balance['to'] = $keys['accounting_year_to'];
            }

            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
            );

            if(isset($_POST['save_trial_balance_settings'])){
                if( $this->accounts_model->save_trial_balance_settings() == true){
                    $bodyData['someMessage'] = array('message'=>'Settings Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }

            //$bodyData['opening_balance'] = $this->accounts_model->opening_balance($agent, $agent_id);
            //$bodyData['closing_balance'] = $this->accounts_model->closing_balance($agent, $agent_id);
            $bodyData['trial_balance'] = $this->accounts_model->trial_balance($agent, $agent_id, $keys);
            $bodyData['font_size'] = $this->settings_model->system_settings('printing font size');
            //fetching the accounts related data of the account holder
            /*switch($agent){
                case "customers":
                    $bodyData['customer_balance'] = $this->accounts_model->customer_balance($agent_id, $accounting_year_for_customer_balance);
                    break;
                case "contractors":
                    $bodyData['contractor_accounts'] = $this->accounts_model->contractor_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "companies":
                    $bodyData['company_accounts'] = $this->accounts_model->company_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "other_agents":
                    $bodyData['other_agents_accounts'] = $this->accounts_model->other_agent_accounts_for_trial_balance($agent_id, $keys);
                    break;
            }*/

            $bodyData['group_bys'] = $this->accounts_model->fetch_trial_balance_settings();

            //name of account holder
            $this->db->select('name');
            $table_name = ($agent == "contractors")?"carriage_contractors":$agent;
            $table_name = ($agent == "users")?"admin":$agent;
            $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;
            $bodyData['account_holder_type'] = $agent;

            //fething the required acount holders list
            /*switch($agent)
            {
                case "contractors":
                    $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                    break;
                case "customers":
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
                case "companies":
                    $bodyData['account_holders'] = $this->companies_model->companies();
                    break;
                case "other_agents":
                    $bodyData['account_holders'] = $this->other_agents_model->other_agents();
                    break;
                case "users":
                    $bodyData['account_holders'] = $this->admin_model->admins();
                    break;
                default:
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
            }*/
            //////////////////////////////////////////////

            if(isset($_GET['print'])){
                $this->load->view('accounts/components/print_trial_balance', $bodyData);
            }
            else if(isset($_GET['export']))
            {
                $this->load->view('accounts/components/export/export_trial_balance', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/trial_balance', $bodyData);
                $this->load->view('components/footer');
            }

        }else{
            $this->load->view('admin/login');
        }
    }

    public function income_statement($agent, $agent_id)
    {
        //die();
        if($this->login == true){

            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();
            $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:$accounting_year['from'];
            $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:$accounting_year['to'];
            $accounting_year['from'] = $accounting_year_from;
            $accounting_year['to'] = $accounting_year_to;
            ///////////////////////////////////////////////////////////////////

            //setting keys for searchings
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['expense_type'] = (isset($_GET['expense_type']))?$_GET['expense_type']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year_from;
            $keys['accounting_year_to'] = $accounting_year_to;

            //calculating accounting year for customer balance
            $accounting_year_for_customer_balance = array();
            if($keys['custom_from'] != '' && $keys['custom_to'] != ''){
                $accounting_year_for_customer_balance['from'] = $keys['custom_from'];
                $accounting_year_for_customer_balance['to'] = $keys['custom_to'];
            }else{
                $accounting_year_for_customer_balance['from'] = $keys['accounting_year_from'];
                $accounting_year_for_customer_balance['to'] = $keys['accounting_year_to'];
            }

            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
            );

            $income_statement = $this->accounts_model->income_statement($agent, $agent_id, $keys);
            $bodyData['revenues'] = $income_statement['revenues'];
            $bodyData['expenses'] = $income_statement['expenses'];


            //fetching the accounts related data of the account holder
            switch($agent){
                case "customers":
                    $bodyData['customer_balance'] = $this->accounts_model->customer_balance($agent_id, $accounting_year_for_customer_balance);
                    break;
                case "contractors":
                    $bodyData['contractor_accounts'] = $this->accounts_model->contractor_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "companies":
                    $bodyData['company_accounts'] = $this->accounts_model->company_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "other_agents":
                    $bodyData['other_agents_accounts'] = $this->accounts_model->other_agent_accounts_for_trial_balance($agent_id, $keys);
                    break;
            }

            $bodyData['group_bys'] = $this->accounts_model->fetch_trial_balance_settings();

            //name of account holder
            $this->db->select('name');
            $table_name = ($agent == "contractors")?"carriage_contractors":$agent;
            $table_name = ($agent == "users")?"admin":$agent;
            $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;
            $bodyData['account_holder_type'] = $agent;

            //fething the required acount holders list
            switch($agent)
            {
                case "contractors":
                    $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                    break;
                case "customers":
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
                case "companies":
                    $bodyData['account_holders'] = $this->companies_model->companies();
                    break;
                case "other_agents":
                    $bodyData['account_holders'] = $this->other_agents_model->other_agents();
                    break;
                case "users":
                    $bodyData['account_holders'] = $this->admin_model->admins();
                    break;
                default:
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
            }
            //////////////////////////////////////////////

            if(isset($_GET['print'])){
                $this->load->view('accounts/components/print_income_statement', $bodyData);
            }
            else if(isset($_GET['export']))
            {
                $this->load->view('accounts/components/export/export_income_statement', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/income_statement', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->load->view('admin/login');
        }
    }

    public function balance_sheet($agent, $agent_id)
    {
        //die();
        if($this->login == true){
            //calculating accounting year
            $accounting_year = $this->accounts_model->accounting_year();
            $accounting_year_from = (isset($_GET['accounting_year_from']))?$_GET['accounting_year_from']:$accounting_year['from'];
            $accounting_year_to = (isset($_GET['accounting_year_to']))?$_GET['accounting_year_to']:$accounting_year['to'];
            $accounting_year['from'] = $accounting_year_from;
            $accounting_year['to'] = $accounting_year_to;
            ///////////////////////////////////////////////////////////////////

            //setting keys for searchings
            $keys['voucher_id'] = (isset($_GET['voucher_id']))?$_GET['voucher_id']:'';
            $keys['voucher_type'] = (isset($_GET['voucher_type']))?$_GET['voucher_type']:'';
            $keys['custom_from'] = (isset($_GET['custom_from']))?$_GET['custom_from']:'';
            $keys['custom_to'] = (isset($_GET['custom_to']))?$_GET['custom_to']:'';
            $keys['title'] = (isset($_GET['title']))?$_GET['title']:'';
            $keys['expense_type'] = (isset($_GET['expense_type']))?$_GET['expense_type']:'';
            $keys['ac_type'] = (isset($_GET['ac_type']))?$_GET['ac_type']:'';
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:'';
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:'';
            $keys['voucher_detail'] = (isset($_GET['voucher_detail']))?$_GET['voucher_detail']:'';
            $keys['summery'] = (isset($_GET['summery']))?$_GET['summery']:'';
            $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
            $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
            $keys['trip_detail_id'] = (isset($_GET['trip_detail_id']))?$_GET['trip_detail_id']:'';
            $keys['accounting_year_from'] = $accounting_year_from;
            $keys['accounting_year_to'] = $accounting_year_to;

            //calculating accounting year for customer balance
            $accounting_year_for_customer_balance = array();
            if($keys['custom_from'] != '' && $keys['custom_to'] != ''){
                $accounting_year_for_customer_balance['from'] = $keys['custom_from'];
                $accounting_year_for_customer_balance['to'] = $keys['custom_to'];
            }else{
                $accounting_year_for_customer_balance['from'] = $keys['accounting_year_from'];
                $accounting_year_for_customer_balance['to'] = $keys['accounting_year_to'];
            }

            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'agent'=>$agent,
                'agent_id'=> $agent_id,
                'accounting_year' => $accounting_year,
            );

            //calculating net profit
            $net_profit = $this->accounts_model->net_profit($agent, $agent_id, $keys);
            /////////////////////////////////////////////////////////////////

            $bodyData['net_profit'] = $net_profit;
            $bodyData['balance_sheet'] = $this->accounts_model->balance_sheet($agent, $agent_id, $keys);

            //fetching the accounts related data of the account holder
            switch($agent){
                case "customers":
                    $bodyData['customer_balance'] = $this->accounts_model->customer_balance($agent_id, $accounting_year_for_customer_balance);
                    break;
                case "contractors":
                    $bodyData['contractor_accounts'] = $this->accounts_model->contractor_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "companies":
                    $bodyData['company_accounts'] = $this->accounts_model->company_accounts_for_trial_balance($agent_id, $keys);
                    break;
                case "other_agents":
                    $bodyData['other_agents_accounts'] = $this->accounts_model->other_agent_accounts_for_trial_balance($agent_id, $keys);
                    break;
            }

            $bodyData['group_bys'] = $this->accounts_model->fetch_trial_balance_settings();

            //name of account holder
            $this->db->select('name');
            $table_name = ($agent == "contractors")?"carriage_contractors":$agent;
            $table_name = ($agent == "users")?"admin":$agent;
            $account_holder = $this->db->get_where($table_name, array('id'=>$agent_id))->result();
            if(sizeof($account_holder) >= 1){
                $account_holder = $account_holder[0]->name;
            }else {$account_holder = '';}
            $bodyData['account_holder'] = $account_holder;
            $bodyData['account_holder_type'] = $agent;

            //fething the required acount holders list
            switch($agent)
            {
                case "contractors":
                    $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                    break;
                case "customers":
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
                case "companies":
                    $bodyData['account_holders'] = $this->companies_model->companies();
                    break;
                case "other_agents":
                    $bodyData['account_holders'] = $this->other_agents_model->other_agents();
                    break;
                case "users":
                    $bodyData['account_holders'] = $this->admin_model->admins();
                    break;
                default:
                    $bodyData['account_holders'] = $this->customers_model->customers();
                    break;
            }
            //////////////////////////////////////////////

            if(isset($_GET['print'])){
                $this->load->view('accounts/components/print_balance_sheet', $bodyData);
            }
            else if(isset($_GET['export']))
            {
                $this->load->view('accounts/components/export/export_balance_sheet', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('accounts/books/balance_sheet', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->load->view('admin/login');
        }
    }

    public function tankers_ledger()
    {
        /* Fetching default tankers */
        $tankers = $this->tankers_model->tankers();
        $selected_tanker_ids = (isset($_GET['tanker_id']))?$_GET['tanker_id']:'';
        $default_from = Carbon::now()->startOfMonth();
        $default_from = $default_from->toDateString();
        $selected_from = (isset($_GET['from']))?$_GET['from']:$default_from;
        $default_to = Carbon::now()->toDateString();
        $selected_to = (isset($_GET['to']))?$_GET['to']:$default_to;

        $keys['tanker_id'] = $selected_tanker_ids;
        $keys['from'] = $selected_from;
        $keys['to'] = $selected_to;

        $headerData = array(
            'title' => 'Virik Logistics | Accounts',
            'page' => 'accounts',
        );
        $bodyData = array(
            'agent'=>"users",
            'agent_id'=> "1",
            'tankers'=>$tankers,
            'selected_tanker_ids'=>$selected_tanker_ids,
            'selected_tankers'=>$this->tankers_model->tankers_by_ids($selected_tanker_ids),
            'selected_from'=>$selected_from,
            'selected_to'=>$selected_to,
            'font_size'=>$this->settings_model->system_settings('printing font size'),
        );
        $bodyData['tanker_ledgers'] = $this->accounts_model->tankers_ledger($keys);
        $bodyData['opening_balance'] = $this->accounts_model->opening_balance_for_tankers_ledger($keys);
        if(isset($_GET['print'])){
            if(isset($_POST['check'])){
                $bodyData['tanker_ledgers'] = $this->helper_model->filter_records($bodyData['tanker_ledgers'], $_POST['check'],"voucher_id");
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('accounts/components/print_tankers_ledger', $bodyData);
        }
        else if(isset($_GET['export']))
        {
            if(isset($_POST['check'])){
                $bodyData['tanker_ledgers'] = $this->helper_model->filter_records($bodyData['tanker_ledgers'], $_POST['check'],"voucher_id");
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('accounts/components/export/export_tankers_ledger', $bodyData);
        }
        else{
            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/books/tankers_ledger', $bodyData);
            $this->load->view('components/footer');
        }
    }


    public function tankers_income_statement()
    {
        /* Fetching default tankers */
        $tankers = $this->tankers_model->tankers();
        $selected_tanker_ids = (isset($_GET['tanker_id']))?$_GET['tanker_id']:'';
        $customers = $this->customers_model->customers();
        $selected_customer_id = (isset($_GET['customer']))?$_GET['customer']:'';
        $default_from = Carbon::now()->startOfMonth();
        $default_from = "2014-01-01";
        $selected_from = (isset($_GET['from']))?$_GET['from']:$default_from;
        $default_to = Carbon::now()->toDateString();
        $selected_to = (isset($_GET['to']))?$_GET['to']:$default_to;

        $keys['tanker_id'] = $selected_tanker_ids;
        $keys['customer_id'] = $selected_customer_id;
        $keys['from'] = $selected_from;
        $keys['to'] = $selected_to;

        //defining the sorting column
        $sort = array(
            'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'voucher_journal.tanker_id',
            'order' => (isset($_GET['order']))?$_GET['order']:'asc',
        );
        $keys['sort'] = $sort;
        ///////////////////////////////////////////////////////////////


        $headerData = array(
            'title' => 'Virik Logistics | Accounts',
            'page' => 'accounts',
        );
        $bodyData = array(
            'agent'=>"users",
            'agent_id'=> "1",
            'tankers'=>$tankers,
            'customers'=>$customers,
            'selected_tanker_ids'=>$selected_tanker_ids,
            'selected_customer_id'=>$selected_customer_id,
            'selected_tankers'=>$this->tankers_model->tankers_by_ids($selected_tanker_ids),
            'selected_customer'=>$this->customers_model->customer($selected_customer_id),
            'selected_from'=>$selected_from,
            'selected_to'=>$selected_to,
            'font_size'=>$this->settings_model->system_settings('printing font size'),
        );
        $bodyData['tanker_income_report'] = $this->accounts_model->tankers_income_statement($keys);
        if(isset($_GET['print'])){
            if(isset($_POST['check'])){
                $tanker_income_report =  $bodyData['tanker_income_report'];
                $income_statement = $tanker_income_report['income_statement'];
                $tankers_routes = $tanker_income_report['tankers_routes'];
                $income_statement = $this->helper_model->filter_records($income_statement, $_POST['check'],"tanker_id");
                $bodyData['tanker_income_report'] = array(
                    'income_statement'=>$income_statement,
                    'tankers_routes'=>$tankers_routes,
                );
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('accounts/components/print_tankers_income_statement', $bodyData);
        }
        else if(isset($_GET['export']))
        {
            if(isset($_POST['check'])){
                $tanker_income_report =  $bodyData['tanker_income_report'];
                $income_statement = $tanker_income_report['income_statement'];
                $tankers_routes = $tanker_income_report['tankers_routes'];
                $income_statement = $this->helper_model->filter_records($income_statement, $_POST['check'],"tanker_id");
                $bodyData['tanker_income_report'] = array(
                    'income_statement'=>$income_statement,
                    'tankers_routes'=>$tankers_routes,
                );
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('accounts/components/export_tankers_income_statement', $bodyData);
        }
        else
        {
            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/books/tankers_income_statement', $bodyData);
            $this->load->view('components/footer');
        }
    }

    public function test_ajax($trip_id = '')
    {
        $bodyData['trip_id']= $trip_id;

        $bodyData['form_id'] = ($this->helper_model->last_id('voucher_journal')+1);

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $this->load->view('accounts/components/vouchers/universal_voucher', $bodyData);
    }
    public function universal_voucher($trip_id = '')
    {
        $account_holder_type = (isset($_GET['account_holder_type']))?$_GET['account_holder_type']:'customers';
        $account_holder_id = (isset($_GET['account_holder_id']))?$_GET['account_holder_id']:'0';
        $bodyData['account_holder_id'] = $account_holder_id;

        $bodyData['tankers'] = $this->tankers_model->tankers();
        $bodyData['trip_id']= $trip_id;

        $bodyData['form_id'] = ($this->helper_model->last_id('voucher_journal')+1);

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        switch($account_holder_type){
            case "contractors":
                $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                $this->load->view('accounts/components/vouchers/universal_voucher_contractor', $bodyData);
                break;
            default:
                $this->load->view('accounts/components/vouchers/universal_voucher', $bodyData);
                break;
        }
    }

    public function edit_universal_voucher($voucher_id, $account_holder_type='')
    {
        $bodyData['voucher']= $this->accounts_model->voucher($voucher_id);

        $bodyData['tankers'] = $this->tankers_model->tankers();

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();
        $bodyData['date_limits'] = $this->helper_model->dates_limit();

        switch($account_holder_type){
            case "contractors":
                $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
                $this->load->view('accounts/components/vouchers/universal_voucher_contractor_edit', $bodyData);
                break;
            case "users":
                //$this->load->view('accounts/components/vouchers/edit_user_voucher', $bodyData);
                $this->load->view('accounts/components/vouchers/journal_voucher_edit', $bodyData);
                break;
            default:
                $this->load->view('accounts/components/vouchers/universal_voucher_edit', $bodyData);
                break;
        }
    }

    public function tanker_expense_voucher($trip_id, $expense_date, $other_info, $expense_title, $agent_type, $agent_id, $amount)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");

        /*fetching customer id*/
        if($trip_id != 'none'){
            $this->db->select('customer_id, tanker_id');
            $result = $this->db->get_where('trips',array('trips.id'=>$trip_id))->result();
            $customer_id = $result[0]->customer_id;
            $tanker_id = $result[0]->tanker_id;
        }else{
            if(isset($_GET['tanker_id']))
            {
                $tanker_id = $_GET['tanker_id'];

                $this->db->select('customerId');
                $result = $this->db->get_where('tankers',array(
                    'id'=>$_GET['tanker_id'],
                ))->result();
                $customer_id = $result[0]->customerId;
            }
            else
            {
                $customer_id = 1;
                $tanker_id = '';
            }
        }

        $voucher = new Universal_Voucher();

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($expense_date == 'none')?'':$expense_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();
        $title_1 = ($expense_title == 'none')?'':$expense_title;
        $amount_1 = ($amount == 'none')?'':$amount;
        //set all properties here and then
        $voucher_entry->setAc_type('expense');
        $voucher_entry->setAccount_title_id($title_1);
        $voucher_entry->setRelated_agent('customers');
        $voucher_entry->setRelated_agent_id($customer_id);
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();

        $this->db->select('id');
        $result = $this->db->get_where('account_titles',array('title'=>'credit a/c'))->result();
        $title_id = $result[0]->id;

        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $amount_2 = ($amount == 'none')?'':$amount;
        //set all properties here and then
        //$ac_type = ($agent_type == 'none')?'bank':$ac_type;
        $related_agent = ($agent_type == 'none')?'other_agents':$agent_type;
        //$voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($title_id);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////

        $bodyData['tankers'] = $this->tankers_model->tankers();

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();
        $bodyData['date_limits'] = $this->helper_model->dates_limit();
        $bodyData['form_id'] = ($this->helper_model->last_id('voucher_journal')+1);

        $this->load->view('accounts/components/vouchers/tanker_expense_voucher_new', $bodyData);
    }

    public function shortage_expense_voucher($trip_id, $trip_detail_id, $shortage, $shortage_at, $price_unit, $product, $agent_type, $agent_id, $shortage_date, $other_info, $destination_voucher)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher = new Universal_Voucher();

        /*fetching customer id*/
        $this->db->select('customer_id');
        $result = $this->db->get_where('trips',array('trips.id'=>$trip_id))->result();
        $customer_id = $result[0]->customer_id;
        /***********************************************/
        /*fetching tanker id*/
        $this->db->select('tanker_id');
        $result = $this->db->get_where('trips',array('trips.id'=>$trip_id))->result();
        $tanker_id = $result[0]->tanker_id;
        /***********************************************/

        /* fetching product type */
        $this->db->select('products.type');
        $this->db->from('trips_details');
        $this->db->join('products', 'products.id = trips_details.product','left');
        $this->db->where('trips_details.id',$trip_detail_id);
        $result = $this->db->get()->result();
        $product_type = $result[0]->type;
        /***************************/

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($shortage_date == 'none')?'':$shortage_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();

        $description = " Shortage_quantity => ".$shortage." Price/Unit => ".$price_unit." Product =>".$product."";

        $this->db->select('id');
        if($shortage_at == 'destination'){
            $result = $this->db->get_where('account_titles', array('title'=>'destination shortage'))->result();
        }else{
            $result = $this->db->get_where('account_titles', array('title'=>'decanding shortage'))->result();
        }
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        $amount_1 = ($shortage * $price_unit);
        //set all properties here and then
        $voucher_entry->setAc_type('expense');
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent('customers');
        $voucher_entry->setRelated_agent_id($customer_id);
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();
        $this->db->select('id');
        $result = $this->db->get_where('account_titles', array('title'=>'credit a/c'))->result();
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        //set all properties here and then
        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $related_agent = ($agent_type == 'none')?'self':$agent_type;
        $amount_2 = ($shortage * $price_unit);
        $ac_type = 'liability';
        $voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////
        $bodyData['trip_detail_id'] = $trip_detail_id;

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $bodyData['destination_voucher'] = $destination_voucher;

        $bodyData['shortage_type'] = ($shortage_at == 'destination')?'1':'2';

        $bodyData['tankers'] = $this->tankers_model->tankers();
        $bodyData['date_limits'] = $this->helper_model->dates_limit();
        $bodyData['product_type'] = $product_type;
        $bodyData['product_name'] = $product;
        $bodyData['price_unit'] = $price_unit;
        $bodyData['shortage_quantity'] = $shortage;
        $bodyData['shortage_rate'] = 0;
        $bodyData['form_id'] = ($this->helper_model->last_id('voucher_journal')+1);

        $this->load->view('accounts/components/vouchers/shortage_expense_voucher', $bodyData);
    }

    public function shortage_expense_voucher_for_black_oil($trip_id, $trip_detail_id, $shortage, $shortage_at, $price_unit, $product, $shortage_rate, $freight_unit, $agent_type, $agent_id, $shortage_date, $other_info, $destination_voucher)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher = new Universal_Voucher();

        /*fetching customer id*/
        $this->db->select('customer_id');
        $result = $this->db->get_where('trips',array('trips.id'=>$trip_id))->result();
        $customer_id = $result[0]->customer_id;
        /***********************************************/
        /*fetching tanker id*/
        $this->db->select('tanker_id');
        $result = $this->db->get_where('trips',array('trips.id'=>$trip_id))->result();
        $tanker_id = $result[0]->tanker_id;
        /***********************************************/

        /* fetching product type */
        $this->db->select('products.type');
        $this->db->from('trips_details');
        $this->db->join('products', 'products.id = trips_details.product','left');
        $this->db->where('trips_details.id',$trip_detail_id);
        $result = $this->db->get()->result();
        $product_type = $result[0]->type;
        /***************************/

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($shortage_date == 'none')?'':$shortage_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();

        $description = " Shortage_quantity => ".$shortage." Price/Unit => ".$price_unit." Product =>".str_replace('%20',' ',$product)." Shortage Rate => ".$shortage_rate;

        $this->db->select('id');
        if($shortage_at == 'destination'){
            $result = $this->db->get_where('account_titles', array('title'=>'destination shortage'))->result();
        }else{
            $result = $this->db->get_where('account_titles', array('title'=>'decanding shortage'))->result();
        }
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        $amount_1 = ($shortage * $shortage_rate);
        //set all properties here and then
        $voucher_entry->setAc_type('expense');
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent('customers');
        $voucher_entry->setRelated_agent_id($customer_id);
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();
        $this->db->select('id');
        $result = $this->db->get_where('account_titles', array('title'=>'credit a/c'))->result();
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        //set all properties here and then
        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $related_agent = ($agent_type == 'none')?'self':$agent_type;
        $amount_2 = ($shortage * $shortage_rate);
        $ac_type = 'liability';
        $voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////
        $bodyData['trip_detail_id'] = $trip_detail_id;

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $bodyData['destination_voucher'] = $destination_voucher;

        $bodyData['shortage_type'] = ($shortage_at == 'destination')?'1':'2';

        $bodyData['tankers'] = $this->tankers_model->tankers();
        $bodyData['date_limits'] = $this->helper_model->dates_limit();
        $bodyData['product_type'] = $product_type;
        $bodyData['product_name'] = $product;
        $bodyData['price_unit'] = $price_unit;
        $bodyData['shortage_quantity'] = $shortage;
        $bodyData['shortage_rate'] = $shortage_rate;
        $bodyData['form_id'] = ($this->helper_model->last_id('voucher_journal')+1);

        $this->load->view('accounts/components/vouchers/shortage_expense_voucher', $bodyData);
    }

    public function contractor_credit_voucher($trip_id, $trip_detail_id, $total_creditable_amount, $credit_amount, $agent_type, $agent_id, $voucher_date, $other_info, $voucher_type)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher = new Universal_Voucher();

        //fetching the tanker id
        $this->db->select('tanker_id, contractor_id');
        $result = $this->db->get_where('trips',array('id'=>$trip_id))->result();
        if($result != null){
            $tanker_id = $result[0]->tanker_id;
            $contractor_id = $result[0]->contractor_id;
        }else{
            $tanker_id = 0;
            $contractor_id = 0;
        }

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($voucher_date == 'none')?'':$voucher_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        $voucher->person_id = $contractor_id;
        $voucher->trip_detail_id = $trip_detail_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();

        $description = "";

        $this->db->select('id');
        if($voucher_type == '1'){
            $result = $this->db->get_where('account_titles', array('title'=>'freight commission'))->result();
        }else{
            $result = $this->db->get_where('account_titles', array('title'=>'service charges'))->result();
        }
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        $amount_1 = ($credit_amount);
        //set all properties here and then
        $voucher_entry->setAc_type('dividend');
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent('self');
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();
        //set all properties here and then
        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $related_agent = ($agent_type == 'none')?'self':$agent_type;
        $amount_2 = ($credit_amount);
        $ac_type = 'payable';
        $voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////
        $bodyData['trip_detail_id'] = $trip_detail_id;

        $bodyData['total_creditable_amount'] = $total_creditable_amount;

        $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
        $bodyData['tankers'] = $this->tankers_model->tankers();

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $this->load->view('accounts/components/vouchers/contractor_credit_voucher', $bodyData);
    }
    public function company_credit_voucher($trip_id, $trip_detail_id, $total_creditable_amount, $credit_amount, $agent_type, $agent_id, $voucher_date, $other_info, $voucher_type)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher = new Universal_Voucher();

        //fetching the tanker id
        $this->db->select('tanker_id, contractor_id');
        $result = $this->db->get_where('trips',array('id'=>$trip_id))->result();
        if($result != null){
            $tanker_id = $result[0]->tanker_id;
            $contractor_id = $result[0]->contractor_id;
        }else{
            $tanker_id = 0;
            $contractor_id = 0;
        }

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($voucher_date == 'none')?'':$voucher_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        $voucher->person_id = $contractor_id;
        $voucher->trip_detail_id = $trip_detail_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();

        $description = "";

        $this->db->select('id');
        if($voucher_type == '1'){
            $result = $this->db->get_where('account_titles', array('title'=>'freight commission'))->result();
        }else{
            $result = $this->db->get_where('account_titles', array('title'=>'service charges'))->result();
        }
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;
        $amount_1 = ($credit_amount);
        //set all properties here and then
        $voucher_entry->setAc_type('dividend');
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent('self');
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();
        //set all properties here and then
        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $related_agent = ($agent_type == 'none')?'self':$agent_type;
        $amount_2 = ($credit_amount);
        $ac_type = 'payable';
        $voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////
        $bodyData['trip_detail_id'] = $trip_detail_id;

        $bodyData['total_creditable_amount'] = $total_creditable_amount;

        $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
        $bodyData['tankers'] = $this->tankers_model->tankers();

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $this->load->view('accounts/components/vouchers/contractor_credit_voucher', $bodyData);
    }


    public function customer_credit_voucher($trip_id, $trip_detail_id, $total_creditable_amount, $credit_amount, $agent_type, $agent_id, $voucher_date, $other_info)
    {
        include_once(APPPATH."models/helperClasses/Universal_Voucher.php");
        include_once(APPPATH."models/helperClasses/Voucher_Entry.php");
        $voucher = new Universal_Voucher();

        //fetching the tanker id
        $this->db->select('tanker_id, customer_id');
        $result = $this->db->get_where('trips',array('id'=>$trip_id))->result();
        if($result != null){
            $tanker_id = $result[0]->tanker_id;
            $customer_id = $result[0]->customer_id;
        }else{
            $tanker_id = 0;
            $customer_id = 0;
        }

        //setting voucher data
        $voucher->trip_id = ($trip_id == 'none')?'':$trip_id;
        $voucher->voucher_date = ($voucher_date == 'none')?'':$voucher_date;
        $voucher->voucher_details = ($other_info == 'none')?'':str_replace("%20"," ",$other_info);
        $voucher->tanker_id = $tanker_id;
        $voucher->person_id = $customer_id;
        $voucher->trip_detail_id = $trip_detail_id;
        /////Setting the entries.....

        $voucher_entry = new Voucher_Entry();

        $description = "";

        $this->db->select('id');
        $result = $this->db->get_where('account_titles', array('title'=>'freight'))->result();
        $account_title_id = (sizeof($result) >=1)?$result[0]->id:0;

        $amount_1 = ($credit_amount);
        //set all properties here and then
        $voucher_entry->setAc_type('dividend');
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent('self');
        $voucher_entry->setDebit($amount_1);
        $voucher_entry->setDr_cr('debit');
        array_push($voucher->entries, $voucher_entry);

        $voucher_entry = new Voucher_Entry();
        //set all properties here and then
        $agent_id_2 = ($agent_id == 'none')?'':$agent_id;
        $related_agent = ($agent_type == 'none')?'self':$agent_type;
        $amount_2 = ($credit_amount);
        $ac_type = 'payable';
        $voucher_entry->setAc_type($ac_type);
        $voucher_entry->setAccount_title_id($account_title_id);
        $voucher_entry->setDescription($description);
        $voucher_entry->setRelated_agent($related_agent);
        $voucher_entry->setRelated_agent_id($agent_id_2);
        $voucher_entry->setCredit($amount_2);
        $voucher_entry->setDr_cr('credit');
        array_push($voucher->entries, $voucher_entry);
        ////////////////////////////////////////

        $bodyData['voucher'] = $voucher;

        //finding the max trip id
        $this->db->select_max('id');
        $result = $this->db->get('trips')->result();
        $bodyData['max_trip_id'] = $result[0]->id;
        ////////////////////////////////////////
        $bodyData['trip_detail_id'] = $trip_detail_id;

        $bodyData['total_creditable_amount'] = $total_creditable_amount;

        $bodyData['account_holders'] = $this->carriageContractors_model->carriageContractors();
        $bodyData['tankers'] = $this->tankers_model->tankers();

        $bodyData['other_agents'] = $this->otherAgents_model->otherAgents();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $this->load->view('accounts/components/vouchers/customer_credit_voucher', $bodyData);
    }

    public function fetch_agents($agent, $row_num='', $class="form-control agent_select", $data_id = 'agent_id', $function_to_be_called_on_change='', $default_agent = '')
    {


        $agents = ''; $selected_agent_id = 0;
        if($agent == 'other_agents'){
            $agents = $this->otherAgents_model->otherAgents();
        }
        if($agent == 'customers'){
            $agents = $this->customers_model->customers();
        }
        if($agent == 'carriage_contractors'){
            $agents = $this->carriageContractors_model->carriageContractors();
            $selected_agent_id = 1;
        }
        if($agent == 'companies'){
            $agents = $this->companies_model->companies();
            $selected_agent_id = 1;
        }

        if($row_num != '' && $row_num != 'none'){
            $row_num = "_"."$row_num";
        }else{
            $row_num = '';
        }

        /*$row_num = ($row_num != '')?"_$row_num":'';*/
        echo "<select name="."agent_id".$row_num." id=$data_id class='".$class."' onchange=$function_to_be_called_on_change>";
        foreach($agents as $agent){
            $selected = '';
            if($agent->id == $default_agent)
            {
                $selected = 'selected';
            }
            else if($selected_agent_id == $agent->id)
            {
                $selected = 'selected';
            }
            echo "<option value=$agent->id ".$selected.">";
            echo $agent->name;
            echo "</option>";
        }
        echo "</select>";
    }
    public function fetch_agents_for_shortage_chit($agent, $product_type )
    {
        $agents = '';
        if($agent == 'other_agents'){
            $agents = $this->otherAgents_model->otherAgents();
        }
        if($agent == 'customers'){
            $agents = $this->customers_model->customers();
        }
        if($agent == 'carriage_contractors'){
            $agents = $this->carriageContractors_model->carriageContractors();
        }
        if($agent == 'companies'){
            $agents = $this->companies_model->companies();
        }

        /*$row_num = ($row_num != '')?"_$row_num":'';*/
        echo "<select name='agent_id' id='".$product_type."agent_id' class='agent_select select_box'>";
        foreach($agents as $agent){
            echo "<option value='".$agent->id."' >";
            echo $agent->name;
            echo "</option>";
        }
        echo "</select>";
    }
    public function fetch_tankers($trip_id, $customer_id, $requested_tanker='')
    {
        $tankers = array();
        if($trip_id != 'null'){
            $this->db->select('tankers.truck_number as tanker_number, tankers.id as tanker_id');
            $this->db->from('trips');
            $this->db->where('trips.id', $trip_id);
            $this->db->join('tankers','tankers.id = trips.tanker_id');
            $trips = $this->db->get()->result();
            foreach($trips as $trip)
            {
                array_unshift($tankers, $trip);
            }
        }else{
            $this->db->select('tankers.truck_number as tanker_number, tankers.id as tanker_id');
            $this->db->from('tankers');
            $this->db->where('customerId',$customer_id);
            $result = $this->db->get()->result();
            foreach($result as $t)
            {
                array_unshift($tankers, $t);
            }
        }
        echo "<select name='tankers' class='tankers_select'>";
        foreach($tankers as $tanker)
        {
            $selected = ($requested_tanker == $tanker->tanker_id)?"selected":'';
            echo "<option $selected value=$tanker->tanker_id>";
            echo $tanker->tanker_number;
            echo "</option>";
        }
        echo "<option value=''>none</option>";
        echo "</select>";
    }

    public function fetch_customers($trip_id, $requested_customer)
    {
        //echo $trip_id;
        $customers = array();
        if($trip_id != 'null'){
            $this->db->select('customers.id as customer_id, customers.name');
            $this->db->from('trips');
            $this->db->join('customers','customers.id = trips.customer_id');
            $this->db->where('trips.id', $trip_id);
            $trips = $this->db->get()->result();
            foreach($trips as $trip)
            {
                array_unshift($customers, $trip);
            }
        }else{
            $this->db->select('customers.id as customer_id, customers.name');
            $result = $this->db->get('customers')->result();
            foreach($result as $r)
            {
                array_unshift($customers, $r);
            }
        }

        echo "<select name='account_holders' id='customers' onchange='i_think_customer_changed()'>";
        foreach($customers as $customer)
        {
            $selected = ($requested_customer == $customer->customer_id)?"selected":'';

            echo "<option value=$customer->customer_id $selected>";
            echo $customer->name;
            echo "</option>";
        }
        echo "</select>";
    }

    public function fetch_balance($agent_type , $agent_id, $title_id, $voucher_number='')
    {
        $balance = $this->accounts_model->opening_balance_for_voucher($agent_type, $agent_id, $title_id, $voucher_number);
        echo $balance;
    }

    public function fetch_title_type($title_id)
    {
        $this->db->select('type');
        $this->db->where('id',$title_id);
        $result = $this->db->get('account_titles')->result();
        if($result != null){
            echo $result[0]->type;
        }else{
            echo "";
        }
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

    function _check_re_submission($form_id, $table){
        if($this->helper_model->re_submission($table, $form_id) == true){
            $this->form_validation->set_message('_check_re_submission','Entry failed because of form re-submission.');
            return false;
        }
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */