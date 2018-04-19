<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/17/14
 * Time: 6:19 PM
 */


class Zeenomla_db extends CI_Model {

    public function __construct(){
        parent::__construct();
    }
    public function register(){
        $data = array(
            'firstName' => $this->input->post('firstName') ,
            'lastName' => $this->input->post('lastName') ,
            'userName' => $this->input->post('username') ,
            'password' => $this->input->post('password') ,
            'email' => $this->input->post('email') ,
            'paymentEmail' => $this->input->post('paymentEmail') ,
            'address' => $this->input->post('address') ,

            'country' => $this->input->post('country') ,
            'cnic' => $this->input->post('cnic') ,
            'bankName' => $this->input->post('bank') ,
            'accountNo' => $this->input->post('accountNo') ,
            'mobile' => $this->input->post('mobile') ,
            'regDate' => date('Y-m-d') ,
            'accountType' => 1,
        );
        $this->db->insert('majboor', $data);

        //Getting the majboor Id
        $this->db->where('userName', $this->input->post('username'));
        $query = $this->db->get('majboor');
        $result = $query->result();
        $result = $result[0];
        $referralId = $result->id;
        $majboorId = $referralId;

        //setting references...
        if($this->input->post('referrer') != null){
            $this->db->where('userName', $this->input->post('referrer'));
            $query = $this->db->get('majboor');
            $result = $query->result();
            $result = $result[0];
            $referrerId = $result->id;
            $data = array(
                'refererId' => $referrerId ,
                'referalId' => $referralId ,
            );
            $this->db->insert('references', $data);

            $data = array(
                'refererId' => $referrerId ,
                'referalId' => $referralId ,
            );
            $this->db->insert('references_visits_earnings', $data);
        }

        $data = array(
            'majboorId' => $majboorId ,
            'totalViewd' => 0 ,
            'viewedAfterWithdraw' => 0 ,
            'totalEarned' => 0 ,
            'earnedAfterWithdraw' => 0 ,
        );
        $this->db->insert('visits_earnings', $data);

    }

    public function login(){
        $this->db->where('userName', $this->input->post('username'));
        $majboor = $this->db->get('majboor')->result();
        $majboor = $majboor[0];
        @session_start();
        $_SESSION["user_id"] = $majboor->id;
        $_SESSION["user_name"] = $majboor->userName;
        $data = array(
            'sessionId' => session_id() ,
            'activeAds' => 0 ,
        );
        $this->db->where('id', $majboor->id);
        $this->db->update('majboor', $data);
    }

    function check_credentials($table, $userName, $password){
        list($uField, $userName)=explode('.', $userName);
        list($pField, $password)=explode('.', $password);
        $query = $this->db->get_where($table, array($uField => $userName, $pField => $password, ));
        if($query->num_rows() >= 1){
            return true;
        }else{
            return false;
        }
    }

    function loggedIn(){
        @session_start();
        if(isset($_SESSION["user_id"])){
            $query = $this->db->get_where('majboor', array('userName' => $_SESSION["user_name"], 'sessionId' => session_id(), ));
            if($query->num_rows() < 1){
                return 2;   //multiple browser login hai
            }else{
                return 1;   //login hai
            }
        }else{
            return 0;       //login nahi
        }
    }

    function isBanned(){
        $this->db->where('userName', $_SESSION['user_name']);
        $majboor = $this->db->get('majboor')->result();
        $majboor = $majboor[0];
        if($majboor->accountStatus == 0){
            return true;
        }else{
            return false;
        }
    }

    function logout(){
        @session_start();
            $this->db->where('userName', $_SESSION["user_name"]);
            $data = array(
                'sessionId' => -1 ,
            );
            $this->db->update('majboor',$data);
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_name"]);
    }

    function contact(){
        $this->helper->mail('nomantufail100@gmail.com', $this->input->post('subject'), $this->input->post('message'), 'nomantufail100@yahoo.com', 'Noman Tufail');
    }

    function  premiumUser($id = ""){
        if($id == ""){
            if(isset($_SESSION['user_id'])){
                $id = $_SESSION['user_id'];
            }
        }
        $this->db->where('id',$id);
        $majboor = $this->db->get('majboor')->result();
        $majboor = $majboor[0];
        if($majboor->accountType == 1){
            return false;
        }else{
            return true;
        }
    }

