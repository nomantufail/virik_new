<?php
/*
 * this variable shows that how many rows will will be displayed by default
 * */
$default_row_counter = 2;
?>
<style>
    .invoice_entries_container{
        border: 0px solid lightgray;
        min-height: 200px;
        margin: 0px;
    }
    .invoice_table{
        width: 50%;
    }
    .invoice_table td{
        margin: 10px;
    }
</style>
<script>
    function display_row(row_num){
        var pannel_count = document.getElementById("pannel_count");
        var row_id = "row_"+row_num;
        document.getElementById(row_id).style.display='';
        pannel_count_value = parseInt(pannel_count.value);
        if(pannel_count_value == row_num)
        {
            pannel_count.value = pannel_count_value+1;

            $("#product_"+row_num).select2('val','');
            place_cross(row_num);
            remove_cross(row_num-1);
        }

    }
    function hide_row(row_num){
        var pannel_count = document.getElementById("pannel_count");
        var decrease_count = parseInt(pannel_count.value)-1;
        var row_id = "row_"+decrease_count;
        if(pannel_count.value > <?= $default_row_counter+1 ?>){
            document.getElementById(row_id).style.display='none';
            pannel_count.value = parseInt(pannel_count.value)-1;
            remove_cross(row_num);
            place_cross(row_num-1);

            grand_total_or_paid_changed();
        }
    }
    function grand_total_or_paid_changed()
    {
        var grand_total_temp = grand_total_cost();
        document.getElementById("grand_total_cost_label").innerHTML = grand_total_temp;
        var paid = document.getElementById("paid").value;
        document.getElementById("remaining").innerHTML = limit_number(grand_total_temp - paid);

    }

    function total_cost(row_num)
    {
        var qty = document.getElementById("quantity_"+row_num).value;
        var costPerItem = document.getElementById("costPerItem_"+row_num).value;
        var total_cost = limit_number(parseFloat(qty)*parseFloat(costPerItem));
        if(isNaN(total_cost))
        {
            total_cost = 0;
        }
        return total_cost;
    }
    function grand_total_cost()
    {
        var pannel_count = document.getElementById("pannel_count").value;
        var grand_total_cost = 0;
        for(i = 1; i < pannel_count; i++)
        {
            var product_selected_index = document.getElementById("product_"+i).selectedIndex;
            var selected_product = document.getElementById("product_"+i).options[product_selected_index].value;
            if(selected_product != '' && document.getElementById('row_'+i).style.display == '')
            {
                grand_total_cost += total_cost(i);
            }
        }
        return grand_total_cost;
    }

    function numbers_changed(row_num)
    {
        var total_cost_temp = total_cost(row_num);
        document.getElementById("total_cost_label_"+row_num).innerHTML = total_cost_temp;
        grand_total_or_paid_changed();
    }
    function product_changed(e)
    {
        var id = e.params.data.element.parentElement.id;
        id = id.split("_");
        id = id[1];
        display_row(parseInt(id)+1);
        grand_total_or_paid_changed();
    }

    function place_cross(row_num)
    {
        document.getElementById('cross_'+row_num).innerHTML='X';
    }
    function remove_cross(row_num)
    {
        document.getElementById('cross_'+row_num).innerHTML='';
    }

</script>

