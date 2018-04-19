<script>

    function set_dr_cr_entry_link(link_id, type)
    {
        var query_string = '';
        if(type == 'customer_freight')
        {
            <?php if(isset($_GET['customer']) && $_GET['customer'] != ''): ?>
            query_string = "?customer="+'<?= $_GET['customer'][0] ?>';
            <?php endif; ?>
        }
        var trips_details_ids = get_selected_ids();

        //preparing the url
        var url = "<?= base_url()."manageaccounts/open_voucher_for_user/" ?>"+trips_details_ids+"/"+type+query_string;
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
    <th><a href="<?php echo $this->helper_model->sorting_info('trip_id'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('trip_id', 'numeric'); ?>"> </i> ID</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('trip_type'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('trip_type', 'string'); ?>"> </i> Trip Type</a></th>
    <th style="width: 5%"><a href="<?php echo $this->helper_model->sorting_info('entry_date'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('entry_date', 'date'); ?>"> </i> Trip Date</a></th> <!--trip entry date-->
    <th style="width: 5%"><a href="<?php echo $this->helper_model->sorting_info('tanker'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('tanker', 'string'); ?>"> </i> Tanker</a></th>
    <th style="text-align: "><a href="<?php echo $this->helper_model->sorting_info('product'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('product', 'string'); ?>"> </i> Product</a></th>
    <th style="text-align: "><a href="<?php echo $this->helper_model->sorting_info('product_quantity'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('product_quantity', 'numeric'); ?>"> </i> Product Quantity</a></th>
    <th style="text-align: "><a href="<?php echo $this->helper_model->sorting_info('shortage_amount'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('shortage_amount', 'numeric'); ?>"> </i> Shortage Amount</a></th>
    <th style="width: 5%"><a href="<?php echo $this->helper_model->sorting_info('route'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('route', 'string'); ?>"> </i> Route</a></th>
    <th style="width: 5%"><a href="<?php echo $this->helper_model->sorting_info('stn'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('stn', 'numeric'); ?>"> </i> Stn-Number</a></th>
    <th style="text-align: center"><a href="<?php echo $this->helper_model->sorting_info('company'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('company', 'string'); ?>"> </i> Company</a></th>
    <th style="text-align: center"><a href="<?php echo $this->helper_model->sorting_info('cmp_freight_unit'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('cmp_freight_unit', 'numeric'); ?>"> </i> Freight/Unit (cmp)</a></th>
    <th style="text-align: center"><a href="<?php echo $this->helper_model->sorting_info('cmp_total_freight'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('total_freight', 'numeric'); ?>"> </i> Total Freight (cmp)</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('wht'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('wht', 'numeric'); ?>"> </i> W.H.T</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('company_commission'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('company_commission', 'numeric'); ?>"> </i> Company's Commission</a></th>
    <th style="text-align: center"><a href="<?php echo $this->helper_model->sorting_info('contractor'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor', 'string'); ?>"> </i> Contractor</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('contractor_freight'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor_freight', 'numeric'); ?>"> </i> Contractor Freight</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('contractor_freight_without_shortage'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor_freight_without_shortage', 'numeric'); ?>"> </i> Contractor Freight Without Shortage</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('contractor_net_freight'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor_freight', 'numeric'); ?>"> </i> Contractor Net Freight</a></th>
    <th>
        <div style="border-bottom:0px solid lightgray"><a href="<?php echo $this->helper_model->sorting_info('contractor_commission'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor_commission', 'numeric'); ?>"> </i> Contractor Commission</a></div>
    </th>

    <th><a href="<?php echo $this->helper_model->sorting_info('customer'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('customer', 'string'); ?>"> </i> Customer</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('customer_freight_unit'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('customer_freight_unit', 'numeric'); ?>"> </i> Freight/Unit (cst)</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('total_freight_for_customer'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('total_freight_for_customer', 'numeric'); ?>"> </i> Total Freight (cst)</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('customer_net_freight'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('customer_net_freight', 'numeric'); ?>"> </i> Customer Freight</a></th>
    <th><a href="<?php echo $this->helper_model->sorting_info('customer_net_freight_without_shortage'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('customer_net_freight_without_shortage', 'numeric'); ?>"> </i> Customer Freight Without Shortage</a></th>
    <th>
        <div style="border-bottom:0px solid lightgray"><a href="<?php echo $this->helper_model->sorting_info('service_charges'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('service_charges', 'numeric'); ?>"> </i> Service Charges</a></div>
    </th>
    <th>
        <div style="border-bottom:0px solid lightgray"><a href="<?php echo $this->helper_model->sorting_info('billed'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('billed', 'string'); ?>"> </i> Billed</a></div>
    </th>
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
<?php  foreach($accounts as $trip): ?>
    <?php
    $count = 0;
    $num_trip_product_details = sizeof($trip->trip_related_details);
    ?>
    <?php foreach($trip->trip_related_details as $detail): ?>
        <?php
        $count++;
        $parent_count++;
        $shortage_amount = $detail->getShortageAmount();
        $total_shortage_amount += $shortage_amount;
        ?>
        <tr style="border-top: <?= ($count == 1)?'3':'0'; ?>px solid lightblue;">
            <td>
                <?php
                echo "<input class='filter_check_box' type='checkbox' name='check[]' style='' value=".$detail->product_detail_id." checked>";
                ?>
            </td>
            <?php
            $style = '';
            if($trip->is_complete() == true)
            {
                $style = "background-color: lightgreen; color:white;";
            }
            ?>
            <?php if($count == 1){echo "<td style='".$style."' rowspan=".($num_trip_product_details). "><a target=_blank href='".base_url()."trips/edit/".$trip->trip_id."'>".$trip->trip_id."</a></td>";} ?>
            <!--computing trip_type-->
            <?php
            $trip_type = 'undefined';
            switch($trip->type)
            {
                case 1:
                    $trip_type = 'Self / Mail';
                    break;
                case 2:
                    $trip_type = 'General';
                    break;
                case 3:
                    $trip_type = 'Local Company';
                    break;
                case 4:
                    $trip_type = 'Local Self';
                    break;
                case 5:
                    $trip_type = 'General Local';
                    break;
                case 6:
                    $trip_type = 'Secondary Local';
                    break;
                default:
                    $trip_type = 'undefined';
                    break;
            }
            ?>
            <!---->
            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".$trip_type."</td>";} ?>
            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".Carbon::createFromFormat('Y-m-d',$trip->dates->entry_date)->toFormattedDateString()."</td>";} ?>
            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".$trip->tanker->tanker_number."</td>";} ?>

            <td> <?= $detail->product->name; ?> </td>
            <?php
            $total_product_quantity += $detail->product_quantity;
            ?>
            <td> <?= $detail->product_quantity; ?> </td>
            <td> <?= rupee_format($shortage_amount); ?> </td>
            <td><?= $detail->source->name." To ".$detail->destination->name; ?></td>
            <td><?= $detail->stn_number; ?></td>
            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".$trip->company->name."</td>";} ?>
            <td><?= round($detail->company_freight_unit, 4); ?></td>
            <td>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('total_freight_for_company'); ?></div>
                <?php
                $total_freight_for_company = $detail->get_total_freight_for_company();
                $grand_total_freight_for_company += $total_freight_for_company;

                echo rupee_format($total_freight_for_company);
                ?>
            </td>
            <td>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('company_wht'); ?></div>
                <?php
                $wht = $trip->company->wht;
                $wht_amount = $detail->get_wht_amount($trip->company->wht);
                $total_wht += $wht_amount;
                ?>
                <?= $wht."% = ".rupee_format($wht_amount); ?></td>
            <?php
            $company_commission_amount = round($detail->get_company_commission_amount($trip->company->commission_1), 3);
            $total_company_commission_amount += $company_commission_amount;
            ?>
            <td>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('company_commission'); ?></div>
                <?= $trip->company->commission_1."% = ".rupee_format($company_commission_amount); ?></td>
            <?php
            $contractor_freight_amount = round($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()),3);
            $contractor_net_freight_amount = $contractor_freight_amount - $company_commission_amount;
            $contractor_commission = $trip->contractor->commission_1 - $trip->company->wht - $trip->company->commission_1;
            $contractor_commission_amount = $detail->get_contractor_commission_amount($contractor_commission);

            $total_contractor_freight_amount += $contractor_freight_amount;
            $total_contractor_net_freight_amount += $contractor_net_freight_amount;
            $total_contractor_commission += $contractor_commission_amount;
            ?>

            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".$trip->contractor->name."</td>";} ?>
            <td>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('contractor_freight'); ?></div>
                <?= $trip->get_contractor_freight_according_to_company()."% = ".rupee_format($contractor_freight_amount); ?>
            </td>
            <td>
                <?php
                $contractor_freight_without_shortage = $contractor_freight_amount - $shortage_amount;
                $grand_total_contractor_freight_without_shortage += $contractor_freight_without_shortage;
                ?>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('contractor_freight_without_shortage'); ?></div>
                <?= rupee_format($contractor_freight_without_shortage) ?>
            </td>

            <td><?= ($trip->get_contractor_freight_according_to_company() - $trip->company->commission_1)."% = ".rupee_format($contractor_net_freight_amount); ?></td>
            <td class="">
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('contractor_commission'); ?></div>
                <div style="border-bottom: 0px solid lightgray;">
                    <?= $contractor_commission."% = ".rupee_format($contractor_commission_amount); ?>
                </div>

            </td>

            <?php
            $customer_freight_amount = round($detail->get_customer_freight_amount($trip->customer->freight), 3);
            $total_freight_for_customer = $detail->get_total_freight_for_customer();

            $grand_total_freight_for_customer += $total_freight_for_customer;
            $total_customer_freight_amount += $customer_freight_amount;

            ?>

            <?php if($count == 1){echo "<td rowspan=".($num_trip_product_details).">".$trip->customer->name."</td>";} ?>
            <td><?= round($detail->customer_freight_unit, 4); ?></td>
            <td><?= $detail->get_total_freight_for_customer(); ?></td>
            <td>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('customer_freight'); ?></div>
                <?= $trip->customer->freight."% = ".rupee_format($customer_freight_amount); ?>
            </td>
            <td>
                <?php
                $customer_net_freight_amount_without_shortage = $customer_freight_amount - $shortage_amount;
                $grand_total_customer_net_freight_without_shortage += $customer_net_freight_amount_without_shortage;

                ?>
                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('customer_freight_without_shortage'); ?></div>
                <?= rupee_format($customer_net_freight_amount_without_shortage) ?>
            </td>
            <td>

                <div class="dr_cr_status"><?= $detail->get_dr_cr_status('contractor_service_charges'); ?></div>
                <?php
                $service_charges = round($detail->contractor_benefits(), 4);
                $total_service_charges += $service_charges;
                ?>

                <a href="<?= base_url()."carriageContractors/show_benefit/".$trip->trip_id."/".$detail->product_detail_id; ?>" class="show_benefit_detail" style="background-color: rgba(0,0,0,0); border: 0px; width: 100%; height: 100%;">
                    <?= (rupee_format($service_charges)); ?>
                </a>
            </td>
            <td style="<?= (($detail->bill->id != 0)?'background-color:green; color:white; font-weight:bold;':'color:red;') ?>">
                <?= (($detail->bill->id != 0)?'billed':'not billed') ?>
            </td>
        </tr>
    <?php endforeach ?>
<?php endforeach; ?>
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

    $('.open_voucher_for_user').magnificPopup({
        type: 'ajax',
        showCloseBtn:false
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