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
                    <div class="col-lg-12">Freight Report For <?= ucwords($_GET['trip_master_type']) ?> Trips</div>
                    <div class="col-lg-12">Product Type: <?= ucwords($product_type) ?></div>
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
                    <table class="calculation_sheet_table table table-bordered table-hover table-striped accounts-table sortable" style="min-width:1000px;">

                    <thead style="border-top: 4px solid lightgray;">
                    <tr>
                        <th style="width: 10%">Route</th>
                        <th style="width: 5%">Stn-Number</th>
                        <th style="width: 10%">Trip Date</th> <!--trip filling date-->
                        <th style="text-align: center; width: 10%;"> Contractor</th>
                        <th style="width: 10%">Truck No</th>
                        <th style="text-align: ;width: 10%;">Product</th>
                        <th style="text-align: ">QTY (ltrs)</th>
                        <th style="text-align: center">Rate (cmp)</th>
                        <th style="text-align: center">Amount Rs.</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Dated</th>
                        <th style="text-align: center">Paid</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $grand_total_freight_for_company = 0;
                    $total_product_quantity = 0;

                    ?>

                    <?php  foreach($report as $trip): ?>
                        <?php
                        ?>
                        <?php foreach($trip->trip_related_details as $detail): ?>

                            <!-- Deciding weather to display the trip details or not -->
                            <?php
                                $product_type = $_GET['product_type'];
                                $should_display = true;
                                if($product_type != 'all')
                                {
                                    $should_display = ($detail->product->type == $product_type)?true:false;
                                }
                            ?>
                            <?php if($should_display == true): ?>
                                <tr style="">
                                    <td><?= $detail->source->name." To ".$detail->destination->name; ?></td>
                                    <td><?= $detail->stn_number; ?></td>
                                    <td><?= Carbon::createFromFormat('Y-m-d',$trip->dates->filling_date)->toFormattedDateString() ?> </td>
                                    <td><?= $trip->contractor->name ?></td>
                                    <td> <?= $trip->tanker->tanker_number ?> </td>

                                    <td> <?= $detail->product->name; ?> </td>
                                    <?php
                                    $total_product_quantity += $detail->product_quantity;
                                    ?>
                                    <td> <?= $detail->product_quantity; ?> </td>
                                    <td><?= round($detail->company_freight_unit, 4); ?></td>

                                    <td>
                                        <?php
                                        $total_freight_for_company = round($detail->get_total_freight_for_company(), 3);
                                        $grand_total_freight_for_company += $total_freight_for_company;

                                        echo $this->helper_model->money($total_freight_for_company);
                                        ?>
                                    </td>

                                    <td> OK </td>
                                    <td>
                                        <?php
                                            $voucher = $detail->get_voucher_of_total_freight_for_company();
                                            $date = $voucher->voucher_date;
                                            echo Carbon::createFromFormat('Y-m-d',$date)->toFormattedDateString();
                                        ?>
                                    </td>
                                    <td><?= rupee_format($total_freight_for_company); ?></td>
                                </tr>
                            <?php endif;?>
                        <?php endforeach ?>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #333333; color:white;">
                        <th colspan="6">TOTALS</th>
                        <th><?= $this->helper_model->money($total_product_quantity) ?></th>
                        <th></th>
                        <th><?= $this->helper_model->money($grand_total_freight_for_company) ?></th>
                        <th colspan="2"></th>
                        <th><?= $this->helper_model->money($grand_total_freight_for_company) ?></th>
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
