
<!--this area is hedden from the view and is used for searching-->
<div id="custom-search-popup" class="custom-search-popup mfp-hide">

   <style>
        .custom_accounts_inputs select{
            height: 30px;
            width: 100%;
            font-size:12px;
        }
        .custom_accounts_inputs input{
            height: 30px;
            width: 100%;
            font-size: ;
        }
        .custom_accounts_inputs .lable{
            color: gray;
            font-weight: bold;
        }
        .custom_accounts_inputs fieldset{
            margin-top: 10px;
        }
       .custom_accounts_inputs table tr{
           border-top: 2px solid white;
       }
    </style>
    <script>

    </script>
    <?php
    $current_page = 'primary';
    if($page == 'secondary'){
        $current_page = 'secondary';
    }else if($page == 'secondary_local'){
        $current_page = 'secondary_local';
    }

    ?>
    <form action="<?= base_url()."trips/show/".$current_page; ?>" method="get">
        <div class="row custom_accounts_inputs">
            <div class="col-lg-12 center-block">
                <fieldset style="">
                    <legend>Duration:</legend>
                    <div class="col-md-6">
                        <label class="lable">From:</label> <input style="width: 100%;" type="date" class="form-control" placeholder="" value="<?php if(isset($_GET['from'])){echo $_GET['from'];} ?>" name="from">
                    </div>
                    <div class="col-md-6">
                        <label class="lable">To:</label>  <input type="date" placeholder="" class="form-control" value="<?php if(isset($_GET['to'])){echo $_GET['to'];} ?>" name="to">
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Trip Info:</legend>
                    <table class="table">
                        <tr>
                            <td>
                                <label class="lable">Trip ID:</label>  <input type="number" class="form-control" min="1" placeholder="Trip Id" value="<?php if(isset($_GET['id'])){echo $_GET['id'];} ?>" name="id">
                            </td>
                            <td>
                                <label class="lable">STN:</label>
                                <?php
                                $stn_number = (isset($_GET['stn']))?$_GET['stn']:'';
                                ?>
                                <input type="text" class="form-control" value="<?= $stn_number ?>" name="stn">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="lable" style="margin-top: 10px;">Trip Type:</label>
                                <?php
                                $trip_type = (isset($_GET['trip_type']))?$_GET['trip_type']:'';
                                ?>
                                <select name="trip_type[]" class="trip_type form-control trip_type_select" multiple style="height: 30px">
                                    <option value=""  >All</option>
                                    <?php if($trip_master_type == 'primary'): ?>
                                        <option value="1"  >Self/Mail</option>
                                        <option value="2"  >General</option>
                                        <option value="4"  >Local Self</option>
                                        <option value="5"  >General Local</option>
                                    <?php else: ?>
                                        <option value="3"  >Local Company</option>
                                    <?php endif; ?>

                                </select>
                            </td>
                            <td>
                                <label class="lable" style="margin-top: 10px;">Trip Status:</label>
                                <?php
                                $trip_status = (isset($_GET['trip_status']))?$_GET['trip_status']:'';
                                ?>
                                <select name="trip_status" class="form-control" style="height: 30px">
                                    <option value="" <?= (($trip_status == '')?'selected':'') ?> >All</option>
                                    <option value="2" <?= (($trip_status == '2')?'selected':'') ?> >Closed</option>
                                    <option value="1" <?= (($trip_status == '1')?'selected':'') ?> >Open</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="lable" style="margin-top: 10px;">Trip Date:</label>  <input type="date" value="<?php if(isset($_GET['entry_date'])){echo $_GET['entry_date'];} ?>" name="entry_date">
                            </td>
                            <td>
                                <label class="lable" style="margin-top: 10px;">Product:</label>
                                <?php
                                $selected_product = (isset($_GET['product']))?$_GET['product']:'';
                                ?>
                                <select name="product[]" class="form-control product_select" style="width: 100%;" multiple>
                                    <option value="">All Of Them</option>
                                    <?php foreach($products as $product): ?>
                                        <?php
                                        $selected = ($selected_product == $product->id)?'selected':'';
                                        ?>
                                        <option value="<?= $product->id ?>" <?= $selected ?>><?= $product->productName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Source:</label>
                                <?php
                                $selected_source = (isset($_GET['source']))?$_GET['source']:'';
                                ?>
                                <select name="source[]" multiple class="source_city_select form-control">
                                    <option value="">All Of Them</option>
                                    <?php foreach($cities as $city): ?>
                                        <?php
                                        $selected = ($selected_source == $city->id)?'selected':'';
                                        ?>
                                        <option value="<?= $city->id ?>" <?= $selected ?>><?= $city->cityName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Destination:</label>
                                <?php
                                $selected_destination = (isset($_GET['destination']))?$_GET['destination']:'';
                                ?>
                                <select name="destination[]" multiple class="destination_city_select form-control">
                                    <option value="">All Of Them</option>
                                    <?php foreach($cities as $city): ?>
                                        <?php
                                        $selected = ($selected_destination == $city->id)?'selected':'';
                                        ?>
                                        <option value="<?= $city->id ?>" <?= $selected ?>><?= $city->cityName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label class="lable" style="margin-top: 0px;">Search By Route</label>
                                <?php
                                $selected_source = (isset($_GET['trips_route']))?$_GET['trips_route']:'';
                                ?>
                                <select name="trips_route[]" multiple class="source_city_select form-control">
                                    <option value="">All Of Them</option>
                                    <?php foreach($trips_routes as $trips_route): ?>
                                        <?php
                                        $selected = ($selected_source == $trips_route->source_id."_".$trips_route->destination_id)?'selected':'';
                                        ?>
                                        <option value="<?= $trips_route->source_id."_".$trips_route->destination_id ?>" <?= $selected ?>><?= $trips_route->source_city." To ".$trips_route->destination_city ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Tanker:</label>
                                <?php
                                $selected_tanker = (isset($_GET['tanker']))?$_GET['tanker']:'';
                                ?>
                                <select name="tanker[]" class="tankers_select form-control" multiple>
                                    <option value="">All Of Them</option>
                                    <?php foreach($tankers as $tanker): ?>
                                        <?php
                                        $selected = ($selected_tanker == $tanker->id)?'selected':'';
                                        ?>
                                        <option value="<?= $tanker->id ?>" <?= $selected ?>><?= $tanker->truck_number ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Company:</label>
                                <?php
                                $selected_company = (isset($_GET['company']))?$_GET['company']:'';
                                ?>
                                <select name="company[]" class="companies_select form-control" multiple>
                                    <option value="">All Of Them</option>
                                    <?php foreach($companies as $company): ?>
                                        <?php
                                        $selected = ($selected_company == $company->id)?'selected':(($company->id == 1)?'selected':'');
                                        ?>
                                        <option value="<?= $company->id ?>" <?= $selected ?>><?= $company->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Contractor:</label>
                                <?php
                                $selected_contractor = (isset($_GET['contractor']))?$_GET['contractor']:'';
                                ?>
                                <select name="contractor[]" class="contractors_select form-control" multiple>
                                    <option value="">All Of Them</option>
                                    <?php foreach($contractors as $contractor): ?>
                                        <?php
                                        $selected = ($selected_contractor == $contractor->id)?'selected':(($contractor->id == 1)?'selected':'');
                                        ?>
                                        <option value="<?= $contractor->id ?>" <?= $selected ?>><?= $contractor->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <label class="lable" style="margin-top: 0px;">Customer:</label>
                                <?php
                                $selected_customer = (isset($_GET['customer']))?$_GET['customer']:'';
                                ?>
                                <select name="customer[]" class="customers_select form-control" multiple>
                                    <option value="">All Of Them</option>
                                    <?php foreach($customers as $customer): ?>
                                        <?php
                                        $selected = ($selected_customer == $customer->id)?'selected':'';
                                        ?>
                                        <option value="<?= $customer->id ?>" <?= $selected ?>><?= $customer->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <hr>
                <div class="col-sm-12 form-group" style="margin-top: 5px;">
                    <div class="col-md-4" style="font-weight: bold;">
                        <?php $pagination =(isset($_GET['pagination']))?$_GET['pagination']:'' ?>
                        <label for="pagination"><input type="checkbox" style="width: 15px; height: 13px; margin-top: 5px;" value="false" <?= (($pagination == 'false')?'checked':'') ?> name="pagination" id="pagination"> No Pagination</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="submit" name="search" style="width: 100%; font-weight: bold; height: 30px;" value="Search!">
                    </div>
                </div>
                <div id="test_div"></div>
            </div>
        </div>
    </form>

</div>
<!--***********************************************************************-->