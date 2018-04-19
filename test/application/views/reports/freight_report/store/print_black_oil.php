<style>
    .calculation_sheet_table{
        font-size: 11px;
    }
    .calculation_sheet_heading_area{
        font-size: 15px;
        font-weight: bold;
        font-family: monospace;
    }


</style>
<html>

<head>

    <title>Trips</title>

    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">

</head>

<body>



<style>

    .trips-table{

        font-size: <?= $font_size ?>;

    }
    table th, td{
        padding: 5px;
    }
    .multiple_entites{
        border-bottom: 1px dashed lightgray;
    }

</style>

<div id="page-wrapper" style="min-height: 700px;">

<div class="container-fluid">


    <div class="row calculation_sheet_heading_area">
        <div class="col-md-6">
            <div class="col-lg-12"><?= ucwords($company) ?></div>
            <div class="col-lg-12">Calculation Sheet</div>
            <div class="col-lg-12"><?= ucwords($contractor) ?></div>
            <div class="col-lg-12"><?= Carbon::createFromFormat('Y-m-d', $_GET['from_date'])->toFormattedDateString()." - ".Carbon::createFromFormat('Y-m-d',$_GET['to_date'])->toFormattedDateString(); ?></div>
        </div>
    </div>
<div class="row">

    <div class="col-lg-12">
        <div class="panel-body">

            <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade in active" id="trips">

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table-bordered table-hover table-striped calculation_sheet_table sortable">

                            <thead style="border-top: 4px solid lightgray;">

                            <tr>


                                <?= ((in_array('source', $columns) == true)?"<th>Source </th>":"") ?>
                                <?= ((in_array('destination', $columns) == true)?"<th style='width: 150px;'>Destination </th>":"") ?>
                                <?= ((in_array('invoice_date', $columns) == true)?"<th> Invoice Date </th>":"") ?>
                                <?= ((in_array('invoice_number', $columns) == true)?"<th>Invoice Number </th>":"") ?>
                                <?= ((in_array('tanker_number', $columns) == true)?"<th>Truck Lorry # </th>":"") ?>
                                <?= ((in_array('contractor', $columns) == true)?"<th> Contractor </th>":"") ?>
                                <?= ((in_array('dis_quantity', $columns) == true)?"<th>Dis Qty</th>":"") ?>
                                <?= ((in_array('rec_quantity', $columns) == true)?"<th>Rec Qty</th>":"") ?>
                                <?= ((in_array('shortage', $columns) == true)?"<th>Shortage </th>":"") ?>
                                <?= ((in_array('freight_on_shortage_quantity', $columns) == true)?"<th style='width: 100px;'>Freight On Shortage Quantity </th>":"") ?>
                                <?= ((in_array('freight_rate', $columns) == true)?"<th>Freight Rate </th>":"") ?>
                                <?= ((in_array('freight_amount', $columns) == true)?"<th>Freight Amount</th>":"") ?>
                                <?= ((in_array('shortage_rate', $columns) == true)?"<th>Shortage Rate</th>":"") ?>
                                <?= ((in_array('shortage_amount', $columns) == true)?"<th>Shortage Amount</th>":"") ?>
                                <?= ((in_array('payable_before_tax', $columns) == true)?"<th>Payable Before Tax</th>":"") ?>
                                <?= ((in_array('tax', $columns) == true)?"<th>Tax</th>":"") ?>
                                <?= ((in_array('net_payable', $columns) == true)?"<th>Net Payable</th>":"") ?>

                            </tr>

                            </thead>

                            <tbody>

                            <?php
                            $grand_total_dis_quantity = 0;
                            $grand_total_ending_quantity = 0;
                            $grand_total_shortage_quantity = 0;
                            $grand_total_freight_on_shortage_quantity = 0;
                            $grand_total_freight_amount = 0;
                            $grand_total_shortage_amount =0;
                            $grand_total_payable_before_tax =0;
                            $grand_total_tax_amount = 0;
                            $grand_total_net_payable = 0;

                            foreach($report as $group): ?>
                            <?php
                            $total_dis_quantity = 0;
                            $total_ending_quantity = 0;
                            $total_shortage_quantity = 0;
                            $total_freight_on_shortage_quantity = 0;
                            $total_freight_amount = 0;
                            $total_shortage_amount =0;
                            $total_payable_before_tax =0;
                            $total_tax_amount = 0;
                            $total_net_payable = 0;
                            ?>
                            <?php foreach($group as $record): ?>
                                <tr>

                                    <?php if(in_array('source', $columns) == true): ?>
                                    <td>
                                        <?= $record->source ?>
                                    </td>
                                    <?php endif; ?>
                                    <?php if(in_array('destination', $columns) == true): ?>
                                        <td style='width: 150px;'>
                                            <?= $record->destination ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('invoice_date', $columns) == true): ?>
                                        <td>
                                            <?= Carbon::createFromFormat('Y-m-d',$record->invoice_date)->toFormattedDateString() ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('invoice_number', $columns) == true): ?>
                                        <td>
                                            <?= $record->invoice_number ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('tanker_number', $columns) == true): ?>
                                        <td>
                                            <?= $record->tanker_number ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('contractor', $columns) == true): ?>
                                        <td>
                                            <?= $record->contractor ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('dis_quantity', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $dis_qty = $record->dis_qty;
                                            $total_dis_quantity += $dis_qty;
                                            echo $dis_qty;
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('rec_quantity', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $ending_quantity = $record->ending_quantity;
                                            $total_ending_quantity += $ending_quantity;
                                            echo $ending_quantity;
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('shortage', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $shortage_quantity = $record->shortage_quantity;
                                            $total_shortage_quantity += $shortage_quantity;
                                            echo $shortage_quantity;
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('freight_on_shortage_quantity', $columns) == true): ?>
                                        <td style='width: 100px;'>
                                            <?php
                                            $freight_on_shortag_quantity = $record->freight_on_shortage_quantity;
                                            $total_freight_on_shortage_quantity += $freight_on_shortag_quantity;
                                            echo rupee_format($freight_on_shortag_quantity);
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('freight_rate', $columns) == true): ?>
                                        <td>
                                            <?= $record->customer_freight_unit ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('freight_amount', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $freight_amount = $record->freight_amount;
                                            $total_freight_amount += $freight_amount;
                                            echo rupee_format($freight_amount);
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('shortage_rate', $columns) == true): ?>
                                        <td>
                                            <?= $record->shortage_rate ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('shortage_amount', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $shortage_amount = $record->shortage_amount;
                                            $total_shortage_amount += $shortage_amount;
                                            echo rupee_format($shortage_amount);
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('payable_before_tax', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $payable_before_tax = $record->payable_before_tax;
                                            $total_payable_before_tax += $payable_before_tax;
                                            echo rupee_format($payable_before_tax);
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if(in_array('tax', $columns) == true): ?>
                                    <td>
                                        <?php
                                        $tax_amount = $record->tax_amount;
                                        $total_tax_amount += $tax_amount;
                                        echo $record->tax."% = ".rupee_format($tax_amount);
                                        ?>
                                    </td>
                                    <?php endif; ?>
                                    <?php if(in_array('net_payable', $columns) == true): ?>
                                        <td>
                                            <?php
                                            $net_payable = $record->net_payable;
                                            $total_net_payable += $net_payable;
                                            echo rupee_format($net_payable);
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach; ?>
                            <tr style="background-color: lightgray;">
                                <?= ((in_array('source', $columns) == true)?"<td>".$group[0]->destination." Totals </td>":"") ?>
                                <?= ((in_array('destination', $columns) == true)?"<td style='width: 150px;'> </td>":"") ?>
                                <?= ((in_array('invoice_date', $columns) == true)?"<td>  </td>":"") ?>
                                <?= ((in_array('invoice_number', $columns) == true)?"<td> </td>":"") ?>
                                <?= ((in_array('tanker_number', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('contractor', $columns) == true)?"<td> </td>":"") ?>
                                <?= ((in_array('dis_quantity', $columns) == true)?"<td>".$total_dis_quantity."</td>":"") ?>
                                <?= ((in_array('rec_quantity', $columns) == true)?"<td>".$total_ending_quantity."</td>":"") ?>
                                <?= ((in_array('shortage', $columns) == true)?"<td>".$total_shortage_quantity."</td>":"") ?>
                                <?= ((in_array('freight_on_shortage_quantity', $columns) == true)?"<td style='width: 100px;'>".rupee_format($total_freight_on_shortage_quantity)."</td>":"") ?>
                                <?= ((in_array('freight_rate', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('freight_amount', $columns) == true)?"<td>".rupee_format($total_freight_amount)."</td>":"") ?>
                                <?= ((in_array('shortage_rate', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('shortage_amount', $columns) == true)?"<td>".rupee_format($total_shortage_amount)."</td>":"") ?>
                                <?= ((in_array('payable_before_tax', $columns) == true)?"<td>".rupee_format($total_payable_before_tax)."</td>":"") ?>
                                <?= ((in_array('tax', $columns) == true)?"<td>".rupee_format($total_tax_amount)."</td>":"") ?>
                                <?= ((in_array('net_payable', $columns) == true)?"<td>".rupee_format($total_net_payable)."</td>":"") ?>
                            </tr>
                            <?php
                            $grand_total_dis_quantity+= $total_dis_quantity;
                            $grand_total_ending_quantity+= $total_ending_quantity;
                            $grand_total_shortage_quantity+= $total_shortage_quantity;
                            $grand_total_freight_on_shortage_quantity+= $total_freight_on_shortage_quantity;
                            $grand_total_freight_amount+= $total_freight_amount;
                            $grand_total_shortage_amount += $total_shortage_amount;
                            $grand_total_payable_before_tax += $total_payable_before_tax;
                            $grand_total_tax_amount+= $total_tax_amount;
                            $grand_total_net_payable+= $total_net_payable;
                            ?>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr style="background-color: lightgray;">
                                <?= ((in_array('source', $columns) == true)?"<td>Grand Totals </td>":"") ?>
                                <?= ((in_array('destination', $columns) == true)?"<td style='width: 150px;'> </td>":"") ?>
                                <?= ((in_array('invoice_date', $columns) == true)?"<td>  </td>":"") ?>
                                <?= ((in_array('invoice_number', $columns) == true)?"<td> </td>":"") ?>
                                <?= ((in_array('tanker_number', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('contractor', $columns) == true)?"<td> </td>":"") ?>
                                <?= ((in_array('dis_quantity', $columns) == true)?"<td>".$grand_total_dis_quantity."</td>":"") ?>
                                <?= ((in_array('rec_quantity', $columns) == true)?"<td>".$grand_total_ending_quantity."</td>":"") ?>
                                <?= ((in_array('shortage', $columns) == true)?"<td>".$grand_total_shortage_quantity."</td>":"") ?>
                                <?= ((in_array('freight_on_shortage_quantity', $columns) == true)?"<td style='width: 100px;'>".rupee_format($grand_total_freight_on_shortage_quantity)."</td>":"") ?>
                                <?= ((in_array('freight_rate', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('freight_amount', $columns) == true)?"<td>".rupee_format($grand_total_freight_amount)."</td>":"") ?>
                                <?= ((in_array('shortage_rate', $columns) == true)?"<td></td>":"") ?>
                                <?= ((in_array('shortage_amount', $columns) == true)?"<td>".rupee_format($grand_total_shortage_amount)."</td>":"") ?>
                                <?= ((in_array('payable_before_tax', $columns) == true)?"<td>".rupee_format($grand_total_payable_before_tax)."</td>":"") ?>
                                <?= ((in_array('tax', $columns) == true)?"<td>".rupee_format($grand_total_tax_amount)."</td>":"") ?>
                                <?= ((in_array('net_payable', $columns) == true)?"<td>".rupee_format($grand_total_net_payable)."</td>":"") ?>
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
