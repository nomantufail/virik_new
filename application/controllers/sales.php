<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Sales extends ParentController {
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
                $this->bodyData['section'] = 'cash_sale';
            }
            $this->cash_sale();
        }
    }

    public function cash_sale()
    {
        $headerData['title']='Sale';
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['customers'] = $this->agents_model->customers();

        if(isset($_POST['save_cash_sale']))
        {
            $saved_invoice = $this->sales_model->insert_cash_sale();
            if($saved_invoice != 0){
                $this->bodyData['someMessage'] = array('message'=>'Invoice Saved Successfully! Invoice# was <b>'.$saved_invoice.'</b>', 'type'=>'alert-success');
            }else{
                $this->bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
            }

        }
        $this->bodyData['invoice_number'] = $this->sales_model->next_invoice();
        $this->load->view('components/header',$headerData);
        $this->load->view('sales/cash/make', $this->bodyData);
        $this->load->view('components/footer');
    }
    public function credit_sale()
    {
        $headerData['title']='sale';
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['customers'] = $this->agents_model->customers();

        if(isset($_POST['save_credit_sale']))
        {
            $saved_invoice = $this->sales_model->insert_credit_sale();
            if($saved_invoice != 0){
                $this->bodyData['someMessage'] = array('message'=>'Invoice Saved Successfully! Invoice# was <b>'.$saved_invoice.'</b>', 'type'=>'alert-success');
            }else{
                $this->bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
            }

        }
        $this->bodyData['invoice_number'] = $this->sales_model->next_invoice();
        $this->load->view('components/header',$headerData);
        $this->load->view('sales/credit/make', $this->bodyData);
        $this->load->view('components/footer');
    }

    public function cash()
    {
        $headerData['title']= 'Cash Invoices';
        $sales = $this->sales_model->cash();
        $this->bodyData['sales']= $sales;

        $this->load->view('components/header', $headerData);
        $this->load->view('sales/cash/show', $this->bodyData);
        $this->load->view('components/footer');
    }

    public function credit()
    {
        $headerData['title']= 'Credit Invoices';
        $sales = $this->sales_model->credit();
        $this->bodyData['sales']= $sales;

        $this->load->view('components/header', $headerData);
        $this->load->view('sales/credit/show', $this->bodyData);
        $this->load->view('components/footer');
    }

}
