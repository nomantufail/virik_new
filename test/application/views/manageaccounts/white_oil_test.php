<script>

    var ids = "";
    function set_dr_cr_entry_link(link_id, type)
    {
        ids = get_selected_ids();
        //preparing the url
        var url = "<?= base_url()."manageaccounts/open_voucher_for_user/"?>"+type+"/white_oil";
        //setting the url
        var link = document.getElementById(link_id);
        link.setAttribute('href', url);
    }

    function get_selected_ids()
    {
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_ids = trip_ids+"_"+check_boxes[count].value;
            }
        }

        return trip_ids;
    }

    function show_monthly_results(month, path){

        var path = path+month;

        window.location.href = path;

    }

    function mass_payment(){
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_ids = trip_ids+"_"+check_boxes[count].value;
            }
        }

        //preparing the url
        var url = "<?= base_url()."carriageContractors/contractor_mass_payment/" ?>"+trip_ids;
        //setting the url
        var mass_payment_link = document.getElementById("mass_payment_link");
        mass_payment_link.setAttribute('href', url);
    }

    function mass_credit(voucher_type){
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_ids = trip_ids+"_"+check_boxes[count].value;
            }
        }

        //preparing the url
        var url = "<?= base_url()."carriageContractors/contractor_mass_credit/" ?>"+trip_ids+"/"+voucher_type;
        //setting the url
        var mass_credit_link = "";
        if(voucher_type == 1){
            mass_credit_link = document.getElementById("commission_mass_credit_link");
        }else{
            mass_credit_link = document.getElementById("service_charges_mass_credit_link");
        }

        mass_credit_link.setAttribute('href', url);
    }

    function set_details_ids_for_billing()
    {
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_details_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_details_ids = trip_details_ids+"_"+check_boxes[count].value;
            }
        }
        document.getElementById("details_ids_for_billing").value = trip_details_ids;
        if(document.getElementById("details_ids_for_billing").value != '')
        {
            return true;
        }
        return false;
    }

    function set_details_ids_for_un_billing()
    {
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_details_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_details_ids = trip_details_ids+"_"+check_boxes[count].value;
            }
        }
        var choice = confirm("ALERT!!\nDear User are you sure you want to Un-Bill selected trips?");
        if(choice == true)
        {
            document.getElementById("un_bill_trips_ids").value = trip_details_ids;
            if(document.getElementById("un_bill_trips_ids").value != '')
            {
                return true;
            }
        }
        return false;
    }

    /*function set_contractor_credit_chit_info(trip_id, trip_detail_id, total_credit_amount, voucher_type)
     {
     document.getElementById('contractor_credit_voucher_trip_detail_id').value = trip_detail_id;
     document.getElementById('contractor_credit_voucher_trip_id').value = trip_id;
     document.getElementById('contractor_credit_voucher_trip_id_label').innerHTML = trip_id;
     document.getElementById('contractor_credit_voucher_type').value = voucher_type;
     document.getElementById('contractor_total_creditable_amount').value = total_credit_amount;

     var test_link = document.getElementById("get_contractor_credit_voucher");
     test_link.setAttribute('href', '#');
     test_link.className="btn btn-success";

     }*/
</script>

<style>
    .dr_cr_btn{
        font-size: 12px;
    }
    .white-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 500px;
        margin: 20px auto;
    }
    .benefits-details-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 500px;
        margin: 20px auto;
    }

    .custom-accounts-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .bill-trips-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 400px;
        margin: 20px auto;
    }

    .contractor_credit_chit_popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .voucher{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        margin: 20px auto;
    }
    .accounts-table{

        font-size: 11px;

    }
    .multiple_entites{
        border-bottom: 1px dashed lightgray;
    }
    .page-header{
        border-bottom: none;
    }
    .paid{
        background-color: rgba(0,255,0,0.2);
    }
    .unpaid{
        background-color: rgba(255,0,0,0.0);
    }
    .sortable-table-heading{
        display: block;
        width: 100%;
        color: #0088cc;
    }
    .sortable-table-heading:hover{
        color: #0088cc;
        text-decoration: underline;
    }
    .dr_cr_status{
        text-decoration: underline;
        color:white;
        text-align: center;
        font-weight: bold;
        background-color: green;
    }
</style>

<div id="page-wrapper">
<div class="container-fluid">
<!--upper navigation-->
<div class="row">
    <div class="col-lg-12">
        <section class="col-md-5">
            <h3 class="page-header">
                Manage Accounts <?= ($this->uri->segment(2) == 'white_oil')?'White Oil':'Black Oil' ?>
            </h3>
        </section>
    </div>
</div>

<!--body of accounts-->
<div class="row">

