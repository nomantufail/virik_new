<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/10/15
 * Time: 5:10 AM
 */

class Intelligent_Router_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

    }

    public function save_latest_route()
    {
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        if($method != 'index')
        {
            $data = array(
                'function'=>$method,
            );
            $this->db->where('controller',$controller);
            $this->db->update('router',$data);
        }

    }

    public function get_last_saved_route_for_current_controller()
    {
        $controller = $this->router->fetch_class();
        $this->db->select('function');
        $this->db->where('controller',$controller);
        $result = $this->db->get('router')->result();
        return $result[0]->function;
    }
} 