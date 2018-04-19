<style>

    .white-popup {

        position: relative;

        background: #FFF;

        padding: 20px;

        width: auto;

        max-width: 1000px;

        margin: 20px auto;

    }
    .trial_balance_settings_popup{
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .search-table{
        font-size: 12px;
    }
    .search-table input{
        width:100%;
        height: 30px;
    }
    .search-table select{
        width:100%;
        height: 30px;
    }
    table{
        font-size: 12px;
    }
</style>
<script>
    function show_monthly_results(month, path){

        var path = path+month;

        window.location.href = path;

    }

</script>

<div id="page-wrapper" style="min-height: 700px;">

<div class="container-fluid">



    <!--Including the main navbar for accounts section-->
    <div class="row">
        <?php
        include_once(APPPATH."views/accounts/components/main_nav_bar.php");
        ?>
    </div>

    <div class="row">
        <h3 style="text-align: center;">Ledger</h3>
    </div>

    <div class="row">
        <div class="panel-body">
            <!--SEARCH AREA FOR TANKERS LEDGERS-->
            <div class="col-lg-12">
                <form action="" method="get">
                    <table class="search-table" style="width:100%;">
                        <tr>
                            <td style="width: 30%;"><b>Tanker#: </b><br>
                                <select name="tanker_id[]" multiple class="tankers_select">
                                    <?php foreach($tankers as $tanker): ?>
                                        <?php
                                        $selected = (in_array($tanker->id, $selected_tanker_ids)?'selected':'');
                                        ?>
                                        <option value="<?= $tanker->id ?>" <?= $selected ?>><?= $tanker->truck_number ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><b>From: </b><input type="date" value="<?= $selected_from ?>" name="from"></td>
                            <td><b>To: </b><input type="date" value="<?= $selected_to ?>" name="to"></td>
                            <td><br><input type="submit" value="Generate" style="width: 100%;"></td>
                        </tr>
                    </table>
                </form>
            </div>
            <!--search area ends here-->

            <div class="col-lg-12">
                <form name="selection_form" id="selection_form" method="post" action="<?php
                if(strpos($this->helper_model->page_url(),'?') == false){
                    echo $this->helper_model->page_url()."?";
                }else{echo $this->helper_model->page_url()."&";}
                ?>print">


                    <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                        <?php
                        $starting_balance = $opening_balance;
                        $searched_balance = 0;
                        ?>
                        <thead style="">
                        <tr>
                            <td colspan="7" style="text-align: right;">Opening Balance: <?= (($opening_balance < 0)?"(".($opening_balance*-1).")":$opening_balance) ?></td>

                        </tr>
                        <tr>
                            <th></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="voucher_id" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="voucher_date" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="account_title" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="debit" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="credit" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="searched_balance" style="" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" value="actual_balance" style="" checked></div></th>
                        </tr>
                        <tr>
                            <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
                            <th style="">Voucher#</th>
                            <th style="width: 10%">Date</th>
                            <th style="width: 55%">Account Title</th>
                            <th style="width: 11%;">Debit</th>
                            <th style="width: 11%;">Credit</th>
                            <th style="width: 11%;">Searched Balance</th>
                            <th style="width: 11%;">Actual Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_balance = 0;
                        $total_debit = 0;
                        $total_credit = 0;
                        ?>

                        <?php  foreach($tanker_ledgers as $record): ?>
                            <?php $total_debit += $record->debit; ?>
                            <?php $total_credit += $record->credit; ?>
                            <tr style="">
                                <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $record->voucher_id; ?>"></td>
                                <td><?= $record->voucher_id ?></td>
                                <td>
                                    <?= $this->carbon->createFromFormat('Y-m-d', $record->voucher_date)->toFormattedDateString(); ?>
                                </td>
                                <td>
                                    <div style="width: 60%; min-height: 10px; float: left; border: 0px solid red; color: gray;">
                                        <?php
                                            echo "<span style='color:black'>".$record->title."</span> ";
                                            echo " Account Type: <span style='color:black;'>".$record->ac_type."</span>| ";
                                        ?>
                                        <?php

                                        if($record->other_agent_id != 0)
                                        {
                                            echo " Related Other Agent: <span style='color:black;'>".$record->other_agent_name."</span> |";
                                        }
                                        if($record->customer_id != 0)
                                        {
                                            echo " Related Customer: <span style='color:black;'>".$record->customer_name."</span> |";
                                        }
                                        if($record->contractor_id != 0)
                                        {
                                            echo " Related Contractor: <span style='color:black;'>".$record->contractor_name."</span> |";
                                        }
                                        if($record->company_id != 0)
                                        {
                                            echo " Related Company: <span style='color:black;'>".$record->company_name."</span> |";
                                        }

                                        ?>
                                        <?= "Description: ".$record->description; ?>
                                    </div>
                                    <div style="width: 20%; float: left; border: 0px solid red;">
                                        <div class="text-center" style="color: #555555;">
                                            <?php
                                            if($record->trip_id > 0){
                                                echo " Trip# ".$record->trip_id;
                                            }
                                            ?>
                                        </div>
                                        <div class="text-center" style="color: #555555;">
                                            <?php
                                            if($record->tanker_number != ''){
                                                echo "Tanker# ".$record->tanker_number;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= (($record->dr_cr == 0)?'':$this->helper_model->money(round($record->debit, 3))); ?></td>
                                <td><?= (($record->dr_cr == 1)?'':$this->helper_model->money(round($record->credit, 3))); ?></td>
                                <td>
                                    <?php
                                    $searched_balance = (($record->debit - $record->credit) + $searched_balance);
                                    $searched_balance = round($searched_balance, 3);
                                    ?>
                                    <?= (($searched_balance < 0)?"(".($this->helper_model->money($searched_balance*-1)).")":$this->helper_model->money($searched_balance)) ?>
                                </td>
                                <td>
                                    <?php
                                    $starting_balance = (($record->debit - $record->credit) + $starting_balance);
                                    $starting_balance = round($starting_balance, 3);
                                    ?>
                                    <?= (($starting_balance < 0)?"(".($this->helper_model->money($starting_balance*-1)).")":$this->helper_model->money($starting_balance)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" style="text-align: right;">Totals:</th>
                            <th><?= $this->helper_model->money(round($total_debit, 3)) ?></th>
                            <th colspan=""><?= $this->helper_model->money(round($total_credit, 3)) ?></th>
                            <th></th><th></th>
                        </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>

</div>



</div>

<script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>

<script>

    $('.trial_balance_settings_link').magnificPopup({
        type: 'inline',
        showCloseBtn:false
    });

    $(".tankers_select").select2();
</script>