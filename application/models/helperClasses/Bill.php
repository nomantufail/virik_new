<?php
/**
 * Created by ZeenomLabs.
 * User: Noman Tufail
 * Date: 3/3/15
 * Time: 10:56 PM
 */

class Bill {

    public $id;
    public $date_time;
    public function __construct()
    {

    }
    public function get_date()
    {
        $date_time_parts = explode(' ', $this->date_time);
        $date = $date_time_parts[0];
        return $date;
    }
    public function get_time()
    {
        $date_time_parts = explode(' ', $this->date_time);
        $date = $date_time_parts[1];
        return $date;
    }

} 