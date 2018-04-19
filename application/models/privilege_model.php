<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privilege_Model extends CI_Model {

    //public variables...
    private $user_role;
    public function __construct()
    {
        parent::__construct();

        $this->user_role = $this->helper_model->user_role();
    }

    public function is_authenticated()
    {
        if((isset($_GET['del']) || isset($_GET['delete_voucher'])) && $this->user_role != 1)
        {
           return false;
        }

        return true;
    }
    public function check_privileges()
    {
        if(isset($_GET['del']) || isset($_GET['delete_voucher']))
        {
            $headerData = array(
                'title' => 'Virik Logistics | Un-Authorized User',
                'page' => 'error_message',
            );
            $bodyData = array(
                'errorMessage'=>'Dear User!<br> You are not allowed to delete this record. <br>
                                       if you want to do that, you should contact your system administrator<br><br>Thank You.',
            );
            $this->load->view('components/header', $headerData);
            $this->load->view('components/messages/any_error', $bodyData);
            $this->load->view('components/footer');
        }
        if($this->uri->segment(1) == 'trips' && $this->uri->segment(2) == 'edit')
        {
            $headerData = array(
                'title' => 'Virik Logistics | Un-Authorized User',
                'page' => 'error_message',
            );
            $bodyData = array(
                'errorMessage'=>'Dear User!<br> You are not allowed to edit trips. <br>
                                       if you want to do that, you should contact your system administrator<br><br>Thank You.',
            );
            $this->load->view('components/header', $headerData);
            $this->load->view('components/messages/any_error', $bodyData);
            $this->load->view('components/footer');
        }
    }

    public function allow_removing()
    {
        if($this->user_role != 1)
        {
            return false;
        }
        return true;
    }
    public function allow_editing()
    {
        if($this->user_role != 1)
        {
            return false;
        }
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */