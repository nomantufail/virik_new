<script>

/*--------------- Pre loading Area ------------------*/

var All_Freights = {};
<?php foreach($freights as $group): ?>
All_Freights["<?= $group[0]->source."_".$group[0]->destination."_".$group[0]->product ?>"] = <?= json_encode($group) ?>;
<?php endforeach; ?>

var Contractor_Customer_Commissions = {};
<?php foreach($contractor_customer_commissions as $record): ?>
Contractor_Customer_Commissions["<?= $record->key ?>"] = <?= json_encode($record) ?>;
<?php endforeach; ?>

var Contractor_Company_Commissions = {};
<?php foreach($contractor_company_commissions as $record): ?>
Contractor_Company_Commissions["<?= $record->key ?>"] = <?= json_encode($record) ?>;
<?php endforeach; ?>

/*---------------------------------------------------*/

function check_form_validations(){
    //alert();
    var fault_pannels = '';
    var pannel_count = document.getElementById("pannel_count").value;
    for(var count = 0; count <( pannel_count-1); count ++){
        var company_freight_unit = document.getElementsByClassName('company_freight_unit_1')[count];
        var freight_unit = document.getElementsByClassName('freight_unit_1')[count];
        if(company_freight_unit.value != freight_unit.value){
            fault_pannels = fault_pannels+''+(count+1)+', ';
        }
    }
    if(fault_pannels != ''){
        var msg = 'WARNING!\n'+'company freight/unit and customer freight/unit is not same in product details# '+fault_pannels+'';
        if(confirm(msg) == false){
            return false;
        }
    }

    var start_meter = document.getElementById('start_meter_reading').value;
    var end_meter = document.getElementById('end_meter_reading').value;
    if((end_meter - start_meter) < 0){
        alert("WARNING!\nend meter reading cannot be less than start meter reading.\nPlease correct.");
        return false;
    }

    /*
     * -----------------------------
     * CHECKING IF THE DATES ARE OK
     * -----------------------------
     */
    var entry_date = document.getElementById('entry_date').value;
    var filling_date = document.getElementById('filling_date').value;
    var email_date = document.getElementById('email_date').value;
    var receiving_date = document.getElementById('receiving_date').value;
    var stn_receiving_date = document.getElementById('stn_receiving_date').value;
    var decanding_date = document.getElementById('decanding_date').value;
    entry_date = (entry_date != '')?new Date(entry_date):'';
    filling_date = (filling_date != '')?new Date(filling_date):'';
    email_date = (email_date != '')?new Date(email_date):'';
    receiving_date = (receiving_date != '')?new Date(receiving_date):'';
    stn_receiving_date = (stn_receiving_date != '')?new Date(stn_receiving_date):'';
    decanding_date = (decanding_date != '')?new Date(decanding_date):'';
    var start_date_limit = new Date('<?= $date_limits['year_before'] ?>');
    var end_date_limit = new Date('<?= $date_limits['year_after'] ?>')
    if(entry_date != '' && (entry_date < start_date_limit || entry_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks entry date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    if(email_date != '' && (email_date < start_date_limit || email_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks email date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    if(filling_date != '' && (filling_date < start_date_limit || filling_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks filling date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    if(receiving_date != '' && (receiving_date < start_date_limit || receiving_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks receiving date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    if(stn_receiving_date != '' && (stn_receiving_date < start_date_limit || stn_receiving_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks stn receiving date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    if(decanding_date != '' && (decanding_date < start_date_limit || decanding_date > end_date_limit)){
        var msg = 'WARNING!\n'+'System thinks decanding date is not ok!';
        if(confirm(msg) == false){
            return false;
        }
    }
    /*
     * ***********************************************************************************************/
    return true;
}
function fetch_tankers(customer_id, callback){
    var tankers_area = document.getElementById("tankers_area");
    tankers_area.innerHTML = "please wait...";

    $.get("<?=base_url()."trips/fetch_tankers/"?>"+customer_id,function(data,status){
        tankers_area.innerHTML= data;
        callback();
    });
}

function show_selected_tanker_info(tanker_id, callback){
    var tanker_info = document.getElementById("tanker_info");
    tanker_info.innerHTML = "loading...";

    $.get("<?=base_url()."trips/fetch_tanker/"?>"+tanker_id,function(data,status){
        tanker_info.innerHTML= data;
        callback();
    });
}

function fetch_customer_contractor_commission(customer_id, contractor_id, callback){
    var key = contractor_id+'_'+customer_id;
    var commission = {
        'freight_commission':null
    };
    if(Contractor_Customer_Commissions[''+key] == undefined)
    {
        callback(commission);
        return;
    }

    commission.freight_commission = Contractor_Customer_Commissions[''+key].freight_commission;
    callback(commission);
}
function fetch_company_contractor_commission(company_id, contractor_id, callback){
    var key = contractor_id+'_'+company_id;
    var commission = {
        'commission_1':null,
        'wht' : null
    };
    if(Contractor_Company_Commissions[''+key] == undefined)
    {
        callback(commission);
        return;
    }
    commission.commission_1 = Contractor_Company_Commissions[''+key].commission_1;
    commission.wht = Contractor_Company_Commissions[''+key].commission_2;
    callback(commission);
}

function fetch_freight(sourceCity, destinationCity, product, callback){
    var date = document.getElementById('filling_date').value;
    var key = sourceCity+"_"+destinationCity+"_"+product;
    var freight = get_freight_by_key(key, date);
    callback(freight);
}
function fetch_source_destination(route_id, callback)
{
    $.get("<?=base_url()."routes/source_destination/"?>"+route_id,function(data,status){
        var route = JSON.parse(data);
        callback(route);
    });
}

function fetch_meter_reading(tanker_id, callback){
    $.get("<?=base_url()."trips/fetch_meter_reading/"?>"+tanker_id, function(data,status){
        var meter_reading = JSON.parse(data);
        callback(meter_reading);
    });
}

function tanker_changed(dropdown){
    var myindex  = dropdown.selectedIndex;
    var tanker_id = dropdown.options[myindex].value;
    show_selected_tanker_info(tanker_id,function(){
        fetch_meter_reading(tanker_id, function(meter_reading){
            document.getElementById('start_meter_reading').value = meter_reading.end;
            document.getElementById('end_meter_reading').value = meter_reading.end;

            meter_reading_changed();
        });
    })
}
function take_care_of_product_info_and_commissions_data(callback){
    var customer_selected_index = document.getElementById("customers").selectedIndex;
    var customer_id = document.getElementById("customers").options[customer_selected_index].value;

    var contractors_selected_index = document.getElementById("contractors").selectedIndex;
    var contractor_id = document.getElementById("contractors").options[contractors_selected_index].value;

    var companies_selected_index = document.getElementById("companies").selectedIndex;
    var company_id = document.getElementById("companies").options[companies_selected_index].value;

    //empty those feilds before you put new data into them
    var contractor_commission_input = document.getElementById("contractor_commission_input");
    var remaining_for_customer_area = document.getElementById("customer_freight");
    var company_commission_1_input = document.getElementById("company_commission_1_input");
    var wht_input = document.getElementById("company_commission_2_input");
    contractor_commission_input.value = "";
    remaining_for_customer_area.innerHTML = "";
    company_commission_1_input.value = "";
    wht_input.value = "";
    //fetching cutomer_contractor_commission and setting it to its objectives...
    fetch_customer_contractor_commission(customer_id, contractor_id, function(result) {
        //setting contractor commission
        contractor_commission_input.value = (result.freight_commission != null)?result.freight_commission: '';
        //setting remaining for customer percentage
        remaining_for_customer_area.innerHTML = (100 - contractor_commission_input.value);
        //setting data related to product info panels

        var pannel_count = document.getElementById("pannel_count");
        for(var count = 0; count<(pannel_count.value-1); count++){
            var initial_quantity_input = document.getElementsByClassName("initial_product_quantity_1")[count];
            var freight_unit_input = document.getElementsByClassName("freight_unit_1")[count];
            var total_freight_rs = document.getElementsByClassName("total_freight_rs_1")[count];
            var price_unit_input = document.getElementsByClassName("price_unit_1")[count];
            var contractor_commission_rs = document.getElementsByClassName("contractor_commission_rs_1")[count];
            var remaining_for_customer_rs = document.getElementsByClassName("remaining_for_customer_rs_1")[count];
            //setting contractor commission rupees in products info area..
            contractor_commission_rs.innerHTML = contractor_commission_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input.value)/100;
            //setting remaining for customer rupees in products info area..
            remaining_for_customer_rs.innerHTML = ((((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input.value) - contractor_commission_rs.innerHTML);

        }//for loop ends here..

        //fetching_contractor_company_commission and setting it to its objectives...
        fetch_company_contractor_commission(company_id, contractor_id, function(result) {
            company_commission_1_input.value = result.commission_1;
            wht_input.value = result.wht;

            var pannel_count = document.getElementById("pannel_count");
            for(var count = 0; count<(pannel_count.value-1); count++){
                var initial_quantity_input = document.getElementsByClassName("initial_product_quantity_1")[count];
                var freight_unit_input = document.getElementsByClassName("freight_unit_1")[count];
                var company_commission_1_rs = document.getElementsByClassName("company_commission_1_rs_1")[count];
                var company_wht_rs_1 = document.getElementsByClassName("wht_rs_1")[count];
                //setting company commissions rupees in products info area..
                company_commission_1_rs.innerHTML = company_commission_1_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input.value)/100;
                company_wht_rs_1.innerHTML = wht_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input.value)/100;

            }//for loop ends here...

        })
    });

    callback();
}
function customer_changed(dropdown){
    var myindex  = dropdown.selectedIndex;
    var customer_id = dropdown.options[myindex].value;

    /*
     below we will fetch tankers and afer successfully rendring them we will fetch selected tanker info
     and render it in tanker info pannel
     */
    take_care_of_product_info_and_commissions_data(function(){
        fetch_tankers(customer_id, function() {
            var tankers_selected_index = document.getElementById("tankers").selectedIndex;
            var tanker_id = document.getElementById("tankers").options[tankers_selected_index].value;

            show_selected_tanker_info(tanker_id, function() {

            });
        });
    });

    return true
}

function contractor_changed(){
    take_care_of_product_info_and_commissions_data(function() {

    });
}

function company_changed(){
    take_care_of_product_info_and_commissions_data(function() {

    });
}

function meter_reading_changed()
{
    var start = parseFloat(document.getElementById('start_meter_reading').value);
    var end = parseFloat(document.getElementById('end_meter_reading').value);
    var fuel = parseFloat(document.getElementById('fuel_consumed').value);
    var distance = end - start;
    var fuel_km = 0;
    if(fuel > 0 && distance > 0){
        fuel_km = limit_number(distance / fuel);
    }

    document.getElementById('fuel_per_km').innerHTML = fuel_km;
    document.getElementById('net_meter').innerHTML = distance;
}

function i_think_route_changed(route_type,count,section){
    var type = route_type;
    if(route_type == undefined)
    {
        type = '1';
    }

    //below we are checking weather this function should countinue or return...
    var pannel_count = document.getElementById("pannel_count");
    if(section === undefined){
        if(count === undefined)
            count = 0;

        if(count >=(pannel_count.value-1)) return;
    }else{
        count = section-1;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //fetching selected sourceCity
    var route_index = document.getElementsByClassName("route")[count].selectedIndex;
    var route_input_value = document.getElementsByClassName("route")[count].options[route_index].value;

    route_input_value = route_input_value.split('_');
    var source_id = route_input_value[0];
    var destination_id = route_input_value[1];
    var product_id = route_input_value[2];
    fetch_freight(source_id, destination_id, product_id, function(freight) {
        var freight_unit = document.getElementsByClassName("freight_unit_1")[count];
        var company_freight_unit = document.getElementsByClassName("company_freight_unit_1")[count];
        freight_unit.value = freight.freight;
        company_freight_unit.value = freight.freight;

        //now setting every thing else...
        i_think_quantity_changed();
        //increment the counter and re-calling the function for muliple products effetct..
        if(section === undefined){
            count++;

            i_think_route_changed(type, count);
        }
        //////////////////////////////////////////////////////////////////////////////////////
    });

}//function ends here...

function i_think_quantity_changed(panel){
    var pannel_count = document.getElementById("pannel_count");
    for(var count = 0; count<(pannel_count.value-1); count++){
        var initial_quantity_input_1 = document.getElementsByClassName("initial_product_quantity_1")[count];
        var freight_unit_input = document.getElementsByClassName("freight_unit_1")[count];
        var total_freight_rs = document.getElementsByClassName("total_freight_rs_1")[count];
        var price_unit_input = document.getElementsByClassName("price_unit_1")[count];
        var shortage_at_destination_input_1 = document.getElementsByClassName("shortage_at_destination_1")[count];
        var shortage_after_decanding_input_1 = document.getElementsByClassName("shortage_after_decanding_1")[count];
        var quantity_at_destination_1 = document.getElementsByClassName("quantity_at_destination_1")[count];
        var quantity_after_decanding_1 = document.getElementsByClassName("quantity_after_decanding_1")[count];
        var total_shortage = document.getElementsByClassName("total_shortage_1")[count];
        var expense_at_destination_rs_1 = document.getElementsByClassName("expense_at_destination_rs_1")[count];
        var expense_after_decanding_rs_1 = document.getElementsByClassName("expense_after_decanding_rs_1")[count];
        var total_product_expense = document.getElementsByClassName("total_expense_rs_1")[count];
        var contractor_commission_input = document.getElementById("contractor_commission_input");
        var company_commission_1_input = document.getElementById("company_commission_1_input");
        var wht_input = document.getElementById("company_commission_2_input");

        total_freight_rs.innerHTML = limit_number(((initial_quantity_input_1.value != '')?initial_quantity_input_1.value:0) * ((freight_unit_input.value !='')?freight_unit_input.value:0));
        quantity_at_destination_1.innerHTML = limit_number(((initial_quantity_input_1.value != '')?initial_quantity_input_1.value:0) - ((shortage_at_destination_input_1.value != 0)?shortage_at_destination_input_1.value:0));
        quantity_after_decanding_1.innerHTML = limit_number(parseFloat(quantity_at_destination_1.innerHTML) - ((shortage_after_decanding_input_1.value != 0)?shortage_after_decanding_input_1.value:0));
        total_shortage.innerHTML = limit_number(parseFloat(((shortage_after_decanding_input_1.value != 0)?shortage_after_decanding_input_1.value:0)) + parseFloat(((shortage_at_destination_input_1.value != 0)?shortage_at_destination_input_1.value:0)));
        expense_at_destination_rs_1.innerHTML = limit_number(((price_unit_input.value != 0)?price_unit_input.value:0) * ((shortage_at_destination_input_1.value != 0)?shortage_at_destination_input_1.value:0));
        expense_after_decanding_rs_1.innerHTML = limit_number(((price_unit_input.value != 0)?price_unit_input.value:0) * ((shortage_after_decanding_input_1.value != 0)?shortage_after_decanding_input_1.value:0));
        total_product_expense.innerHTML = limit_number(parseFloat(expense_at_destination_rs_1.innerHTML) + parseFloat(expense_after_decanding_rs_1.innerHTML));

        var company_commission_1_rs = document.getElementsByClassName("company_commission_1_rs_1")[count];
        var company_wht_rs_1 = document.getElementsByClassName("wht_rs_1")[count];
        //setting company commissions rupees in products info area..
        company_commission_1_rs.innerHTML = limit_number(company_commission_1_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input_1.value)/100);
        company_wht_rs_1.innerHTML = limit_number(wht_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input_1.value)/100);

        var contractor_commission_rs = document.getElementsByClassName("contractor_commission_rs_1")[count];
        var remaining_for_customer_rs = document.getElementsByClassName("remaining_for_customer_rs_1")[count];
        //setting contractor commission rupees in products info area..
        contractor_commission_rs.innerHTML = limit_number(contractor_commission_input.value * (((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input_1.value)/100);
        //setting remaining for customer rupees in products info area..
        remaining_for_customer_rs.innerHTML = limit_number((((freight_unit_input.value !='')?freight_unit_input.value:0) * initial_quantity_input_1.value) - contractor_commission_rs.innerHTML);

        //setting customer percentage on changing commissions inputs...
        var remaining_for_customer_percentage = document.getElementById("customer_freight");
        remaining_for_customer_percentage.innerHTML = 100-contractor_commission_input.value;

    }//for loop ends....


}   //function ends here...

function display_row(){
    var pannel_count = document.getElementById("pannel_count");
    var row_id = "row_"+pannel_count.value;
    document.getElementById(row_id).style.display='table';
    document.getElementById(row_id).style.width='100%';

    //making new displayed fields required
    document.getElementById("company_freight_unit_"+pannel_count.value).required = true;
    document.getElementById("freight_unit_"+pannel_count.value).required = true;
    ////////////////////////////////////////////////////////////////////////////////////

    pannel_count.value = parseInt(pannel_count.value)+1;

    //we think that when a new row is added we should re_populate the form...
    <?php
            $route_type = 0;
            if($this->uri->segment(3) == 'local_self')
            {
                $route_type = 4;
            }else if($this->uri->segment(3) == 'local_cmp'){
                $route_type = 3;
            }
    ?>
    i_think_route_changed(<?= $route_type ?>,0,pannel_count.value-1);
}

function hide_row(){
    var pannel_count = document.getElementById("pannel_count");
    var decrease_count = parseInt(pannel_count.value)-1;
    var row_id = "row_"+decrease_count;
    if(pannel_count.value > 2){
        document.getElementById(row_id).style.display='none';
        pannel_count.value = parseInt(pannel_count.value)-1;
    }
}

//here is the stuff we do when document is ready...
$( document ).ready(function() {
    //setting the tanker info in the tanker inof pannel on page load..
    //tanker_changed(document.getElementById("tankers"));
    customer_changed(document.getElementById("customers"));
    //fetching the freight/unit for the selected route in product info....
    <?php
            $route_type = 0;
            if($this->uri->segment(3) == 'local_self')
            {
                $route_type = 1;
            }else if($this->uri->segment(3) == 'local_cmp'){
                $route_type = 3;
            }
    ?>
    i_think_route_changed(<?= $route_type ?>);
    meter_reading_changed();

});
$(document).ajaxComplete(function(){

});
</script>