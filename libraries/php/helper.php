<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 9/16/14
 * Time: 3:38 AM
 */

function group_objects_by($objects, $property)
{
    return Arrays::groupBy($objects, Functions::extractField($property));
}

function first_array_key($array)
{
    reset($array);
    return key($array);
}
function form_id()
{
    return md5(time());
}
function arr_val_del($value, $array)
{
    while(($key = array_search($value, $array)) != false)
    {
        unset($array[$key]);
    }
    return $array;
}
function print_form()
{
    echo '<form name="selection_form" id="selection_form" method="post"';
    echo ' action="';
    if(strpos(page_url(),'?') == false){
        echo page_url()."?";
    }else{echo page_url()."&";}
    echo 'print"></form>';
}

function explode_camelCase($str)
{
    $input = $str;
    $pass1 = preg_replace("/([a-z])([A-Z])/","\\1 \\2",$input);
    $pass2 = preg_replace("/([A-Z])([A-Z][a-z])/","\\1 \\2",$pass1);
    return $pass2;
}

function td($value='')
{
    echo"<td>";
    echo $value;
    echo "</td>";
}

function property_to_array($property, $objects)
{
    $temp_array = array();
    foreach($objects as $obj)
    {
        array_push($temp_array,$obj->$property);
    }
    return $temp_array;
}
function find_in_objects_by_key_value($key, $value, $objects)
{
    $item = null;
    foreach($objects as $struct) {
        if ($value == $struct->$key) {
            $item = $struct;
            break;
        }
    }
    return $item;
}
function in_objects($key,$value,$objects)
{
    foreach($objects as $object)
    {
        if($object->$key == $value){
            return true;
        }
    }
    return false;
}

function in_objects_m($key_value,$objects)
{
    if(sizeof($objects) == 0)
        return false;
    foreach($objects as $object)
    {
        $matched = true;
        foreach($key_value as $key => $value){
            if($object->$key != $value){
                $matched = false;
            }
        }
        if($matched == true){
            return true;
        }
    }
    return false;
}