    function visited_ads_ids(){
        $visited_ads = array();
        $ads = $this->db->get_where('visits', array('majboorId'=>$_SESSION['user_id']))->result();
        foreach($ads as $ad){
            if($this->visited($ad->addId)){
                array_push($visited_ads, $ad->addId);
            }
        }
        return $visited_ads;

    }

    function visited($adId){
        $result = $this->db->get_where('visits', array('addId'=>$adId, 'majboorId'=>$_SESSION['user_id']))->result();
        if($result){
            $ad = $result[0];
            $newNow = $this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateString();
            $timeDiff = array();
            $date = new DateTime($ad->time);
            $date->modify("+5 hours");
            $date= $date->format("Y-m-d");
            $timeDiff = timeDifference($date, $newNow);
            if($timeDiff["days"] < 1 ){
                return true;
            }else{
                false;
            }
        }else{
            return false;
        }
    }

    function ad_is_active(){
        $activeAd = $this->db->get_where('majboor', array('id'=>$_SESSION['user_id'], 'activeAds'=>'0'))->num_rows;
        if($activeAd >=1){
            return true;
        }else{
            return false;
        }
    }
    function check_ad_expires($adId){
        $result = $this->db->get_where('advertiser', array('id'=>$adId))->result();
        $ad = $result[0];
        if($ad->planid != 1){
            $result = $this->db->get_where('advertplan', array('id'=>$ad->planid))->result();
            $advertPlan = $result[0];
            $allowVisits = $advertPlan->visits;
            if(($ad->memberVisits + $ad->outsideVisits) >= $allowVisits){
                $data = array(
                    'status'=>2
                );
                $this->db->where('id', $adId);
                $this->db->update('advertiser', $data);
            }
        }
    }
    function save_visit_time($adId){
        //chek that if there already a record or not?
        $visits = $this->db->get_where('visits', array('majboorId'=>$_SESSION['user_id'], 'addId'=>$adId))->num_rows();
        if($visits >=1){
            $data = array('time'=>date("Y-m-d H:i:s"));
            $this->db->where(array('addId'=>$adId, 'majboorId'=>$_SESSION['user_id']));
            $this->db->update('visits', $data);
        }else{
            $data = array(
                'time'=>date("Y-m-d H:i:s"),
                'majboorId' => $_SESSION['user_id'],
                'addId' => $adId,
            );
            $this->db->insert('visits', $data);
        }
    }
    function recordEarnings(){
        $result = $this->db->get_where('majboor',array('id'=>$_SESSION['user_id']))->result();
        $majboor = $result[0];
        $result = $this->db->get_where('accounttypes', array('id'=>$majboor->accountType))->result();
        $accountType = $result[0];
        $pricePerVisit = $accountType->amount;

        $result = $this->db->get_where('visits_earnings', array('majboorId'=>$majboor->id))->result();
        $visits_earnings = $result[0];
        if($visits_earnings){
            $visits_earnings->totalViewd  = 1+$visits_earnings->totalViewd;
            $visits_earnings->viewedAfterWithdraw = 1+ $visits_earnings->viewedAfterWithdraw;
            $visits_earnings->totalEarned = $pricePerVisit + $visits_earnings->totalEarned;
            $visits_earnings->earnedAfterWithdraw = $pricePerVisit + $visits_earnings->earnedAfterWithdraw;
            $this->db->where('majboorId', $majboor->id);
            $this->db->update('visits_earnings', $visits_earnings);

            $referers = $this->db->get_where('references', array('referalId'=>$majboor->id))->result();
            foreach($referers as $referer){
                $result = $this->db->get_where('majboor',array('id'=>$referer->refererId))->result();
                $majboor = $result[0];
                if($majboor->accountType == 2){
                    $result = $this->db->get_where('references_visits_earnings',array(
                        'refererId'=>$referer->refererId,
                        'referalId' =>$referer->referalId,
                        ))->result();
                    $references_visits_earnings = $result[0];
                    if($references_visits_earnings){
                        $references_visits_earnings->totalViewd  = 1+$references_visits_earnings->totalViewd;
                        $references_visits_earnings->viewedAfterWithdraw = 1+ $references_visits_earnings->viewedAfterWithdraw;
                        $references_visits_earnings->totalEarned = 0.5 + $references_visits_earnings->totalEarned;
                        $references_visits_earnings->earnedAfterWithdraw = 0.5 + $references_visits_earnings->earnedAfterWithdraw;
                        $this->db->where(array(
                            'refererId'=>$referer->refererId,
                            'referalId' =>$referer->referalId,
                        ));
                        $this->db->update('references_visits_earnings', $references_visits_earnings);
                    }
                }
            }
            return true;
        }else{
            return false;
        }
    }

