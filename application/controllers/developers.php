<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


include_once(APPPATH."controllers/parentController.php");
class Developers extends ParentController {

    //public variables...
    public $login;

    public function __construct()
    {
        parent::__construct();
    }


    /* The default function that gets called when visiting the page */
    public function index()
    {
        $headerData = array(
            'title' => 'Virik-Logistics | Developers',
            'page' => 'developers',
        );
        $bodyData = array(
            'area' => 'Developers'
        );
        $this->load->view('components/header', $headerData);
        $this->load->view('components/under_construction', $bodyData);
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