<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Reports extends ParentController {

    //public variables...
    public function __construct()
    {
        parent::__construct();
    }

    /* The default function that gets called when visiting the page */
    public function index($section = '')
    {
        $headerData = array(
            'title' => 'Virik-Logistics | Reports',
            'page' => 'reports',
        );
        $bodyData = array(
            'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
            'customers' => $this->customers_model->customers(),
            'tankers' => '',
            'section' => '',
            'someMessage' => '',
        );
        $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;

        $this->load->view('components/header', $headerData);
        $this->load->view('reports/welcome', $bodyData);
        $this->load->view('components/footer');
    }

    public function company($section = '')
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );
        $bodyData = array(
            'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
            'customers' => $this->customers_model->customers(),
            'companies' => $this->companies_model->companies(),
            'tankers' => '',
            'section' => '',
            'someMessage' => '',
        );
        $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
        $this->load->view('components/header', $headerData);
        $this->load->view('reports/company', $bodyData);
        $this->load->view('components/footer');
    }

    public function generate_customer_reports(){

        if($this->form_validation->run('generate_customer_reports') == false){
            $bodyData = array(
                'from_date'=>'',
                'to_date'=>'',
                'contractor'=>'',
                'customer'=>'',
                'reports' => '',
                'columns' => array(),
            );
            $headerData = array(
                'title' => 'Virik Logistics | Reports',
                'page' => 'reports',
            );
            $bodyData['from_date'] = $this->carbon->createFromFormat('Y-m-d', $this->input->get('from_date'))->toFormattedDateString();
            $bodyData['to_date'] = $this->carbon->createFromFormat('Y-m-d', $this->input->get('to_date'))->toFormattedDateString();
            $bodyData['contractor'] = $this->carriageContractors_model->carriageContractor($this->input->get('contractors'))->name ;
            $bodyData['customer'] =  $this->customers_model->customer($this->input->get('customers'))->name ;
            $bodyData['tanker'] = ($this->input->get('tankers') == 'all')?"All": $this->tankers_model->tanker($this->input->get('tankers'))->truck_number;


            $bodyData['reports'] = $this->reports_model->generate_customer_reports();

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    $bodyData['reports'] = $this->helper_model->filter_records($bodyData['reports'], $_POST['check'],"trip_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/components/print_customer_reports', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    $bodyData['reports'] = $this->helper_model->filter_records($bodyData['reports'], $_POST['check'],"trip_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/components/export/export_customer_reports', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/show_customer_reports', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->index();
        }
    }

    public function generate_company_reports(){

        if($this->form_validation->run('generate_company_reports') == false){
            $headerData = array(
                'title' => 'Virik Logistics | Reports',
                'page' => 'reports',
            );
            $bodyData = array(
                'from_date'=>'',
                'to_date'=>'',
                'company'=>'',
                'contractor'=>'',
                'customer'=>'',
                'reports' => '',
                'columns' => array(),
            );
            $bodyData['from_date'] = $this->carbon->createFromFormat('Y-m-d', $this->input->get('from_date'))->toFormattedDateString();
            $bodyData['to_date'] = $this->carbon->createFromFormat('Y-m-d', $this->input->get('to_date'))->toFormattedDateString();
            $bodyData['contractor'] = $this->carriageContractors_model->carriageContractor($this->input->get('contractors'))->name ;
            $bodyData['company'] = $this->companies_model->company($this->input->get('companies'))->name ;
            $bodyData['customer'] =  ($this->input->get('customers') != 'all')?$this->customers_model->customer($this->input->get('customers'))->name: 'All';
            $bodyData['tanker'] = ($this->input->get('tankers') == 'all')?"All": $this->tankers_model->tanker($this->input->get('tankers'))->truck_number;


            $bodyData['reports'] = $this->reports_model->generate_company_reports();

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    $bodyData['reports'] = $this->helper_model->filter_records($bodyData['reports'], $_POST['check'],"trip_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/components/print_company_reports', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    $bodyData['reports'] = $this->helper_model->filter_records($bodyData['reports'], $_POST['check'],"trip_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/components/export/export_company_reports', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/show_company_reports', $bodyData);
                $this->load->view('components/footer');
            }
        }else{
            $this->company();
        }
    }

    public function calculation_sheet_black_oil($comand = '')
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );

        if($comand == 'generate')
        {
            $keys = array();
            $keys['company_id'] = (isset($_GET['companies']))?$_GET['companies']:1;
            $keys['contractor_id'] = (isset($_GET['contractors']))?$_GET['contractors']:1;
            $keys['from_date'] = (isset($_GET['from_date']))?$_GET['from_date']:date("Y-m-d");
            $keys['to_date'] = (isset($_GET['to_date']))?$_GET['to_date']:date("Y-m-d");
            $company = 'none';
            $contractor = 'none';
            if(isset($_GET['companies']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $company_data = $this->companies_model->company($_GET['companies']);
                    $company = $company_data->name;
                }
            }
            if(isset($_GET['contractors']))
            {
                if($_GET['contractors'] == 'all')
                {
                    $contractor = '';
                }
                else
                {
                    $contractor_data = $this->carriageContractors_model->carriageContractor($_GET['contractors']);
                    $contractor = $contractor_data->name;
                }
            }
            $bodyData = array(
                'report' => $this->reports_model->calculation_sheet_black_oil($keys),
                'company'=>$company,
                'contractor'=>$contractor,
                'someMessage' => '',
            );

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/print/black_oil', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/export/black_oil', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/calculation_sheets/show/black_oil', $bodyData);
                $this->load->view('components/footer');
            }
        }
        else
        {
            $bodyData = array(
                'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
                'customers' => $this->customers_model->customers(),
                'companies' => $this->companies_model->companies(),
                'someMessage' => '',
            );
            $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
            $this->load->view('components/header', $headerData);
            $this->load->view('reports/calculation_sheets/make/black_oil', $bodyData);
            $this->load->view('components/footer');
        }

    }

    public function calculation_sheet_custom($comand = '')
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );

        if($comand == 'generate')
        {
            $keys = array();
            $keys['company_id'] = (isset($_GET['companies']))?$_GET['companies']:1;
            $keys['contractor_id'] = (isset($_GET['contractors']))?$_GET['contractors']:1;
            $keys['from_date'] = (isset($_GET['from_date']))?$_GET['from_date']:date("Y-m-d");
            $keys['to_date'] = (isset($_GET['to_date']))?$_GET['to_date']:date("Y-m-d");
            $keys['product'] = (isset($_GET['product']))?$_GET['product']:"";
            $keys['product_type'] = (isset($_GET['product_type']))?$_GET['product_type']:"";
            $company = 'none';
            $contractor = 'none';
            $product = "All";
            $product_type = "All";
            if(isset($_GET['companies']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $company_data = $this->companies_model->company($_GET['companies']);
                    $company = $company_data->name;
                }
            }
            if(isset($_GET['contractors']))
            {
                if($_GET['contractors'] == 'all')
                {
                    $contractor = '';
                }
                else
                {
                    $contractor_data = $this->carriageContractors_model->carriageContractor($_GET['contractors']);
                    $contractor = $contractor_data->name;
                }
            }
            if(isset($_GET['product']))
            {
                //var_dump($_GET['product']); die();
                if($_GET['product'] != 'all')
                {
                    $product_data = $this->routes_model->product($_GET['product']);
                    $product = $product_data->productName;
                }
            }
            if(isset($_GET['product_type']))
            {
                //var_dump($_GET['product']); die();
                if($_GET['product_type'] != 'all')
                {
                    $product_type = $_GET['product_type'];
                }
            }
            $bodyData = array(
                'report' => $this->reports_model->calculation_sheet_custom($keys),
                'company'=>$company,
                'contractor'=>$contractor,
                'product'=>$product,
                'product_type'=>$product_type,
                'someMessage' => '',
            );

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    //$bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/print/custom', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/export/custom', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/calculation_sheets/show/custom', $bodyData);
                $this->load->view('components/footer');
            }
        }
        else
        {
            $bodyData = array(
                'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
                'customers' => $this->customers_model->customers(),
                'companies' => $this->companies_model->companies(),
                'products' => $this->routes_model->products(),
                'someMessage' => '',
            );
            $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
            $this->load->view('components/header', $headerData);
            $this->load->view('reports/calculation_sheets/make/custom', $bodyData);
            $this->load->view('components/footer');
        }

    }

    public function calculation_sheet_white_oil($comand = '')
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );

        if($comand == 'generate')
        {
            $keys = array();
            $keys['company_id'] = (isset($_GET['companies']))?$_GET['companies']:1;
            $keys['contractor_id'] = (isset($_GET['contractors']))?$_GET['contractors']:1;
            $keys['from_date'] = (isset($_GET['from_date']))?$_GET['from_date']:date("Y-m-d");
            $keys['to_date'] = (isset($_GET['to_date']))?$_GET['to_date']:date("Y-m-d");
            $company = 'none';
            $contractor = 'none';
            if(isset($_GET['companies']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $company_data = $this->companies_model->company($_GET['companies']);
                    $company = $company_data->name;
                }
            }
            if(isset($_GET['contractors']))
            {
                if($_GET['contractors'] == 'all')
                {
                    $contractor = '';
                }
                else
                {
                    $contractor_data = $this->carriageContractors_model->carriageContractor($_GET['contractors']);
                    $contractor = $contractor_data->name;
                }
            }
            $bodyData = array(
                'report' => $this->reports_model->calculation_sheet_white_oil($keys),
                'company'=>$company,
                'contractor'=>$contractor,
                'someMessage' => '',
            );

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    //$bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/print/white_oil', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/calculation_sheets/export/white_oil', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/calculation_sheets/show/white_oil', $bodyData);
                $this->load->view('components/footer');
            }
        }
        else
        {
            $bodyData = array(
                'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
                'customers' => $this->customers_model->customers(),
                'companies' => $this->companies_model->companies(),
                'someMessage' => '',
            );
            $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
            $this->load->view('components/header', $headerData);
            $this->load->view('reports/calculation_sheets/make/white_oil', $bodyData);
            $this->load->view('components/footer');
        }

    }

    public function shortage_report($comand = '')
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );

        if($comand == 'generate')
        {
            $keys = array();
            $keys['company_id'] = (isset($_GET['companies']))?$_GET['companies']:1;
            $keys['contractor_id'] = (isset($_GET['contractors']))?$_GET['contractors']:1;
            $keys['from_date'] = (isset($_GET['from_date']))?$_GET['from_date']:date("Y-m-d");
            $keys['to_date'] = (isset($_GET['to_date']))?$_GET['to_date']:date("Y-m-d");
            $keys['product'] = (isset($_GET['product']))?$_GET['product']:"";
            $keys['product_type'] = (isset($_GET['product_type']))?$_GET['product_type']:"";
            $keys['agent_type'] = (isset($_GET['agent_type']))?$_GET['agent_type']:"";
            $keys['agent_id'] = (isset($_GET['agent_id']))?$_GET['agent_id']:"";
            $company = 'none';
            $contractor = 'none';
            $product = "All";
            $related_agent = "All";
            $product_type = "All";
            if(isset($_GET['companies']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $company_data = $this->companies_model->company($_GET['companies']);
                    $company = $company_data->name;
                }
            }
            if(isset($_GET['contractors']))
            {
                if($_GET['contractors'] == 'all')
                {
                    $contractor = '';
                }
                else
                {
                    $contractor_data = $this->carriageContractors_model->carriageContractor($_GET['contractors']);
                    $contractor = $contractor_data->name;
                }
            }
            if(isset($_GET['product']))
            {
                //var_dump($_GET['product']); die();
                if($_GET['product'] != 'all')
                {
                    $product_data = $this->routes_model->product($_GET['product']);
                    $product = $product_data->productName;
                }
            }
            if(isset($_GET['product_type']))
            {
                //var_dump($_GET['product']); die();
                if($_GET['product_type'] != 'all')
                {
                    $product_type = $_GET['product_type'];
                }
            }
            if($keys['agent_type'] != '')
            {
                $related_agent = ucwords(join(' ', explode('_',$keys['agent_type'])));
                if($keys['agent_id'] != '')
                {
                    switch($keys['agent_type'])
                    {
                        case "other_agents":
                            $agent = $this->otherAgents_model->otherAgent($keys['agent_id']);
                            if($agent != null){
                                $related_agent.=" ".ucwords($agent->name);
                            }
                            break;
                        case "customers":
                            $agent = $this->customers_model->customer($keys['agent_id']);
                            if($agent != null){
                                $related_agent.=" ".ucwords($agent->name);
                            }
                            break;
                        case "carriage_contractors":
                            $agent = $this->carriageContractors_model->carriageContractor($keys['agent_id']);
                            if($agent != null){
                                $related_agent.=" ".ucwords($agent->name);
                            }
                            break;
                        case "companies":
                            $agent = $this->companies_model->company($keys['agent_id']);
                            if($agent != null){
                                $related_agent.=" ".ucwords($agent->name);
                            }
                            break;
                    }
                }
            }
            $bodyData = array(
                'report' => $this->reports_model->shortage_report($keys),
                'company'=>$company,
                'contractor'=>$contractor,
                'product'=>$product,
                'product_type'=>$product_type,
                'related_agent'=>$related_agent,
                'someMessage' => '',
            );

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    //$bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/shortage_report/print/shortage_report', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/shortage_report/export/shortage_report', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/shortage_report/show/shortage_report', $bodyData);
                $this->load->view('components/footer');
            }
        }
        else
        {
            $bodyData = array(
                'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
                'customers' => $this->customers_model->customers(),
                'companies' => $this->companies_model->companies(),
                'products' => $this->routes_model->products(),
                'someMessage' => '',
            );
            $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
            $this->load->view('components/header', $headerData);
            $this->load->view('reports/shortage_report/make/shortage_report', $bodyData);
            $this->load->view('components/footer');
        }
    }

    public function freight_report($comand=null)
    {
        $headerData = array(
            'title' => 'Virik Logistics | Reports',
            'page' => 'reports',
        );

        if($comand == 'generate')
        {
            $keys = array();

            $company = 'any';
            $contractor = 'Contractor: any';
            $trip_type = "All";
            $product_type = "All";
            if(isset($_GET['companies']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $company_data = $this->companies_model->company($_GET['companies']);
                    $company = $company_data->name;
                }
            }
            if(isset($_GET['contractors']))
            {
                if($_GET['companies'] == 'all')
                {
                    $company = '';
                }
                else
                {
                    $contractor_data = $this->carriageContractors_model->carriageContractor($_GET['contractors']);
                    $contractor = $contractor_data->name;
                }
            }
            if(isset($_GET['trip_type']))
            {
                //var_dump($_GET['product']); die();

            }
            if(isset($_GET['product_type']))
            {
                //var_dump($_GET['product']); die();
                if($_GET['product_type'] != 'all')
                {
                    $product_type = $_GET['product_type'];
                }
            }
            $bodyData = array(
                'report' => $this->reports_model->freight_report(),
                'company'=>$company,
                'contractor'=>$contractor,
                'trip_type'=>$trip_type,
                'product_type'=>$product_type,
                'someMessage' => '',
            );

            if(isset($_GET['print'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    //$bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/freight_report/print/freight_report', $bodyData);
            }
            else if(isset($_GET['export'])){
                if(isset($_POST['check'])){
                    //$bodyData['report'] = $this->helper_model->filter_records($bodyData['report'], $_POST['check'],"trip_detail_id");
                }
                if(isset($_POST['column'])){
                    $bodyData['columns'] = $_POST['column'];
                }
                $this->load->view('reports/freight_report/export/freight_report', $bodyData);
            }
            else{
                $this->load->view('components/header', $headerData);
                $this->load->view('reports/freight_report/show/freight_report', $bodyData);
                $this->load->view('components/footer');
            }
        }
        else
        {
            $bodyData = array(
                'contractors' => $this->carriageContractors_model->carriageContractors('desc'),
                'customers' => $this->customers_model->customers(),
                'companies' => $this->companies_model->companies(),
                'someMessage' => '',
            );
            $bodyData['tankers'] = ($bodyData['customers'] != null)?$this->tankers_model->tankers($bodyData['customers'][0]->id):null;
            $this->load->view('components/header', $headerData);
            $this->load->view('reports/freight_report/make/freight_report', $bodyData);
            $this->load->view('components/footer');
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

    function _unique_company_deal(){
        if($this->manageCommissions_model->contractor_company_commission($this->input->post('contractors'),$this->input->post('companies')) != null){
            $this->form_validation->set_message('_unique_company_deal','A deal has already been made b/w these two agents');
            return false;
        }
        return true;
    }

    function _unique_customer_deal(){
        if($this->manageCommissions_model->contractor_customer_commission($this->input->post('contractors'),$this->input->post('customers')) != null){

            $this->form_validation->set_message('_unique_customer_deal','A deal has already been made b/w these two agents');
            return false;
        }
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */