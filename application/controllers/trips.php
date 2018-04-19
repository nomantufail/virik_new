<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



include_once(APPPATH."controllers/parentController.php");
include_once(APPPATH.'repositories/TripsRepository.php');
class Trips extends ParentController {

    private $tripsRepository = null;
    public function __construct()
    {
        parent::__construct();
        $this->tripsRepository = new TripsRepository();
    }

    public function index($month ='' )
    {
        redirect(base_url()."trips/show/primary");

    }
    public function show($trip_master_type='primary')
    {
$this->output->set_header("HTTP/1.0 200 OK");
$this->output->set_header("HTTP/1.1 200 OK");
$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
$this->output->set_header("Pragma: no-cache");
        $module = 'primary_trips';
        if($trip_master_type == 'primary')
            $module = 'primary_trips';
        if($trip_master_type == 'secondary')
            $module = 'secondary_trips';
        if($trip_master_type == 'secondary_local')
            $module = 'secondary_local_trips';

        $keys['module'] = $module;
        $keys['from'] = (isset($_GET['from']))?$_GET['from']:'';
        $keys['to'] = (isset($_GET['to']))?$_GET['to']:'';
        $keys['trip_type'] = (isset($_GET['trip_type']))?$_GET['trip_type']:'';
        $keys['trip_master_type'] = $trip_master_type;
        $keys['trip_master_types'] = (isset($_GET['trip_master_types']))?$_GET['trip_master_types']:'';
        $keys['trip_id'] = (isset($_GET['id']))?$_GET['id']:'';
        $keys['entryDate'] = (isset($_GET['entry_date']))?$_GET['entry_date']:'';
        $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
        $keys['contractor'] = (isset($_GET['contractor']))?$_GET['contractor']:'';
        $keys['customer'] = (isset($_GET['customer']))?$_GET['customer']:'';
        $keys['company'] = (isset($_GET['company']))?$_GET['company']:'';
        $keys['source'] = (isset($_GET['source']))?$_GET['source']:'';
        $keys['destination'] = (isset($_GET['destination']))?$_GET['destination']:'';
        $keys['trips_routes'] = (isset($_GET['trips_route']))?$_GET['trips_route']:'';
        $keys['product'] = (isset($_GET['product']))?$_GET['product']:'';
        $keys['stn_number'] = (isset($_GET['stn']))?$_GET['stn']:'';
        $keys['trip_status'] = (isset($_GET['trip_status'])?$_GET['trip_status']:'');
        ///////////////////////////////////////////////////////////////

        //defining the sorting column
        $sort = array(
            'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'trips.id',
            'order' => (isset($_GET['order']))?$_GET['order']:'desc',
        );
        ///////////////////////////////////////////////////////////////

        $total_rows = 1000/*$this->trips_model->count_searched_trips($keys)*/;
        $total_rows = ($total_rows == 0)?1:$total_rows;

        //********Calculating Records/Page***********//
        if(isset($_GET['pagination']) && $_GET['pagination'] == 'false'){
            $per_page = $total_rows;
        }else{
            $per_page = 'false';
        }
        if(isset($_GET['print'])){
            $per_page = $total_rows;
        }
        /////****************************************//
        /////////////////
        //computing the url for page number
        $query_string = explode('&page',$_SERVER['QUERY_STRING']);
        $query_string = $query_string[0];
        //////////////////////////////////
        $config = $this->helper_model->pagination_configs("trips/show/".$trip_master_type."/?".$query_string, "trips", $total_rows, $per_page);
        $this->pagination->initialize($config);

        $pageNumber = 0;
        if(isset($_GET['page'])){
            $pageNumber = $_GET['page'];
            if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
        }
        //////////////////////////////////////////////////////////////////////////////////

        $headerData = array(
            'title' => 'Virik-Logistics | Make Trip',
            'page' => $trip_master_type,
            'trip_master_type' => $trip_master_type,
            'trip_type'=>''
        );

        $bodyData = array(
            'trips' => '',
            'routes' => '',
            'drivers' => '',
            'pre_saved_sorting_columns'=>$this->helper_model->pre_saved_sorting_columns('primary_trips'),
            'pages' => $this->pagination->create_links(),
            'columns' => array(),
            'someMessage'=>'',
        );

        //deleting the customer*****************//
        if(isset($_GET['del'])){
            $_POST['del'] = $_GET['del'];
            $this->form_validation->set_rules('del', 'Delete Trip', 'required|numeric|callback__is_deletable_trip');
            if ($this->form_validation->run() == true)
            {
                if( $this->trips_model->safely_remove_trip($_GET['del']) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////

        //deleting the shortage voucher*****************//
        if(isset($_GET['del_dest_shortage_voucher'])){
            $_POST['del_dest_shortage_voucher'] = $_GET['del_dest_shortage_voucher'];
            $this->form_validation->set_rules('del_dest_shortage_voucher', 'Delete Shortage Expense', 'required|numeric');
            if ($this->form_validation->run() == true)
            {
                if( $this->trips_model->delete_dest_shortage_voucher($_GET['del_dest_shortage_voucher']) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        if(isset($_GET['del_decnd_shortage_voucher'])){
            $_POST['del_decnd_shortage_voucher'] = $_GET['del_decnd_shortage_voucher'];
            $this->form_validation->set_rules('del_decnd_shortage_voucher', 'Delete Shortage Expense', 'required|numeric');
            if ($this->form_validation->run() == true)
            {
                if( $this->trips_model->delete_decnd_shortage_voucher($_GET['del_decnd_shortage_voucher']) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////

        //saving the voucher
        if(isset($_POST['save_shortage_voucher'])){

            $this->form_validation->set_rules('form_id', 'Form Re-Submission', 'required|numeric|callback__check_re_submission[voucher_journal]');
            $this->form_validation->set_rules('trip_detail_id', 'Product Id', 'required|numeric|greater_than[0]|callback__check_trip_detail_id_for_shortage_voucher');
            $this->form_validation->set_rules('shortage_type', 'Shortage Type', 'required|numeric|greater_than[0]');
            if($this->form_validation->run() == true){
                if( $this->trips_model->save_shortage_voucher_completely() == true){
                    $bodyData['someMessage'] = array('message'=>'Voucher Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }////////////////////////////////////////////////////////

        //saving tanker expense voucher
        if(isset($_POST['save_tanker_expense_voucher'])){

            $this->form_validation->set_rules('form_id', 'Form Unique Id', 'required|callback__check_form_re_submission');
            if($this->form_validation->run() == true){
                if( $this->accounts_model->save_tanker_expense_voucher() == true){
                    $this->helper_model->redirect_with_success('Expense Saved Successfully');
                }else{
                    $this->helper_model->redirect_with_errors('Some unknown database fault happened. please try again. ');
                }
            }
            else{
                $this->helper_model->redirect_with_errors(validation_errors());
            }
        }////////////////////////////////////////////////////////

        //$trips = $this->tripsRepository->get($keys, $config["per_page"], $pageNumber, $sort);
//echo "<pre>"; print_r($this->trips_model->search_trips($keys, $config["per_page"], $pageNumber, $sort)); echo "</pre>"; die();
        $bodyData['trips'] = $this->trips_model->search_trips($keys, $config["per_page"], $pageNumber, $sort);
        $bodyData['routes'] = $this->routes_model->routes();
        $bodyData['drivers'] = $this->drivers_model->drivers();
        $bodyData['companies'] = $this->companies_model->companies();
        $bodyData['contractors'] = $this->carriageContractors_model->carriageContractors();
        $bodyData['products'] = $this->routes_model->products();
        $bodyData['cities'] = $this->routes_model->cities();
        $bodyData['trips_routes'] = $this->routes_model->trips_routes();
        $bodyData['customers'] = $this->customers_model->customers();
        $bodyData['tankers'] = $this->tankers_model->tankers();
        $bodyData['font_size'] = $this->settings_model->system_settings('printing font size');
        $bodyData['trip_master_type'] = $trip_master_type;

        if(isset($_GET['print'])){
            if(isset($_POST['check'])){
                $bodyData['trips'] = $this->helper_model->filter_records($bodyData['trips'], $_POST['check'],"trip_id");
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('trips/components/print_trips', $bodyData);
        }
        else if(isset($_GET['export']))
        {
            if(isset($_POST['check'])){
                $bodyData['trips'] = $this->helper_model->filter_records($bodyData['trips'], $_POST['check'],"trip_id");
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('trips/components/export/export_trips', $bodyData);
        }
        else{
            $this->load->view('components/header', $headerData);
            $this->load->view('trips/welcome', $bodyData);
            $this->load->view('components/footer');
        }


    }

    public function _where_search(){
        if(isset($_GET['trip_search'])){
            $search =   "route_id = ".$this->input->get('routes');
            return $search;
        }
        return "";
    }

    public function save_trip()
    {

        if(isset($_POST['save_new_trip'])){
            if($this->form_validation->run('save_new_trip') == true){ /*local/self*/
                if( $this->trips_model->save_new_trip_completely() == true){
                    redirect(base_url()."trips/show/primary");
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                    $this->make("simple",$bodyData['someMessage']);
                }
            }else{
                $this->make();
            }
        }else if(isset($_POST['save_local_company'])){
            if($this->form_validation->run('save_new_trip') == true){
                if( $this->trips_model->save_other_new_trip_completely('','local_cmp') == true){
                    redirect(base_url()."trips/show/secondary");
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                    $this->make('local_cmp', $bodyData['someMessage']);
                }
            }else{
                $this->make('local_cmp');
            }
        }else if(isset($_POST['save_local_self'])){
            if($this->form_validation->run('save_new_trip') == true){
                if( $this->trips_model->save_other_new_trip_completely('','local_self') == true){
                    redirect(base_url()."trips/show/primary");
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                    $this->make('local_self', $bodyData['someMessage']);
                }
            }else{
                $this->make('local_self');
            }
        }else if(isset($_POST['save_secondary_local_trip'])){
            if($this->form_validation->run('save_new_trip') == true){ /*local/self*/
                if( $this->trips_model->save_new_trip_completely() == true){
                    redirect(base_url()."trips/show/secondary_local");
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                    $this->make("simple",$bodyData['someMessage']);
                }
            }else{
                $this->make();
            }
        }
        else{
            redirect(base_url()."trips/show/primary");
        }
    }

    public function fetch_meter_reading($tanker_id){
        $meter_reading = $this->trips_model->meter_reading($tanker_id);
        echo json_encode($meter_reading);
    }

    public function fetch_tankers($customer_id, $width='300px', $area=''){

        $style = "width:".$width;

        if($area == 'reports'){
            $tankers = $this->tankers_model->tankers($customer_id);
        }else{
            $tankers = $this->tankers_model->tankers();
        }
        echo '<select class="form-control tankers_select" id="tankers" style="'.$style.'" name="tankers"  id="tankers" onchange="tanker_changed(this.form.tankers)">';

        if($area == 'reports' && sizeof($tankers)>=1){

            echo '<option value="all">';

            echo "--All--";

            echo '</option>';

            foreach($tankers as $tanker){

                    echo "<option value=$tanker->id>$tanker->truck_number</option>";

            }

        }else{

            foreach($tankers as $tanker){

                $disable = ($tanker->free == true)?"":"disabled";

                $style = ($tanker->free == true)?"":"style='color:red'";
                if($tanker->free == true && $tanker->customerId == $customer_id)
                {
                    $style = "style='color:white; background-color:green ;border-bottom:1px solid white;'";
                }
                $value = ($tanker->free == true)?$tanker->id:'';
                echo "<option $disable $style value=$value>$tanker->truck_number</option>";

            }

        }

        echo '</select>';

    }



    public function fetch_tanker($tanker_id = 0){

        $tanker = @$this->tankers_model->tanker($tanker_id);

        if($tanker){
            echo '<table class="table table-hover">';



            echo '<tbody>';

            echo '<tr>';

            echo "<td>Engin #</td>";

            echo "<td>".@$tanker->truck_number. "</td>";

            echo '</tr>';

            echo '<tr>';

            echo "<td>Chase #</td>";

            echo "<td>".@$tanker->chase_number."</td>";

            echo '</tr>';

            echo '<tr>';

            echo "<td>Fitness Certificate</td>";

            echo "<td>";

            echo (@$tanker->fitness_certificate == 1)? "Yes": "No";

            echo "</td>";

            echo '</tr>';

            echo '</tbody>';

            echo '</table>';
        }


    }

    public function make($trip_type = 'simple', $someMessage="")
    {
        /*
         * some variables that should be initialized befor
         */
        $bodyData['trip_type'] = $trip_type;
        /***************************************************/
        /***************************************************/
        $headerData = array(
            'title' => 'Virik-Logistics | Trips',
            'page' => 'trips'
        );
        $bodyData = array(
            'customers' => '',
            'contractors' => '',
            'companies' => '',
            'tankers' => '',
            'drivers' => '',
            'freight_liter' => '',
            'contractor_commission' => '',
            'company_commission' => '',
            'remaining_for_customer' => '',
            'selected_tanker'=>'',
            'someMessage'=>$someMessage,
            'form_id'=>'',
            'cities'=>$this->db->get('cities')->result(),
            'products'=>$this->db->get('products')->result(),
            'date_limits'=>$this->helper_model->dates_limit(),

        );

        //setting ids
        $customers = $this->customers_model->customers('asc');
        $companies = $this->companies_model->companies();
        $contractors = $this->carriageContractors_model->carriageContractors();
        $routes = $this->routes_model->routes();
        $company_id = null;
        $customer_id = null;
        $contractor_id = null;
        $freight_liter = null;
        if($customers != null){
            $customer_id = (isset($_GET['customer_id']))? $_GET['customer_id'] : $customers[0]->id;
        }
        if($companies != null){
            $company_id = (isset($_GET['company_id']))? $_GET['company_id'] : $companies[0]->id;
        }
        if($contractors != null){
            $contractor_id = (isset($_GET['contractor_id']))? $_GET['contractor_id'] : $contractors[0]->id;
        }
        if($routes != null){
            $freight_liter = $routes[0]->freight;
        }

        //fetching free tankers
        $tankers = array();
        $all_tankers = $this->tankers_model->tankers();
        $tankers = $all_tankers;

        //selecting a right tanker
        if(sizeof($tankers) >=1){
            $tanker_id = (isset($_GET['tanker_id']))? $_GET['tanker_id'] : $tankers[0]->id;
            $selected_tanker = $this->tankers_model->tanker($tanker_id);
            $selected_tanker = ($selected_tanker->free == true)? $selected_tanker: $this->tankers_model->tanker($tankers[0]->id);
        }else{$selected_tanker = '';}

        $drivers = $this->drivers_model->drivers();
        $contractor_commission = $this->manageCommissions_model->contractor_customer_commission($contractor_id, $customer_id);
        if($contractor_commission == null){ $contractor_commission = 0;}else{ $contractor_commission = $contractor_commission->freight_commission; }

        $company_commission = $this->manageCommissions_model->contractor_company_commission($contractor_id, $company_id);
        //if($company_commission == null){ $company_commission = 0;}else{ $company_commission = $company_commission->commission_1 + $company_commission->commission_2 + $company_commission->commission_3; }
        if($contractor_commission == 0){
            $remaining_for_customer = 100;
        }else{
            $remaining_for_customer = 100 - $contractor_commission;
        }

        $bodyData['customers'] = $customers;
        $bodyData['tankers'] = $tankers;
        $bodyData['contractors'] = $contractors;
        $bodyData['companies'] = $companies;
        $bodyData['routes'] = $routes;
        $bodyData['drivers'] = $drivers;
        $bodyData['freight_liter'] = $freight_liter;
        $bodyData['contractor_customer_commissions'] = $this->manageCommissions_model->contractor_customer_commissions_simple();
        $bodyData['contractor_company_commissions'] = $this->manageCommissions_model->contractor_company_commissions_simple();

        $bodyData['remaining_for_customer'] = $remaining_for_customer;
        $bodyData['selected_tanker'] = $selected_tanker;
        $bodyData['form_id'] = ($this->helper_model->last_id('trips')+1);


        switch($trip_type)
        {
            case "local":
                $bodyData['trip_type'] = "general";
                $bodyData['trip_master_type'] = "primary";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "primary";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_general_trip', $bodyData);
                break;
            case "local_cmp":
                $bodyData['secondary_routes'] = $this->routes_model->routes('all','3');
                $bodyData['trip_type'] = "local_company";
                $bodyData['trip_master_type'] = "secondary";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "secondary";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_other_trip', $bodyData);
                break;
            case "local_self":
                $bodyData['primary_routes'] = $this->routes_model->routes('all','1');
                $bodyData['trip_type'] = "local_self";
                $bodyData['trip_master_type'] = "primary";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "primary";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_other_trip', $bodyData);
                break;
            case "general_local":
                $bodyData['trip_type'] = "general_local";
                $bodyData['trip_master_type'] = "primary";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "primary";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_general_local_trip', $bodyData);
                break;
            case "secondary_local":
                $bodyData['trip_type'] = "secondary_local";
                $bodyData['trip_master_type'] = "secondary_local";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "secondary_local";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_secondary_local_trip', $bodyData);
                break;
            default:
                $bodyData['trip_type'] = "self_mail";
                $bodyData['trip_master_type'] = "primary";
                $bodyData['freights'] = $this->routes_model->all_routes_freights($bodyData['trip_master_type']);
                $headerData['trip_master_type'] = "primary";
                $this->load->view('components/header', $headerData);
                $this->load->view('trips/make_simple_trip', $bodyData);
                break;
        }

        $this->load->view('components/footer');
    }



    public function details($trip_id = '', $section = '')

    {

        if($this->helper_model->record_exists("trips",array('id'=>$trip_id)) == false){

            $this->index();

        }else{

            $headerData = array(

            'title' => 'Virik-Logistics | Trips Details',

            'page' => 'trips',

            );

            $bodyData = array(

                'trip' => $this->trips_model->trip_details($trip_id),

                'trip_id' => $trip_id,

                'section' => '',

                'someMessage' => '',

            );

            if($section == '' || $section == 'all'){$bodyData['section'] = 'all';}else if($section== 'add'){ $bodyData['section'] = 'add';}



            if(isset($_GET['print'])){

                $this->load->view('trips/components/print_trip_details', $bodyData);

            }
            else if(isset($_GET['export']))
            {
                $this->load->view('trips/components/export_trip_details', $bodyData);
            }
            else{

                $this->load->view('components/header', $headerData);

                $this->load->view('trips/details', $bodyData);

                $this->load->view('components/footer');

            }

        }
    }

    public function edit($trip_id = '', $section = '')
    {

        $headerData = array(
            'title' => 'Virik-Logistics | Edit Trip',
            'page' => 'trips'
        );
        $bodyData = array(
            'trip' => '',
            'customers' => '',
            'contractors' => '',
            'companies' => '',
            'tankers' => '',
            'cities' => '',
            'products' => '',
            'drivers' => '',
            'freight_liter' => '','contractor_customer_commissions' => $this->manageCommissions_model->contractor_customer_commissions_simple(),
            'contractor_company_commissions' => $this->manageCommissions_model->contractor_company_commissions_simple(),
            'remaining_for_customer' => '',
            'trip_id' => $trip_id,
            'someMessage'=>'',

        );

        //Saving the edited trip
        if(isset($_POST['edit_trip'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_existing_trip_completely($trip_id) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }

        //Saving the edited trip
        if(isset($_POST['save_general_trip'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_existing_trip_completely($trip_id) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //Saving the edited trip
        if(isset($_POST['save_general_local_trip'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_existing_trip_completely($trip_id) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //Saving the edited trip
        if(isset($_POST['save_secondary_local_trip'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_existing_trip_completely($trip_id) == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }

        //Saving the edited trip
        if(isset($_POST['save_local_company'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_other_existing_trip_completely($trip_id, 'local_cmp') == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }

        //Saving the edited trip
        if(isset($_POST['save_local_self'])){
            if($this->form_validation->run('save_existing_trip') == true){
                if( $this->trips_model->save_other_existing_trip_completely($trip_id, 'local_self') == true){
                    $bodyData['someMessage'] = array('message'=>'Trip Saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }

        $trip = $this->trips_model->trip($trip_id);
        if($trip == null){
            $this->index();
        }else{
            //populating the edited trip
            $bodyData['trip'] = $trip;
            $customers = $this->customers_model->customers('asc');
            $companies = $this->companies_model->companies();
            $tankers = $this->tankers_model->tankers();
            $contractors = $this->carriageContractors_model->carriageContractors();
            $routes = $this->routes_model->routes();
            $drivers = $this->drivers_model->drivers();

            $bodyData['customers'] = $customers;
            $bodyData['tankers'] = $tankers;
            $bodyData['contractors'] = $contractors;
            $bodyData['companies'] = $companies;
            $bodyData['cities'] = $this->db->get('cities')->result();
            $bodyData['products'] = $this->db->get('products')->result();
            $bodyData['drivers'] = $drivers;
            $bodyData['selected_customer'] = $trip[0]->customer_id;

            $bodyData['trip_with_mass_payment'] = $this->trips_model->trip_with_mass_payment($trip_id);
            $bodyData['pin'] = $this->helper_model->edit_pin();

            $this->load->view('components/header', $headerData);
            switch($trip[0]->type)
            {
                case '1':
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $bodyData['trip_type'] = 'self_mail';
                    $this->load->view('trips/edit_simple_trip', $bodyData);
                    break;
                case '2':
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $bodyData['trip_type'] = 'general';
                    $this->load->view('trips/edit_local_trip', $bodyData);
                    break;
                case '3':
                    $bodyData['local_cmp_routes'] = $this->routes_model->routes('all','3');
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('secondary');
                    $bodyData['trip_type'] = 'local_company';
                    $this->load->view('trips/edit_other_trip', $bodyData);
                    break;
                case '4':
                    $bodyData['local_self_routes'] = $this->routes_model->routes('all','1');
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $bodyData['trip_type'] = 'local_self';
                    $this->load->view('trips/edit_other_trip', $bodyData);
                    break;
                case '5':
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $bodyData['trip_type'] = 'general_local';
                    $this->load->view('trips/edit_general_local_trip', $bodyData);
                    break;
                case '6':
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $bodyData['trip_type'] = 'secondary_local';
                    $this->load->view('trips/edit_secondary_local_trip', $bodyData);
                    break;
                default:
                    $bodyData['freights'] = $this->routes_model->all_routes_freights('primary');
                    $this->load->view('trips/edit_simple_trip', $bodyData);
                    break;

            }

            $this->load->view('components/footer');
        }

    }


    public function print_fuel_report()
    {

        $bodyData['trips'] = null;
        if(isset($_POST['show_fuel_report']))
        {
            $trip_ids = explode('_',$_POST['trip_ids_for_fuel_report']);
            $final_trips = $this->trips_model->parametrized_trips_engine($trip_ids, "trips_welcome");
            $bodyData['trips'] = $final_trips;
            if(isset($_POST['columns_for_fuel_report'])){
                $bodyData['columns'] = explode('/', $_POST['columns_for_fuel_report']);
            }

            if(isset($_GET['export'])){
                $this->load->view('trips/components/export/export_fuel_report', $bodyData);
            }else{
                $this->load->view('trips/components/print_fuel_report', $bodyData);
            }
        }

    }

    public function trip_report()
    {
        $id = $_GET['id'];
        $headerData = array(
            'title' => 'Viriklogistics | Trip Report',
            'page' => 'trips'
        );

        $trip_ids = array($id,);
        $trips = $this->trips_model->parametrized_trips_engine($trip_ids, 'trip_report');
        $trip = (sizeof($trips > 0))?$trips[0]:null;
        $bodyData['trip'] = $trip;

        $expenses = $this->trips_model->expenses($id);
        $other_expenses = array();
        $destination_shortage = null;
        $decanding_shortage = null;
        foreach($expenses as $expense)
        {
            if(strtolower($expense->title) == strtolower("Destination Shortage"))
            {
                $destination_shortage = $expense;
            }
            else if(strtolower($expense->title) == strtolower("Decanding Shortage"))
            {
                $decanding_shortage = $expense;
            }
            else
            {
                array_push($other_expenses, $expense);
            }
        }
        $bodyData['other_expenses'] = $other_expenses;
        $bodyData['destination_shortage'] = $destination_shortage;
        $bodyData['decanding_shortage'] = $decanding_shortage;

        $this->load->view('components/header', $headerData);
        $this->load->view('trips/trip_report', $bodyData);
        $this->load->view('components/footer');

    }

    function edit_shortage_chit($trip_id, $trip_detail_id, $editing_voucher, $destination_voucher, $product, $product_type)
    {
        $bodyData = array(
            'voucher_number'=>$voucher,
        );
        switch($product_type)
        {
            case "black_oil":
                $this->load->view('trips/components/edit_black_oil_shortage_chit');
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



    function _is_single($str, $data){

        $data = explode('.',$data);

        $trip_id = $this->uri->segment(3);

        if($this->helper_model->is_single($data[0], $data[1], $str, $trip_id) == true){

            return true;

        }else{

            $this->form_validation->set_message('_is_single',"%s must be unique.");

            return false;

        }

    }



    function _check_re_submission($form_id, $table){
        if($this->helper_model->re_submission($table, $form_id) == true){
            $this->form_validation->set_message('_check_re_submission','Entry failed because of form re-submission.');
            return false;
        }
        return true;
    }

    function _check_form_re_submission($form_id)
    {
        if($this->helper_model->form_re_submission($form_id) == true){
            $this->form_validation->set_message('_check_form_re_submission','Entry failed because of form re-submission.');
            return false;
        }
        else{
            $this->helper_model->set_form_id($form_id);
            return true;
        }
    }

    /*
     * below are the rules for editing trip
     * when customer , contractor, company is changed than system has to check that chagings..
     */
    function _customer_changed($previous_customer)
    {
        if($previous_customer != $this->input->post('customers')){
            $this->db->select('SUM(customer_accounts.amount) as amount_paid');
            $this->db->from('trips');
            $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
            $this->db->join('customer_accounts','customer_accounts.trip_detail_id = trips_details.id','left');
            $this->db->where('trips.id',$this->input->post('trip_id'));
            $this->db->where('trips.customer_id',$previous_customer);
            $result = $this->db->get()->result();
            if($result[0]->amount_paid != 0){
                $this->form_validation->set_message('_customer_changed','Customer cannot be changed because you\'ve made some payments for this customer regarding to this trip.');
                return false;
            }
        }
        return true;
    }

    function _contractor_changed($previous_contractor)
    {
        if($previous_contractor != $this->input->post('contractors')){
            $this->db->select('SUM(contractor_accounts.amount) as amount_paid');
            $this->db->from('trips');
            $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
            $this->db->join('contractor_accounts','contractor_accounts.trip_detail_id = trips_details.id','left');
            $this->db->where('trips.id',$this->input->post('trip_id'));
            $this->db->where('trips.contractor_id',$previous_contractor);
            $result = $this->db->get()->result();
            if($result[0]->amount_paid != 0){
                $this->form_validation->set_message('_contractor_changed','Contractor cannot be changed because you\'ve made some payments for this Contractor regarding to this trip.');
                return false;
            }
        }
        return true;
    }

    function _company_changed($previous_company)
    {
        if($previous_company != $this->input->post('companies')){
            $this->db->select('SUM(company_accounts.amount) as amount_paid');
            $this->db->from('trips');
            $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
            $this->db->join('company_accounts','company_accounts.trip_detail_id = trips_details.id','left');
            $this->db->where('trips.id',$this->input->post('trip_id'));
            $this->db->where('trips.company_id',$previous_company);
            $result = $this->db->get()->result();
            if($result[0]->amount_paid != 0){
                $this->form_validation->set_message('_company_changed','Company cannot be changed because you\'ve made some payments for this Company regarding to this trip.');
                return false;
            }
        }
        return true;
    }

    function _is_deletable_trip($id)
    {
        /*
         * -------------------------------------------
         * Fetching trip detail ids
         * --------------------------------------------
         */
        $this->db->select('trips_details.id');
        $result = $this->db->get_where('trips_details', array(
            'trips_details.trip_id'=>$id,
        ))->result();
        $details_ids = array();
        foreach($result as $record)
        {
            array_push($details_ids, $record->id);
        }
        /*************************************************/

        $this->db->select('voucher_journal.id');
        $this->db->from('trip_detail_voucher_relation');
        $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
        $this->db->where(array(
            'voucher_journal.active'=>1,
            'voucher_journal.auto_generated'=>0,
        ));
        $this->db->where_in('trip_detail_voucher_relation.trip_detail_id', $details_ids);
        $result = $this->db->get()->num_rows();

        if($result > 0)
        {
            $this->form_validation->set_message('_is_deletable_trip','Error! Dear user there are some mass payments
            happened for this trip, So you cannot delete this trip right now.');
            return false;
        }
        return true;
    }

    function _check_trip_detail_id_for_shortage_voucher($detail_id)
    {
        if(isset($_POST['trip_detail_id']) && $_POST['trip_detail_id'] != 0)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('_check_trip_detail_id_for_shortage_voucher','Something Went Wrong! please try again.');
            return false;
        }
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