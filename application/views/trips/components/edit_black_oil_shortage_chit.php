<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 5/23/15
 * Time: 12:14 AM
 */
?>
<div id="black_oil_shortage_expense_chit_popup" class="shortage_expense_chit_popup mfp-hide">
    <style>
        .custom_accounts_inputs select{
            height: 30px;
            width: 100%;
            font-size:12px;
        }
        .custom_accounts_inputs input{
            height: 30px;
            width: 100%;
            font-size: ;
        }
        .custom_accounts_inputs .lable{
            color: gray;
            font-weight: bold;
        }
        .custom_accounts_inputs fieldset{
            margin-top: 10px;
        }
    </style>

    <script>
        function black_oil_fetch_agents(agent, callback){
            var agents_area = document.getElementById("black_oil_agents_area");
            $.get("<?=base_url()."accounts/fetch_agents_for_shortage_chit/"?>"+agent+"/black_oil_",function(data,status){
                agents_area.innerHTML= data;
                callback();
            });
        }

        function black_oil_agent_type_changed()
        {
            var agent_type_selected_index = document.getElementById("black_oil_agent_type").selectedIndex;
            var agent_type = document.getElementById("black_oil_agent_type").options[agent_type_selected_index].value;
            if(agent_type != ''){
                document.getElementById('black_oil_agents_area').innerHTML = '<span style="font-style: italic; color: gray;">Loading...</span>';
                black_oil_fetch_agents(agent_type,function(){

                });
            }
        }

        function black_oil_shortage_changed()
        {
            var shortage = document.getElementById('black_oil_shortage_quantity').value;
            var price_unit = document.getElementById('black_oil_price_unit').value;
            var freight_unit = parseFloat(document.getElementById('black_oil_shortage_freight_unit_label').innerHTML);
            var shortage_rate = parseFloat(document.getElementById('black_oil_shortage_rate').value);

            document.getElementById("black_oil_shortage_amount_label").innerHTML = limit_number(shortage*shortage_rate)+' Rs.';
            document.getElementById("black_oil_freight_on_shortage_label").innerHTML = limit_number(shortage*freight_unit)+' Rs.';
        }
        function black_oil_get_shortage_expense_voucher()
        {
            var url = ""
            var trip_id = document.getElementById('black_oil_shortage_trip_id').value;
            var trip_detail_id = document.getElementById('black_oil_shortage_trip_detail_id').value;
            var shortage = document.getElementById('black_oil_shortage_quantity').value;
            shortage = (shortage == '')?0:shortage;
            var price_unit = document.getElementById('black_oil_price_unit').value;
            price_unit = (price_unit == '')?0:price_unit;
            var shortage_at_selected_index = document.getElementById("black_oil_shortage_at").selectedIndex;
            var shortage_at = document.getElementById("black_oil_shortage_at").options[shortage_at_selected_index].value;

            var agent_type_selected_index = document.getElementById("black_oil_agent_type").selectedIndex;
            var agent_type = document.getElementById("black_oil_agent_type").options[agent_type_selected_index].value;
            agent_type = (agent_type == '')?'none':agent_type;
            var agent_id = 'none'
            if(agent_type != 'none'){
                var agent_id_selected_index = document.getElementById("black_oil_agent_id").selectedIndex;
                var agent_id = document.getElementById("black_oil_agent_id").options[agent_id_selected_index].value;
            }

            var freight_unit = parseFloat(document.getElementById('black_oil_shortage_freight_unit_label').innerHTML);
            var shortage_rate = parseFloat(document.getElementById('black_oil_shortage_rate').value);

            var shortage_product = document.getElementById('black_oil_shortage_product').value;
            var destination_voucher = document.getElementById('black_oil_destination_voucher').value;

            var shortage_date = document.getElementById('black_oil_shortage_date').value;
            shortage_date = (shortage_date == '')?'none':shortage_date;
            var other_info = document.getElementById('black_oil_shortage_other_info').value;
            other_info = (other_info == '')?'none':other_info;

            //preparing the url
            url = "<?= base_url()."accounts/shortage_expense_voucher_for_black_oil/" ?>"+trip_id+"/"+trip_detail_id+"/"+shortage+"/"+shortage_at+"/"+price_unit+"/"+shortage_product+"/"+shortage_rate+"/"+freight_unit+"/"+agent_type+"/"+agent_id+"/"+shortage_date+"/"+other_info+"/"+destination_voucher;
            //setting the url
            var test_link = document.getElementById("black_oil_get_shortage_expense_voucher");
            test_link.setAttribute('href', url);
            test_link.className+=" mfp-close";
        }
    </script>
    <div>
        <div id="voucher_area">
            <script>

            </script>
            <div class="row" style="">
                <div class="col-lg-12 text-center" style="color: #269abc">
                    <h3>Add Shortage Expense | Black Oil</h3><hr>
                </div>
                <div class="col-md-12 center-block">

                    <!--Hidden fields-->
                    <input type="hidden" name="trip_detail_id" value="<?= $trip_detail_id ?>" id="black_oil_shortage_trip_detail_id" required="required">
                    <input type="hidden" name="trip_id" value="<?= $trip_id ?>" id="black_oil_shortage_trip_id" required="required">
                    <input type="hidden" name="shortage_product" <?= $shortage_product ?> id="black_oil_shortage_product">
                    <input type="hidden" name="destination_voucher" id="black_oil_destination_voucher" value="<?= $destination_voucher ?>">
                    <!--*********************************************-->

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Trip ID</label>
                        <div class="col-lg-8">
                            <span id="black_oil_shortage_trip_id_label"></span>
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Freight / Unit</label>
                        <div class="col-lg-8">
                            <span id="black_oil_shortage_freight_unit_label"></span>
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage</label>
                        <div class="col-lg-8">
                            <input onchange="black_oil_shortage_changed()" onkeyup="black_oil_shortage_changed()" class="form-control" type="number" step="any" required="required" id="black_oil_shortage_quantity" name="shortage" placeholder="shortage..">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage At</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="black_oil_shortage_at" name="shortage_at">
                                <option value="destination">Destination</option>
                                <option value="decanding">Decanding</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage Rate</label>
                        <div class="col-lg-8">
                            <input onchange="black_oil_shortage_changed()" onkeyup="black_oil_shortage_changed()" class="form-control" type="number" step="any" required="required" id="black_oil_shortage_rate" name="shortage_rate" placeholder="shortage rate..">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Price/Unit</label>
                        <div class="col-lg-8">
                            <input onchange="black_oil_shortage_changed()" onkeyup="black_oil_shortage_changed()" type="number" step="any" class="form-control" required="required" name="amount" id="black_oil_price_unit">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Related Agent (credit)</label>
                        <div class="col-md-4">
                            <select class="form-control" id="black_oil_agent_type" onchange="black_oil_agent_type_changed()" name="agent_type">
                                <option value="">None</option>
                                <option value="other_agents">Other Agent</option>
                                <option value="customers">Customer</option>
                                <option value="carriage_contractors">Contractor</option>>
                                <option value="companies">Company</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="black_oil_agents_area">

                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Shortage Amount</label>
                        <div class="col-lg-8">
                            <span id="black_oil_shortage_amount_label" style="font-style: italic; color: darkgrey;"></span>
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Frt On Shortage</label>
                        <div class="col-lg-8">
                            <span id="black_oil_freight_on_shortage_label" style="font-style: italic; color: darkgrey;"></span>
                        </div>
                    </div>

                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Date</label>
                        <div class="col-md-8">
                            <input class="form-control" id="black_oil_shortage_date" type="date" value="<?= date('Y-m-d') ?>" name="date">
                        </div>
                    </div>
                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label">Other Info:</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" value="" name="other_info" id="black_oil_shortage_other_info" maxlength="100">
                        </div>
                    </div>
                    <div class="col-sm-12 form-group" style="margin-top: 5px;">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-lg-8">
                            <a href="#" id="black_oil_get_shortage_expense_voucher" onclick="black_oil_get_shortage_expense_voucher()" class="fetch_shortage_expense_voucher btn btn-success" style="width: 100%;">Generate Voucher</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>
    <script>
        $('.fetch_shortage_expense_voucher').magnificPopup({
            type: 'ajax',
            showCloseBtn:false
        });
    </script>
</div>