    function fetch_ads($demand = "guest"){
        $ads = array(
            'standard'=>'',
            'premium'=>'',
        );
        if($demand == "guest"){
            $this->db->where('id <', '4');
            $ads['standard'] = $this->db->get('advertiser')->result();
            $this->db->where('status', 1);
            $ads['premium'] = $this->db->get('advertiser')->result();
        }else if($demand == "standard"){
            $this->db->where('id <', '4');
            $ads['standard'] = $this->db->get('advertiser')->result();
        }else if($demand == "premium"){
            $this->db->where('status', 1);
            $ads['premium'] = $this->db->get('advertiser')->result();
        }else{
            $this->db->where('id <', '4');
            $ads['standard'] = $this->db->get('advertiser')->result();
            $this->db->where('status', 1);
            $ads['premium'] = $this->db->get('advertiser')->result();
        }
        return $ads;
    }

    function advertPlans(){
        return $this->db->get('advertplan')->result();
    }

    function advertise(){
        $data = array(
            'requestDate' => date('Y-m-d'),
            'paymentEmail' => $this->input->post('paymentEmail'),
            'linkurl'=>$this->input->post('linkUrl'),
            'linktext' => $this->input->post('linkText'),
            'planid' => $this->input->post('plan'),
        );
        $this->db->insert('advertiser', $data);
    }

    function accountInfo($id=""){
        if($id == ""){
            if(isset($_SESSION['user_id'])){
                $id = $_SESSION['user_id'];
            }else{
                return null;
            }
        }

        $data = array(
            'majboor' =>'',
            'my' =>'',
            'references' =>'',
            'accountType' =>'',
            'totalReferals' =>'',
            'totalPaid' =>'',
            'expiryDate' =>'',
        );
        $my = array(
            'totalVisits'=>'',
            'visitsAfter'=>'',
            'totalEarned'=>'',
            'earnedAfter'=>'',
        );
        $referalsData = array(
            'totalVisits'=>'',
            'visitsAfter'=>'',
            'totalEarned'=>'',
            'earnedAfter'=>'',
        );
        $majboorId = $id;
        $majboor = $this->db->get_where('majboor', array('id'=> $majboorId))->result();
        $majboor = $majboor[0];
        $data['majboor'] = $majboor;

        $totalPaid = 0;
        $payments = $this->db->get_where('paymenthistory',array('majboorId'=> $majboorId , 'receiveDate !=' => '0000-00-00'))->result();
        foreach($payments as $payment){
            $totalPaid += $payment->amount;
        }
        $data['totalPaid'] = $totalPaid;

        $joinedDate = $majboor->regDate;
        $premiumDate = $majboor->gotPremium;
        $expiryDate = "N/A";
        if($majboor->accountType != 1){
            $date=date_create($premiumDate);
            date_add($date,date_interval_create_from_date_string("180 days"));
            $expiryDate = date_format($date,"Y-m-d");
        }
        $data['expiryDate'] = $expiryDate;

        $account = $this->db->get_where('accounttypes', array('id'=>$majboor->accountType))->result();
        $account = $account[0];
        $data['accountType'] = $account->type;

        $visits_earnings = $this->db->get_where('visits_earnings', array('majboorId'=> $majboorId))->result();
        $visits_earnings = $visits_earnings[0];
        $totalVisits = $visits_earnings->viewedAfterWithdraw;
        $earnings = $visits_earnings->earnedAfterWithdraw;
        $my['visitsAfter'] = $totalVisits;
        $my['earnedAfter'] = $earnings;
        $my['totalEarned'] = $visits_earnings->totalEarned;
        $my['totalVisits'] = $visits_earnings->totalViewd;
        $data['my'] = $my;

        $references = $this->db->get_where('references', array('refererId' => $majboorId));
        $data['totalReferals'] = $references->num_rows();

        $references = $this->db->get_where('references', array('refererId' => $majboorId))->result();
        $referalVisits = 0;
        $referalEarnings = 0;
        $referalTotalVisits = 0;
        $referalTotalEarnings = 0;
        foreach($references as $reference){
            /*    $visits_earnings = ORM::for_table('visits_earnings')->where('majboorId', $reference->referalId)->find_one();
                $referalVisits += $visits_earnings->viewedAfterWithdraw;*/

            $references_visits_earnings = $this->db->get_where('references_visits_earnings', array('refererId' => $reference->refererId , 'referalId' =>$reference->referalId,))->result();
            $references_visits_earnings = $references_visits_earnings[0];
            if($references_visits_earnings){
                $referalVisits += $references_visits_earnings->viewedAfterWithdraw;
                $referalEarnings += $references_visits_earnings->earnedAfterWithdraw;
                $referalTotalVisits += $references_visits_earnings->totalViewd;
                $referalTotalEarnings += $references_visits_earnings->totalEarned;
            }
        }
        $referalsData['visitsAfter'] = $referalVisits;
        $referalsData['earnedAfter'] = $referalEarnings;
        $referalsData['totalEarned'] = $referalTotalEarnings;
        $referalsData['totalVisits'] = $referalTotalVisits;
        $data['references'] = $referalsData;

        return $data;

    }

