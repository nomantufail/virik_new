<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/parentController.php");
class Routes extends ParentController {

    //public variables...
    public $login;

    public function __construct()
    {
        parent::__construct();
    }

    /* The default function that gets called when visiting the page */
    public function index($route_type = 'primary')
    {
        /*
         *Some important variables that should be initialized first
         */
        $section = '';
        /******************************************************/
        /*****************************************************/

        $id = (isset($_GET['id']))?$_GET['id']:'';
        $source = (isset($_GET['source']))?$_GET['source']:'';
        $destination = (isset($_GET['destination']))?$_GET['destination']:'';
        $product = (isset($_GET['product']))?$_GET['product']:'';
        $freight = (isset($_GET['freight']))?$_GET['freight']:'';
        $keys = array(
            'id'=>$id,
            'source'=>$source,
            'destination'=>$destination,
            'product'=>$product,
            'freight'=>$freight,
            'route_type'=>$route_type,
        );
        //defining the sorting column
        $sort = array(
            'sort_by'=>(isset($_GET['sort_by']))?$_GET['sort_by']:'entryDate',
            'order' => (isset($_GET['order']))?$_GET['order']:'asc',
        );
        ///////////////////////////////////////////////////////////////

        //counting total agents
        $num_routes = $this->routes_model->count_searched_routes($keys);
        $num_routes = ($num_routes == 0)?1:$num_routes;
        $config = $this->helper_model->pagination_configs("routes/index/$route_type?", "routes", $num_routes);
        $this->pagination->initialize($config);

        $pageNumber = 0;
        if(isset($_GET['page'])){
            $pageNumber = $_GET['page'];
            if($pageNumber>=0){$pageNumber = $pageNumber;}else{ $pageNumber = 0;}
        }

        $headerData = array(
            'title' => 'Virik-Logistics | Routes',
            'page' => 'routes',
        );
        $bodyData = array(
            'routes' => '',
            'section' => '',
            'someMessage' => '',
            'pages' => $this->pagination->create_links(),
            'columns' => array(),
        );
        if($section == '' || $section == 'all'){$bodyData['section'] = 'all';}else if($section== 'add'){ $bodyData['section'] = 'add';}

        //deleting the route*****************//
        if(isset($_GET['del'])){
            $_POST['del'] = $_GET['del'];
            $this->form_validation->set_rules('del', 'Delete Route', 'required|numeric|callback__validate_route_deleting');
            if ($this->form_validation->run() == true)
            {
                if( $this->helper_model->safe_delete('routes',$_GET['del']) == true){
                    $bodyData['someMessage'] = array('message'=>'Route Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////
        //deleting the City*****************//
        if(isset($_GET['del_city'])){
            $bodyData['section'] = 'add';
            $_POST['del_city'] = $_GET['del_city'];
            $this->form_validation->set_rules('del_city', 'Delete City Id', 'required|numeric|callback__validate_city_deleting');
            if ($this->form_validation->run() == true)
            {
                if( $this->helper_model->delete_record('cities',$_GET['del_city']) == true){
                    $bodyData['someMessage'] = array('message'=>'City Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////

        //deleting the Product*****************//
        if(isset($_GET['del_product'])){
            $bodyData['section'] = 'add';
            $_POST['del_product'] = $_GET['del_product'];
            $this->form_validation->set_rules('del_product', 'Delete Product Id', 'required|numeric|callback__validate_product_deleting');
            if ($this->form_validation->run() == true)
            {
                if( $this->helper_model->delete_record('products',$_GET['del_product']) == true){
                    $bodyData['someMessage'] = array('message'=>'Product Deleted Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //////////////////////////////////////////////////////////

        //adding a new route
        if(isset($_POST['addRoute'])){
            $bodyData['section'] = 'add';
            if($this->form_validation->run('add_route') == true){
                if( $this->routes_model->add_route() == true){
                    $bodyData['someMessage'] = array('message'=>'Route Added Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //adding a new route
        if(isset($_POST['addCity'])){
            $bodyData['section'] = 'add';
            if($this->form_validation->run('addCity') == true){
                if( $this->routes_model->add_city() == true){
                    $bodyData['someMessage'] = array('message'=>'City Added Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //adding a new route
        if(isset($_POST['addProduct'])){
            $bodyData['section'] = 'add';
            if($this->form_validation->run('addProduct') == true){
                if( $this->routes_model->add_product() == true){
                    $bodyData['someMessage'] = array('message'=>'Product Added Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        //saving the edited route
        if(isset($_POST['save_route'])){
            if($this->form_validation->run('save_route') == true){
                if( $this->routes_model->save_route() == true){
                    $bodyData['someMessage'] = array('message'=>'Route saved Successfully!', 'type'=>'alert-success');
                }else{
                    $bodyData['someMessage'] = array('message'=>'Some Unknown database fault happened. please try again a few moments later. Or you can contact your system provider.<br>Thank You', 'type'=>'alert-warning');
                }
            }
        }
        $bodyData['cities']=$this->routes_model->cities();
        $bodyData['products']=$this->routes_model->products();
        $bodyData['routes'] = $this->routes_model->search_limited_routes($config["per_page"], $pageNumber, $keys, $sort);
        $bodyData['route_type'] = $route_type;

        if(isset($_GET['print'])){
            if(isset($_POST['check'])){
                $bodyData['routes'] = $this->helper_model->filter_records($bodyData['routes'], $_POST['check'],"id");
            }
            if(isset($_POST['column'])){
                $bodyData['columns'] = $_POST['column'];
            }
            $this->load->view('routes/components/print_routes', $bodyData);
        }else{
            $this->load->view('components/header', $headerData);
            $this->load->view('routes/welcome', $bodyData);
            $this->load->view('components/footer');
        }
    }

    public function save_route(){

    }

    public function edit_route($route_id){
        $bodyData = array(
            'route' => $this->routes_model->route($route_id),
            'form_id'=>($this->helper_model->last_id('customer_accounts')+1),
            'route_id'=>$route_id,
            'cities' => $this->routes_model->cities(),
            'products' => $this->routes_model->products(),

        );
        $this->load->view('routes/components/edit_route', $bodyData);
    }

    //this function might be called by client with an ajax request...
    public function freight($source_id, $destination_id, $product_id, $date='', $route_type='1'){

        /*fething freight for a given duration*/
        $this->db->select('freights.freight, routes.id as route_id, freights.startDate, freights.endDate');
        $this->db->order_by("freights.id","desc");
        $this->db->from('routes');
        $this->db->join('freights', 'freights.route_id = routes.id');
        $this->db->where(array(
            'routes.source'=>$source_id,
            'routes.destination'=>$destination_id,
            'routes.product'=>$product_id,
            'freights.startDate <=' => ($date == '')?date('Y-m-d'):$date,
            'freights.endDate >=' => ($date == '')?date('Y-m-d'):$date,
            'routes.type'=>$route_type,
        ));
        $result = $this->db->get()->result();

        /*fetching latest freight if not given*/
        if($result == null)
        {
            $this->db->select('freights.freight, routes.id as route_id, freights.startDate, freights.endDate');
            $this->db->order_by("freights.id","desc");
            $this->db->from('routes');
            $this->db->join('freights', 'freights.route_id = routes.id');
            $this->db->where(array(
                'routes.source'=>$source_id,
                'routes.destination'=>$destination_id,
                'routes.product'=>$product_id,
                'routes.type'=>$route_type,
            ));
            $result = $this->db->get()->result();
        }
        $freight = (!$result)?null :$result[0]->freight ;
        $arr = array('freight'=>$freight);
        echo json_encode($arr);

    }

    //this function might be called by client with an ajax request...
    public function source_destination($route_id){

        $route = $this->routes_model->route($route_id, 'simple');
        echo json_encode($route);

    }

    //this function might be called by client with an ajax request...
    public function freight_for_editing_routes($route_id, $from, $to){
       $frights = $this->routes_model->fetch_freight_history($route_id, $from, $to);
        $freight = '';
        if(sizeof($frights) >= 1){
            $freight = $frights[0]->freight;
        }

        $arr = array('freight'=>$freight);
        echo json_encode($arr);

    }

    public function show_freight_history($route_id, $from, $to){
        $history = $this->routes_model->fetch_freight_history($route_id, $from, $to);
        echo "<table style='width: 100%;'>";
        echo "<tr>";
        echo "<th>Start Date</th><th>End Date</th><th>Freight</th>";
        echo "</tr>";
        foreach($history as $record){
            echo "<tr style='border-bottom: 1px solid lightgray;'>";
            echo "<td>".Carbon::createFromFormat('Y-m-d',$record->startDate)->toFormattedDateString()."</td><td>".Carbon::createFromFormat('Y-m-d',$record->endDate)->toFormattedDateString()."</td><td>".$record->freight."</td>";
            echo "</tr>";
        }
        echo "</table>";
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

    function _validate_route_deleting($route_id){
        $used_in = '';
        $route = $this->routes_model->route($route_id);
        if($route == null){
            return true;
        }
        $this->db->from('trips');
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $this->db->where('trips.active',1);
        $this->db->where(array(
            'trips_details.source'=>$route->sourceId,
            'trips_details.destination'=>$route->destinationId,
            'trips_details.product'=>$route->productId,
        ));
        $trips = $this->db->get()->num_rows();
        if($trips >=1){
            $used_in = ' Trips ';
        }

        if($used_in != ''){
            $this->form_validation->set_message('_validate_route_deleting','This Route is being used in the other parts of the system! e.g('.$used_in.').');
            return false;
        }
        return true;
    }

    function _validate_city_deleting($city_id){
        $used_in = '';
        $this->db->from('trips');
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $this->db->where('trips.active',1);
        $where = "(trips_details.source = ".$city_id." OR trips_details.destination = ".$city_id.")";
        $this->db->where($where);
        $trips = $this->db->get()->num_rows();
        if($trips >=1){
            $used_in = ' Trips, ';
        }

        $this->db->select("*");
        $this->db->where('routes.active',1);
        $where = "(routes.source = ".$city_id." OR routes.destination = ".$city_id.")";
        $this->db->where($where);
        $routes = $this->db->get('routes')->num_rows();
        if($routes >=1){
            $used_in = ' Routes ';
        }

        if($used_in != ''){
            $this->form_validation->set_message('_validate_city_deleting','This City is being used in the other parts of the system! e.g('.$used_in.').');
            return false;
        }
        return true;
    }

    function _validate_product_deleting($product_id){
        $used_in = '';
        $this->db->from('trips');
        $this->db->join('trips_details', 'trips_details.trip_id = trips.id','left');
        $this->db->where('trips.active',1);
        $this->db->where('trips_details.product',$product_id);
        $trips = $this->db->get()->num_rows();
        if($trips >=1){
            $used_in = ' Trips, ';
        }

        $this->db->select("*");
        $this->db->where('routes.active',1);
        $this->db->where('routes.product',$product_id);
        $routes = $this->db->get('routes')->num_rows();
        if($routes >=1){
            $used_in = ' Routes ';
        }

        if($used_in != ''){
            $this->form_validation->set_message('_validate_product_deleting','This Product is being used in the other parts of the system! e.g('.$used_in.').');
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

    function pagination_configs(){
        $config["base_url"] = base_url() . "routes/index/?";
        $config["total_rows"] = $this->helper_model->rows_in('routes');
        $config["per_page"] = 30;
        $config["uri_segment"] = 2;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = "<nav><ul class='pagination'>";
        $config['full_tag_close'] = "</ul></nav>";
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_link'] = '<span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        return $config;
    }
    function _unique_route(){
        if($this->routes_model->_unique_route($this->input->post('source'), $this->input->post('destination'), $this->input->post('product'), $this->input->post('route_type')) == false){
            $this->form_validation->set_message('_unique_route','Same Route cannot be added twice.');
            return false;
        }
        return true;
    }

    function _unique_edited_route(){
        if($this->routes_model->_unique_edited_route($this->input->post('source'), $this->input->post('destination'), $this->input->post('product'), $this->input->post('route_type')) == false){
            $this->form_validation->set_message('_unique_edited_route','Same Route cannot be added twice.');
            return false;
        }
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */