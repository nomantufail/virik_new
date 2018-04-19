<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Shortages extends ParentController {
    //public variables...

    public function __construct()
    {
        parent::__construct();

        $this->_need_to_save_something();
    }

    public function index()
    {
        redirect(base_url()."shortages/create");
    }

    public function show($type='destination')
    {
        $headerData = array(
            'title' => 'Shortages | '.ucwords($type),
            'page'=>'',
        );


        //deleting the customer*****************//
        if(isset($_GET['del'])){
            $_POST['del'] = $_GET['del'];
            $this->form_validation->set_rules('del', 'Shortage#', 'required|numeric|callback__is_shortage_exist');
            if ($this->form_validation->run() == true)
            {
                if( $this->shortages_model->delete_shortage($_GET['del']) == true){
                    $this->helper_model->redirect_with_success('Shortage Removed successfully',url_path()."?".unset_query_string_var('del',$_SERVER['QUERY_STRING']));
                }else{
                    $this->helper_model->redirect_with_errors('Some unknown database fault happened. please try again. ',url_path()."?".unset_query_string_var('del',$_SERVER['QUERY_STRING']));
                }
            }
            else
            {
                $this->helper_model->redirect_with_errors(validation_errors(),url_path()."?".unset_query_string_var('del',$_SERVER['QUERY_STRING']));
            }
        }
        //////////////////////////////////////////////////////////

        if(isset($_POST['commit_shortages']))
        {
            if($this->form_validation->run('commit_shortages') == true){
                $result = $this->shortages_model->commit();
                if($result == true)
                {
                    $this->helper_model->redirect_with_success('Shortages Commited successfully.');
                }
            }
            else{
                $this->helper_model->redirect_with_errors(validation_errors());
            }

        }

        $this->bodyData['someMessage'] = '';
        $this->bodyData['columns'] = array();
        $this->bodyData['cities'] = $this->routes_model->cities();
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['tankers'] = $this->db->get('tankers')->result();

        $shortages = null;
        if($type == 'destination')
            $shortages = $this->shortages_model->destination();
        else if($type == 'decanding')
            $shortages = $this->shortages_model->decanding();

        $this->bodyData['shortages'] = $shortages;
        $this->load->view('components/header', $headerData);
        $this->load->view('shortages/show', $this->bodyData);
        $this->load->view('components/footer');

    }

    public function create()
    {
        $headerData = array(
            'title' => 'Shortages',
            'page'=>'',
        );

        if(isset($_POST['insert_shortages']))
        {
            $shortages = [];
            for($i = 1; $i <= $_POST['counter']; $i++)
            {
                $quantity = $_POST['quantity_'.$i];
                $rate = $_POST['rate_'.$i];
                if($quantity != '' && $quantity > 0 && $rate != '')
                {
                    $date = $_POST['shrt_date_'.$i];
                    $shortage_type = $_POST['shortage_type_'.$i];

                    $shortage = [];
                    $shortage['quantity'] = $quantity;
                    $shortage['rate'] = $rate;
                    $shortage['date'] = $date;
                    $shortage['type'] = $shortage_type;
                    $shortage['trip_detail_id'] = $_POST['trip_detail_id_'.$i];

                    array_push($shortages, $shortage);
                }
            }

            if(sizeof($shortages) > 0)
            {
                $result = $this->db->insert_batch('shortages',$shortages);
                if($result == true)
                {
                    $this->helper_model->redirect_with_success('Shortages saved successfully.');
                }
                $this->helper_model->redirect_with_errors('System was unable to complete your request. please try again or contact your system provider.');
            }
            $this->helper_model->redirect_with_errors('No information was provided.');
        }

        $this->bodyData['someMessage'] = '';
        $this->bodyData['columns'] = array();
        $this->bodyData['trips'] = $this->shortages_model->get_trips();
        $this->bodyData['cities'] = $this->routes_model->cities();
        $this->bodyData['products'] = $this->products_model->get();
        $this->bodyData['tankers'] = $this->db->get('tankers')->result();

        $this->load->view('components/header', $headerData);
        $this->load->view('shortages/create', $this->bodyData);
        $this->load->view('components/footer');
    }

    public function edit($shortage_id)
    {
        $bodyData['shortage_id'] = $shortage_id;
        $bodyData['shortage'] = $this->shortages_model->find($shortage_id);
        $this->load->view('shortages/components/edit_shortage_form', $bodyData);
    }

    public function _need_to_save_something()
    {
        if(isset($_POST['update_shortage']))
        {
            if($this->form_validation->run('update_shortage') == true)
            {
                $updated_shortage = $this->shortages_model->update_shortage();
                if($updated_shortage != 0){
                    $this->helper_model->redirect_with_success('Shortage Updated Successfully!');
                }else{
                    $this->helper_model->redirect_with_errors('Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You');
                }
            }
            $this->helper_model->redirect_with_errors(validation_errors());
        }
    }

    public function _is_shortage_exist($shortage_id)
    {
        $this->db->select('shortages.id');
        $this->db->where('id',$shortage_id);
        $result = $this->db->get('shortages')->num_rows();
        if($result == 0)
        {
            $this->form_validation->set_message('_is_shortage_exist','Shortage# '.$shortage_id.' does not exist in the system.');
            return false;
        }
        return true;
    }

    public function _validate_shortage_ids_for_commiting($value)
    {
        $shortage_ids = explode('_',$value);
        $shortage_ids = arr_val_del(0, $shortage_ids);

        //checking if there is atleast one id selected
        if(sizeof($shortage_ids) == 0)
        {
            $this->form_validation->set_message('_validate_shortage_ids_for_commiting','Please select atleast one shortage.');
            return false;
        }

        //checking if the ids are all integers.
        foreach($shortage_ids as $id)
        {

            if(ctype_digit($id) == false)
            {
                $this->form_validation->set_message('_validate_shortage_ids_for_commiting','Invalid shortage ids.');
                return false;
            }
        }

        //checking if there is already a voucher of given shortage ids
        $this->db->select('shortage_id');
        $this->db->where_in('voucher_journal.shortage_id',$shortage_ids);
        $this->db->where('voucher_journal.active',1);
        $result = $this->db->get('voucher_journal')->result();
        if(sizeof($result) > 0)
        {
            $this->form_validation->set_message('_validate_shortage_ids_for_commiting','Some shortages ('.join(', ',property_to_array('shortage_id',$result)).') have already been commited. please try again.');
            return false;
        }

        //checking if shortage ids are there in the shortage table..
        $this->db->select('id');
        $this->db->distinct();
        $this->db->where_in('id',$shortage_ids);
        $result = $this->db->get('shortages')->result();
        if(sizeof($result) != sizeof($shortage_ids))
        {
            $this->form_validation->set_message('_validate_shortage_ids_for_commiting','Some shortages have been deleted from the system. please try again.');
            return false;
        }


        return true;
    }


}
