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
<div id="page-wrapper" style="min-height: 700px;">
<div class="container-fluid">

<!--body of accounts-->
    <div class="row">

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

            <div class="row calculation_sheet_heading_area">
                <div class="col-md-6">
                    <div class="col-lg-12"><?= ucwords($company) ?></div>
                    <div class="col-lg-12">Calculation Sheet Black Oil</div>
                    <div class="col-lg-12"><?= ucwords($contractor) ?></div>
                    <div class="col-lg-12"><?= Carbon::createFromFormat('Y-m-d', $_GET['from_date'])->toFormattedDateString()." - ".Carbon::createFromFormat('Y-m-d',$_GET['to_date'])->toFormattedDateString(); ?></div>
                </div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">

                    <form name="selection_form" id="selection_form" method="post" action="<?php
                    if(strpos($this->helper_model->page_url(),'?') == false){
                        echo $this->helper_model->page_url()."?";
                    }else{echo $this->helper_model->page_url()."&";}
                    ?>print">
                        <table class="table table-bordered table-hover table-striped calculation_sheet_table" style="min-width:1900px;">

                            <thead class="">
                            <tr>
                                <th>Source</th>
                                <th style="width: 150px;">Customer Name</th>
                                <th>Invoice Date</th>
                                <th>Invoice Number</th>
                                <th>Truck Lorry #</th>
                                <th>Contractor</th>
                                <th>Dis Quantity</th>
                                <th>Rec Qty</th>
                                <th>Shortage</th>
                                <th style="width: 100px;">Freight On Shortage Quantity</th>
                                <th>Freight Rate</th>
                                <th>Freight Amount</th>
                                <th>Shortage Rate</th>
                                <th>Shortage Amount</th>
                                <th>Payable Before Tax</th>
                                <th>Tax</th>
                                <th>Net Payable</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php

                            $unit = 1000; //used to convert from liters to tuns or vice versa.


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
                                        <td><?= $record->source ?></td>
                                        <td><?= $record->destination ?></td>
                                        <td><?= ($record->invoice_date == '0000-00-00')?'n/a':Carbon::createFromFormat('Y-m-d',$record->invoice_date)->toFormattedDateString()  ?></td>
                                        <td><?= $record->invoice_number ?></td>
                                        <td><?= $record->tanker_number ?></td>
                                        <td><?= $record->contractor ?></td>
                                        <td>
                                            <?php
                                            $dis_qty = $record->dis_qty/$unit;
                                            $total_dis_quantity += $dis_qty;
                                            echo $dis_qty;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $ending_quantity = $record->ending_quantity/$unit;
                                            $total_ending_quantity += $ending_quantity;
                                            echo $ending_quantity;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $shortage_quantity = $record->shortage_quantity/$unit;
                                            $total_shortage_quantity += $shortage_quantity;
                                            echo $shortage_quantity;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $freight_on_shortag_quantity = $record->freight_on_shortage_quantity;
                                            $total_freight_on_shortage_quantity += $freight_on_shortag_quantity;
                                            echo rupee_format($freight_on_shortag_quantity);
                                            ?>
                                        </td>
                                        <td><?= round($record->company_freight_unit*$unit, 3) ?></td>
                                        <td>
                                            <?php
                                            $freight_amount = $record->freight_amount;
                                            $total_freight_amount += $freight_amount;
                                            echo rupee_format($freight_amount);
                                            ?>
                                        </td>
                                        <td><?= round($record->shortage_rate/$unit, 3) ?></td>
                                        <td>
                                            <?php
                                            $shortage_amount = $record->shortage_amount;
                                            $total_shortage_amount += $shortage_amount;
                                            echo rupee_format($shortage_amount);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $payable_before_tax = $record->payable_before_tax;
                                            $total_payable_before_tax += $payable_before_tax;
                                            echo rupee_format($payable_before_tax);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $tax_amount = $record->tax_amount;
                                            $total_tax_amount += $tax_amount;
                                            echo $record->tax."% = ".rupee_format($tax_amount);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $net_payable = $record->net_payable;
                                            $total_net_payable += $net_payable;
                                            echo rupee_format($net_payable);
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr style="background-color: lightgray;">
                                    <th></th>
                                    <th></th>
                                    <th colspan="4"><?= $group[0]->destination." Totals" ?></th>
                                    <th><?= rupee_format($total_dis_quantity) ?></th>
                                    <th><?= rupee_format($total_ending_quantity) ?></th>
                                    <th><?= rupee_format($total_shortage_quantity) ?></th>
                                    <th><?= rupee_format($total_freight_on_shortage_quantity) ?></th>
                                    <th></th>
                                    <th><?= rupee_format($total_freight_amount) ?></th>
                                    <th></th>
                                    <th><?= rupee_format($total_shortage_amount) ?></th>
                                    <th><?= rupee_format($total_payable_before_tax) ?></th>
                                    <th><?= rupee_format($total_tax_amount) ?></th>
                                    <th><?= rupee_format($total_net_payable) ?></th>
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
                            <tr style="color: #ffffff; background-color: #444444;">
                                <th></th>
                                <th></th>
                                <th colspan="4">Grand Totals</th>
                                <th><?= rupee_format($grand_total_dis_quantity) ?></th>
                                <th><?= rupee_format($grand_total_ending_quantity) ?></th>
                                <th><?= rupee_format($grand_total_shortage_quantity) ?></th>
                                <th><?= rupee_format($grand_total_freight_on_shortage_quantity) ?></th>
                                <th></th>
                                <th><?= rupee_format($grand_total_freight_amount) ?></th>
                                <th></th>
                                <th><?= rupee_format($grand_total_shortage_amount) ?></th>
                                <th><?= rupee_format($grand_total_payable_before_tax) ?></th>
                                <th><?= rupee_format($grand_total_tax_amount) ?></th>
                                <th><?= rupee_format($grand_total_net_payable) ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

</div>
