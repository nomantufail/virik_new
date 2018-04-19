<html>
<head>
    <title>Trip Details</title>
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">
</head>
<body>

<style>
    table{
        font-size: 14px;
    }
</style>
<div id="page-wrapper" style="min-height: 700px;">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-6">
                    <h1 class="page-header">
                        Trip# <?= $trip->trip_id; ?><small></small>
                    </h1>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <thead style="border-top: 3px solid <?= ($trip->stn_number != '')?"lightgreen":"#e7c3c3"; ?>;">
                        <tr>
                            <th>Customer</th><td><?= ucwords($trip->customerName) ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Carriage Contractor</th><td><?= ucwords($trip->contractorName) ?></td>
                    </tr>
                    <tr>
                        <th>Company</th><td><?= ucwords($trip->companyName) ?></td>
                    </tr>
                    <tr>
                        <th>Tanker</th><td><?= ($trip->tanker_number) ?></td>
                    </tr>
                    <tr>
                        <th>Route</th><td><?= ucwords($trip->route) ?></td>
                    </tr>
                    <tr>
                        <th>Driver_1</th><td><?= ucwords($trip->driver_name_1) ?></td>
                    </tr>
                    <tr>
                        <th>Driver_2/Helper</th><td><?= ucwords($trip->driver_name_3) ?></td>
                    </tr>
                    <tr>
                        <th>Driver_3/Helper</th><td><?= ucwords($trip->driver_name_3) ?></td>
                    </tr>
                    <tr>
                        <th>Product Quantity (initial)</th><td><?= ($trip->product_quantity) ?></td>
                    </tr>
                    <tr>
                        <th>Price/Unit</th><td><?= ($trip->price_unit) ?></td>
                    </tr>
                    <tr>
                        <th>Freight/Unit</th><td><?= ($trip->freight_unit) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Total Freight</th><td><?= ($trip->total_freight) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Contractor Commission</th><td><?= ($trip->contractor_commission) ?>% = <?= ($trip->contractor_commission * $trip->total_freight/100) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Company Commission_1</th><td><?= ($trip->company_commission_1) ?>% = <?= ($trip->company_commission_1 * $trip->total_freight/100) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Withholding Tax</th><td><?= ($trip->company_commission_2) ?>% = <?= ($trip->company_commission_2 * $trip->total_freight/100) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Remaining For Customer</th><td>Total Freight - Carriage Contractor Commission = <?= (100 - $trip->contractor_commission) ?>% = <?= ((100 - $trip->contractor_commission) * $trip->total_freight/100) ?> Rs.</td>
                    </tr>
                    <tr>
                        <th>Entry Date</th><td><?= ($trip->entry_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->entry_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>E-Mail Date</th><td><?= ($trip->email_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->email_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>Filling Date</th><td><?= ($trip->filling_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->filling_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>Receiving Date</th><td><?= ($trip->receiving_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->receiving_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>STN-Receiving Date</th><td><?= ($trip->stn_receiving_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->stn_receiving_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>Decanding Date</th><td><?= ($trip->decanding_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->decanding_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>Invoice Date</th><td><?= ($trip->invoice_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$trip->invoice_date)->toFormattedDateString():"--"; ?></td>
                    </tr>
                    <tr>
                        <th>Invoice Number</th><td><?= $trip->invoice_number; ?></td>
                    </tr>
                    <tr>
                        <th>STN Number</th><td><?= $trip->stn_number; ?></td>
                    </tr>
                    <tr>
                        <th>Final Quantity</th><td><?= ($trip->final_quantity == 0)?"--":$trip->final_quantity; ?></td>
                    </tr>
                    <tr>
                        <th>Product Decrease Expense</th><td>(Product Quantity - Final Quantity) X Price Per Unit = <?= ($trip->final_quantity == 0)?"--":($trip->price_unit*($trip->product_quantity - $trip->final_quantity)); ?> Rs.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


<script src="<?= js()?>sorttable.js"></script>
</body>
</html>