function is_date_btw($key, $start_date, $end_date)
{
    $key = Carbon::createFromFormat('Y-m-d', $key);
    $start_date = Carbon::createFromFormat('Y-m-d', $start_date);
    $end_date = Carbon::createFromFormat('Y-m-d',$end_date);
    if($key->gte($start_date) && $key->lte($end_date))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function rupee_format($num)
{
    $num = round($num, 3);
    $sign = "";
    if($num < 0){ $sign = "-"; $num *= -1;}
    $numParts = explode('.', $num);
    $num = $numParts[0];
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    //attaching the fractional part
    $thecash = (sizeof($numParts)>1)?$thecash.".".$numParts[1]:$thecash;
    return $sign."".$thecash; // writes the final format where $currency is the currency symbol.
}

function exporting_file_name($file_name)
{
    return $file_name;
}

function bigger_date($from, $to){
    $from_date = new DateTime($from);
    $to_date = new DateTime($to);
    if($from_date > $to_date){
        return true;
    }
    return false;

}

function font_size()
{

    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "myDB";

// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, firstname, lastname FROM MyGuests";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();

}

function unset_query_string_var($varname,$query_string) {
    $query_array = array();
    parse_str($query_string,$query_array);
    unset($query_array[$varname]);
    $query_string = http_build_query($query_array);
    return $query_string;
}

function include_editable_libs()
{

    $libs = '<link href="'.css()."editable.css".'" rel="stylesheet">';
    $libs .= '<script src="'.js()."bootstrap-editable.min.js".'"></script>';
    if($_SESSION['role'] == 1)
    {
        //echo $libs;
    }
    else
    {
        /*---------------------------------------*
         *In this section we will see if
         * we have to give some permissions to
         * un authorized members or not...
         *-----------------------------------------
         */

        $ci = CI_Controller::get_instance();
        if($ci->router->fetch_class() == 'routes')
        {
            //echo $libs;
        }

    }
}


function datecmp($date_1, $date_2){
    $from_date = new DateTime($date_1);
    $to_date = new DateTime($date_2);
    if($from_date > $to_date){
        return 1;
    }else if($from_date < $to_date){
        return -1;
    }
    return 0;
}
function easyDate($str){
    $originalDate = $str;
    $newDate = date("Y-m-d", strtotime($originalDate));
    return $newDate;
}
function images(){
    $path = base_url()."images/";
    return $path;
}
function css(){
    $path = base_url()."libraries/css/";
    return $path;
}
function js(){
    $path = base_url()."libraries/js/";
    return $path;
}
function fonts(){
    $path = base_url()."fonts/";
    return $path;
}

function timeDifference($time1, $time2){
    $date1 = new DateTime($time1);
    $date2 = new DateTime($time2);

// The diff-methods returns a new DateInterval-object...
    $diff = $date2->diff($date1);

// Call the format method on the DateInterval-object
    $d = array(
        'days' => $diff->format('%a'),
        'hours' => $diff->format('%h'),
    );
    /*echo $diff->format('%a_%h');*/
    return $d;
}
function login($majboor, $userName){
    @session_start();
    $_SESSION["user_id"] = $majboor->id;
    $_SESSION["user_name"] = $majboor->userName;
    $majboor->sessionId = session_id();
    $majboor->activeAds = 0;
    $majboor->save();
}

function loggedIn(){
    @session_start();
    if(isset($_SESSION["user_id"])){
        $majboor = ORM::for_table('majboor')->where(array(
            'userName'=>$_SESSION["user_name"],
            'sessionId' => session_id(),
        ))->find_one();
        if(!$majboor){
            return 2;   //multiple browser login hai
        }else if(banned($_SESSION["user_id"]) == true){
            logout();
            return 0;   //banned
        }else{
            return 1;   //login hai
        }
    }else{
        return 0;       //login nahi
    }
}

function logout(){
    @session_start();
    $majboor = ORM::for_table('majboor')->where(array(
        'userName'=>$_SESSION["user_name"],
        'sessionId'=>session_id(),
    ))->find_one();
    if($majboor){
        $majboor->sessionId = "-1";
        $majboor->save();
    }
    unset($_SESSION["user_id"]);
    unset($_SESSION["user_name"]);
    header('Location: ?req=home');
}

function location(){
    //header("Location: ");
}


function get_class_name($object)
{
    return basename(str_replace('\\', '/', get_class($object)));
}


function sorting_info($columnName)
{

    //just testing

    $sort_by = (isset($_GET['sort_by']))?$_GET['sort_by']:'';
    $order = (isset($_GET['order']))?$_GET['order']:'';

    if($sort_by == $columnName && $order == 'asc')
    {
        $order = 'desc';
    }else{
        $order = 'asc';
    }
    $query_string = merge_query($_SERVER['QUERY_STRING'], array('sort_by'=>$columnName,'order'=>$order, 'page'=>0));
    $link = url_path()."?".$query_string;
    return $link;
    //////////////////////////////////
}
function merge_query($query_string, $arr)
{
    parse_str($query_string, $query_array);
    $processed_query = array_merge($query_array, $arr);
    $query_string = http_build_query($processed_query);
    return $query_string;
}
function url_path(){
    $url_parts = explode('?',page_url());
    return $url_parts[0];
}

function page_url(){
    $pageURL = 'http';
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function printable_column($column, $columns, $text)
{
    $markup = ((in_array($column, $columns) == true)?"<td>".$text."</td>":"")."";
    return $markup;
}

function th($text)
{
    return '<th class="column_heading">'.$text.'</th>';
}
function sortable_header($sortable_column_value, $type, $column_text)
{
    return '<th class="column_heading">'.sortable_link($sortable_column_value, $type, $column_text).'</th>';
}
function sortable_link($sortable_column_value, $type, $column_text)
{
    return '<a class="sortable_link" href="'.sorting_info($sortable_column_value).'"> <i class="'.sorting_icon($sortable_column_value,$type).'"> </i> '.$column_text.'</a>';
}
function sorting_icon($column_name, $type)
{
    /*$ci = & get_instance();
    $sorting_icon = $ci->helper_model->sorting_icon($column_name, $type);
    return $sorting_icon;*/
}
function current_time()
{
    return Carbon::now(new DateTimeZone('Asia/Karachi'))->toDateTimeString();
}
function current_date()
{
    return Carbon::now(new DateTimeZone('Asia/Karachi'))->toDateString();
}
