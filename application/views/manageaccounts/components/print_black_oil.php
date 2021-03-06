<html>
<head>
    <title>Accounts</title>
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">
</head>
<body>

<style>
    table{
        font-size: <?= $font_size ?>;
    }
    table td, th{
        padding: 5px;
    }
    .multiple_entites{
        border-bottom: 1px dashed lightgray;
    }
</style>
<div id="page-wrapper" style="min-height: 700px;">
<div class="container-fluid">

<div class="row">
    <div class="col-lg-12">
        <section class="col-md-12" style="text-align: center;">
            <h3 class="">
                Manage Accounts Black Oil
            </h3>
        </section>
    </div>
</div>
<div class="row">
<div class="col-lg-12">
<div class="panel-body">
<div id="myTabContent" class="tab-content" style="min-height: 500px;">
<div class="tab-pane fade in active" id="customers">
<div class="table-responsive">
<table class="table table-bordered table-hover table-striped accounts-table sortable" style="min-width:1900px;">

<thead style="border-top: 4px solid lightgray;">

<tr>
    <?= Sort::createPrintableHeaders("manage_accounts_black_oil",$columns) ?>
</tr>
</thead>
<tbody>
<!-- Totals Variables Declaration -->
<?php
$total_shortage_qty = 0;
$total_dis_qty = 0;
$total_rec_qty = 0;
$total_freight_on_shrt_qty_cmp = 0;
$total_freight_on_shrt_qty_cst = 0;
$grand_total_frieght_cmp = 0;
$grand_total_frieght_cst = 0;
$total_freight_amount_cmp = 0;
$total_shortage_amount = 0;
$total_payable_before_tax = 0;
$total_wht = 0;
$total_net_payable = 0;
$total_contractor_net_freight = 0;
$total_company_commission = 0;
$total_contractor_commission = 0;
$total_freight_amount_cst = 0;
$total_customer_freight = 0;
$total_service_charges = 0;
?>
<!---------------------------------------->

<?php

$parent_count = 0;
$unit = 1000; //used to convert from liters to tuns or vice versa.

?>

