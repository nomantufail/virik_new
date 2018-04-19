<?php
    /**
     * @var IrfanTankerReport2::class $engine
     **/
    $engine = clone($generator);
?>
<style>
    .calculation_sheet_table{
        font-size: 11px;
    }
    .calculation_sheet_heading_area{
        font-size: 15px;
        font-weight: bold;
        font-family: monospace;
    }

    .outer-table td {
        padding: 1px;
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
                Tankers Expense Report By Routes.
            </div>
            <?php print_form() ?>
            <div class="report-container" style="overflow-x: auto;">
                <table border="1" class="outer-table" style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th rowspan="2" style="min-width: 200px;">Route</th>
                        <?php
                        foreach($engine->getTankers() as $tankerId => $tanker){
                            ?>
                            <th colspan="<?= 2+sizeof($engine->tanker_expense_titles($tankerId)) ?>"><?= $tanker ?></th>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        foreach($engine->getTankers() as $tankerId => $tanker) {
                            ?>
                            <th>Trips</th>
                            <th>HSD</th>
                            <?php
                            foreach ($engine->tanker_expense_titles($tankerId) as $expenseId => $title){
                                ?>
                                <th title="<?= $title ?>"><?= $engine->short_title($title) ?></th>
                            <?php
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($engine->reportGroupedByRoute as $route_key => $routeRecords) {
                        ?>
                        <tr>
                            <th><?= $engine->getRouteTextByIds($route_key) ?></th>
                            <?php
                            foreach($engine->getTankers() as $tankerId => $tanker) {
                                ?>
                                <td><?= $engine->count_trips_of_tanker_by_route($route_key, $tankerId) ?></td>
                                <td><?= $engine->fuel_consumed($route_key, $tankerId) ?></td>
                                <?php
                                foreach ($engine->tanker_expense_titles($tankerId) as $expenseId => $title){
                                    ?>
                                    <td><?= $engine->expense_by_title($route_key, $tankerId, $expenseId) ?></td>
                                <?php
                                }
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th>Other Expenses</th>
                        <?php
                        foreach($engine->getTankers() as $tankerId => $tanker) {
                            ?>
                            <td colspan="<?= 2+sizeof($engine->tanker_expense_titles($tankerId)) ?>">
                                <?= $engine->getTankerOtherExpense($tankerId) ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #555555; color: white;">
                        <th>
                            Totals:
                        </th>
                        <?php
                        foreach($engine->getTankers() as $tankerId => $tanker) {
                            ?>
                            <td colspan="<?= 2+sizeof($engine->tanker_expense_titles($tankerId)) ?>">
                                <?= $engine->getTankerOtherExpense($tankerId)+$engine->totalTripsExpenseOfTanker($tankerId) ?>
                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

</div>

</div>
