<style>
    .white-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 1000px;
        margin: 20px auto;
    }
    .search-table{
        font-size: 12px;
    }
    .search-table input{
        width:100%;
    }
    .search-table select{
        width:100%;
        height: 23px;
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
</style>
<script>
    function check_dates($from, $to){
        $from = new Date($from);
        $to = new Date($to);
        if($from > $to){
            document.getElementById("error").innerHTML = "From Date should be less than To Date.";
            return false;
        }else if($from < $to){
            return true;
        }else{
            document.getElementById("error").innerHTML = "From Date and To Date can not be same.";
            return false;
        }
    }
    function check_routes(){
        if(check_dates(document.getElementById("add_from").value, document.getElementById("add_to").value) == false){
            return false;
        }
        var source = document.getElementById("source").selectedIndex;
        var destination = document.getElementById("destination").selectedIndex;

        var sourceValue = document.getElementById("source").options[source].value;
        var destinationValue = document.getElementById("destination").options[destination].value;

        if(sourceValue == destinationValue){
            var c = confirm("Dear user! destination and source are identical. are you sure you want to proceed ?");
            if(c == true){
                return true;
            }else{
                //document.getElementById("error").innerHTML = "Source and Destination cannot be same";
                return false;
            }
        }else{
            return true;
        }
    }

    function check_editing_routes(){
        var source = document.getElementById("editing_source").selectedIndex;
        var destination = document.getElementById("editing_destination").selectedIndex;

        var sourceValue = document.getElementById("editing_source").options[source].value;
        var destinationValue = document.getElementById("editing_destination").options[destination].value;

        if(sourceValue == destinationValue){
            var cnf = confirm("Dear user! destination and source are identical. are you sure you want to proceed ?");
            if(cnf == true){
                return true;
            }else{
                //document.getElementById("error").innerHTML = "Source and Destination cannot be same";
                return false;
            }
        }else{
            return true;
        }
    }

    function validate_route_editing(){
        if(check_editing_routes() == false){
            return false;
        }
        if(check_freight_editing() == false){
            return false;
        }
        return true;
    }

    function check_freight_editing(){
        document.getElementById("error").innerHTML = "";
        if(check_dates(document.getElementById("edit_from").value, document.getElementById("edit_to").value) == false){
            return false;
        }else{
            return true;
        }
    }
</script>
<div id="page-wrapper" style="min-height: 700px;">
    <div class="container-fluid">

    <!--including editable links-->
    <?php include_editable_libs(); ?>

        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-4">
                    <h3 class="">
                        Routes <small></small>
                    </h3>
                </section>
                <section class="col-md-8">

                    <ul id="myTab" class="nav nav-pills" style="width: 100%;">
                        <li class="<?= (($route_type == 'primary')?'active':'') ?>"><a href="<?= base_url()."routes/index/";?>">Primary</a></li>
                        <li class="<?= (($route_type == 'secondary')?'active':'') ?>"><a href="<?= base_url()."routes/index/secondary";?>">Secondary</a></li>
                        <li class="<?= (($route_type == 'secondary_local')?'active':'') ?>"><a href="<?= base_url()."routes/index/secondary_local";?>">Secondary Local</a></li>
                    </ul>

                </section>
            </div>
        </div>
<hr>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel-body">
                    <div id="myTabContent" class="tab-content" style="min-height: 500px;">
                        <div class="tab-pane fade in <?php if($section == 'all'){echo "active";} ?>" id="routes">
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
                            </div>
                            <div class="text-left" style="font-size: 18px;">
                                <a href="#addRoute" data-toggle="tab"><i class="fa fa-plus-circle"></i> Add a Route</a>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto;">
                                <form action="<?= base_url()."routes/index/".$route_type; ?>" method="get">
                                    <table class="search-table" style="width:100%;">
                                        <tr>
                                            <td>
                                                <span style="color: darkgray; font-weight: bold;">ID:</span>
                                                <input class="form-control" style="height: 30px;" size="3" type="text" placeholder="id" value="<?php if(isset($_GET['id'])){echo $_GET['id'];} ?>" name="id"></td>
                                            <td>
                                                <span style="color: darkgray; font-weight: bold;">Source:</span>
                                                <select name="source" class="source_city_select" id="">
                                                    <?php
                                                    foreach($cities as $city){
                                                        $source = (isset($_GET['source']))?$_GET['source']:'';
                                                        $selected = ($source == $city->id)?'selected':'';
                                                        ?>
                                                        <option value="<?= $city->id; ?>" <?= $selected ?>><?= $city->cityName; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <option value="" <?= (($source == '')?'selected':'') ?>>All Of Them</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span style="color: darkgray; font-weight: bold;">Destination:</span>
                                                <select name="destination" class="destination_city_select" id="">
                                                    <?php
                                                    foreach($cities as $city){
                                                        $destination = (isset($_GET['destination']))?$_GET['destination']:'';
                                                        $selected = ($destination == $city->id)?'selected':'';
                                                        ?>
                                                        <option value="<?= $city->id; ?>" <?= $selected ?>><?= $city->cityName; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <option value="" <?= (($destination == '')?'selected':'') ?>>All Of Them</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span style="color: darkgray; font-weight: bold;">Product:</span>
                                                <select name="product" class="product_select" id="">
                                                    <?php
                                                    foreach($products as $product){
                                                        $p = (isset($_GET['product']))?$_GET['product']:'';
                                                        $selected = ($p == $product->id)?'selected':'';
                                                        ?>
                                                        <option value="<?= $product->id; ?>" <?= $selected ?>><?= $product->productName; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <option value="" <?= (($p == '')?'selected':'') ?>>All Of Them</option>
                                                </select>

                                            </td>
                                            <td>
                                                <span style="color: darkgray; font-weight: bold;">Freight:</span>
                                                <input class="form-control" style="height: 30px;" type="number" step="any" placeholder="search Freight.." value="<?php if(isset($_GET['freight'])){echo $_GET['freight'];} ?>" name="freight">
                                            </td>
                                            <td>
                                                <br>
                                                <button style="width: 100%; height: 30px;" class="btn-success">Search</button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                                <form name="selection_form" id="selection_form" method="post" action="<?php
                                if(strpos($this->helper_model->page_url(),'?') == false){
                                    echo $this->helper_model->page_url()."?";
                                }else{echo $this->helper_model->page_url()."&";}
                                ?>print">
                                <table class="table table-bordered table-hover table-striped" style="font-size:12px;">
                                    <thead style="border-top: 3px solid lightgray;">
                                    <tr>
                                        <th></th>
                                        <th><div><input id="" type="checkbox" name="column[]" value="id" style="" checked></div></th>
                                        <th><div><input id="" type="checkbox" name="column[]" value="source" style="" checked></div></th>
                                        <th><div><input id="" type="checkbox" name="column[]" value="destination" style="" checked></div></th>
                                        <th><div><input id="" type="checkbox" name="column[]" value="product" style="" checked></div></th>
                                        <th><div><input id="" type="checkbox" name="column[]" value="freight" style="" checked></div> </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
                                        <th><a href="<?php echo $this->helper_model->sorting_info('routes.id'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('routes.id', 'numeric'); ?>"> </i> ID</a></th>
                                        <th><a href="<?php echo $this->helper_model->sorting_info('sourceCity.cityName'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('sourceCity.cityName', 'string'); ?>"> </i> Source</a></th>
                                        <th><a href="<?php echo $this->helper_model->sorting_info('destinationCity.cityName'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('destinationCity.cityName', 'string'); ?>"> </i> Destination</a></th>
                                        <th><a href="<?php echo $this->helper_model->sorting_info('products.productName'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('product.productName', 'string'); ?>"> </i> Product</a></th>
                                        <th><a href="<?php echo $this->helper_model->sorting_info('freight'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('freight', 'numeric'); ?>"> </i> Freight</a></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach($routes as $route){
                                            ?>
                                            <tr>
                                                <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $route->id; ?>"></td>
                                                <td><?= $route->id; ?></td>
                                                <td><?= $route->source; ?></td>
                                                <td><?= $route->destination; ?></td>
                                                <td><?= $route->product; ?></td>
                                                <?php
                                                $color = ($route->is_freight_active == true)?"green":"rgba(0,0,0,0.3)";
                                                ?>
                                                <td style="color:<?= $color ?>; font-weight: bold;"><?= $route->freight; ?></td>
                                                <td>
                                                    <?php if($this->privilege_model->allow_removing() == true): ?>
                                                        <a href="<?= base_url()."routes/edit_route/".$route->id; ?>" class="edit_route_link" style="background-color: rgba(0,0,0,0); border: 0px; width: 100%; height: 100%; border-bottom: 1px solid lightgray;"><i class="fa fa-edit" style="color: #31b0d5"></i> edit</a>

                                                        <?php
                                                        $query_string = $this->helper_model->merge_query($_SERVER['QUERY_STRING'],array('del'=>$route->id));
                                                        $url = $this->helper_model->url_path()."?".$query_string;
                                                        ?>
                                                        <div><a href="<?= $url ?>" onclick="return confirm_deleting()"><i class="fa fa-minus-circle" style="color: red"></i> remove</a></div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                               </form>
                            </div>
                            <div class="col-lg-12 text-center">
                                <?php
                                echo $pages;
                                ?>
                            </div>
                        </div>

                        <div class="tab-pane fade in <?php if($section == 'add'){echo "active";} ?>" id="addRoute">
                            <div id="error" style="color: red;" class="col-lg-12">
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
                            </div>
                            <section style="" class="col-lg-7 center-block">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Add Route</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="list-group">

                                            <?php
                                            //opening the form
                                            $attributes = array('class' => 'form-horizontal', 'role' => 'form','onSubmit'=>'return check_routes()');
                                            echo form_open(base_url().'routes/index/'.$route_type, $attributes);
                                            ?>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Source</label>
                                                <div class="col-md-9">
                                                   <select name="source" style="width: 100%;" required="required" class="form-control source_city_select" id="source">
                                                       <?php
                                                            foreach($cities as $city){
                                                                $selected = '';
                                                                if($route_type == 'local'){
                                                                    $selected = (ucwords($city->id) == 2)?'selected':'';
                                                                }
                                                                ?>
                                                                <option <?= $selected ?> value="<?= $city->id; ?>"><?= $city->cityName; ?></option>
                                                                <?php
                                                            }
                                                       ?>
                                                   </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Destination</label>
                                                <div class="col-md-9">
                                                    <select style="width: 100%;" name="destination" required="required" class="form-control destination_city_select" id="destination">
                                                        <?php
                                                        foreach($cities as $city){
                                                            ?>
                                                            <option value="<?= $city->id; ?>"><?= $city->cityName; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Product</label>
                                                <div class="col-md-9">
                                                    <select name="product" required="required" style="width: 100%;" class="form-control product_select" id="product">
                                                        <?php
                                                        foreach($products as $product){
                                                            ?>
                                                            <option value="<?= $product->id; ?>"><?= $product->productName; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Freight</label>
                                                <div class="col-md-9">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'freight',
                                                        'class'=>'form-control',
                                                        'placeholder'=>'',
                                                        'maxlength'=>'12',
                                                        'required'=>'required',
                                                    );
                                                    echo form_input($data);
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">From</label>
                                                <div class="col-md-9">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'from',
                                                        'class'=>'form-control',
                                                        'placeholder'=>'',
                                                        'type'=>'date',
                                                        'required'=>'required',
                                                        'id'=>'add_from',
                                                    );
                                                    echo form_input($data);
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">To</label>
                                                <div class="col-md-9">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'to',
                                                        'class'=>'form-control',
                                                        'placeholder'=>'',
                                                        'type'=>'date',
                                                        'id'=>'add_to',
                                                        'required'=>'required',
                                                    );
                                                    echo form_input($data);
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Type</label>
                                                <div class="col-md-9">
                                                    <select name="route_type" class="form-control">
                                                        <?php if($route_type == 'primary'){ ?>
                                                            <option value="1">Primary</option>
                                                        <?php }else if($route_type == 'secondary'){ ?>
                                                            <option value="3">Secondary</option>
                                                        <?php }else if($route_type == 'secondary_local'){ ?>
                                                            <option value="4">Secondary Local</option>
                                                        <?php }; ?>
                                                     </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-9">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'addRoute',
                                                        'class'=>'btn btn-success center-block',
                                                        'value'=>'Add Route',
                                                    );
                                                    echo form_submit($data);
                                                    ?>
                                                </div>
                                            </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <section style="" class="col-lg-12 center-block">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Products</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="list-group">
                                                <?php
                                                //opening the form
                                                $attributes = array('class' => 'form-horizontal', 'role' => 'form');
                                                echo form_open(base_url().'routes/', $attributes);
                                                ?>

                                                <div class="col-md-6 form-group">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'productName',
                                                        'class'=>'form-control',
                                                        'placeholder'=>'product name..',
                                                        'maxlength'=>'100',
                                                        'required' => 'required',
                                                    );
                                                    echo form_input($data);
                                                    ?>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <select name="product_type" class="form-control">
                                                        <option value="black oil">Black Oil</option>
                                                        <option value="white oil">White Oil</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'addProduct',
                                                        'class'=>'btn btn-success center-block',
                                                        'value'=>'Add Product',
                                                    );
                                                    echo form_submit($data);
                                                    ?>
                                                </div>

                                                </form>

                                                <div class="col-lg-12">
                                                    <table class="table">
                                                        <?php
                                                        foreach($products as $product){
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="#" id="product_<?= $product->id ?>" data-name="productName" data-type="text" data-pk="<?= $product->id ?>" data-url="<?= base_url()."helper_controller/edit_record/products/required|is_unique[products.productName]" ?>" data-title="Product Name"><?= $product->productName ?></a>
                                                                </td>
                                                                <td>
                                                                    <?= $product->type ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($this->privilege_model->allow_removing() == true): ?>
                                                                        <?php
                                                                        $url = $this->helper_model->url_path()."?del_product=".$product->id;
                                                                        ?>
                                                                        <a href="<?= $url ?>" onclick="return confirm_deleting()"><i class="fa fa-minus-circle" style="color: red"></i> remove</a>
                                                                    <?php endif; ?>
                                                                </td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </section>
                            <section style="" class="col-lg-5 center-block">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Add Source & Destinations</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="list-group">
                                            <?php
                                            //opening the form
                                            $attributes = array('class' => 'form-horizontal', 'role' => 'form');
                                            echo form_open(base_url().'routes/', $attributes);
                                            ?>

                                                <div class="col-md-9 form-group">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'cityName',
                                                        'class'=>'form-control',
                                                        'placeholder'=>'city name..',
                                                        'maxlength'=>'100',
                                                        'required' => 'required',
                                                    );
                                                    echo form_input($data);
                                                    ?>
                                                </div>

                                                <div class="col-md-3 form-group">
                                                    <?php
                                                    $data = array(
                                                        'name' => 'addCity',
                                                        'class'=>'btn btn-success center-block',
                                                        'value'=>'Add City',
                                                    );
                                                    echo form_submit($data);
                                                    ?>
                                                </div>

                                            </form>

                                            <div class="col-lg-12">
                                                <table class="table">
                                                    <?php
                                                        foreach($cities as $city){
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="#" id="city_<?= $city->id ?>" data-name="cityName" data-type="text" data-pk="<?= $city->id ?>" data-url="<?= base_url()."helper_controller/edit_record/cities/required|is_unique[cities.cityName]" ?>" data-title="City Name"><?= $city->cityName ?></a>
                                                                </td>
                                                                <td>
                                                                <?php if($this->privilege_model->allow_removing() == true): ?>
                                                                    <?php
                                                                    $url = $this->helper_model->url_path()."?del_city=".$city->id;
                                                                    ?>
                                                                    <a href="<?= $url ?>" onclick="return confirm_deleting()"><i class="fa fa-minus-circle" style="color: red"></i> remove</a>
                                                                <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="col-lg-12">
                                <div class="text-left" style="font-size: 18px;">
                                    <a href="#routes" data-toggle="tab"><i class="fa fa-arrow-circle-left"> Back</i></a>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>
<script>
    $('.edit_route_link').magnificPopup({
        type: 'ajax',
        showCloseBtn:false
    });
</script>
<script>
    $.magnificPopup.instance._onFocusIn = function(e) {
        // Do nothing if target element is select2 input
        if( $(e.target).hasClass('select2-search__field') ) {
            return true;
        }
        // Else call parent method
        $.magnificPopup.proto._onFocusIn.call(this,e);
    };

    $(function(){
        <?php
        foreach($cities as $city){
        echo "$('#city_".$city->id."').editable({inputclass: 'form-control'});";
        }
        ?>
        <?php
        foreach($products as $product){
        echo "$('#product_".$product->id."').editable({inputclass: 'form-control'});";
        }
        ?>
    });

    $(".source_city_select").select2();
    $(".destination_city_select").select2();
    $(".product_select").select2();

</script>