<?php foreach($accounts as $detail): ?>
    <?php

    /*----- Calculating things ---------*/
    $shortage_quantity = $detail->shortage_quantity/$unit;
    $dis_quantity = $detail->dis_qty/$unit;
    $rec_quantity = $detail->rec_qty/$unit;
    $company_freight_unit = $detail->company_freight_unit*$unit;
    $customer_freight_unit = $detail->customer_freight_unit*$unit;
    $shortage_rate = $detail->shortage_rate/$unit;
    /*---------------------------------------------*/

    /**--------------------------------
     * Totaling Things
     * ------------------------------
     * */
    $total_shortage_qty += $shortage_quantity;
    $total_dis_qty += $dis_quantity;
    $total_rec_qty += $rec_quantity;
    $total_freight_on_shrt_qty_cmp += $detail->freight_on_shortage_qty_cmp;
    $total_freight_on_shrt_qty_cst += $detail->freight_on_shortage_qty_cst;
    $grand_total_frieght_cmp += $detail->total_freight_cmp;
    $grand_total_frieght_cst += $detail->total_freight_cst;
    $total_freight_amount_cmp += $detail->freight_amount_cmp;
    $total_shortage_amount += $detail->shortage_amount;
    $total_payable_before_tax += $detail->payable_before_tax;
    $total_wht += $detail->wht_amount;
    $total_net_payable += $detail->net_payables;
    $total_contractor_net_freight += $detail->contractor_net_freight;
    $total_company_commission += $detail->company_commission_amount;
    $total_contractor_commission += $detail->contractor_commission_amount;
    $total_freight_amount_cst += $detail->freight_amount_cst;
    $total_customer_freight += $detail->customer_freight;
    /*-----------------------------------------*/

    ?>

    <tr>
    <?php if(in_array('trip_id', $columns) == true): ?>

        <td>
            <?= $detail->trip_id ?>
        </td>
    <?php endif; ?>

    <?php if(in_array('trip_sub_type', $columns) == true): ?>
        <!--computing trip_type-->
        <?php
        echo "<td>".$detail->trip_sub_type."</td>";
        ?>
        <!---->

    <?php endif; ?>

    <?php if(in_array('trip_date', $columns) == true): ?>
        <?= "<td>".Carbon::createFromFormat('Y-m-d',$detail->trip_date)->toFormattedDateString()."</td>" ?>
    <?php endif; ?>

    <?php if(in_array('source', $columns) == true):?>
        <td>
            <?= $detail->source ?>
        </td>
    <?php endif; ?>

    <?php if(in_array('destination', $columns) == true): ?>
        <td>
            <?= $detail->destination ?>
        </td>
    <?php endif; ?>

    <?php if(in_array('invoice_date', $columns) == true): ?>
        <td>
            <?= ($detail->invoice_date != '0000-00-00')?Carbon::createFromFormat('Y-m-d',$detail->invoice_date)->toFormattedDateString():"" ?>
        </td>
    <?php endif; ?>

        <?php if(in_array('invoice_number', $columns) == true): ?>
            <td style="">
                <?= $detail->invoice_number; ?>
            </td>
        <?php endif; ?>
        <?php if(in_array('stn_number', $columns) == true): ?>
            <td style="">
                <?= $detail->stn_number; ?>
            </td>
        <?php endif; ?>
        <?php
        if(in_array('tanker_number', $columns) == true)
        {
            td($detail->tanker_number);
        }
        ?>
        <?php     if(in_array('product', $columns) == true)     {         td($detail->product); } ?>
        <?php     if(in_array('dis_qty', $columns) == true)     {         td($detail->dis_qty / $unit); } ?>
        <?php     if(in_array('rec_qty', $columns) == true)     {         td($detail->rec_qty / $unit); } ?>
        <?php     if(in_array('shortage_qty', $columns) == true)     {         td($detail->shortage_quantity / $unit); } ?>
        <?php     if(in_array('freight_on_shortage_qty_cmp', $columns) == true)     {         td($detail->freight_on_shortage_qty_cmp); } ?>
        <?php     if(in_array('freight_on_shortage_qty_cst', $columns) == true)     {         td($detail->freight_on_shortage_qty_cst); } ?>
        <?php     if(in_array('company_freight_unit', $columns) == true)     {         td($detail->company_freight_unit * $unit); } ?>
        <?php     if(in_array('total_freight_cmp', $columns) == true)     {         td($detail->total_freight_cmp); } ?>
        <?php     if(in_array('freight_amount_cmp', $columns) == true)     {         td($detail->freight_amount_cmp); } ?>
        <?php     if(in_array('company', $columns) == true)     {         td($detail->company); } ?>
        <?php     if(in_array('shortage_rate', $columns) == true)     {         td($detail->shortage_rate); } ?>
        <?php     if(in_array('shortage_amount', $columns) == true)     {         td($detail->shortage_amount); } ?>
        <?php     if(in_array('payable_before_tax', $columns) == true)     {         td($detail->payable_before_tax); } ?>
    <?php
    if(in_array('wht_amount', $columns) == true)
    {
        td($detail->wht_amount);
    }
    ?>
    <?php
    if(in_array('net_payables', $columns) == true)
    {
        td($detail->net_payables);
    }
    ?>
    <?php
    if(in_array('contractor_net_freight', $columns) == true)
    {
        td($detail->contractor_net_freight);
    }
    ?>
    <?php
    if(in_array('company_commission_amount', $columns) == true)
    {
        td($detail->company_commission."% = ".$detail->company_commission_amount);
    }
    ?>
    <?php
    if(in_array('contractor_commission_amount', $columns) == true)
    {
        td($detail->contractor_commission."% = ".$detail->contractor_commission_amount);
    }
    ?>
    <?php
    if(in_array('contractor', $columns) == true)
    {
        td($detail->contractor);
    }
    ?>
    <?php
    if(in_array('customer_freight_unit', $columns) == true)
    {
        td($detail->customer_freight_unit * $unit);
    }
    ?>
    <?php
    if(in_array('total_freight_cst', $columns) == true)
    {
        td($detail->total_freight_cst);
    }
    ?>
    <?php
    if(in_array('freight_amount_cst', $columns) == true)
    {
        td($detail->freight_amount_cst);
    }
    ?>
    <?php
    if(in_array('customer_freight', $columns) == true)
    {
        td($detail->customer_freight);
    }
    ?>
    <?php
    if(in_array('customer', $columns) == true)
    {
        td($detail->customer);
    }
    ?>
    <?php
    if(in_array('service_charges', $columns) == true)
    {

        $service_charges = 0;
        $service_charges = $detail->freight_amount_cmp - $detail->company_commission_amount - $detail->customer_freight - $detail->contractor_commission_amount - $detail->wht_amount - $detail->shortage_amount;
        if($service_charges > -0.1 && $service_charges < 0.1){
            $service_charges = 0;
        }

        $total_service_charges += $service_charges;

        td($service_charges);
    }
    ?>
    <?php
    if(in_array('billed', $columns) == true)
    {
        td($detail->billed);
    }
    ?>


    </tr>
