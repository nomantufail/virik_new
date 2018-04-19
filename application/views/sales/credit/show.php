<style>
    .insert_table td input{
        width: 100%;
    }
    .insert_table td select{
        width: 100%;
        height: 25px;
    }
    .insert_table button
    {
        width: 100%;
    }
    .insert_table .lable{

    }
</style>

<div id="page-wrapper" class="whole_page_container">

    <div class="container-fluid">
        <div class="row">
            <?php
            include_once(APPPATH."views/sales/components/nav_bar.php");
            ?>
        </div>
        <!--Notifications Area-->
        <div class="row">
            <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">

                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                                            <strong>Error! </strong>', '</div>'); ?>


        </div>
        <!--notifications area ends-->

        <div class="row actual_body_contents">

            <div class="row">

                <div class="col-lg-12">

                    <table class="my_table list_table table table-bordered">
                        <thead class="table_header">
                        <tr class="table_row table_header_row">
                            <th class="column_heading">Invoice#</th>
                            <th class="column_heading">Date</th>
                            <th class="column_heading">Customer</th>
                            <th class="column_heading">Product</th>
                            <th class="column_heading">Qty</th>
                            <th class="column_heading">Sale Price / Item</th>
                            <th class="column_heading">Total Price</th>
                            <th class="column_heading">Received</th>
                            <th class="column_heading">Remaining</th>
                            <th class="column_heading">Extra Info</th>
                            <th class="column_heading"></th>
                        </tr>
                        </thead>
                        <tbody class="table_body">
                        <?php
                        $total_product_quantity = 0;
                        $total_cost = 0;
                        ?>
                        <?php $parent_count = 0; ?>
                        <?php  foreach($sales as $record): ?>
                            <?php
                            $count = 0;
                            $num_invoice_items = sizeof($record->entries);
                            ?>
                            <?php foreach($record->entries as $entry): ?>
                                <?php
                                $count++;
                                $parent_count++;
                                ?>

                                <tr style="border-top: <?= ($count == 1)?'3':'0'; ?>px solid lightblue;">
                                    <?php if($count == 1){echo "<td rowspan=".($num_invoice_items)." style=''><a target=_blank href='#".$record->id."'>".$record->id."</a></td>";} ?>
                                    <?php if($count == 1){echo "<td rowspan=".($num_invoice_items).">".Carbon::createFromFormat('Y-m-d',$record->date)->toFormattedDateString()."</td>";} ?>

                                    <?php if($count == 1){echo "<td rowspan=".($num_invoice_items).">".$record->customer->name."</td>";} ?>

                                    <td>
                                        <?php
                                        echo $entry->product->name;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $total_product_quantity += $entry->quantity;
                                        echo $entry->quantity;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $entry->salePricePerItem;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $entry->total_cost();
                                        ?>
                                    </td>

                                    <?php if($count == 1):?>
                                        <td rowspan="<?=($num_invoice_items)?>" style="vertical-align: middle;">
                                            <?php
                                            echo $record->received;
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($count == 1):?>
                                        <td rowspan="<?=($num_invoice_items)?>" style="vertical-align: middle;">
                                            <?php
                                            echo $record->remaining();
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if($count == 1):?>
                                        <td rowspan="<?=($num_invoice_items)?>">
                                            <?php
                                            echo $record->extra_info_simplified();
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if($count == 1):?>
                                        <td rowspan="<?=($num_invoice_items)?>" style="vertical-align: middle;">

                                        </td>
                                    <?php endif; ?>


                                </tr>
                            <?php endforeach ?>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table_footer">
                        <tr class="table_footer_row">

                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>



    </div>

</div>