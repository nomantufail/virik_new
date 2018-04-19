<?php
class Reports_model extends Parent_Model {

    public function __construct(){
        parent::__construct();
    }

    public function calculation_sheet_black_oil($keys)
    {
        include_once(APPPATH."models/helperClasses/Calculation_Sheet.php");

        $this->db->select("*");
        $this->db->where('product_type','black oil');
        $this->db->where(array(
            'entryDate >='=>$keys['from_date'],
            'entryDate <='=>$keys['to_date'],
        ));
        if($keys['company_id'] != 'all')
        {
            $this->db->where('company_id',$keys['company_id']);
        }
        if($keys['contractor_id'] != 'all')
        {
            $this->db->where('contractor_id',$keys['contractor_id']);
        }
        $result = $this->db->get('calculation_sheet_view')->result();
        $calculation_sheet_black_oil = array();
        foreach($result as $record)
        {
            array_push($calculation_sheet_black_oil, new Calculation_Sheet($record));
        }

        $grouped = Arrays::groupBy($calculation_sheet_black_oil, Functions::extractField('destination'), 'invoice_number');
        ksort($grouped);
        return $grouped;
    }


    public function calculation_sheet_white_oil($keys)
    {
        include_once(APPPATH."models/helperClasses/Calculation_Sheet.php");

        $this->db->select("*");
        $this->db->where('product_type','white oil');
        $this->db->where(array(
            'entryDate >='=>$keys['from_date'],
            'entryDate <='=>$keys['to_date'],
        ));
        if($keys['company_id'] != 'all')
        {
            $this->db->where('company_id',$keys['company_id']);
        }
        if($keys['contractor_id'] != 'all')
        {
            $this->db->where('contractor_id',$keys['contractor_id']);
        }
        $result = $this->db->get('calculation_sheet_view')->result();
        $calculation_sheet_white_oil = array();
        foreach($result as $record)
        {
            array_push($calculation_sheet_white_oil, new Calculation_Sheet($record));
        }

        $grouped = Arrays::groupBy($calculation_sheet_white_oil, Functions::extractField('destination'), 'invoice_number');
        ksort($grouped);
        return $grouped;
    }

    public function calculation_sheet_custom($keys)
    {
        include_once(APPPATH."models/helperClasses/Calculation_Sheet.php");

        $this->db->select("*");
        $this->db->where(array(
            'entryDate >='=>$keys['from_date'],
            'entryDate <='=>$keys['to_date'],
        ));
        if($keys['company_id'] != 'all')
        {
            $this->db->where('company_id',$keys['company_id']);
        }
        if($keys['contractor_id'] != 'all')
        {
            $this->db->where('contractor_id',$keys['contractor_id']);
        }
        if($keys['product'] != 'all' && $keys['product'] != '')
        {
            $this->db->where('product', $keys['product']);
        }
        if($keys['product_type'] != 'all' && $keys['product_type'] != '')
        {
            if($_GET['product_type'] == 'black oil')
            {
                $this->db->where('product_type','black oil');
            }
            if($_GET['product_type'] == 'white oil')
            {
                $this->db->where('product_type','white oil');
            }
        }
        $result = $this->db->get('calculation_sheet_view')->result();
        $calculation_sheet_white_oil = array();
        foreach($result as $record)
        {
            array_push($calculation_sheet_white_oil, new Calculation_Sheet($record));
        }

        $grouped = Arrays::groupBy($calculation_sheet_white_oil, Functions::extractField('destination'), 'invoice_number');
        ksort($grouped);
        return $grouped;

    }

    public function shortage_report($keys)
    {
        $report = array();
        $groups = $this->calculation_sheet_custom($keys);
        foreach($groups as $group)
        {
            foreach($group as $record)
            {
                array_push($report, $record);
            }
        }
        usort($report, array("Sorting_Model", "sort_shortage_report"));
        return $report;
    }

