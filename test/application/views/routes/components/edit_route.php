
<div class="white-popup">

<script>
    function fetch_freight_history(route_id, from, to, callback){
        var history_area = document.getElementById("freight_history");
        history_area.innerHTML = "fetching.....";

        $.get("<?=base_url()."routes/show_freight_history/"?>"+route_id+"/"+from+"/"+to ,function(data,status){
            history_area.innerHTML= data;
            callback();
        });
    }


    function fetch_freight(route_id, from, to, callback){
        $.get("<?=base_url()."routes/freight_for_editing_routes/"?>"+route_id+"/"+from+"/"+to ,function(data,status){
            var freight = JSON.parse(data);
            callback(freight);
        });
    }

    function date_changed_for_editing()
    {
        var route_id = document.getElementById("route_id").value;
        var from = document.getElementById("edit_from").value;
        var to = document.getElementById("edit_to").value;
        if(from != '' && to != ''){
            fetch_freight(route_id, from, to, function(freight){
               freight = freight.freight;
                document.getElementById("edit_freight_field").value = freight;
            });
        }
    }

    function history_form_submitted()
    {
        var route_id = document.getElementById("route_id").value;
        var from = document.getElementById("startDate").value;
        var to = document.getElementById("endDate").value;

        fetch_freight_history(route_id, from, to, function(){

        });

        return false;
    }


    //here is the stuff we do when document is ready...
    $( document ).ready(function() {
        var route_id = document.getElementById("route_id").value;
        var from = "1947-01-01";
        <?php
            $to_year = intval(date('Y'))+5;
            $to_date = $to_year."-".date('m')."-".date('d');
        ?>
        var to = "<?= $to_date; ?>";

        fetch_freight_history(route_id, from, to, function(){

        });
    });

</script>
    <style>
        #history_form_area input{
            height: 25px;
        }
    </style>
    <div class="row">
        <div class="col-lg-6">
            <h4 style="text-align: center; color: #269abc">Edit Route</h4><hr>
            <div class="col-lg-12" id="error" style="color: red;">

            </div>
            <form action="" method="post" onsubmit="return validate_route_editing()">
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">Source</label>
                    <input type="hidden" name="form_id" value="<?= $form_id; ?>">
                    <input type="hidden" name="route_id" value="<?= $route_id; ?>">
                    <input type="hidden" name="previous_freight" value="<?= $route->freight; ?>">
                    <input type="hidden" name="freight_id" value="<?= $route->freight_id; ?>">
                    <div class="col-lg-8">
                        <select name="source" required="required" class="form-control source_city_select" id="editing_source">
                            <?php
                            foreach($cities as $city){
                                $selected = ($route->sourceId == $city->id)?"selected":"";
                                ?>
                                <option <?= $selected ?> value="<?= $city->id; ?>"><?= $city->cityName; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">Destination</label>
                    <div class="col-lg-8">
                        <select name="destination" required="required" class="form-control destination_city_select" id="editing_destination">
                            <?php
                            foreach($cities as $city){
                                $selected = ($route->destinationId == $city->id)?"selected":"";
                                ?>
                                <option <?= $selected ?> value="<?= $city->id; ?>"><?= $city->cityName; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">Product</label>
                    <div class="col-lg-8">
                        <select name="product" required="required" class="form-control product_select" id="product">
                            <?php
                            foreach($products as $product){
                                $selected = ($route->productId == $product->id)?"selected":"";
                                ?>
                                <option <?= $selected ?> value="<?= $product->id; ?>"><?= $product->productName; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">Freight</label>
                    <div class="col-lg-8">
                        <input class="form-control" id="edit_freight_field" onkeyup="" required="required" type="text" name="freight" value="<?= $route->freight ?>" placeholder="freight here">
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">From</label>
                    <div class="col-lg-8">
                        <input class="form-control" id="edit_from" onchange="date_changed_for_editing()" type="date" name="from" value="<?= $route->startDate ?>" placeholder="">
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label">To</label>
                    <div class="col-lg-8">
                        <input class="form-control" onchange="date_changed_for_editing()" id="edit_to" type="date" name="to" value="<?= $route->endDate ?>" placeholder="">
                    </div>
                </div>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-lg-8">
                        <div class="col-sm-8" style="margin-top: 5px;"><input type="submit" name="save_route" style="width: 100%;" class="btn btn-success" value="Save"></div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-6" style="font-size: 12px;">
            <h4>Previous Freights History</h4>
            <div id="history_form_area">
                <form onsubmit="return history_form_submitted()">
                    <input type="hidden" id="route_id" value="<?= $route_id ?>">
                    <table style="width: 100%;">
                        <tr>
                            <th>From: </th><td><input type="date" id="startDate" required="required"></td> <th>To: </th><td><input id="endDate" required="required" type="date"></td><th><input style="width: 100%;" type="submit" value="Fetch"></th>
                        </tr>
                    </table>
                </form>
            </div>
            <hr>
            <div id="freight_history" style="max-height: 300px; overflow-y: auto; font-size: 12px;">

            </div>
        </div>
    </div>
    <script>
        $(".source_city_select").select2();
        $(".destination_city_select").select2();
        $(".product_select").select2();
    </script>
</div>