    function majboor($id = ""){
        if($id == ""){
            $id = $_SESSION['user_id'];
        }

        $result = $this->db->get_where('majboor', array('id'=> $id))->result();
        $result = $result[0];
        return $result;

    }

    function check_email_unique($email, $field){
        $majboor = $this->db->get_where('majboor', array($field => $email, 'id' => $_SESSION['user_id'],))->num_rows();
        if($majboor >= 1){
            return true;
        }else{
            $this->db->where('email',$email);
            $this->db->or_where('paymentEmail', $email);
            $majboor = $this->db->get('majboor')->num_rows();
            if($majboor >= 1){
                return false;
            }else{
                return true;
            }
        }
    }
    function saveProfile(){
        $data = array(
            'firstName' => $this->input->post('firstName'),
            'lastName' => $this->input->post('lastName'),
            'paymentEmail' => $this->input->post('paymentEmail'),
            'password' => $this->input->post('password'),
            'bankName' => $this->input->post('bank'),
            'accountNo' => $this->input->post('accountNo'),
            'address' => $this->input->post('address'),
            'country' => $this->input->post('country'),
        );

        $this->db->where('id', $_SESSION['user_id']);
        $this->db->update('majboor', $data);
    }

    /*
     * below section is for ads view and stuff like that
     */
    function ad_exists($id){
        if($id == ""){
            return false;
        }else{
            $ads_found = $this->db->get_where('advertiser',array('id'=> $id))->num_rows();
            if($ads_found >=1){
                return true;
            }else{
                return false;
            }
        }
    }

    function active_ad($id){
        $data = array(
            'activeAds' => 0,
        );
        $this->db->where('id',$_SESSION['user_id']);
        $this->db->update('majboor', $data);
    }

    function ad_deactivate($id, $cookie){
        $data = array(
            'activeAds' => 0,
        );

        if($cookie == 'zeenomTestingCookie'){

        }else{
            $data = array(
                'activeAds' => 1,
            );
        }

        $this->db->where('id',$_SESSION['user_id']);
        $this->db->update('majboor', $data);
    }

    function increment_visits($adId, $visitType){
        $result = $this->db->get_where('advertiser', array('id'=>$adId))->result();
        $memberVisits = $result[0]->memberVisits;
        $outsideVisits = $result[0]->outsideVisits;
        if($visitType == 1){
            $memberVisits++;
            $data = array('memberVisits' => $memberVisits);
            $this->db->where('id', $adId);
            $this->db->update('advertiser', $data);
        }else{
            $outsideVisits++;
            $data = array('outsideVisits' => $outsideVisits);
            $this->db->where('id', $adId);
            $this->db->update('advertiser', $data);
        }
    }

    function ad_info($id){
        $result = $this->db->get_where('advertiser', array('id' => $id))->result();
        return $result[0];
    }

    // Payment History data
    function histories($majboorId=""){
        if($majboorId == ""){
            $majboorId = $_SESSION['user_id'];
        }
        $histories = $this->db->get_where('paymenthistory', array('majboorId'=> $majboorId))->result();
        return $histories;
    }

    // Referals data
    function referals($majboorId=""){
        include_once(APPPATH.'models/helperClasses/ReferalData.php');
        if($majboorId == ""){
            $majboorId = $_SESSION['user_id'];
        }
        $majboors = array();
        $referrals = $this->db->get_where('references', array('refererId'=> $majboorId))->result();
        foreach($referrals as $referal){
            $referalData = new ReferalData($referal->referalId);
            array_push($majboors,$referalData);
        }
        return $majboors;
    }

    //upgrade account info
    function upgradeInfo(){
        include_once(APPPATH.'models/helperClasses/UpgradeAccountData.php');

        $upgradeData = new UpgradeAccountData();
        return $upgradeData;
    }

    function is_premium($id = ''){
        if($id == ''){
            $id = $_SESSION['user_id'];
        }

        $result = $this->db->get_where('majboor', array('id' => $id))->result();
        $majboor = $result[0];
        if($majboor->accountType !=1){
            return true;
        }
        return false;
    }

    function admin(){
        $result = $this->db->get('admin')->result();
        $admin = $result[0];
        return $admin;
    }

    function visits_earnings($id = ''){
        if($id == ''){
            $id = $_SESSION['user_id'];
        }

        $result = $this->db->get_where('visits_earnings', array('majboorId'=> $id))->result();
        $visits_earnings = $result[0];
        return $visits_earnings;

    }

    function referals_visits_earnings($refererId = ''){
        include_once(APPPATH.'models/helperClasses/Referals_visits_earnings.php');

        $referals_visits_earnings = new Referals_visits_earnings();
        if($refererId == ''){
            $refererId = $_SESSION['user_id'];
        }

        $referals = $this->db->get_where('references', array('refererId'=> $refererId))->result();
        foreach($referals as $referal){

            $result = $this->db->get_where('references_visits_earnings',array(
                'refererId'=>$referal->refererId,
                'referalId' =>$referal->referalId,
            ))->result();
            $references_visits_earnings = $result[0];
            if($references_visits_earnings){
                $referals_visits_earnings->totalVisits += $references_visits_earnings->totalViewd;
                $referals_visits_earnings->visitsAfter += $references_visits_earnings->viewedAfterWithdraw;
                $referals_visits_earnings->totalEarned += $references_visits_earnings->totalEarned;
                $referals_visits_earnings->earnedAfter += $references_visits_earnings->earnedAfterWithdraw;
            }
        }
        return $referals_visits_earnings;

    }

    function payment_methods(){
        $payment_methods = $this->db->get('paymentmethods')->result();
        return $payment_methods;
    }

    function unprocess_requests($id = ''){
        if($id == ''){
            $id = $_SESSION['user_id'];
        }
        $unprocess_requests = $this->db->get_where('paymenthistory', array('majboorId' => $id, 'status' => 0))->num_rows();
        return $unprocess_requests;
    }

    function total_requests_made_in($days, $id = ''){
        if($id == ''){
            $id = $_SESSION['user_id'];
        }
        $requests = $this->db->get_where('paymenthistory', array('majboorId'=>$id, 'requestDate >'=>$this->carbon->now()->subDays($days)->toDateString()))->num_rows();
        return $requests;
    }

    function withdraw(){
        $majboor = $this->majboor();

        $data = array(
            'requestDate'=> date('Y-m-d'),
            'majboorId' => $majboor->id,
            'amount'    => $this->input->post('cash'),
            'method'    => $this->input->post('method'),
        );
        $this->db->insert('paymenthistory', $data);

        $admin = $this->admin();
        $to = $admin->withdrawEmail;

        $message = " Name:  ".$majboor->firstName." ".$majboor->lastName."<br>";
        $message = $message. " Username:  ".$majboor->userName."\n";
        $message = $message. " Join Date:  ".$majboor->regDate."\n";
        $message = $message. " Amount:  ".$this->input->post('cash')."\n";
        $message = $message. " Payment Email:  ".$majboor->paymentEmail."\n";

        $this->helper->mail($to,"Cash Withdraw Request",$message, $majboor->paymentEmail, $_SESSION['user_name']);

        $data = array(
            'earnedAfterWithdraw' => 0,
            'viewedAfterWithdraw' => 0,
        );
        $this->db->where(array(
            'majboorId'=>$majboor->id,
        ));
        $this->db->update('visits_earnings', $data);

        $referals = $this->db->get_where('references', array('refererId'=> $majboor->id))->result();
        foreach($referals as $referal){
            $data = array(
                'earnedAfterWithdraw' => 0,
                'viewedAfterWithdraw' => 0,
            );
            $this->db->where(array(
                'refererId'=>$referal->refererId,
                'referalId' =>$referal->referalId,
            ));
            $this->db->update('references_visits_earnings', $data);
        }
    }

}