<?php
include_once(APPPATH."views/manageaccounts/components/custom_search.php");
?>

<!--this area is hedden from the view and is used for billing-->
<div id="bill-trips-popup" class="bill-trips-popup mfp-hide">
    <style></style>
    <script>
    </script>
    <div><h3 style="text-align: center;">Billing Trips</h3></div><hr>
    <form action="" method="post">
        <div class="row">
            <div class="col-lg-2" style="height: 10px;"></div><div class="col-lg-8"><b>Bill Date: </b><input type="date" name="bill_date" required="required" value="<?= date('Y-m-d') ?>" id="bill_date_time"></div>
        </div>

        <input type="hidden" name="details_ids" id="details_ids_for_billing">
        <input type="hidden" name="bill_trips">
        <hr>
        <input type="submit" name="bill_trips" value="Bill Trips" class="center-block btn btn-success">
    </form>
</div>
<!--***********************************************************************-->

<!--------------------Searched Queries----------------------------->
<?php
if(sizeof($_GET) > 0)
{
    include_once(APPPATH."views/manageaccounts/components/searched_queries.php");
}
?>
<!----------------------------------------------------------------->

<div class="col-lg-12">
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">

                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                                            <strong>Error! </strong>', '</div>');
?>

<?php if(is_array($someMessage)){ ?>

    <div class="alert <?= $someMessage['type']; ?> alert-dismissible" role="alert">

        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

        <?= $someMessage['message']; ?>

    </div>

<?php } ?>

<div class="panel-body">

<div id="myTabContent" class="tab-content" style="min-height: 500px;">

<div class="tab-pane fade in active" id="customers">

<div class="table-responsive">
<table style="width: 100%;">
    <tr>
        <td style="width: 50%;">
            <div class="col-md-3">
                <a href="<?= base_url()."manageaccounts/open_custom_voucher_for_user" ?>" class="open_custom_voucher_for_user btn btn-danger"><i class="fa fa-plus-circle"></i> Voucher</a>
            </div>
            <div class="col-md-3">
                <a href="#bill-trips-popup" onclick="set_details_ids_for_billing()" class="open-bill-trips-popup btn btn-primary">Bill Trips</a>
            </div>
            <div class="col-md-2">
                <form method="post" onsubmit="return set_details_ids_for_un_billing()">
                    <input type="hidden" name="un_bill_trips">
                    <input type="hidden" id="un_bill_trips_ids" name="un_bill_trips_ids" required="required">
                    <input type="submit" class="btn btn-warning" value="Un-Bill Trips" name="un_bill">
                </form>
            </div>
        </td>
        <td style="text-align: right"><a href="#custom-accounts-popup" class="open-custom-accounts-popup btn btn-success" style="border-radius: 0px;">Create Custom Accounts</a> </td>
    </tr>
</table>
<form name="selection_form" id="selection_form" method="post" action="<?php
if(strpos($this->helper_model->page_url(),'?') == false){
    echo $this->helper_model->page_url()."?";
}else{echo $this->helper_model->page_url()."&";}
?>print">
<table class="table table-bordered table-hover table-striped accounts-table sortable" style="min-width:1900px;">

<thead style="border-top: 4px solid lightgray;">


<tr>
    <th></th>
    <?= Sort::createCheckBoxes("manage_accounts_white_oil"); ?>
    <th></th>
</tr>
<tr>
    <th colspan="7"></th>
    <th><!--<a href="#" onclick="set_dr_cr_entry_link('shortage_amount_entry_link','shortage_amount')" id="shortage_amount_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a>--></th>
    <th colspan="4"></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('company_total_freight_entry_link','company_total_freight')" id="company_total_freight_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('company_wht_entry_link','company_wht')" id="company_wht_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('company_commission_entry_link','company_commission')" id="company_commission_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('contractor_freight_entry_link','contractor_freight')" id="contractor_freight_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('contractor_freight_without_shortage_entry_link','contractor_freight_without_shortage')" id="contractor_freight_without_shortage_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('contractor_commission_entry_link','contractor_commission')" id="contractor_commission_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th colspan="3"></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('customer_freight_entry_link','customer_freight')" id="customer_freight_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('customer_freight_without_shortage_entry_link','customer_freight_without_shortage')" id="customer_freight_without_shortage_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th><a href="#" onclick="set_dr_cr_entry_link('contractor_service_charges_entry_link', 'service_charges')" id="contractor_service_charges_entry_link" class="btn btn-danger dr_cr_btn open_voucher_for_user">Dr / Cr</a></th>
    <th></th>
