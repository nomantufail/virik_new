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

            </div>

            <?php
                foreach($reports as $type_id => $report){
            ?>

                    <div class="panel-body">
                        <h4><?= find_in_objects_by_key_value('id',$type_id, $trip_types)->trip_sub_type ?></h4>
                        <div class="table-responsive">

                            <form name="selection_form" id="selection_form" method="post" action="<?php
                            if(strpos($this->helper_model->page_url(),'?') == false){
                                echo $this->helper_model->page_url()."?";
                            }else{echo $this->helper_model->page_url()."&";}
                            ?>print">
                                <table class="calculation_sheet_table table table-bordered table-hover table-striped accounts-table sortable" style="min-width:1000px;">
                                    <thead style="border-top: 4px solid lightgray;">
                                    <tr>
                                        <th style="width: 10%">Driver</th>
                                        <th style="width: 5%">Vehicle No</th>
                                        <th style="width: 10%">Cap</th> <!--trip filling date-->
                                        <th style="text-align: center; width: 10%;"> Income</th>
                                        <th style="width: 10%">Trips</th>
                                        <th style="text-align: ;width: 10%;">Trip Exp</th>
                                        <th style="text-align: ">Exp Per Trip</th>
                                        <th style="text-align: center">Repair Maint</th>
                                        <th style="text-align: center">Installment</th>
                                        <th style="text-align: center">Short Dip</th>
                                        <th style="text-align: center">Short Amount</th>
                                        <th style="text-align: center">Salary</th>
                                        <th style="text-align: center">Total Fuel</th>
                                        <th style="text-align: center">Total K.M</th>
                                        <th style="text-align: center">AVG</th>
                                        <th style="text-align: center">Profit/Loss</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach($report as $record){
                                    ?>
                                            <tr style="">
                                                <td></td>
                                                <td><?= $record->getVehicleNumber() ?></td>
                                                <td><?= $record->getCapacity() ?></td>
                                                <td><?= $record->getIncome() ?></td>
                                                <td><?= $record->getTrips() ?></td>
                                                <td><?= $record->getTripExpenses() ?></td>
                                                <td><?= round($record->getTripExpenses()/$record->getTrips(), 2)  ?></td>
                                                <td><?= $record->getRepairMaintainence() ?></td>
                                                <td><?= $record->getInstallment() ?></td>
                                                <td><?= $record->getShortDip() ?></td>
                                                <td><?= $record->getShortageAmount() ?></td>
                                                <td><?= $record->getSalary() ?></td>
                                                <td><?= $record->getTotalFuel() ?></td>
                                                <td><?= $record->getTotalKm() ?></td>
                                                <td><?= $record->getAvgFuel() ?></td>
                                                <td><?= ($record->getIncome() - $record->getTripExpenses() - $record->getRepairMaintainence() - $record->getSalary()) ?></td>
                                            </tr>
                                    <?php
                                        }
                                    ?>

                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>

</div>

</div>
