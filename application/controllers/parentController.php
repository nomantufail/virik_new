<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ParentController extends CI_Controller {

    //public variables...
    public $login;

    public function __construct()
    {
        parent::__construct();

        include_once("libraries/php/helper.php");
        include_once(APPPATH."serviceProviders/Sort.php");

        $this->load->helper(array('form', 'url', 'captcha'));
        $this->load->library(array('form_validation','email','Carbon','Helper','pagination','session'));
        $this->load->model(array(
            'parent_model',
            'admin_model',
            'accounts_model',
            'trips_model',
            'customers_model',
            'companies_model',
            'tankers_model',
            'carriageContractors_model',
            'manageCommissions_model',
            'routes_model',
            'drivers_model',
            'helper_model',
            'otherAgents_model',
            'sorting_model',
            'manageaccounts_model',
            'settings_model',
            'privilege_model',
            'reports_model',
            'shortages_model',
            'products_model',
        ));




        //checking the login state below...
        $this->login = $this->helper_model->is_login();

        $this->take_care_of_pre_stuff();


    }

    function _remap($method){
        if($this->login == false){
            $this->login();
        }
        else if($this->privilege_model->is_authenticated() == false)
        {
            $this->privilege_model->check_privileges();
        }
        else
        {
            $this->_call_with_args($method);
        }
    }

    public function take_care_of_pre_stuff()
    {
        if($this->login == true)
        {
            //checking for auto logout expiration
            if($_SESSION['role'] == 1){
                $date1 = new DateTime($_SESSION['logged_in_at']);
                $date2 = new DateTime(date('Y-m-d H:i:s'));
                $diff = $date2->diff($date1);
                $hours = $diff->h;
                $hours = $hours + ($diff->days*24);
                if($hours > 5){
                    $this->logout();
                }
            }

            //save multiple sorting info
            $this->save_multiple_sorting_info();
        }
    }

    public function save_multiple_sorting_info()
    {
        if(isset($_POST['multiple_sort']))
        {
            $sorting_module = $_POST['sorting_module'];

            $sorting_info = array();
            $num_of_columns = $_POST['num_of_columns'];
            for($i = 1; $i<$num_of_columns; $i++)
            {
                $column = 'column_'.$i;
                if(isset($_POST[$column]) && $_POST['priority_'.$i] != '')
                {
                    $column_data = [];
                    $column_data['view'] = $sorting_module;
                    $column_data['sort_by'] = $_POST[$column];
                    $column_data['priority'] = $_POST['priority_'.$i];
                    $column_data['order_by'] = $_POST['order_'.$i];

                    array_push($sorting_info, $column_data);
                }
            }

            if(sizeof($sorting_info) > 0){
                $result = $this->helper_model->save_multiple_sorting_info($sorting_info);
                if($result == true){
                    $this->helper_model->redirect_with_success('Sorting Applied Successfully');
                }else{
                    $this->helper_model->redirect_with_errors('Sorting Failed! Please try again.');
                }
            }else{
                $this->helper_model->redirect_with_errors('Please select atleast one column to apply sorting.');
            }
        }
    }
    public function login($msg = "")
    {
        if($this->login == true){
            $this->index();
        }else{
            $headerData = array(

            );
            $captcha = $this->helper_model->_create_captcha();
            $bodyData = array(
                'captcha' =>$captcha["image"],
                'captcha_word' =>$captcha['word'],

            );

            if ($this->form_validation->run('login') == true)
            {
                //logging in...
                $this->_LOGIN();
                if($this->login == false){
                    $data['message'] = "Login Failed!";
                    $data['type']="alert-danger";
                    $this->load->view('admin/login', $data);
                }else{
                    $this->index();
                }
            }
            else
            {
                $this->load->view('admin/login', $bodyData);
            }
        }
    }
    function _LOGIN(){
        $this->helper_model->login($this->input->post('username'));
        $this->login = $this->helper_model->is_login();
    }
    function logout(){
        $this->helper_model->logout();
        redirect(base_url()."customers");
    }

    private function _loggedIn(){
        /*if($this->admin_model->loggedIn() == 1){
            return true;
        }else{
            return false;
        }*/
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



    private function _call_with_args($method, $args=""){
        if($args == ""){
            $args = array_slice($this->uri->rsegments,2);
        }
        if(method_exists($this,$method)){
            return call_user_func_array(array(&$this,$method),$args);
        }
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */