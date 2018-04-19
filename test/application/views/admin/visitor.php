<style>
    .standard{
        background-color: lightgray;
    }
    .statsTable tr{

    }
</style>
<script>
    function confirmSave(){
        var sure = confirm("are you sure you want to change personal information?");
        if(sure){
            return true;
        }else{
            return false;
        }
    }
</script>
<?php

if(isset($_POST["pay"])){
    $historyId = $_POST["historyId"];
    $paymentHistory = ORM::for_table('paymenthistory')->find_one($historyId);
    $paymentHistory->receiveDate = $todayDate;
    $paymentHistory->status = '1';
    $paymentHistory->save();
}

$majboorId = $_GET["find"];
$rowClass = "";
$majboor = ORM::for_table('majboor')->find_one($majboorId);
if($majboor){
    $username = $majboor->userName;
    $firstName = $majboor->firstName;
    $lastName = $majboor->lastName;
    $password = $majboor->password;
    $paymentEmail = $majboor->paymentEmail;
    $email = $majboor->email;
    $address = $majboor->address;
    $bank = $majboor->bankName;
    $cnic = $majboor->cnic;
    $accountNo = $majboor->accountNo;
    $mobileNo = $majboor->mobile;
    $country = $majboor->country;
    $joinDate = $majboor->regDate;

    $visits_earnings = ORM::for_table('visits_earnings')->where('majboorId', $majboor->id)->find_one();
    $totalVisits = $visits_earnings->totalViewd;
    $visitsAfterWithDraw = $visits_earnings->viewedAfterWithdraw;
    $totalEarned = $visits_earnings->totalEarned;
    $earnedAfterWithDraw = $visits_earnings->earnedAfterWithdraw;

    $references = ORM::for_table('references')->where('refererId', $majboor->id)->find_many();
    $referalVisitsAfterWithdraw = 0;
    $totalReferralVisits = 0;
    $referalEarnedAfterWithdraw = 0;
    $totalReferralEarned = 0;
    foreach($references as $reference){
        $references_visits_earnings = ORM::for_table('references_visits_earnings')->where(array(
            'refererId'=>$reference->refererId,
            'referalId' =>$reference->referalId,
        ))->find_one();
        if($references_visits_earnings){
            $referalVisitsAfterWithdraw += $references_visits_earnings->viewedAfterWithdraw;
            $totalReferralVisits += $references_visits_earnings->totalViewd;
            $referalEarnedAfterWithdraw += $references_visits_earnings->earnedAfterWithdraw;
            $totalReferralEarned += $references_visits_earnings->totalEarned;
        }
    }

    if($majboor->accountStatus == 0){
        $status = "<b style='color: red'>banned</b>";
    }else{
        $status = "<b style='color: green'>active</b>";
    }
    if($majboor->accountType == 2){
        $rowClass = "alert-success";
        $accountType = "<b style='color: green'>Premium</b>";
    }else{
        $accountType = "Standard";
        $rowClass = "standard";
    }
    ?>
    <div class="row">
        <section class="col-md-6">
            <?php
            if(isset($_POST['saveProfile'])){
                if($savedSuccessfully == true){
                    ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Success!</strong> Data Saved.
                    </div>
                <?php
                }else{
                    ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>Error!</strong> <?php echo $errorMessage; ?>
                    </div>
                <?php
                }
            }
            ?>
        </section>
    </div>
    <section class="col-md-6">
        <form action="" method="post" onsubmit="return confirmSave()">
            <table class="table statsTable" style="border:1px solid lightgray;">
                <tr class="<?php echo $rowClass; ?>" style="border-top: 2px solid white; background-color: ;">
                    <td colspan="2" style=" font-size: 22px;">Personal Information</td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">User Name</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $username; ?>" type="text" placeholder="" name='username' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">First Name</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $firstName; ?>" type="text" placeholder="" name='firstName' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Last Name</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $lastName; ?>" type="text" placeholder="" name='lastName' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Email</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $email; ?>" type="text" placeholder="" name='email' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Password</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $password; ?>" type="text" placeholder="" name='password' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Payment Email</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $paymentEmail; ?>" type="text" placeholder="" name='paymentEmail' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Address</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $address; ?>" type="text" placeholder="" name='address' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Bank</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $bank; ?>" type="text" placeholder="" name='bank' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">CNIC#</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $cnic; ?>" type="text" placeholder="" name='cnic' id='username' required="required">
                        </div></td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Account No</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $accountNo; ?>" type="text" placeholder="" name='accountNo' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Mobile No</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $mobileNo; ?>" type="text" placeholder="" name='mobile' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Country</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <select class="form-control" name="country">
                                <option value="Saudi Arab" <?php if($country == "Saudi Arab"){echo "selected";} ?>>Saudi Arab</option>
                                <option value="Pakistan" <?php if($country == "Pakistan"){echo "selected";} ?>>Pakistan</option>
                                <option value="United Kingdom" <?php if($country == "United Kingdom"){echo "selected";} ?>>United Kingdom</option>
                                <option value="United States" <?php if($country == "United States"){echo "selected";} ?>>United States</option>
                            </select>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">Join Date</td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control" value="<?php echo $joinDate; ?>" type="text" placeholder="" name='regDate' id='username' required="required">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size: 14px;"></td>
                    <td style=" font-size: 14px;">
                        <div class="col-sm-12">
                            <input class="form-control btn btn-success" value="Save Personal Information" type="submit" name='saveProfile' id=''>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </section>
    <section class="col-md-6">
        <table class="table statsTable" style="border:1px solid lightgray;">
            <tr class="<?php echo $rowClass; ?>" style="border-top: 2px solid white; background-color: ;">
                <td colspan="2" style=" font-size: 22px;">Other Information</td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Total Visits</td>
                <td style=" font-size: 14px;"><?php echo $totalVisits;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Visits After Withdraw</td>
                <td style=" font-size: 14px;"><?php echo $visitsAfterWithDraw;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Total Referral Visits</td>
                <td style=" font-size: 14px;"><?php echo $totalReferralVisits;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Referrals Visits </td>
                <td style=" font-size: 14px;"><?php echo $referalVisitsAfterWithdraw;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Total Earned</td>
                <td style=" font-size: 14px;"><?php echo $totalEarned;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Balance</td>
                <td style=" font-size: 14px;"><?php echo $earnedAfterWithDraw;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Total Referral Earnings</td>
                <td style=" font-size: 14px;"><?php echo $totalReferralEarned;?></td>
                <td></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Referrals Earning Balance</td>
                <td style=" font-size: 14px;"><?php echo $referalEarnedAfterWithdraw;?></td>
                <td></td>
            </tr>

            <tr>
                <td style=" font-size: 14px;">Account Status</td>
                <td style=" font-size: 14px;"><?php echo $status; ?></td>
                <td><form action="" method="post">
                        <input type="submit" class="btn btn-default" name="accountStatus" value="<?php if($majboor->accountStatus == 0){echo"Activate";}else{echo"Deactivate";} ?>">
                    </form></td>
            </tr>
            <tr>
                <td style=" font-size: 14px;">Account Type</td>
                <td style=" font-size: 14px;"><?php echo $accountType; ?></td>
                <td><form action="" method="post">
                        <input type="submit" class="btn btn-default" name="accountType" value="<?php if($majboor->accountType == 1){echo"Upgrade";}else{echo"Standard";} ?>">
                    </form></td>
            </tr>
        </table>

    </section>

    <?php
    $references = ORM::for_table('references')->where('refererId', $majboorId);
    $existingReferences = $references->count();

    ?>
    <section class="col-lg-12" style="height: 400px; overflow: scroll; border: 0px solid red;">


        <h1>Referrals</h1>
        <div style="">
            <table class="table table-bordered referencesTable" style="position: relative;">
                <tr class="<?php echo $rowClass; ?>" style="border-top: 2px solid white; background-color: ;">
                    <th style=" font-size: 16px;">Existing(<?php echo $existingReferences; ?>)</th>
                    <th>Add More</th>
                </tr>
                <tr>
                    <td style=" font-size: 14px;">
                        <?php
                        $allReferences = ORM::for_table('references')->where('refererId', $majboorId)->find_many();
                        echo "<form method='post' action=''>";
                        foreach($allReferences as $ref){
                            $ref1 = ORM::for_table('majboor')->find_one($ref->referalId);
                            $referalName = $ref1->userName;
                            ?>
                            <div class="checkbox">
                                <label style="">
                                    <input type="checkbox" value="<?php echo $ref1->id; ?>" name="<?php echo $ref1->id; ?>"> <?php echo $referalName; ?>
                                </label>
                            </div>
                        <?php
                        }
                        echo "<input type='submit' name='removeReferals' value='Remove Selected >>' class='btn btn-default'>";
                        echo "</form>";
                        ?>
                    </td>
                    <td style=" font-size: 14px;">
                        <?php
                        $majboors = ORM::for_table('majboor')->where_not_equal('id', $majboorId)->find_many();
                        echo "<form method='post' action=''>";
                        foreach($majboors as $ref){
                            $referal = ORM::for_table('references')->where(array(
                                'referalId'=> $ref->id,
                                'refererId' => $majboorId,
                            ))->find_one();
                            if($referal){

                            }else{
                                ?>
                                <div class="checkbox">
                                    <label style="">
                                        <input class="" type="checkbox" value="<?php echo $ref->id; ?>" name="<?php echo $ref->id; ?>"> <?php echo $ref->userName; ?>
                                    </label>
                                </div>
                            <?php
                            }
                        }
                        echo "<input type='submit' name='addReferals' value='<< Add Selected' class='btn btn-default'>";
                        echo "</form>";
                        ?>
                    </td>
                </tr>
            </table
        </div>
    </section>
    <section class="col-lg-12" style="height: 400px; overflow: scroll; border: 0px solid red;">

        <div style="">
            <h2 id="paymentsTable">Payment History</h2>
            <table  class="table table-bordered"  style="position: relative; left: 10px; top: 20px; margin: auto; width: 100%; background-color: ; color: ">
                <tr class="<?php echo $rowClass; ?>" style="border-top: 2px solid white;">
                    <th>Request Date</th><th>Amount</th><th>Received Date</th><th>Payment Method</th><th>Status</th>
                </tr>
                <?php

                $paymentHistory = ORM::for_table('paymenthistory')->where('majboorId', $majboorId)->where_equal('receiveDate', '0000-00-00')->find_many();
                foreach($paymentHistory as $history){
                    $requestDate = $history->requestDate;
                    $receiveDate = $history->receiveDate;
                    $amount = $history->amount;
                    $status = $history->status;
                    $method = $history->method;
                    echo "<tr>";
                    echo "<td>$requestDate</td><td>$amount</td><td>$receiveDate</td><td>$method</td>";
                    ?>
                    <td>
                        <form method="post" action="#paymentsTable">
                            <input type="hidden" value="<?php echo $history->id ?>" name="historyId">
                            <input class="btn btn-success" type="submit" name="pay" value="Pay">
                        </form>
                    </td>
                    <?php
                    echo "</tr>";
                }

                $paymentHistory = ORM::for_table('paymenthistory')->where('majboorId', $majboorId)->where_not_equal('receiveDate', '0000-00-00')->find_many();
                foreach($paymentHistory as $history){
                    $requestDate = $history->requestDate;
                    $receiveDate = $history->receiveDate;
                    $amount = $history->amount;
                    $status = $history->status;
                    $method = $history->method;
                    echo "<tr>";
                    echo "<td>$requestDate</td><td>$amount</td><td>$receiveDate</td><td>$method</td>";
                    ?>
                    <td>
                        <form method="post" action="#paymentsTable">
                            <input type="hidden" value="<?php echo $history->id ?>" name="historyId">
                            <input class="btn btn-danger" type="submit" name="unPay" value="UnPay">
                        </form>
                    </td>
                    <?php
                    echo "</tr>";
                }

                ?>
            </table>
        </div>
    </section>
<?php

}else{
    echo "
        <div class='alert-danger' style='font-size: 14px;'>No such visitor exits in the system :(</div>
        ";
}
?>