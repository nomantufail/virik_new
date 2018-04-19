<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Purchases extends ParentController {
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
                $this->bodyData['section'] = 'cash_purchase';
            }
            $this->cash_purchase();
        }
    }

    public function cash_purchase()
    {
        $headerData['title']='Purchase';
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['suppliers'] = $this->agents_model->suppliers();

        if(isset($_POST['save_cash_purchase']))
        {
            $saved_invoice = $this->purchases_model->insert_cash_purchase();
            if($saved_invoice != 0){
                $this->bodyData['someMessage'] = array('message'=>'Invoice Saved Successfully! Invoice# was <b>'.$saved_invoice.'</b>', 'type'=>'alert-success');
            }else{
                $this->bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
            }

        }
        $this->bodyData['invoice_number'] = $this->purchases_model->next_invoice();
        $this->load->view('components/header',$headerData);
        $this->load->view('purchases/cash/make', $this->bodyData);
        $this->load->view('components/footer');
    }
    public function credit_purchase()
    {
        $headerData['title']='Purchase';
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['suppliers'] = $this->agents_model->suppliers();

        if(isset($_POST['save_credit_purchase']))
        {
            $saved_invoice = $this->purchases_model->insert_credit_purchase();
            if($saved_invoice != 0){
                $this->bodyData['someMessage'] = array('message'=>'Invoice Saved Successfully! Invoice# was <b>'.$saved_invoice.'</b>', 'type'=>'alert-success');
            }else{
                $this->bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
            }

        }
        $this->bodyData['invoice_number'] = $this->purchases_model->next_invoice();
        $this->load->view('components/header',$headerData);
        $this->load->view('purchases/credit/make', $this->bodyData);
        $this->load->view('components/footer');
    }

    public function cash()
    {
        $headerData['title']= 'Cash Invoices';
        $purchases = $this->purchases_model->cash();
        $this->bodyData['purchases']= $purchases;

        $this->load->view('components/header', $headerData);
        $this->load->view('purchases/cash/show', $this->bodyData);
        $this->load->view('components/footer');
    }

    public function credit()
    {
        $headerData['title']= 'Credit Invoices';
        $purchases = $this->purchases_model->credit();
        $this->bodyData['purchases']= $purchases;

        $this->load->view('components/header', $headerData);
        $this->load->view('purchases/credit/show', $this->bodyData);
        $this->load->view('components/footer');
    }

}