</tr>
<tr>
    <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
    <th><?= sortable_link('trip_id', 'numeric', 'Trip#'); ?></th>
    <th><?= sortable_link('trip_sub_type', 'string', 'Trip Type'); ?></th>
    <th><?= sortable_link('trip_date', 'string', 'Trip Date'); ?></th>
    <th><?= sortable_link('tanker_number', 'string', 'Tanker#'); ?></th>
    <th><?= sortable_link('productName', 'string', 'Product'); ?></th>
    <th><?= sortable_link('product_quantity', 'numeric', 'Product Qty'); ?></th>
    <th><?= sortable_link('shortage_amount', 'numeric', 'Shortage Amount'); ?></th>
    <th><?= sortable_link('route', 'string', 'Route'); ?></th>
    <th><?= sortable_link('stn_number', 'numeric', 'Stn'); ?></th>
    <th><?= sortable_link('company', 'string', 'Company'); ?></th>
    <th><?= sortable_link('company_freight_unit', 'numeric', 'Company frt/unit'); ?></th>
    <th><?= sortable_link('total_freight_cmp', 'numeric', 'Total Freight cmp'); ?></th>
    <th><?= sortable_link('wht_amount', 'numeric', 'W.H.T'); ?></th>
    <th><?= sortable_link('company_commission_amount', 'numeric', 'Company Commission'); ?></th>
    <th><?= sortable_link('contractor', 'string', 'Contractor'); ?></th>
    <th><?= sortable_link('contractor_freight', 'numeric', 'Contractor Freight'); ?></th>
    <th><?= sortable_link('contractor_freight_without_shortage', 'numeric', 'Contractor Freight W/O Shortage'); ?></th>
    <th><?= sortable_link('contractor_net_freight', 'numeric', 'contractor_net_freight'); ?></th>
    <th><?= sortable_link('contractor_commission_amount', 'numeric', 'Contractor Commission'); ?></th>
    <th><?= sortable_link('customer', 'string', 'Customer'); ?></th>
    <th><?= sortable_link('customer_freight_unit', 'numeric', 'Customer frt/unit'); ?></th>
    <th><?= sortable_link('total_freight_cst', 'numeric', 'Total Freight cst'); ?></th>
    <th><?= sortable_link('customer_net_freight', 'numeric', 'Customer Net Frt'); ?></th>
    <th><?= sortable_link('customer_net_freight_without_shortage', 'numeric', 'customer net frt w/o shortage'); ?></th>
    <th><?= sortable_link('service_charges', 'numeric', 'Service Charges'); ?></th>
    <th><?= sortable_link('billed', 'numeric', 'billed'); ?></th>
</tr>
</thead>
<tbody>
<?php
$grand_total_freight_for_company = 0;
$grand_total_net_freight_for_company = 0;
$total_shortage_amount = 0;
$total_wht = 0;
$total_company_commission_amount = 0;
$total_contractor_freight_amount = 0;
$grand_total_contractor_freight_without_shortage = 0;
$total_contractor_net_freight_amount = 0;
$total_contractor_commission =0;
$grand_total_freight_for_customer = 0;
$total_customer_freight_amount =0;
$grand_total_customer_net_freight_without_shortage = 0;
$total_service_charges =0;
$total_product_quantity = 0;
?>
<?php $parent_count = 0; ?>

    <?php foreach($accounts as $detail): ?>

        <?php
        $grand_total_freight_for_company += $detail->total_freight_cmp;
        $total_shortage_amount += $detail->shortage_amount;
        $total_wht += $detail->wht_amount;
        $total_company_commission_amount += $detail->company_commission_amount;
        $total_contractor_freight_amount += $detail->contractor_freight_amount;
        $grand_total_contractor_freight_without_shortage += $detail->contractor_freight_without_shortage;
        $total_contractor_net_freight_amount += $detail->contractor_net_freight;
        $total_contractor_commission += $detail->contractor_commission_amount;
        $grand_total_freight_for_customer += $detail->total_freight_cst;
        $total_customer_freight_amount += $detail->customer_freight_amount;
        $grand_total_customer_net_freight_without_shortage += $detail->customer_freight_without_shortage;
        $total_product_quantity += $detail->product_quantity;

        ?>

    <tr>
        <?php
        td("<input class='filter_check_box' type='checkbox' name='check[]' style='' value=".$detail->trip_detail_id." checked>");
        td("<a href='".base_url()."trips/edit/".$detail->trip_id."' target='_blank'>".$detail->trip_id."</a>");
        td($detail->trip_sub_type);
        td($detail->trip_date);
        td($detail->tanker_number);
        td($detail->productName);
        td($detail->product_quantity);
        td($detail->shortage_amount);
        td($detail->source." To ".$detail->destination);
        td($detail->stn_number);
        td($detail->company);
        td(round($detail->company_freight_unit, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'total_freight_for_company').rupee_format(round($detail->total_freight_cmp, 3)));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'company_wht').$detail->wht." %: ".round($detail->wht_amount, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'company_commission').$detail->company_commission." %: ".round($detail->company_commission_amount, 3));
        td($detail->contractor);
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'contractor_freight').round($detail->contractor_freight_amount, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'contractor_freight_without_shortage').round($detail->contractor_freight_without_shortage, 3));
        td(round($detail->contractor_net_freight, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'contractor_commission').$detail->contractor_commission." %: ".round($detail->contractor_commission_amount, 3));
        td($detail->customer);
        td(round($detail->freight_unit_cst, 3));
        td(round($detail->total_freight_cst, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'customer_freight').round($detail->customer_freight_amount, 3));
        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'customer_freight_without_shortage').round($detail->customer_freight_without_shortage, 3));

        $service_charges = 0;
        $service_charges = $detail->total_freight_cmp - $detail->company_commission_amount - $detail->customer_freight_amount - $detail->contractor_commission_amount - $detail->wht_amount;
        if($service_charges > -0.1 && $service_charges < 0.1){
            $service_charges = 0;
        }

        $total_service_charges += $service_charges;

        td($dr_cr_status_manager->get_status($detail->trip_detail_id, 'contractor_service_charges').round($service_charges, 3));
        td($detail->billed);
        ?>
    </tr>

    <?php endforeach ?>

