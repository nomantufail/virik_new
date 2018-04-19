<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/9/2015
 * Time: 12:16 AM
 */
?>
<style>
    .sortable-table-heading{
        display: block;
        width: 100%;
        color: #0088cc;
    }
    .sortable-table-heading:hover{
        color: #0088cc;
        text-decoration: underline;
    }
    .custom-search-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
</style>
<?php include_once(APPPATH."views/shortages/components/custom_trips_search_widget.php"); ?>
<div id="page-wrapper" class="whole_page_container">
    <div class="container-fluid">
        <div class="col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <?php include_once(APPPATH."views/shortages/components/nav_bar.php"); ?>
                </div>
            </div>
            <hr>
            <div class="row">

                <?php
                echo $this->helper_model->display_flash_errors();
                echo $this->helper_model->display_flash_success();
                ?>
                <div class="table-responsive">
                    <div>
                        <a href="#custom-search-popup" class="open-custom-search-popup btn btn-success"  style="float: right;" ><i class="fa fa-search"></i> Search</a>
                    </div>
                    <form method="post">
                        <table class="table table-bordered table-hover table-striped" border="1" style="font-size: 11px;">

                            <thead style="background-color: lightgray;">
                            <tr>
                                <th>Trip#</th>
                                <th>Trip Date</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Product</th>
                                <th style="width:150px;">Shrt Type</th>
                                <th style="width:150px;">Shrt Date</th>
                                <th style="width:100px;">Quantity</th>
                                <th style="width:100px;">Rate</th>
                                <th style="width:100px;">Total Amount</th>
                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            //Showing Customers Data
                            $counter = 1;
                            foreach($trips as $trip){

                                ?>

                                <tr>
                                    <td><?= $trip->trip_id; ?></td>
                                    <td><?= Carbon::createFromFormat('Y-m-d',$trip->trip_entry_date)->toFormattedDateString(); ?></td>
                                    <td><?= $trip->source; ?></td>
                                    <td><?= $trip->destination; ?></td>
                                    <td><?= $trip->product_name; ?></td>
                                    <td>
                                        <!-- Hidden fields -->
                                        <input type="hidden" name="<?= 'trip_detail_id_'.$counter?>" value="<?= $trip->detail_id ?>">
                                        <!------------------------->
                                        <select id="<?= "shortage_type_".$counter ?>"  name="<?= "shortage_type_".$counter ?>" class="form-control">
                                            <?= ($trip->pending_destination())?'<option value="1">Destination</option>':'' ?>
                                            <?= ($trip->pending_decanding())?'<option value="2">Decanding</option>':'' ?>
                                        </select>
                                    </td>
                                    <td><input name="<?= "shrt_date_".$counter ?>" id="<?= "shrt_date_".$counter ?>" type="date" class="form-control"></td>
                                    <td><input name="<?= "quantity_".$counter ?>" id="<?= "quantity_".$counter ?>" type="number"class="form-control" onchange="shortage_changed(<?= $counter ?>)" onkeyup="shortage_changed(<?= $counter ?>)" ></td>
                                    <td><input name="<?= "rate_".$counter ?>" id="<?= "rate_".$counter ?>" style="font-size: 11px;" type="number" class="form-control" step="any" onchange="shortage_changed(<?= $counter ?>)" onkeyup="shortage_changed(<?= $counter ?>)"  ></td>
                                    <td><span id="<?= "total_amount_".$counter ?>"></span></td>
                                </tr>

                                <?php
                                $counter++;
                            }

                            ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="9" style="text-align: center;">
                                    <input type="hidden" name="counter" value="<?= ($counter -1) ?>">

                                    <input type="submit" name="insert_shortages" class="btn btn-success">
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                    </form>
                </div>
            </div>
        </div>

        <script>

            document.getElementById("shortage_type_1").focus();

            function shortage_changed(counter)
            {
                var quantity = document.getElementById('quantity_'+counter).value;

                quantity = (quantity == '')?0:quantity;
                rate = document.getElementById('rate_'+counter).value;
                rate = (rate == '')?0:rate;

                document.getElementById("total_amount_"+counter).innerHTML = limit_number(rate * quantity)+'';

            }
        </script>
    </div>

    <script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>
    <script>
        $('.open-custom-search-popup').magnificPopup({
            type: 'inline',
            showCloseBtn:false
        });
    </script>
</div>
