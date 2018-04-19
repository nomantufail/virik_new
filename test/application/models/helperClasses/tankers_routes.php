<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/2/15
 * Time: 2:12 AM
 */

class Tankers_Routes {

    public $tankers_routes;

    public function __construct($tankers_routes)
    {
        $this->tankers_routes = $tankers_routes;
    }

    public function tanker_routes($tanker_id)
    {
        $tanker_routes = array();
        foreach($this->tankers_routes as $record)
        {
            if($record->tanker_id == $tanker_id)
            {
                $route_data = array(
                    'source'=>$record->source,
                    'destination'=>$record->destination,
                    'counter'=>$record->route_counter,
                );
                array_push($tanker_routes, $route_data);
            }
        }
        return $tanker_routes;
    }

} 