<style>

</style>

<script>
</script>

<div id="page-wrapper" style="min-height: 700px;">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="text-align: center">
                <h3>Trip Report# <?= $_GET['id'] ?></h3>
            </div>
        </div>

        <!--Calculating required data-->
        <?php
            $total_freight = 0;
            $total_customer_freight = 0;
            $total_contractor_freight = 0;
            $total_wht = 0;
            $total_company_commission = 0;
            $total_contractor_commission = 0;
            $total_service_charges = 0;

            foreach($trip->trip_related_details as $detail)
            {
                $total_freight += $detail->get_total_freight_for_company();
                $total_customer_freight += round($detail->get_customer_freight_amount($trip->customer->freight), 3);
                $total_contractor_freight += round($detail->get_contractor_freight_amount_according_to_company($trip->get_contractor_freight_according_to_company()),3);
                $total_wht += $detail->get_wht_amount($trip->company->wht);
                $total_company_commission += round($detail->get_company_commission_amount($trip->company->commission_1), 3);

                $contractor_commission = $trip->contractor->commission_1 - $trip->company->wht - $trip->company->commission_1;
                $total_contractor_commission += $detail->get_contractor_commission_amount($contractor_commission);
                $total_service_charges += round($detail->contractor_benefits(), 4);
            }
        ?>
        <!----------------------------->

        <div class="row">
            <div class="panel-body">

                <div class="col-lg-6">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle"></i> General Info</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                                    <tbody>
                                    <tr><th>Customer</th><td><?= $trip->customer->name ?></td></tr>
                                    <tr><th>Contractor</th><td><?= $trip->contractor->name ?></td></tr>
                                    <tr><th>Company</th><td><?= $trip->company->name ?></td></tr>
                                    <tr><th>Tanker</th><td><?= $trip->tanker->tanker_number ?></td></tr>
                                    <tr><th>Routes</th>
                                        <td>
                                            <?php
                                            foreach($trip->trip_related_details as $detail)
                                            {
                                                echo $detail->source->name." To ".$detail->destination->name."<br>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr><th>Products</th>
                                        <td>
                                            <?php
                                            foreach($trip->trip_related_details as $detail)
                                            {
                                                echo $detail->product->name."<br>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle"></i> Freights Info</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                                    <tbody>
                                    <tr><th>Total Freight</th><td><?= $this->helper_model->money($total_freight) ?></td></tr>
                                    <tr><th>Total Contractor Freight</th><td><?= $this->helper_model->money($total_contractor_freight) ?></td></tr>
                                    <tr><th>Total Contractor Commission</th><td><?= $this->helper_model->money($total_contractor_commission) ?></td></tr>
                                    <tr><th>Total Company Commission</th><td><?= $this->helper_model->money($total_company_commission) ?></td></tr>
                                    <tr><th>Total W.H.T</th><td><?= $this->helper_model->money($total_wht) ?></td></tr>
                                    <tr><th>Total Customer Freight</th><td><?= $this->helper_model->money($total_customer_freight) ?></td></tr>
                                    <tr><th>Total Service Charges</th><td><?= $this->helper_model->money($total_service_charges) ?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!--Computing expenses-->
                <?php
                $total_other_expenses = 0;
                $grand_total_expense = 0;
                ?>
                <!---------------------->
                <div class="col-lg-6">
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle"></i> Expenses</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                                    <tbody>
                                    <tr>
                                        <th colspan="2" style="text-align: center">Other Expenses</th>
                                    </tr>
                                    <?php if(sizeof($other_expenses) > 0): ?>
                                        <?php foreach($other_expenses as $expense): ?>
                                            <?php
                                            $total_other_expenses += ($expense->dr_cr == 1)?$expense->debit_amount: $expense->credit_amount;
                                            ?>
                                            <tr><td><?= $expense->title ?></td><td><?= (($expense->dr_cr == 1)?$this->helper_model->money($expense->debit_amount):$this->helper_model->money($expense->credit_amount)) ?></td></tr>
                                        <?php endforeach; ?>
                                        <tr><th style="text-align: right">Totals:</th><td><?= $this->helper_model->money($total_other_expenses) ?></td></tr>
                                    <?php else: ?>
                                        <tr><td colspan="2" style="text-align: center"><i>There is no other expense.</i></td></tr>
                                    <?php endif; ?>

                                    <tr>
                                        <th colspan="2" style="text-align: center">Shortage Expense</th>
                                    </tr>
                                    <tr>
                                        <th>Destination Shortage</th>
                                        <td>
                                            <?php
                                            if($destination_shortage == null)
                                            {
                                                echo "0";
                                            }else{
                                                echo $this->helper_model->money($destination_shortage->debit_amount);
                                                if($decanding_shortage == null)
                                                {
                                                    $grand_total_expense += $destination_shortage->debit_amount;
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Decanding Shortage</th>
                                        <td>
                                            <?php
                                            if($decanding_shortage == null)
                                            {
                                                echo "0";
                                            }else{
                                                echo $this->helper_model->money($decanding_shortage->debit_amount);
                                                $grand_total_expense += $decanding_shortage->debit_amount;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $grand_total_expense += $total_other_expenses;
                                    ?>
                                    <tr>
                                        <th colspan="2" style="text-align: center">Grand Total: <?= $this->helper_model->money($grand_total_expense); ?></th>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle"></i> Income Statement (Customer Freight - Expenses)</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                                    <tbody>
                                    <?php
                                    $income_statement = $total_customer_freight - $grand_total_expense;
                                    ?>
                                    <tr>
                                        <th colspan="2" style="text-align: center">
                                            <?php
                                            if($income_statement < 0)
                                            {
                                                echo "<span style='color:red;'>Loss: ".$this->helper_model->money($income_statement*-1)."</span>";
                                            }else{
                                                echo "<span style='color:green;'>Profit: ".$this->helper_model->money($income_statement)."</span>";
                                            }
                                            ?>
                                        </th>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
</script>