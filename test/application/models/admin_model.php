<?php
class Admin_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    function admins()
    {
       $this->db->select("*");
        return $this->db->get('admin')->result();

    }
    function login($userName){
        @session_start();
        $_SESSION['admin']=$userName;
    }

    function loggedIn(){
        @session_start();
        if(isset($_SESSION["admin"])){
            return true;
        }else{
            return false;
        }
    }


    function check_credentials($table, $userName, $password){
       /* list($uField, $userName)=explode('.', $userName);
        list($pField, $password)=explode('.', $password);
        $query = $this->db->get_where($table, array($uField => $userName, $pField => $password, ));
        if($query->num_rows() >= 1){
            return true;
        }else{
            return false;
        }*/
    }

    function logout(){
        /*@session_start();
        unset($_SESSION["admin"]);
        session_destroy();
        header('Location ?req=home');*/
    }
}