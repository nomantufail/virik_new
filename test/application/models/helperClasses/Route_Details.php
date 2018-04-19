<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Route_Details {

    public $id;
    public $type;
    public $source;
    public $destination;
    public $product;
    public $sourceId;
    public $destinationId;
    public $productId;
    public $freight;
    public $is_freight_active;
    public $startDate;
    public $endDate;
    public $formatted_startDate;
    public $formatted_endDate;
    public $ci;

    function Route_Details($route){
        $this->ci =& get_instance();

        //setting default values
        $this->freight = '';
        $this->is_freight_active = null;
        $this->set_data($route);

    }

    private function  set_data($route){
        $this->type = $route->type;
        $this->id = $route->id;
        $this->sourceId = $route->source;
        $this->destinationId = $route->destination;
        $this->productId = $route->product;
        $product = $this->ci->routes_model->product($route->product);
        $this->product = $product->productName;
        $source = $this->ci->routes_model->city($route->source);
        $this->source = $source->cityName;
        $destination = $this->ci->routes_model->city($route->destination);
        $this->destination = $destination->cityName;

        $this->ci->db->order_by('id','desc');
        $this->ci->db->where(array(
            'route_id' =>$route->id,
            /*'freights.startDate <=' => date('Y-m-d'),
            'freights.endDate >=' => date('Y-m-d'),*/
        ));
        $this->ci->db->limit(1);
        $freights = $this->ci->db->get('freights')->result();
        //var_dump($freights);
        if($freights){
            $freight = $freights[0];
            $this->freight = $freight->freight;
            $this->startDate = $freight->startDate;
            $this->endDate = $freight->endDate;

            /*---checking is freight active----*/
            $start = Carbon::createFromFormat('Y-m-d',$freight->startDate);
            $end = Carbon::createFromFormat('Y-m-d',$freight->endDate);
            $now = Carbon::createFromFormat('Y-m-d',date('Y-m-d'));
            $this->is_freight_active = $now->between($start, $end);


            $this->freight_id = ($freight != null)?$freight->id:null;

            $this->formatted_startDate = ($freight != null)?$this->ci->carbon->createFromFormat('Y-m-d',$freight->startDate)->toFormattedDateString():null;
            $this->formatted_endDate = ($freight != null)?$this->ci->carbon->createFromFormat('Y-m-d',$freight->endDate)->toFormattedDateString():null;

        }else{
            $this->freight = null;
            $this->startDate = null;
            $this->endDate = null;
            $this->formatted_endDate = null;
            $this->formatted_startDate = null;
            $this->freight_id = null;
        }

    }

}