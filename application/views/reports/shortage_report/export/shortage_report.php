<?php
$file = exporting_file_name("calculation_sheet_black_oil").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>

<html>
<head>
    <title>Trips</title>
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="page-wrapper" style="min-height: 700px;">
<div class="container-fluid">


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

<div class="row">

    <div class="col-lg-12">
        <div class="panel-body">

            <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade in active" id="trips">

                    <div class="table-responsive" style="overflow-x: auto;">
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