    public function freight_report()
    {
        $trip_type = (isset($_GET['trip_master_type']))?$_GET['trip_master_type']:'';
        $trip_type = ($trip_type == 'all')?'':$trip_type;
        $product_type = (isset($_GET['product_type']))?$_GET['product_type']:'';
        $product_type = ($product_type == 'all')?'':$product_type;
        $company = (isset($_GET['company']))?$_GET['company']:'';
        $company = ($company == 'all')?'':$company;
        $contractor = (isset($_GET['contractor']))?$_GET['contractor']:'';
        $contractor = ($contractor == 'all')?'':$contractor;

        $keys['product_type'] = $product_type;
        $keys['from'] = (isset($_GET['from_date']))?$_GET['from_date']:'';
        $keys['to'] = (isset($_GET['to_date']))?$_GET['to_date']:'';
        $keys['trip_id'] = (isset($_GET['trip_id']))?$_GET['trip_id']:'';
        $keys['trip_master_type'] = $trip_type;
        $keys['trip_master_types'] = '';
        $keys['company'] = $company;
        $keys['contractor'] = $contractor;
        $keys['trip_status'] = '2';
        $keys['account_title'] = '54';
        $keys['dr_cr'] = '0';

        /*****----- Faltu Keys -----******/
        $keys['tanker'] = (isset($_GET['tanker']))?$_GET['tanker']:'';
        $keys['entryDate'] = (isset($_GET['entry_date']))?$_GET['entry_date']:'';
        $keys['product'] = (isset($_GET['product']))?$_GET['product']:'';
        $keys['source'] = (isset($_GET['source']))?$_GET['source']:'';
        $keys['destination'] = (isset($_GET['destination']))?$_GET['destination']:'';
        $keys['trips_routes'] = (isset($_GET['trips_route']))?$_GET['trips_route']:'';
        $keys['cmp_freight_unit'] = (isset($_GET['company_freight_unit']))?$_GET['company_freight_unit']:'';
        $keys['wht'] = (isset($_GET['wht']))?$_GET['wht']:'';
        $keys['trip_type'] = (isset($_GET['trip_type']))?$_GET['trip_type']:'';
        $keys['company_commission'] = (isset($_GET['company_commission']))?$_GET['company_commission']:'';
        $keys['company_commission_status'] = (isset($_GET['company_commission_status']))?$_GET['company_commission_status']:'';
        $keys['contractor_freight_status'] = (isset($_GET['contractor_freight_status']))?$_GET['contractor_freight_status']:'';
        $keys['contractor_commission'] = (isset($_GET['contractor_commission']))?$_GET['contractor_commission']:'';
        $keys['contractor_commission_status'] = (isset($_GET['contractor_commission_status']))?$_GET['contractor_commission_status']:'';
        $keys['customer'] = (isset($_GET['customer']))?$_GET['customer']:'';
        $keys['cst_freight_unit'] = (isset($_GET['cst_freight_unit']))?$_GET['cst_freight_unit']:'';
        $keys['customer_freight_status'] = (isset($_GET['customer_freight_status']))?$_GET['customer_freight_status']:'';
        $keys['searched'] = (isset($_GET['searched']))?true:false;
        $keys['sort'] = (isset($_GET['sort_by']))?true:false;
        $keys['billed_from'] = (isset($_GET['billed_from']))?$_GET['billed_from']:'';
        $keys['billed_to'] = (isset($_GET['billed_to']))?$_GET['billed_to']:'';
        $keys['bill_status'] = (isset($_GET['bill_status']))?$_GET['bill_status']:'';

        $total_rows = $this->manageaccounts_model->count_searched_trips_accounts($keys);
        $report = $this->manageaccounts_model->search_trips_accounts($keys, $total_rows, 0);
        return $report;
    }

    public function  generate_customer_reports(){
        include_once(APPPATH."models/helperClasses/Customer_Report_Details.php");

        $contractor_id = $this->input->get('contractors');
        $customer_id = $this->input->get('customers');
        $tanker_id = $this->input->get('tankers');
        $tanker_column = "tanker_id";
        if($tanker_id == 'all'){
            $tanker_id = "0";
            $tanker_column = "tanker_id !=";
        }
        $from_date = new DateTime(easyDate($this->input->get('from_date')));
        $to_date = new DateTime(easyDate($this->input->get('to_date')));
        if($from_date < $to_date){
            $smaller_date = $from_date->format('Y-m-d');
            $bigger_date = $to_date->format('Y-m-d');
        }else{
            $smaller_date = $to_date->format('Y-m-d');
            $bigger_date = $from_date->format('Y-m-d');
        }

        $where = array(
            'contractor_id'=>$contractor_id,
            'customer_id'=>$customer_id,
            $tanker_column=>$tanker_id,
            'entryDate >='=>$smaller_date,
            'entryDate <='=>$bigger_date,
        );
        $this->db->select('trips.id as trip_id');
        $this->db->where($where);
        $this->db->from('trips');
        $this->db->where('trips.active',1);

        $trips = $this->db->get()->result();
        //print_r($trips);die();
        $reports = array();
        foreach($trips as $trip){
            array_push($reports, new Customer_Report_Details($this->trips_model->trip_details($trip->trip_id)));
        }
        return $reports;

    }

