<?php
class Routes_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function routes($detail = 'all', $type='all'){
        include_once(APPPATH."models/helperClasses/Route_Details.php");

        $this->db->order_by("entryDate", "desc");
        $this->db->where('routes.active',1);

        switch($type)
        {
            case "1":
                $this->db->where('type',1);
                break;
            case "2":
                $this->db->where('type',2);
                break;
            case "3":
                $this->db->where('type',3);
                break;
            case "4":
                $this->db->where('type',4);
                break;
        }
        $results = $this->db->get('routes')->result();

        if($detail == 'all'){
            $cities = group_objects_by($this->routes_model->cities(), 'id');
            $products = group_objects_by($this->routes_model->products(), 'id');
            $routes = array();
            foreach($results as $result){
                array_push($routes,new Route_Details($result, $cities, $products));
            }
        }else{
            $routes = $results;
        }
        return $routes;
    }

    public function cities(){
        return $this->db->get('cities')->result();
    }
    public function city($id){
        $result = $this->db->get_where('cities',array('id'=>$id))->result();
        return $result[0];
    }
    public function cities_by_ids($ids)
    {
        $this->db->select('cityName');
        $this->db->where_in('id',$ids);
        $result = $this->db->get("cities")->result();
        return $result;
    }
    public function products_by_ids($ids)
    {
        $this->db->select('productName');
        $this->db->where_in('id',$ids);
        $result = $this->db->get("products")->result();
        return $result;
    }
    public function products(){
        return $this->db->get('products')->result();
    }
    public function product($id){
        $result = $this->db->get_where('products',array('id'=>$id))->result();
        return $result[0];
    }

    public function trips_routes()
    {
        /*
            source_cities.cityName as source_city, trips_details.source as source_id,
            destination_cities.cityName as destination_city, trips_details.destination as destination_id,
         */
        $this->db->select("
            source_cities.cityName as source_city, trips_details.source as source_id,
            destination_cities.cityName as destination_city, trips_details.destination as destination_id,
            ");
        $this->db->from("trips");
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('cities as source_cities','source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities','destination_cities.id = trips_details.destination','left');
        $this->db->where(array(
            'trips.active'=>1,
        ));
        $this->db->distinct();
        $this->db->order_by("source_cities.cityName, destination_cities.cityName");
        $result = $this->db->get()->result();
        return $result;
    }
    public function trips_routes_by_ids($ids)
    {
        $this->db->select("source_cities.cityName as source_city, trips_details.source as source_id,
                            destination_cities.cityName as destination_city, trips_details.destination as destination_id,");
        $this->db->from("trips");
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('cities as source_cities','source_cities.id = trips_details.source','left');
        $this->db->join('cities as destination_cities','destination_cities.id = trips_details.destination','left');
        $this->db->where(array(
            'trips.active'=>1,
        ));
        $where = "(";
        foreach($ids as $route)
        {
            $route_parts = explode('_',$route);
            $where.="(trips_details.source = ".$route_parts[0]." AND trips_details.destination = ".$route_parts[1].") OR ";
        }
        $where.=")";
        $where_parts = explode(') OR )',$where);
        $where = $where_parts[0];
        $where.="))";
        $this->db->where($where);
        $this->db->distinct();
        $this->db->order_by("source_cities.cityName, destination_cities.cityName");
        $result = $this->db->get()->result();
        return $result;
    }
    public function freight($where){
        $result = $this->db->get_where('freights', $where)->result();
        if($result){
            return $result[0];
        }else{
            return null;
        }
    }
    public function all_routes_freights($route_type = 'primary') {
        include_once(APPPATH."models/helperClasses/Route_Details.php");


        switch($route_type)
        {
            case 'primary':
                $this->db->where('routes.type', 1);
                break;
            case 'secondary':
                $this->db->where('routes.type', 3);
                break;
            case 'secondary_local':
                $this->db->where('routes.type', 4);
                break;
            default:
                $this->db->where('routes.type', 1);
                break;
        }

        $this->db->select('routes.id as route_id, freights.id as freight_id,
                freights.startDate, freights.endDate, freights.freight,
                routes.source, routes.destination, routes.product,
        ');
        $this->db->from('routes');
        $this->db->join('freights','freights.route_id = routes.id','left');
        $this->db->where('routes.active',1);
        /*$this->db->join('cities as sourceCity','sourceCity.id = routes.source','left');
        $this->db->join('cities as destinationCity','destinationCity.id = routes.destination','left');
        $this->db->join('products','products.id = routes.product','left');*/

        $this->db->order_by('freights.id','desc');
        $results = $this->db->get()->result();
        foreach($results as &$record)
        {
            $record->route = $record->source."_".$record->destination."_".$record->product;
        }
        $grouped = Arrays::groupBy($results, Functions::extractField('route'));


        return $grouped;
    }
    public function search_limited_routes($limit, $start,$keys, $sort) {
        include_once(APPPATH."models/helperClasses/Route_Details.php");

        if($keys['id'] != ''){
            $this->db->where('routes.id', $keys['id']);
        }
        if($keys['source'] != ''){
            $this->db->where('routes.source', $keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('routes.destination', $keys['destination']);
        }
        if($keys['product'] != ''){
            $this->db->where('routes.product', $keys['product']);
        }
        switch($keys['route_type'])
        {
            case 'primary':
                $this->db->where('routes.type', 1);
                break;
            case 'secondary':
                $this->db->where('routes.type', 3);
                break;
            case 'secondary_local':
                $this->db->where('routes.type', 4);
                break;
            default:
                $this->db->where('routes.type', 1);
                break;
        }

        if($sort['sort_by'] != 'freight'){
            $this->db->order_by($sort['sort_by'], $sort['order']);
        }
        if($keys['freight'] == ''){
            $this->db->limit($limit, $start);
        }
        $this->db->select('routes.*');
        $this->db->from('routes');
        $this->db->where('routes.active',1);
        $this->db->join('cities as sourceCity','sourceCity.id = routes.source','inner');
        $this->db->join('cities as destinationCity','destinationCity.id = routes.destination','inner');
        $this->db->join('products','products.id = routes.product','inner');
        $results = $this->db->get()->result();

        $routes = array();
        foreach($results as $result){
            array_push($routes,new Route_Details($result));
        }
        if($keys['freight'] != ''){
            $searched_routes = array();
            foreach($routes as $route){
                if($route->freight == $keys['freight']){
                    array_push($searched_routes, $route);
                }
            }
            $routes = $searched_routes;
        }
        if($sort['sort_by'] == 'freight'){
            usort($routes, array("Sorting_Model", "sort_routes"));
            $start = ($start == '')?0:$start;
            $routes = array_slice($routes, $start,($limit));
        }

        return $routes;
    }
    public function count_searched_routes($keys) {
        include_once(APPPATH."models/helperClasses/Route_Details.php");

        if($keys['id'] != ''){
            $this->db->where('routes.id', $keys['id']);
        }
        if($keys['source'] != ''){
            $this->db->where('routes.source', $keys['source']);
        }
        if($keys['destination'] != ''){
            $this->db->where('routes.destination', $keys['destination']);
        }
        if($keys['product'] != ''){
            $this->db->where('routes.product', $keys['product']);
        }

        switch($keys['route_type'])
        {
            case 'primary':
                $this->db->where('routes.type', 1);
                break;
            case 'secondary':
                $this->db->where('routes.type', 3);
                break;
            case 'secondary_local':
                $this->db->where('routes.type', 4);
                break;
            default:
                $this->db->where('routes.type', 1);
                break;
        }

        $this->db->select('routes.id as id, routes.source as source, routes.destination as destination, routes.entryDate as entryDate, routes.product as product, freights.freight as freight, routes.type as type, routes.active as active');
        $this->db->from('routes');
        $this->db->join('freights','routes.id = freights.route_id','left');
        $this->db->where('routes.active',1);
        $this->db->where('`freights`.`id` = (SELECT `id` from `freights` where `route_id` = routes.id ORDER by `id` DESC LIMIT 1)', NULL, FALSE);
        
        if($keys['freight'] != ''){
           $this->db->where('freights.freight',$keys['freight']);
        }
        $results = $this->db->get()->result();
        // $routes = array();
        // foreach($results as $result){
        //     array_push($routes,new Route_Details($result));
        // }
        // if($keys['freight'] != ''){
        //     $searched_routes = array();
        //     foreach($routes as $route){
        //         if($route->freight == $keys['freight']){
        //             array_push($searched_routes, $route);
        //         }
        //     }
        //     $routes = $searched_routes;
        // }

        return sizeof($results);
    }

    public function route($id, $detail='all'){
        include_once(APPPATH."models/helperClasses/Route_Details.php");
        $result = $this->db->get_where('routes', array('id'=>$id))->result();
        if($result){
            if($detail == 'all'){
                $route = new Route_Details($result[0]);
            }else if($detail == 'simple'){
                $route = $result[0];
            }
            return $route;
        }else{
            return null;
        }
    }

    public function add_route(){
        $data_routes = array(
            'source'=>$this->input->post('source'),
            'destination'=>$this->input->post('destination'),
            'product'=>$this->input->post('product'),
            'type'=>$this->input->post('route_type'),
            'entryDate' => $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateTimeString(),
        );
        $result = $this->db->insert('routes', $data_routes);
        if($result == true){
            $data_freights = array(
                'route_id'=>mysql_insert_id(),
                'freight'=>$this->input->post('freight'),
                'startDate'=>easyDate($this->input->post('from')),
                'endDate'=>easyDate($this->input->post('to')),
            );
            $result = $this->db->insert('freights', $data_freights);
            if($result == true){
                return true;
            }
        }
        return false;
    }

    public function add_city(){
        $data = array(
            'cityName'=>$this->input->post('cityName'),
        );
        $result = $this->db->insert('cities', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function add_product(){
        $data = array(
            'productName'=>$this->input->post('productName'),
            'type'=>$this->input->post('product_type'),
        );
        $result = $this->db->insert('products', $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

    public function save_route(){
        $route_id = $this->input->post('route_id');
        $freight = $this->input->post('freight');
        $source = $this->input->post('source');
        $destination = $this->input->post('destination');
        $product = $this->input->post('product');
        $startDate = easyDate($this->input->post('from'));
        $endDate = easyDate($this->input->post('to'));
        $previous_freight = $this->input->post('previous_freight');
        $previous_from = $this->input->post('previous_from');
        $previous_to = $this->input->post('previous_to');
        $freight_id = $this->input->post('freight_id');

        $freight_data = array(
            'route_id'=>$route_id,
            'freight'=>$freight,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
        );


        $route_data = array(
            'source'=>$source,
            'destination'=>$destination,
            'product'=>$product,
        );
        $this->db->where('id',$route_id);
        if($this->db->update('routes', $route_data) == true){
            $this->db->where(array('id'=>$freight_id,));
            if($this->db->insert('freights',$freight_data) == true){
                $this->db->select("trips.id as trip_id");
                $this->db->distinct();
                $this->db->from('trips');
                $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
                $this->db->where(array(
                    'trips_details.source'=>$source,
                    'trips_details.destination'=>$destination,
                    'trips_details.product'=>$product,
                    'trips.filling_date >='=>$startDate,
                    'trips.filling_date <='=>$endDate,
                ));
                $trips = $this->db->get()->result();
                $trip_ids = array();
                if(sizeof($trips) > 0){
                    foreach($trips as $trip){
                        array_push($trip_ids, $trip->trip_id);
                    }

                    /*
                     * --------------------------------------------
                     *  Checking if the freight is used in trips
                     *  with mass payments.
                     * --------------------------------------------
                     */
                        $this->db->select('trips_details.id as detail_id');
                        $this->db->where_in('trips_details.trip_id',$trip_ids);
                        $raw_trip_detail_ids = $this->db->get('trips_details')->result();
                        $trip_detail_ids = array();
                        foreach($raw_trip_detail_ids as $detail)
                        {
                            array_push($trip_detail_ids, $detail->detail_id);
                        }

                        $this->db->select('trip_detail_voucher_relation.voucher_id');
                        $this->db->from('trip_detail_voucher_relation');
                        $this->db->join('voucher_journal','voucher_journal.id = trip_detail_voucher_relation.voucher_id','left');
                        $this->db->where_in('trip_detail_voucher_relation.trip_detail_id',$trip_detail_ids);
                        $this->db->where(array(
                            'voucher_journal.active'=>1,
                            'voucher_journal.auto_generated'=>0,
                            'voucher_journal.transaction_column !='=>'',
                        ));
                        $mass_payament_trips = $this->db->get()->num_rows();
                     /*-----------------------------------------------*/

                    /*
                     *  ----------------------------------------
                     *        CHECK TRIPS MASS PAYMENTS
                     *  ----------------------------------------
                     *  if there are trips with mass payment
                     *  happened on them with the current
                     *  updating route, then dont update
                     *  company freight per unit in the trips
                     *  table.
                     *
                    \* -----------------------------------------*/
                        if($mass_payament_trips > 0){
                            return true;
                        }else{
                            $trips_data = array(
                                'trips_details.company_freight_unit'=>$freight,
                            );

                            $this->db->where_in('trips_details.trip_id',$trip_ids);
                            if($this->db->update('trips_details', $trips_data) == true){
                                return true;
                            }
                        }
                     /*~~~~~~~~~~~~~~ CHECKING ENDS ~~~~~~~~~~~~~*/
                }else{
                    return true;
                }
            }
        }
        /*if($freight == $previous_freight){
            $data = array(
                'source'=>$source,
                'destination'=>$destination,
                'product'=>$product,
            );
            $this->db->where('id',$route_id);
            if($this->db->update('routes', $data) == true){
                $this->db->where(array('id'=>$freight_id,));
                if($this->db->update('freights',$freight_data) == true)
                return true;
            }
        }else{
            $routes_data = array(
                'source'=>$source,
                'destination'=>$destination,
                'product'=>$product,
            );
            $this->db->where('id',$route_id);
            $this->db->update('routes', $routes_data);
            $this->db->insert('freights', $freight_data);
            return true;
        }*/
        return false;
    }

    public function _unique_route($source, $destination, $product, $type){
        $records = $this->db->get_where('routes', array(
            'source' => $source,
            'destination' => $destination,
            'product' => $product,
            'type' => $type,
            'active'=>1,
        ))->num_rows();

        if($records >= 1){
            return false;
        }else{
            return true;
        }
    }

    public function _unique_edited_route($source, $destination, $product, $type){
        $query = $this->db->get_where('routes', array(
            'source' => $source,
            'destination' => $destination,
            'product' => $product,
            'type' => $type,
            'active' => 1,
        ));
        $record = $query->result();
        if($query->num_rows()>=1 && $record[0]->id == $this->input->post('route_id')){
            return true;
        }else if($query->num_rows() < 1){
            return true;
        }else{
            return false;
        }
    }

    public function fetch_freight_history($route_id, $startDate, $endDate){
        $this->db->select("freights.freight, freights.startDate, freights.endDate");
        $this->db->order_by('freights.id','desc');
        $freight_history = $this->db->get_where('freights', array(
            'route_id'=>$route_id,
            'freights.startDate <='=>$endDate,
            'freights.endDate >=' =>$startDate,
        ))->result();

        return $freight_history;
    }

}