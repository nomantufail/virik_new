<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Helper_Controller extends ParentController {

    //public variables...
    public $login;

    public function __construct()
    {
        parent::__construct();
    }


    /* The default function that gets called when visiting the page */
    public function index()
    {

    }

    public function delete_record($table, $id)
    {
        if($this->helper_model->delete_record($table, $id) == true){
            echo "ok";
        }else{
            echo "error";
        }
    }
    public function edit_record($table, $rules='')
    {
        $ci = CI_Controller::get_instance();

        $rules = str_replace('%7C','|',$rules);
        $this->form_validation->set_rules('value', 'given', $rules);
        if($_SESSION['role'] == 1)
        {
            if ($this->form_validation->run() == FALSE)
            {
                $this->output->set_content_type('application/json');
                echo strip_tags(validation_errors());
            }else{
                $pk = $this->input->post('pk');
                $name = $this->input->post('name');
                $value = $this->input->post('value');

                $this->helper_model->edit_global_record($table, $pk, $name, $value);
            }
        }
        else
        {
            /*---------------------------------------*
             *In this section we will see if
             * we have to give some permissions to
             * un authorized members or not...
             *-----------------------------------------
             */
            switch($table)
            {
                case "cities":
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->output->set_content_type('application/json');
                        echo strip_tags(validation_errors());
                    }else{
                        $pk = $this->input->post('pk');
                        $name = $this->input->post('name');
                        $value = $this->input->post('value');

                        $this->helper_model->edit_global_record($table, $pk, $name, $value);
                    }
                    break;
                case "products":
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->output->set_content_type('application/json');
                        echo strip_tags(validation_errors());
                    }else{
                        $pk = $this->input->post('pk');
                        $name = $this->input->post('name');
                        $value = $this->input->post('value');

                        $this->helper_model->edit_global_record($table, $pk, $name, $value);
                    }
                    break;
                default:
                    $this->output->set_content_type('application/json');
                    echo "No Accessibility! Please contact your system administrator";
                    break;
            }

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

    function _is_exist_for_editing($value, $data)
    {
        $data_parts = explode('.',$data);
        $this->db->select($data_parts[1]);
        $records = $this->db->get_where($data_parts[0],array(
            'id !='=>$data_parts[2],
            $data_parts[1]=>$value,
        ))->num_rows();

        if($records > 0){
            $this->form_validation->set_message('_is_exist_for_editing','Sorry! Same tanker number already exist in the system.');
            return false;
        }
        return true;
    }
    function _validate_tanker_customer_changed($default, $tanker_customer){
        $tanker_customer = explode('.',$tanker_customer);
        $tanker_id = $tanker_customer[0];
        $customer_id = $tanker_customer[1];

        /**------------------------------
         * Below area was commented because
         * when user transfer a tanker from
         * one customer to another customer
         * than system will not restrict that
         *--------------------------------
         */
        /*$this->db->select("*");
        $trips = $this->db->get_where('trips', array('tanker_id'=>$tanker_id, 'customer_id'=>$customer_id))->num_rows();
        if($trips > 0){
            $this->form_validation->set_message('_validate_tanker_customer_changed','Sorry! customer cannot be changed because tanker is being used in the trips.');
            return false;
        }*/
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