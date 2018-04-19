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

        $unit = 1000; //used to convert from liters to tuns or vice versa.

        ?>
        <table class="table table-bordered table-hover table-striped sortable" style="font-size: 12px;">

            <thead style="border-top: 3px solid lightgray;">

            <tr>
                <?= Sort::createPrintableHeaders("manage_accounts_black_oil",$columns) ?>
            </tr>

            </thead>

            <tbody>

            <?php
            $selected_columns = $columns;
            $columns = Sort::columns('manage_accounts_black_oil');
            //Showing Customers Data
            foreach($accounts as $record){
                echo "<tr>";
                $markup = "";

                $markup.=printable_column('trip_id', $selected_columns, $record->trip_id);
                $markup.=printable_column('trip_sub_type', $selected_columns, $record->trip_sub_type);
                $markup.=printable_column('trip_date', $selected_columns, $record->trip_date);
                $markup.=printable_column('source', $selected_columns, $record->source);
                $markup.=printable_column('destination', $selected_columns, $record->destination);
                $markup.=printable_column('invoice_date', $selected_columns, $record->invoice_date);
                $markup.=printable_column('invoice_number', $selected_columns, $record->invoice_number);
                $markup.=printable_column('stn_number', $selected_columns, $record->stn_number);
                $markup.=printable_column('tanker_number', $selected_columns, $record->tanker_number);
                $markup.=printable_column('product', $selected_columns, $record->product);
                $markup.=printable_column('dis_qty', $selected_columns, $record->dis_qty / $unit);
                $markup.=printable_column('rec_qty', $selected_columns, $record->rec_qty / $unit);
                $markup.=printable_column('shortage_qty', $selected_columns, $record->shortage_quantity / $unit);
                $markup.=printable_column('freight_on_shortage_qty_cmp', $selected_columns, $record->freight_on_shortage_qty_cmp);
                $markup.=printable_column('freight_on_shortage_qty_cst', $selected_columns, $record->freight_on_shortage_qty_cst);
                $markup.=printable_column('company_freight_unit', $selected_columns, $record->company_freight_unit * $unit);
                $markup.=printable_column('total_freight_cmp', $selected_columns, $record->total_freight_cmp);
                $markup.=printable_column('freight_amount_cmp', $selected_columns, $record->freight_amount_cmp);
                $markup.=printable_column('company', $selected_columns, $record->company);
                $markup.=printable_column('shortage_rate', $selected_columns, $record->shortage_rate);
                $markup.=printable_column('shortage_amount', $selected_columns, $record->shortage_amount);
                $markup.=printable_column('payable_before_tax', $selected_columns, $record->payable_before_tax);
                $markup.=printable_column('wht_amount', $selected_columns, $record->wht."% = ".$record->wht_amount);
                $markup.=printable_column('net_payables', $selected_columns, $record->net_payables);
                $markup.=printable_column('contractor_net_freight', $selected_columns, $record->contractor_net_freight);
                $markup.=printable_column('company_commission_amount', $selected_columns, $record->company_commission."% = ".$record->company_commission_amount);
                $markup.=printable_column('contractor_commission_amount', $selected_columns, $record->contractor_commission."% = ".$record->contractor_commission_amount);
                $markup.=printable_column('contractor', $selected_columns, $record->contractor);
                $markup.=printable_column('customer_freight_unit', $selected_columns, $record->customer_freight_unit * $unit);
                $markup.=printable_column('total_freight_cst', $selected_columns, $record->total_freight_cst);
                $markup.=printable_column('freight_amount_cst', $selected_columns, $record->freight_amount_cst);
                $markup.=printable_column('customer_freight', $selected_columns, $record->customer_freight);
                $markup.=printable_column('customer', $selected_columns, $record->customer);

                $service_charges = 0;
                $service_charges = $record->total_freight_cmp - $record->company_commission_amount - $record->customer_freight - $record->contractor_commission_amount - $record->wht_amount;
                if($service_charges > -0.1 && $service_charges < 0.1){
                    $service_charges = 0;
                }

                $total_service_charges += $service_charges;

                $markup.=printable_column('service_charges', $selected_columns, $service_charges);
                $markup.=printable_column('billed', $selected_columns, $record->billed);

                echo $markup;
                echo "</tr>";
            }
            ?>
            </tbody>
            <tfoot>

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
