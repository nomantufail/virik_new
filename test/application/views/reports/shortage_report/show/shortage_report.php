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
                    <div class="col-lg-12">Terminal Shortage</div>
                    <div class="col-lg-12">Product: <?= $product ?></div>
                    <div class="col-lg-12">Type: <?= ucwords($product_type) ?></div>
                    <div class="col-lg-12">Related Agent: <?= $related_agent?></div>
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
                        <table class="table table-bordered table-hover table-striped calculation_sheet_table" style="">

                            <thead class="">
                            <tr>
                                <th>Invoice Date</th>
                                <th>Invoice Number</th>
                                <th>Truck Lorry #</th>
                                <th>Product Description</th>
                                <th>Shortage</th>
                                <th>Shortage Amount</th>
                            </tr>
                            </thead>

                            <tbody>

                                <?php
                                $total_shortage_quantity = 0;
                                $total_shortage_amount =0;
                                ?>
                               <?php foreach($report as $record): ?>

                                    <tr>
                                        <td><?= Carbon::createFromFormat('Y-m-d',$record->invoice_date)->toFormattedDateString()  ?></td>
                                        <td><?= $record->invoice_number ?></td>
                                        <td><?= $record->tanker_number ?></td>
                                        <td><?= $record->productName ?></td>
                                        <td>
                                            <?php
                                            $shortage_quantity = $record->shortage_quantity;
                                            $total_shortage_quantity += $shortage_quantity;
                                            echo $shortage_quantity;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $shortage_amount = $record->shortage_amount;
                                            $total_shortage_amount += $shortage_amount;
                                            echo rupee_format($shortage_amount);
                                            ?>
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr style="background-color: lightgray;">
                                <th colspan="4">Totals</th>
                                <th><?= rupee_format($total_shortage_quantity) ?></th>
                                <th><?= rupee_format($total_shortage_amount) ?></th>
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
