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
<?php
    $engine = clone($generator);
?>
<div class="report-container" style="">
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

</body>

</html>