<?php endforeach ?>
</tbody>
<tfoot>
<tr style="color: white; background-color: #444444">
    <?= ((in_array('trip_id', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('trip_sub_type', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('trip_date', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('source', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('destination', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('invoice_date', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('invoice_number', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('stn_number', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('tanker_number', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('product', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('dis_qty', $columns) == true)?"<td>$total_dis_qty</td>":"") ?>
    <?= ((in_array('rec_qty', $columns) == true)?"<td>$total_rec_qty</td>":"") ?>
    <?= ((in_array('shortage_qty', $columns) == true)?"<td>$total_shortage_qty ?></td>":"") ?>
    <?= ((in_array('freight_on_shortage_qty_cmp', $columns) == true)?"<td>". rupee_format($total_freight_on_shrt_qty_cmp)."</td>":"") ?>
    <?= ((in_array('freight_on_shortage_qty_cst', $columns) == true)?"<td>".rupee_format($total_freight_on_shrt_qty_cst)."</td>":"") ?>
    <?= ((in_array('company_freight_unit', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('total_freight_cmp', $columns) == true)?"<td>".rupee_format($grand_total_frieght_cmp)."</td>":"") ?>
    <?= ((in_array('freight_amount_cmp', $columns) == true)?"<td>".rupee_format($total_freight_amount_cmp)."</td>":"") ?>
    <!--company-->
    <?= ((in_array('company', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('shortage_rate', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('shortage_amount', $columns) == true)?"<td>".rupee_format($total_shortage_amount)."</td>":"") ?>
    <?= ((in_array('payable_before_tax', $columns) == true)?"<td>".rupee_format($total_payable_before_tax)."</td>":"") ?>
    <?= ((in_array('wht_amount', $columns) == true)?"<td>".rupee_format($total_wht)."</td>":"") ?>
    <?= ((in_array('net_payables', $columns) == true)?"<td>".rupee_format($total_net_payable)."</td>":"") ?>
    <?= ((in_array('contractor_net_freight', $columns) == true)?"<td>".rupee_format($total_contractor_net_freight)."</td>":"") ?>
    <?= ((in_array('company_commission_amount', $columns) == true)?"<td>".rupee_format($total_company_commission)."</td>":"") ?>
    <?= ((in_array('contractor_commission_amount', $columns) == true)?"<td>".rupee_format($total_contractor_commission)."</td>":"") ?>
    <?= ((in_array('contractor', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('customer_freight_unit', $columns) == true)?"<td> </td>":"") ?>
    <?= ((in_array('total_freight_cst', $columns) == true)?"<td>".rupee_format($grand_total_frieght_cst)."</td>":"") ?>
    <?= ((in_array('freight_amount_cst', $columns) == true)?"<td>".rupee_format($total_freight_amount_cst)."</td>":"") ?>
    <?= ((in_array('customer_freight', $columns) == true)?"<td>".rupee_format($total_customer_freight)."</td>":"") ?>
    <!--customer-->
    <?= ((in_array('customer', $columns) == true)?"<td></td>":"") ?>
    <?= ((in_array('service_charges', $columns) == true)?"<td>".rupee_format($total_service_charges)."</td>":"") ?>
    <?= ((in_array('billed', $columns) == true)?"<td></td>":"") ?>
</tr>
</tfoot>
</table>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
