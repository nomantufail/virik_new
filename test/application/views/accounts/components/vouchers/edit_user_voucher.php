<div class="white-popup" style="background-color: #ffffff;"><script>    function write_balance(id, balance)    {        var target = document.getElementById(id);        if(balance < 0){            balance *= -1;            target.innerHTML = '<span class="credit_balance">('+limit_number(balance)+')</span>';        }else{            target.innerHTML = '<span class="debit_balance">'+limit_number(balance)+'</span>';        }    }    function set_closing_balance(area_id)    {        var dr_cr_selected_index = document.getElementById('dr_cr_'+area_id).selectedIndex;        var dr_cr = document.getElementById('dr_cr_'+area_id).options[dr_cr_selected_index].value;        var amount = parseFloat(document.getElementById('amount_'+area_id).value);        var opening_balance = parseFloat(document.getElementById('opening_balance_input_'+area_id).value);        var closing_balance = opening_balance;        if(dr_cr == 1){            closing_balance += amount;        }else{            closing_balance -= amount;        }        write_balance('closing_balance_'+area_id, closing_balance);    }    function set_opening_balance(balance, area_id)    {        var opening_balance_input = document.getElementById('opening_balance_input_'+area_id);        opening_balance_input.value = balance;        write_balance('opening_balance_'+area_id, balance);    }    function display_row(){        var pannel_count = document.getElementById("pannel_count");        var row_id = "row_"+pannel_count.value;        document.getElementById(row_id).style.display='';        document.getElementById(row_id).style.width='100%';        pannel_count.value = parseInt(pannel_count.value)+1;        //we think that when a new row is added we should re_populate the form...        //i_think_route_changed(0,pannel_count.value-1);    }    function hide_row(){        var pannel_count = document.getElementById("pannel_count");        var decrease_count = parseInt(pannel_count.value)-1;        var row_id = "row_"+decrease_count;        if(pannel_count.value > 3){            document.getElementById(row_id).style.display='none';            pannel_count.value = parseInt(pannel_count.value)-1;        }    }    function fetch_tankers(trip_id, customer_id, callback){        var tankers_area = document.getElementById("voucher_tankers_area");        tankers_area.innerHTML = ".....";        $.get("<?=base_url()."accounts/fetch_tankers/"?>"+trip_id+"/"+customer_id,function(data,status){            tankers_area.innerHTML= data;            callback();        });    }    function fetch_agents(agent,area_id, callback){        var agents_area = document.getElementById("agent_id_area_"+area_id);        $.get("<?=base_url()."accounts/fetch_agents/"?>"+agent+"/"+area_id+"/"+"scrap"+"/"+"agent_id_"+area_id+"/"+"selected_agent_changed("+area_id+")",function(data,status){            agents_area.innerHTML= data;            callback();        });    }    function fetch_customers(trip_id, callback){        var customers_area = document.getElementById("voucher_customers_area");        customers_area.innerHTML = ".....";        var requested_customer = document.getElementById("requested_customer").value;        requested_customer = (requested_customer == '')?'none':requested_customer;        $.get("<?=base_url()."accounts/fetch_customers/"?>"+trip_id+"/"+requested_customer ,function(data,status){            customers_area.innerHTML= data;            callback();        });    }    function i_think_trip_changed()    {        var tankers_area = document.getElementById("voucher_tankers_area");        tankers_area.innerHTML = ".....";        var customers_area = document.getElementById("voucher_customers_area");        customers_area.innerHTML = ".....";        trip_id = document.getElementById('trip_id').value;        if(trip_id == '' || trip_id < 1){            trip_id = "null";            fetch_customers(trip_id, function(){                i_think_customer_changed();            })        }else{            trip_id = parseInt(trip_id);            fetch_tankers(trip_id,'null', function() {                fetch_customers(trip_id, function(){                })            });        }    }    function i_think_customer_changed()    {        var customers_selected_index = document.getElementById("customers").selectedIndex;        var customer_id = document.getElementById("customers").options[customers_selected_index].value;        fetch_tankers('null', customer_id, function(){            //nothing here..        });    }    function selected_agent_changed(area_id)    {        var id = "agent_type_"+area_id;        var agent_type_selected_index = document.getElementById(id).selectedIndex;        var agent_type = document.getElementById(id).options[agent_type_selected_index].value;        var agent_id = selected_agent_id(area_id);        document.getElementById('opening_balance_'+area_id).innerHTML = '<span style="font-size: 12px;">Loading..</span>';        document.getElementById('closing_balance_'+area_id).innerHTML = '<span style="font-size: 12px;">Loading..</span>';        //now its time to fetch the opening balance        fetch_balance(agent_type,agent_id, function(balance){            //alert(balance);            //set_opening_balance(balance, area_id);            //set_closing_balance(area_id);        })    }    function amount_changed(area_id)    {        set_closing_balance(area_id);        var balance_target = document.getElementById('voucher_balance');        var pannel_count = document.getElementById("pannel_count");        var debit_amount = 0;        var credit_amount = 0;        for(var count = 0; count<(pannel_count.value-1); count++){            var amount = document.getElementsByClassName("amount")[count].value;            amount = (amount == '')?0:parseFloat(amount);            var payment_type_selected_index = document.getElementsByClassName("payment_type")[count].selectedIndex;            var payment_type = document.getElementsByClassName("payment_type")[count].options[payment_type_selected_index].value;            if(payment_type == '1'){                debit_amount = debit_amount+amount;            }else{                credit_amount += amount;            }        }        var balance = debit_amount - credit_amount;        balance_target.innerHTML =limit_number(balance);    }    function fetch_balance(agent_type,agent_id, callback){        $.get("<?=base_url()."accounts/fetch_balance/"?>"+agent_type+"/"+agent_id,function(data,status){            callback(data);        });    }    function selected_agent_id(area_id)    {        var id = "agent_id_"+area_id;        var agent_selected_index = document.getElementById(id).selectedIndex;        var agent_id = document.getElementById(id).options[agent_selected_index].value;        return agent_id;    }    function agent_type_changed(area_id)    {        var id = "agent_type_"+area_id;        var agent_type_selected_index = document.getElementById(id).selectedIndex;        var agent_type = document.getElementById(id).options[agent_type_selected_index].value;        if(agent_type != ''){            document.getElementById('agent_id_area_'+area_id).innerHTML = '<span style="font-size:12px; font-style: italic; color: gray;">Loading...</span>';            fetch_agents(agent_type, area_id,function(){                var agent_id = selected_agent_id(area_id);                /*document.getElementById('opening_balance_'+area_id).innerHTML = '<span style="font-size: 12px;">Loading..</span>';                document.getElementById('closing_balance_'+area_id).innerHTML = '<span style="font-size: 12px;">Loading..</span>';                //now its time to fetch the opening balance                fetch_balance(agent_type,agent_id, function(balance){                    //alert(balance);                    set_opening_balance(balance, area_id);                    set_closing_balance(area_id);                })*/                //now its time to fetch closing blance            });        }else{            document.getElementById('agent_id_area_'+area_id).innerHTML = '';        }    }    //here is the stuff we do when document is ready...    $( document ).ready(function() {        //i_think_trip_changed();        agent_type_changed(1);        agent_type_changed(2);        amount_changed(1);    });    function validate_voucher_form()    {        if(parseInt(document.getElementById("voucher_balance").innerHTML) != 0){            document.getElementById("balance_area").className+=' some_problem';            alert("Balance in not zero...\n please recheck your entries.");            return false;        }        var pannel_count = document.getElementById("pannel_count");        var total_debits = 0;        var total_credits = 0;        for(var count = 0; count<(pannel_count.value-1); count++){            var payment_type = document.getElementsByClassName("payment_type")[count].selectedIndex;            var payment_type_value = document.getElementsByClassName("payment_type")[count].options[payment_type].value;            if(payment_type_value == 1){                total_debits++;            }else if(payment_type_value == 0){                total_credits++;            }        }        if(total_credits == 0){            alert("WARNING!\nAll entries cannot be debit.\nAtleast one of them should be credit.");            return false;        }        if(total_debits == 0){            alert("WARNING!\nAll entries cannot be credit.\nAtleast one of them should be debit.");            return false;        }        return true;    }</script><style>    .voucher_title{        font-size: 18px;        font-weight: bold;        color: #0088cc;    }    .voucher_date{        font-weight: bold;        font-size: 14px;    }    .voucher_form input{        height: 25px;    }    .un_balanced{        color: red;    }    .some_problem{        border: 2px dashed red;    }    .debit_balance{        color: green;    }    .credit_balance{        color: red;    }</style><div class="row">    <form method="post" class="voucher_form" onsubmit="return validate_voucher_form()">        <!--hidden fields-->        <!--<input type="hidden" name="trips_details_ids" value="<?/*= $details_ids */?>">-->        <!---->        <div class="col-lg-12">            <div class="col-sm-12" style="border: 0px solid red;">                <div class="voucher_title col-sm-6">Voucher</div>                <div class="voucher_date col-sm-6 text-right"><input type="date" name="voucher_date" value="<?= date('Y-m-d') ?>" required="required"></div>            </div>            <div class="col-lg-12" style="overflow-x: auto; border: 0px solid red;">                <table class="table table-bordered"  style="font-size: 12px; overflow: auto;">                    <tr>                        <td>                            <div class="panel-body">                                <table style="font-size: 12px;" class="product_details_table table">                                    <thead>                                    <tr id="">                                        <th>Tr_Title</th>                                        <th>Description</th>                                        <th>Agent</th>                                        <th>Amount</th>                                        <th>Dr/Cr</th>                                        <th>Balance (opn)</th>                                        <th>Balance (cls)</th>                                    </tr>                                    </thead>                                    <tbody>                                    <?php $row_counter = 0; ?>                                    <?php foreach($voucher->entries as $voucher_entry): ?>                                        <?php $row_counter++; ?>                                        <tr id="row_<?= $row_counter ?>" style="display: <?= ($row_counter > 2)?"none":"visible"; ?>; width: 100%;">                                            <td>                                                <select style="width: 100px;" name="<?= "tr_title_".$row_counter; ?>">                                                    <?php                                                    foreach($titles as $title)                                                    {                                                        $selected = ($voucher_entry->account_title_id == $title->id)?"selected":'';                                                        echo"<option value=".$title->id." $selected>".$title->title."</option>";                                                    }                                                    ?>                                                </select>                                            </td>                                            <td>                                                <input type="text" placeholder="other info..." name="<?= "description_".$row_counter; ?>">                                            </td>                                            <td>                                                <select class="agent_type" onchange="agent_type_changed(<?= $row_counter ?>)" id="<?= "agent_type_".$row_counter; ?>" name="<?= "agent_type_".$row_counter; ?>">                                                    <option value="other_agents" <?= ($voucher_entry->related_agent == 'other_agents')?'selected':'' ?> >Other Agent</option>                                                    <option value="customers" <?= ($voucher_entry->related_agent == 'customers')?'selected':'' ?> >Customer</option>                                                    <option value="carriage_contractors" <?= ($voucher_entry->related_agent == 'carriage_contractors')?'selected':'' ?> >Contractor</option>                                                    <option value="companies" <?= ($voucher_entry->related_agent == 'companies')?'selected':'' ?> >Company</option>                                                    <option value="">-- select --</option>                                                </select>                                                <span id="<?= "agent_id_area_".$row_counter; ?>">                                                     <?php if($voucher_entry->related_agent != 'self'): ?>                                                         <select name="<?= "agent_id_".$row_counter; ?>">                                                             <?php                                                             foreach($voucher_entry->related_agents_list as $agent)                                                             {                                                                 $selected = ($voucher_entry->related_agent_id == $agent->id)?"selected":'';                                                                 echo"<option value=".$agent->id." $selected>".$agent->name."</option>";                                                             }                                                             ?>                                                         </select>                                                     <?php endif; ?>                                                </span>                                            </td>                                            <td>                                                <?php $total_amount = (($voucher_entry->dr_cr == 'credit')?$voucher_entry->credit:$voucher_entry->debit); ?>                                                <input <?= (($row_counter < 3)?'required=required':'') ?> id="amount_<?= $row_counter ?>" onkeyup="amount_changed(<?= $row_counter ?>)" onchange="amount_changed(<?= $row_counter ?>)" type="number" step="any" min="<?= $total_amount ?>" max="<?= $total_amount ?>" <?= (($row_counter <3)?'required':'') ?> class="amount" name="<?= "amount_".$row_counter; ?>" placeholder="amount.." value="<?= $total_amount ?>"></td>                                            <td>                                                <select id="dr_cr_<?= $row_counter ?>" class="payment_type" onchange="amount_changed(<?= $row_counter ?>)" name="<?= "payment_type_".$row_counter; ?>">                                                    <option value="1" <?= (($voucher_entry->dr_cr == 'debit')?'selected':''); ?> >Debit</option>                                                    <option value="0" <?= (($voucher_entry->dr_cr == 'credit')?'selected':''); ?> >Credit</option>                                                </select>                                            </td>                                            <td>                                                <input type="hidden" id="opening_balance_input_<?= $row_counter ?>">                                                <div id="opening_balance_<?= $row_counter ?>"></div>                                            </td>                                            <td>                                                <div id="closing_balance_<?= $row_counter ?>"></div>                                            </td>                                        </tr>                                    <?php endforeach; ?>                                    </tbody>                                    <tfoot>                                    <tr style="border-bottom: 0px; display: table-row; font-size: 13px;">                                        <td><a style="text-decoration: none; cursor: default; color: darkgray" onclick="display_row()"><i class="fa fa-plus-circle"></i> Add Entry</a></td><td><a   style="float: right; color: red; cursor: default; text-decoration: none; color: darkgrey;" onclick="hide_row()"><i class="fa fa-minus-circle"></i> remove</a><input size="2" value="3" type="hidden" id="pannel_count" name="pannel_count"></td>                                    </tr>                                    </tfoot>                                </table>                            </div>                        </td>                    </tr>                    <tr>                        <td>                            <div class="col-sm-2 text-center" id="balance_area">                                <strong>Balance: </strong><span class="balance" id="voucher_balance"></span>                            </div>                            <div class="col-sm-8">                                <input type="text" class="form-control" name="voucher_details" placeholder="your voucher details... if any" style="width: 100%;">                            </div>                            <div class="col-sm-2 text-center">                                <input type="submit" name="save_voucher" value="Save" style="background-color: #31708f; color: #ffffff;">                            </div>                        </td>                    </tr>                </table>            </div>        </div>    </form></div></div>