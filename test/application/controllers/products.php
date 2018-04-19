<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Products extends ParentController {
    //public variables...

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $target_function = $this->intelligent_router_model->get_last_saved_route_for_current_controller();

        if($target_function != 'index')
        {
            //setting section
            $this->bodyData['section'] = $target_function;
            //and there we go...
            $this->$target_function();
        }else{
            if($this->bodyData['section'] == 'index')
            {
                $this->bodyData['section'] = 'show';
            }
            $this->show();
        }
    }

    public function show()
    {
        $headerData = array(
            'title' => 'Products',
        );
        $this->bodyData['someMessage'] = '';
        $this->bodyData['columns'] = array();

        if(isset($_POST['addProduct'])){
            if($this->form_validation->run('add_product') == true){
                if( $this->products_model->insert() == true){
                    $this->bodyData['someMessage'] = array('message'=>'Product Added Successfully!', 'type'=>'alert-success');
                }else{
                    $this->bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        $this->bodyData['products'] = $this->products_model->get();

        $this->load->view('components/header', $headerData);
        $this->load->view('products/welcome', $this->bodyData);
        $this->load->view('components/footer');

    }


}