</tbody>
<tfoot>
<tr style="color: white; background-color: #444444">
    <th colspan="6">TOTALS</th>
    <th><?= rupee_format($total_product_quantity) ?></th>
    <th><?= rupee_format($total_shortage_amount) ?></th>
    <th colspan="4"></th>
    <th><?= rupee_format($grand_total_freight_for_company) ?></th>
    <th><?= rupee_format($total_wht) ?></th>
    <th><?= rupee_format($total_company_commission_amount) ?></th>
    <th></th>
    <th><?= rupee_format($total_contractor_freight_amount) ?></th>
    <th><?= rupee_format($grand_total_contractor_freight_without_shortage) ?></th>
    <th><?= rupee_format($total_contractor_net_freight_amount) ?></th>
    <th colspan="">
        <div style="border-bottom: 0px solid lightgray;"><?= rupee_format($total_contractor_commission) ?></div>

    </th>
    <th></th>
    <th></th>
    <th colspan=""><?= rupee_format($grand_total_freight_for_customer) ?></th>
    <th colspan=""><?= rupee_format($total_customer_freight_amount) ?></th>
    <th colspan=""><?= rupee_format($grand_total_customer_net_freight_without_shortage) ?></th>
    <th colspan="">
        <div style="border-bottom: 0px solid lightgray;"><?= rupee_format($total_service_charges) ?></div>
    </th>
    <th></th>
</tr>
</tfoot>
</table>
</form>
</div>
<!--//pages-->
<div class="col-lg-12 text-center">
    <?php
    echo $pages;
    ?>
</div>
</div>
</div>
</div>
</div>
</div>

</div>

</div>

<script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>

<script>

    $.magnificPopup.instance._onFocusIn = function(e) {
        // Do nothing if target element is select2 input
        if( $(e.target).hasClass('select2-search__field') ) {
            return true;
        }
        // Else call parent method
        $.magnificPopup.proto._onFocusIn.call(this,e);
    };

/*    $('.open_voucher_for_user').magnificPopup({
        type:'ajax',
        ajax: {
            settings: {
                type: 'POST',
                data: {
                    ids: get_selected_ids()
                }
            }
        }
    });*/

    $('.open_voucher_for_user').magnificPopup({
        callbacks: {
            elementParse: function(item){
                postData = {
                    ids      : get_selected_ids()
                }
                var mp = $.magnificPopup.instance;
                mp.st.ajax.settings.data = postData;
            }
        },
        type: 'ajax',
            ajax: {
            settings: {
                    type: 'POST'
            }
        }
    });

    $('.open-custom-accounts-popup').magnificPopup({
        type: 'inline',
        showCloseBtn:true,
        callbacks: {
            open: function() {
                $(".customers_select").select2();
                $(".contractors_select").select2();
                $(".companies_select").select2();
                $(".drivers_select").select2();
                $(".source_city_select").select2();
                $(".destination_city_select").select2();
                $(".product_select").select2();
                $(".tankers_select").select2();
                $(".titles_select").select2();
            }
            // e.t.c.
        }
    });
    $('.open-bill-trips-popup').magnificPopup({
        type: 'inline',
        showCloseBtn:true
    });
    $('.open_custom_voucher_for_user').magnificPopup({
        type: 'ajax',
        showCloseBtn:true
    });

</script>