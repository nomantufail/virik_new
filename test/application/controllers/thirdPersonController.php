<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class ThirdPersonController extends ParentController {
    public function __construct()
    {
        parent::__construct();
    }
    /* The default function that gets called when visiting the page */
    /* The default function that gets called when visiting the page */
    public function index()
    {
        if($this->login == true){
            $headerData = array(
                'title' => 'Virik Logistics | Accounts',
                'page' => 'accounts',
            );
            $bodyData = array(
                'max_trip_id'=> 0,
            );



            $this->load->view('components/header', $headerData);
            $this->load->view('accounts/welcome', $bodyData);
            $this->load->view('components/footer');
        }else{
            $this->load->view('admin/login');
        }

    }

    public function zeeshan()
    {
        $data = array(
            'name' => 'zeeshan',
            'date_created' => date('Y-m-d'),
            'detail' => 'Empty'
        );
        $this->thirdPerson->save($data);
    }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */