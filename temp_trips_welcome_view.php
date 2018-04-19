<style>
    .trips-table{
        font-size: 11px;
    }
    .multiple_entites{
        border-bottom: 1px dashed lightgray;
    }
    .search-table2 , .search-table1{
        font-size: 12px;
    }
    .search-table2 input{
        width:100%;
    }
    .search-table1 input{
        width:100%;
    }
    .search-table2 input{
        height: 30px;
    }
    .search-table1 input{
        height: 30px;
    }
    .search-table2 button{
        height: 30px;
    }
    .sortable-table-heading{
        display: block;
        width: 100%;
        color: #0088cc;
    }
    .sortable-table-heading:hover{
        color: #0088cc;
        text-decoration: underline;
    }

    .shortage_expense_chit_popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }

    .custom-search-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .multiple_sorting_popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .white-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 1000px;
        margin: 20px auto;
    }
</style>

<script>

    function show_fuel_report(purpose)
    {
        fuel_report_submitted();
        var action = document.getElementById("fuel_report_form").action;
        if(purpose == 'print')
        {
            new_action = action.replace("?export","?print")
            new_action = new_action.replace("&export","&print")
        }
        else if(purpose == 'export')
        {
            new_action = action.replace("?print","?export")
            new_action = new_action.replace("&print","&export")
            var query = new_action.indexOf("?");
            if(query == -1)
            {
                new_action+="?export";
            }
            else
            {
                new_action+="&export";
            }
        }
        document.getElementById("fuel_report_form").action = new_action;
        document.getElementById("fuel_report_form").submit();
    }

    function get_selected_ids()
    {
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_ids = trip_ids+"_"+check_boxes[count].value;
            }
        }

        return trip_ids;
    }
    function get_selected_columns()
    {
        var columns = "0";
        var column = document.getElementById("id_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("type_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("trip_date_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("tanker_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("capacity_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("contractor_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("customer_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("company_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("source");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("destination");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("product_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("product_quantity_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        var column = document.getElementById("stn_column");
        if(column.checked == true)
        {
            columns+= "/"+column.value;
        }
        return columns;

    }
    function fuel_report_submitted()
    {
        var selected_ids = get_selected_ids();
        var selected_columns = get_selected_columns();
        if(selected_ids != "0")
        {
            document.getElementById("trip_ids_for_fuel_report").value = selected_ids;
            document.getElementById("columns_for_fuel_report").value = selected_columns;
            return true;
        }else{
            alert("Warning!\n\nNo trip selected.")
        }
        return false;
    }
    function show_monthly_results(month, path){
        var path = path+month;
        window.location.href = path;
    }

    function set_white_oil_dest_shortage_trip_ids(trip_id, trip_detail_id, product)
    {
        document.getElementById('white_oil_shortage_trip_detail_id').value = trip_detail_id;
        document.getElementById('white_oil_shortage_trip_id').value = trip_id;
        document.getElementById('white_oil_shortage_trip_id_label').innerHTML = trip_id;
        document.getElementById('white_oil_shortage_product').value = product;
        $('#white_oil_shortage_at').val('destination');
        document.getElementById("white_oil_shortage_at").options[1].disabled = true;
        document.getElementById("white_oil_destination_voucher").value = 0;
        var test_link = document.getElementById("white_oil_get_shortage_expense_voucher");
        test_link.setAttribute('href', '#');
        test_link.className="btn btn-success";
    }
    function set_black_oil_dest_shortage_trip_ids(trip_id, trip_detail_id, product, freight_unit)
    {
        document.getElementById('black_oil_shortage_trip_detail_id').value = trip_detail_id;
        document.getElementById('black_oil_shortage_trip_id').value = trip_id;
        document.getElementById('black_oil_shortage_trip_id_label').innerHTML = trip_id;
        document.getElementById('black_oil_shortage_freight_unit_label').innerHTML = freight_unit;
        document.getElementById('black_oil_shortage_product').value = product;
        $('#black_oil_shortage_at').val('destination');
        document.getElementById("black_oil_shortage_at").options[1].disabled = true;
        document.getElementById("black_oil_destination_voucher").value = 0;
        var test_link = document.getElementById("black_oil_get_shortage_expense_voucher");
        test_link.setAttribute('href', '#');
        test_link.className="btn btn-success";
    }

    function set_white_oil_decnd_shortage_trip_ids(trip_id, trip_detail_id, product, destination_voucher){
        document.getElementById('white_oil_shortage_trip_detail_id').value = trip_detail_id;
        document.getElementById('white_oil_shortage_trip_id').value = trip_id;
        document.getElementById('white_oil_shortage_trip_id_label').innerHTML = trip_id;
        document.getElementById('white_oil_shortage_product').value = product;
        $('#white_oil_shortage_at').val('decanding');
        document.getElementById("white_oil_shortage_at").options[0].disabled = true;
        document.getElementById("white_oil_destination_voucher").value = destination_voucher;

        var test_link = document.getElementById("white_oil_get_shortage_expense_voucher");
        test_link.setAttribute('href', '#');
        test_link.className="btn btn-success";
    }

    function set_black_oil_decnd_shortage_trip_ids(trip_id, trip_detail_id, product, destination_voucher, freight_unit){
        document.getElementById('black_oil_shortage_trip_detail_id').value = trip_detail_id;
        document.getElementById('black_oil_shortage_trip_id').value = trip_id;
        document.getElementById('black_oil_shortage_trip_id_label').innerHTML = trip_id;
        document.getElementById('black_oil_shortage_freight_unit_label').innerHTML = freight_unit;
        document.getElementById('black_oil_shortage_product').value = product;
        $('#black_oil_shortage_at').val('decanding');
        document.getElementById("black_oil_shortage_at").options[0].disabled = true;
        document.getElementById("black_oil_destination_voucher").value = destination_voucher;

        var test_link = document.getElementById("black_oil_get_shortage_expense_voucher");
        test_link.setAttribute('href', '#');
        test_link.className="btn btn-success";
    }
</script>

<div id="page-wrapper" style="min-height: 700px;">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-2">
                    <h2 class="">
                        Trips
                    </h2>
                </section>
                <section class="col-md-10" style="border-bottom: 0px solid;">
                    <?php
                    include_once(APPPATH."views/trips/components/main_nav_bar.php");
                    ?>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel-body">

                    <div id="myTabContent" class="tab-content">

                    <?php
                        include_once(APPPATH."views/trips/components/custom_search_widget.php");
                    ?>
                    <?php
                        include_once(APPPATH."views/trips/components/multiple_sorting_widget.php");
                    ?>

                    <!--this area is hidden from the view and is used for shortage expense-->
                    <?php
                    include_once(APPPATH."views/trips/components/white_oil_shortage_chit.php");
                    ?>
                    <?php
                    include_once(APPPATH."views/trips/components/black_oil_shortage_chit.php");
                    ?>
                    <!--***********************************************************************-->
                        <div class="tab-pane fade in active" id="trips">

                            <?php
                            echo $this->helper_model->display_flash_errors();
                            echo $this->helper_model->display_flash_success();
                            ?>

                            <?php if(isset($_GET['del']) || isset($_POST['save_shortage_voucher']) || isset($_POST['save_tanker_expense_voucher'])): ?>
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
                            <?php endif; ?>

                            <div class="table-responsive col-lg-12" style="overflow-x: auto;">
                                <table style="">
                                    <tr>
                                        <td>
                                            <a href="#custom-search-popup" class="open-custom-search-popup btn btn-success" style="border-radius: 0px;"><i class="fa fa-search"></i> Search Trips</a>
                                            <a href="#multiple_sorting_popup" class="open-multiple-sorting-popup btn btn-success" style="border-radius: 0px;"><i class="fa fa-sort"></i> Sort Trips</a>
                                            <a  href="<?= base_url()."tankers/add_tanker_expense/?tanker_id=1" ?>" class="add_tanker_expense btn btn-danger"><i class="fa fa-plus-circle" style="color: white;"></i> Add Expense</a>
                                        </td>
                                        <td>
                                            <form id="fuel_report_form" method="post" action="<?= base_url()."trips/print_fuel_report" ?>" target="_blank" onsubmit="return fuel_report_submitted()">
                                                <input type="hidden" name="trip_ids_for_fuel_report" id="trip_ids_for_fuel_report" value="">
                                                <input type="hidden" name="columns_for_fuel_report" id="columns_for_fuel_report" value="">
                                                <ul class="" style="list-style: none; margin: 0px; padding: 0px; margin-left: 5px;">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-success" data-toggle="dropdown">Fuel Report <b class="caret"></b></a>
                                                        <ul class="dropdown-menu">
                                                            <li class="">
                                                                <a href="#" onclick="show_fuel_report('print')"><i class="fa fa-fw fa-print"></i> Print in Browser</a>
                                                            </li>
                                                            <li >
                                                                <a href="#" onclick="show_fuel_report('export')"><i class="fa fa-fw fa-file-excel-o"></i> Export to Excel</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                                <input type="hidden" name="show_fuel_report" value="Fuel Report">
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                                <form name="selection_form" id="selection_form" method="post" action="<?php
                                if(strpos($this->helper_model->page_url(),'?') == false){
                                    echo $this->helper_model->page_url()."?";
                                }else{echo $this->helper_model->page_url()."&";}
                                ?>print">
                                    <table class="table table-bordered table-hover table-striped trips-table" style="width: 1200px;">

                                        <thead style="border-top: 4px solid lightgray;">

                                        <tr>
                                            <th></th>
                                            <th><div><input id="id_column" type="checkbox" name="column[]" value="id" style="" checked></div></th>
                                            <th><div><input id="type_column" type="checkbox" name="column[]" value="type" style="" checked></div></th>
                                            <th><div><input id="trip_date_column" type="checkbox" name="column[]" value="trip_date" style="" checked></div></th>
                                            <th><div><input id="tanker_column" type="checkbox" name="column[]" value="tanker" style="" checked></div></th>
                                            <th><div><input id="capacity_column" type="checkbox" name="column[]" value="capacity" style="" checked></div></th>
                                            <th><div><input id="contractor_column" type="checkbox" name="column[]" value="contractor" style="" checked></div></th>
                                            <th><div><input id="customer_column" type="checkbox" name="column[]" value="customer" style="" checked></div> </th>
                                            <th><div><input id="company_column" type="checkbox" name="column[]" value="company" style="" checked></div></th>
                                            <th><div><input id="source" type="checkbox" name="column[]" value="source" style="" checked></div></th>
                                            <th><div><input id="destination" type="checkbox" name="column[]" value="destination" style="" checked></div></th>
                                            <th><div><input id="product_column" type="checkbox" name="column[]" value="product" style="" checked></div></th>
                                            <th><div><input id="product_quantity_column" type="checkbox" name="column[]" value="product_quantity" style="" checked></div></th>
                                            <th><div><input id="stn_column" type="checkbox" name="column[]" value="stn" style="" checked></div> </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>

                                        <tr>
                                            <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('trip_id'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('trip_id', 'numeric'); ?>"> </i> ID</a></th>
                                            <th style="width: 7%;"><a href="<?php echo $this->helper_model->sorting_info('trip_sub_type'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('trip_sub_type', 'string'); ?>"> </i> Type</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('entryDate'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('entryDate', 'string'); ?>"> </i> Trip Date</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('tanker_number'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('tanker_number', 'string'); ?>"> </i> Tanker</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('capacity'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('capacity', 'numeric'); ?>"> </i> Capacity</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('contractor_name'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('contractor_name', 'string'); ?>"> </i> Contractor</a></th>
                                            <th style="width: 7%;"><a href="<?php echo $this->helper_model->sorting_info('customer_name'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('customer_name', 'string'); ?>"> </i> Customers</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('company_name'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('company_name', 'string'); ?>"> </i> Company</a></th>
                                            <th style="width: 9%;"><a href="<?php echo $this->helper_model->sorting_info('source'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('source', 'string'); ?>"> </i> Source</a></th>
                                            <th style="width: 9%;"><a href="<?php echo $this->helper_model->sorting_info('destination'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('destination', 'string'); ?>"> </i> Destination</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('product'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('product', 'string'); ?>"> </i> Product</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('product_quantity'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('product_quantity', 'numeric'); ?>"> </i> Product Quantity</a></th>
                                            <th><a href="<?php echo $this->helper_model->sorting_info('stn_number'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('stn_number', 'string'); ?>"> </i> STN-Number</a></th>
                                            <th style="">Tanker Expense</th>
                                            <th style="width: 80px;"></th>
                                            <th style="width: 80px;"></th>
                                            <th style="width: 80px;"></th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        <?php
                                        $total_product_quantity = 0;
                                        $total_capacity = 0;
                                        ?>
                                        <?php $parent_count = 0; ?>
                                        <?php  foreach($trips as $trip): ?>
                                            <tr style="border-top: <?= ($count == 1)?'3':'0'; ?>px solid lightblue;">


                                                <td>
                                                    <input class='filter_check_box' type='checkbox' name='check[]' style='' value="<?= $trip->trip_id ?>" checked>
                                                </td>

                                                <?php
                                                $style = '';
                                                if($trip->stn_number != '')
                                                {
                                                    $style = "background-color: lightgreen; color:white;";
                                                }
                                                ?>
                                                <td style="<?= $style ?>">
                                                    <a target=_blank href='<?= base_url()."trips/edit/".$trip->trip_id ?>'><?= $trip->trip_id ?></a>
                                                </td>
                                                <td>
                                                    <?= $trip->trip_sub_type ?>
                                                </td>
                                                <td>
                                                    <?= Carbon::createFromFormat('Y-m-d',$trip->entryDate)->toFormattedDateString() ?>
                                                </td>
                                                <td>
                                                    <?= $trip->tanker_number ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $total_capacity += $trip->capacity;
                                                    echo $trip->capacity;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $trip->contractor_name ?>
                                                </td>
                                                <td>
                                                    <?= $trip->customer_name ?>
                                                </td>
                                                <td>
                                                    <?= $trip->company_name ?>
                                                </td>

                                                <td style="font-size: 11px;"><?= $trip->source; ?></td>
                                                <td style="font-size: 11px;"><?= $trip->destination; ?></td>
                                                <td style="font-size: 11px;"><?= $trip->product; ?></td>

                                                <?php
                                                $total_product_quantity += $trip->product_quantity;
                                                ?>
                                                <td style="font-size: 11px;"><?= $trip->product_quantity; ?></td>
                                                <td><?= $trip->stn_number; ?></td>

                                                <td>
                                                    <a href="<?= base_url()."tankers/add_tanker_expense/$trip->trip_id" ?>" class="add_tanker_expense" style="background-color: rgba(0,0,0,0); border-bottom: 1px solid lightgray; width: 100%; height: 100%;"><i class="fa fa-plus-circle" style="color: #0088cc; font-size: 12px;">Add</i></a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url()."trips/edit/".$trip->trip_id; ?>" style="display: block; height: 100%; width: 100%; border-bottom: 1px solid lightgray;"><li class="fa fa-edit"></li> edit</a>
                                                    <?php
                                                    $query_string = $this->helper_model->merge_query($_SERVER['QUERY_STRING'],array('del'=>$trip->trip_id));
                                                    $url = $this->helper_model->url_path()."?".$query_string;
                                                    ?>
                                                    <?php if($this->privilege_model->allow_removing() == true): ?>
                                                        <a href="<?= $url ?>" onclick="return confirm_deleting('Alert!\nAll products of this trip will be removed.\nAre you sure you want to delete this record?')"><i class="fa fa-minus-circle" style="color: red"></i> remove</a>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $expense_leger_url = "accounts/ledger/users/1/?ac_type=expense&trip_id=".$trip->trip_id."&ledger_title=%2A&ledger_account_title_id=%2A&ledger_ac_type=expense&related_other_agent=%2A&ledger_other_agent_name=%2A&related_customer=%2A&ledger_customer_name=%2A&related_contractor=%2A&ledger_contractor_name=%2A&related_company=%2A&ledger_company_name=%2A";
                                                    ?>
                                                    <a target="_blank" href="<?= base_url().$expense_leger_url ?>" style="background-color: rgba(0,0,0,0); border-bottom: 1px solid lightgray; width: 100%; height: 100%;"><i class="fa fa-eye" style="color: #0088cc; font-size: 12px;">Expenses</i></a>
                                                </td>
                                                <td >
                                                    <a target="_blank" href="<?= base_url()."trips/trip_report/?id=".$trip->trip_id; ?>" style="display: block; height: 100%; width: 100%; border-bottom: 1px solid lightgray;"><li class="fa fa-file-o"></li> Report</a>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td>
                                                <?php
                                                //echo $this->helper_model->money($total_capacity);
                                                ?>
                                            </td>
                                            <td colspan="6"></td>
                                            <td>
                                                <?php
                                                echo $this->helper_model->money($total_product_quantity);
                                                ?>
                                            </td>
                                            <td colspan="7"></td>
                                        </tr>
                                        </tfoot>

                                    </table>
                                </form>

                            </div>
                            <!--//pages-->
                            <div class="col-lg-12 text-center">
                                <?php
                                echo $pages;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= js()."jquery.magnific-popup.js"; ?>"></script>
<script>
//    $.magnificPopup.instance._onFocusIn = function(e) {
//        // Do nothing if target element is select2 input
//        if( $(e.target).hasClass('select2-search__field') ) {
//            return true;
//        }
//        // Else call parent method
//        $.magnificPopup.proto._onFocusIn.call(this,e);
//    };
    $('.shortage_expense_chit').magnificPopup({
        type: 'inline',
        showCloseBtn:false
    });
    $('.open-multiple-sorting-popup').magnificPopup({
        type: 'inline',
        showCloseBtn:false
    });
    $('.open-custom-search-popup').magnificPopup({
        type: 'inline',
        showCloseBtn:true,
        callbacks: {
            open: function() {
                $(".customers_select").select2();
                $(".contractors_select").select2();
                $(".companies_select").select2();
                $(".drivers_select").select2();
                $(".source_city_select").select2();
                $(".destination_city_select").select2();
                $(".product_select").select2();
                $(".tankers_select").select2();
                $(".trip_type_select").select2();
            }
            // e.t.c.
        }
    });
    $('.add_tanker_expense').magnificPopup({
        type: 'ajax',
        showCloseBtn:false,
        callbacks: {
            open: function() {
                $(".titles_select").select2();
            }
            // e.t.c.
        }
    });
    $(document).ajaxComplete(function(){
        $(".agent_select").select2();
    });

</script>