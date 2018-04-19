<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Settings extends ParentController {

    //public variables...

    public function __construct()
    {
        parent::__construct();
    }


    /* The default function that gets called when visiting the page */
    public function index()
    {
        $headerData = array(
            'title' => 'Virik Logistics | Settings',
            'page' => 'settings',
        );
        $bodyData = array(
            'someMessage'=>'',
        );
        $bodyData['system_settings'] = $this->settings_model->system_settings();
        $this->load->view('components/header', $headerData);
        $this->load->view('settings/system', $bodyData);
        $this->load->view('components/footer');

    }

    public function accounts()
    {
        $headerData = array(
            'title' => 'Virik Logistics | Settings',
            'page' => 'settings',
        );
        $bodyData = array(
            'someMessage'=>'',
        );

        //deleting the account title*****************//
        if(isset($_GET['del_ac_title'])){
            $_POST['del_ac_title'] = $_GET['del_ac_title'];
            $this->form_validation->set_rules('del_ac_title', 'Account Title Id', 'required|numeric|callback__validate_ac_title_deleting');
            if ($this->form_validation->run() == true)
            {
                if( $this->helper_model->delete_record('account_titles',$_GET['del_ac_title']) == true){
                    $bodyData['someMessage'] = array('message'=>'Account Title Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////

        //saving the title
        if(isset($_POST['add_title'])){
            $this->form_validation->set_rules('title', 'Title', 'required|is_unique[account_titles.title]');
            if ($this->form_validation->run() == true)
            {
                $this->accounts_model->add_account_title();
                $bodyData['someMessage'] = array('message'=>'Title Saved Successfully!', 'type'=>'success');
            }
        }
        //saving the accounting Year
        if(isset($_POST['save_accounting_year'])){
            $this->settings_model->save_accounting_year();
            $bodyData['someMessage'] = array('message'=>'Accounting Period Saved Successfully!', 'type'=>'success');
        }

        $bodyData['accounting_year'] = $this->accounts_model->accounting_year();
        $bodyData['titles'] = $this->accounts_model->account_titles();

        $this->load->view('components/header', $headerData);
        $this->load->view('settings/accounts', $bodyData);
        $this->load->view('components/footer');
    }


    public function show($c_id = '')
    {
        if($c_id == ''){
            $this->index();
        }else{
            $headerData = array(
                'title' => 'Virik-Logistics | Customers'
            );
            $bodyData = array(


            );
            $this->load->view('components/header', $headerData);
            $this->load->view('trips/trip', $bodyData);
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

    function _validate_ac_title_deleting($title_id){
        $used_in = '';

        $this->db->from('voucher_journal');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->where('voucher_journal.active',1);

        $this->db->where('voucher_entry.account_title_id',$title_id);
        $accounts = $this->db->get()->num_rows();
        if($accounts >= 1){
            $used_in .= 'Accounts';
        }

        if($used_in != ''){
            $this->form_validation->set_message('_validate_ac_title_deleting','This Title is being used in the other parts of the system! e.g('.$used_in.').');
            return false;
        }
        return true;
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