    public function  generate_company_reports(){
        $contractor_id = $this->input->get('contractors');
        $customer_id = $this->input->get('customers');
        $company_id = $this->input->get('companies');
        $tanker_id = $this->input->get('tankers');

        $from_date = new DateTime(easyDate($this->input->get('from_date')));
        $to_date = new DateTime(easyDate($this->input->get('to_date')));
        if($from_date < $to_date){
            $smaller_date = $from_date->format('Y-m-d');
            $bigger_date = $to_date->format('Y-m-d');
        }else{
            $smaller_date = $to_date->format('Y-m-d');
            $bigger_date = $from_date->format('Y-m-d');
        }

        if($tanker_id != 'all'){
            $this->db->where('trips.tanker_id',$tanker_id);
        }
        if($customer_id != 'all'){
            $this->db->where('tankers.customerId',$customer_id);
        }
        $this->db->where(array(
            'trips.contractor_id'=>$contractor_id,
            'trips.company_id'=>$company_id,
            'trips.entryDate >='=>$smaller_date,
            'trips.entryDate <='=>$bigger_date,
        ));
        $this->db->select('trips.id as trip_id');
        $this->db->from('trips');
        $this->db->where('trips.active',1);
        /*$this->db->join('trips_details','trips_details.trip_id = trips.id');
        $this->db->join('tankers', 'tankers.id = trips.tanker_id');
        $this->db->join('customers', 'customers.id = tankers.customerId');
        $this->db->join('companies', 'companies.id = trips.company_id');
        //$this->db->join('routes', 'routes.id = trips.route_id');
        $this->db->join('cities as source_city', 'source_city.id = trips_details.source');
        $this->db->join('cities as destin_city', 'destin_city.id = trips_details.destination');
        //$this->db->order_by("source_city.cityName","asc");
        //$this->db->order_by("trips_details.stn_number","asc");
        $this->db->order_by("trips_details.trip_id","asc");*/
        $this->db->order_by('trips.entryDate', 'asc');
        $reports = $this->db->get()->result();
        $reports_details = array();
        foreach($reports as $report)
        {
            array_push($reports_details, $this->trips_model->trip_details($report->trip_id));
        }
        usort($reports_details, array("Reports_Model", "cmp_company_report_route_stn"));

        return $reports_details;

    }

    public function cmp_trip_related_data_objects($obj1, $obj2)
    {
        $obj1_route = $obj1->sourceCity." to ".$obj1->destinationCity;
        $obj2_route = $obj2->sourceCity." to ".$obj2->destinationCity;

        $route_comparison = strcmp($obj1_route, $obj2_route);
        if($route_comparison == 0)
        {
            $obj1_stn = $obj1->stn_number;
            $obj2_stn = $obj2->stn_number;
            if(strlen($obj1_stn) == strlen($obj2_stn)){
                return strcmp($obj1_stn, $obj2_stn);
            }else{
                return ($obj1_stn > $obj2_stn)?1:-1;
            }
        }
        return $route_comparison;
    }

    public function cmp_company_report_route_stn($obj1, $obj2){
        usort($obj1->trip_related_details, array("Reports_Model", "cmp_trip_related_data_objects"));
        usort($obj2->trip_related_details, array("Reports_Model", "cmp_trip_related_data_objects"));
        $obj1_route = $obj1->trip_related_details[0]->sourceCity." to ".$obj1->trip_related_details[0]->destinationCity;
        $obj2_route = $obj2->trip_related_details[0]->sourceCity." to ".$obj2->trip_related_details[0]->destinationCity;

        $route_comparison = strcmp($obj1_route, $obj2_route);
        if($route_comparison == 0)
        {
            $obj1_stn = $obj1->trip_related_details[0]->stn_number;
            $obj2_stn = $obj2->trip_related_details[0]->stn_number;

            return strcmp($obj1_stn, $obj2_stn);
        }
        return $route_comparison;
    }

}

