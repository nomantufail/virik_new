<?php
/**
 * Created by PhpStorm.
 * User: zeeshan
 * Date: 10/28/14
 * Time: 2:50 AM
 */

class Helper {

    public $ci;

    function __construct(){
        $this->ci =& get_instance();
    }

    public function get_client_ip(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function mail($to, $sub, $msg, $from, $name){
        $this->ci->email->from($from, $name);
        $this->ci->email->to($to);

        $this->ci->email->subject($sub);
        $this->ci->email->message($msg);

        $this->ci->email->send();
    }

} 