<div id="page-wrapper" class="whole_page_container">

    <div class="container-fluid">
        <div class="row">
            <?php
            include_once(APPPATH."views/purchases/components/nav_bar.php");
            ?>
        </div>

        <!--Notifications Area-->
        <div class="row">
            <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">

                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                                            <strong>Error! </strong>', '</div>'); ?>
            <?php if(is_array(@$someMessage)){ ?>
                <div class="alert <?= $someMessage['type']; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <?= $someMessage['message']; ?>
                </div>
            <?php } ?>

        </div>
        <!--notifications area ends-->

        <div class="row actual_body_contents" style="background-color: rgba(0,0,0,0.03); margin-top: 20px;">
            <form method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="invoice_table table table-bordered">
                            <tr>
                                <th>Invoice#</th>
                                <td><?= $invoice_number ?></td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>
                                    <select class="select_box suppliers_select_box" style="width: 200px;" name="supplier" id="supplier">
                                        <?php foreach($suppliers as $supplier):?>
                                            <option value="<?= $supplier->id ?>"><?= $supplier->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Invoice Date</th>
                                <td><input class="form-control" value="<?= date("Y-m-d"); ?>" style="width: 200px;" type="date" name="invoice_date"></td>
                            </tr>
                            <tr>
                                <th>Transaction Type</th>
                                <td>Credit</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">

                    <div class="row">

                        <div class="col-lg-12 invoice_entries_container">

                            <table class="table">
                                <thead>
                                <tr style="background-color: lightblue;">
                                    <th style="width: 25%;">Product</th>
                                    <th style="width: 12%;">Qty</th>
                                    <th style="width: 12%;">Cst/item</th>
                                    <th style="width: 12%;">Total/Cst</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php for($row_counter = 1; $row_counter<10; $row_counter++):?>

                                    <tr id="row_<?= $row_counter ?>" style="display: <?= ($row_counter > $default_row_counter)?"none":""; ?>; background-color: <?= (($row_counter % 2 == 0)?'rgba(0,0,0,0.06)':'') ?>;">
                                        <td>
                                            <select class="select_box product_select_box" style="width: 200px;" name="product_<?= $row_counter ?>" id="product_<?= $row_counter ?>">
                                                <option value="">--Select--</option>
                                                <?php foreach($products as $product):?>
                                                    <option value="<?= $product->id ?>"><?= $product->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" step="any" name="quantity_<?= $row_counter ?>" id="quantity_<?= $row_counter ?>" onchange="numbers_changed(<?= $row_counter ?>)" onkeyup="numbers_changed(<?= $row_counter ?>)"></td>
                                        <td><input type="number" step="any" name="costPerItem_<?= $row_counter ?>" id="costPerItem_<?= $row_counter ?>" onchange="numbers_changed(<?= $row_counter ?>)" onkeyup="numbers_changed(<?= $row_counter ?>)"></td>
                                        <td><span id="total_cost_label_<?= $row_counter ?>"></span></td>
                                        <td><span onclick="hide_row(<?= $row_counter ?>)" style="color: red; cursor: pointer; font-weight: bold;" id="cross_<?= $row_counter ?>"><?= (($row_counter == $default_row_counter)?'':'') ?></span></td>
                                    </tr>
                                <?php endfor; ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <input value="<?= $default_row_counter+1 ?>" type="hidden" id="pannel_count" name="pannel_count">
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="margin:0px;">
                            <label class="" style="font-size: 18px;">Other Information:</label>
                            <textarea class="form-control" name="extra_info"></textarea>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 30px;">
                        <div class="col-md-4" style="margin: 0px;">
                            <section style="font-size: 20px; font-weight: normal; color: red;">Total Cost: <span id="grand_total_cost_label">0</span> Rs.</section>
                        </div>
                        <div class="col-md-4" style="margin: 0px;">
                             <strong style="font-size: 20px;">Paid: </strong><input style="height: 30px;" type="number" step="any" id="paid" name="paid"  onchange="grand_total_or_paid_changed()" onkeyup="grand_total_or_paid_changed()">
                        </div>
                        <div class="col-md-4" style="margin: 0px;">
                            <section style="font-size: 20px; font-weight: normal; color: red;">Remaining: <span id="remaining">0</span> Rs.</section>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12" style="text-align: center">
                        <button name="save_credit_purchase" class="btn btn-success" style="font-size: 20px;"><i class="fa fa-save" style="color: white;"></i> Save Invoice</button>
                    </div>

                </div>
            </form>
        </div>



    </div>

</div>
<script>
    var $eventSelect = $(".product_select_box");
    $eventSelect.on("select2:select", function (e) {
        product_changed(e);
    });
</script>