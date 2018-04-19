
<!--this area is hedden from the view and is used for searching-->
<div id="custom-accounts-popup" class="custom-accounts-popup mfp-hide">
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
</style>
<script>

</script>
<form action="<?= base_url()."manageaccounts/".(($this->uri->segment(2) == 'white_oil')?'white_oil':'black_oil'); ?>" method="get">
<div class="row custom_accounts_inputs">
<div class="col-lg-12 center-block">
<fieldset style="">
    <legend>Duration:</legend>
    <div class="col-md-6">
        <label class="lable">From:</label> <input style="width: 100%;" type="date" placeholder="" value="<?php if(isset($_GET['from'])){echo $_GET['from'];} ?>" name="from">
    </div>
    <div class="col-md-6">
        <label class="lable">To:</label>  <input type="date" placeholder="" value="<?php if(isset($_GET['to'])){echo $_GET['to'];} ?>" name="to">
    </div>
</fieldset>

<fieldset>
    <legend>Trip Info:</legend>
    <div class="col-md-6">
        <label class="lable">Trip ID:</label>  <input type="number" min="1" placeholder="Trip Id" value="<?php if(isset($_GET['trip_id'])){echo $_GET['trip_id'];} ?>" name="trip_id">
    </div>
    <div class="col-md-6">
        <label class="lable">Trip Type:</label>
        <?php
        $trip_type = '';
        ?>
        <select class="product_select" name="trip_master_types[]" style="height: 30px" multiple>
            <option value=""  >All</option>
            <option value="primary"  >Primary</option>
            <option value="secondary"  >Secondary</option>
            <option value="secondary_local"  >Secondary Local</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="lable">Trip Sub Type:</label>
        <?php
        $trip_type = '';
        ?>
        <select class="product_select" name="trip_type[]" style="height: 30px" multiple>
            <option value=""  >All</option>
            <option value="1"  >Self / Mail</option>
            <option value="2"  >General Trip</option>
            <option value="3"  >Local Company</option>
            <option value="4"  >Local Self</option>
            <option value="5"  >General Local</option>
            <option value="6"  >Secondary Local</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Trip Date:</label>  <input type="date" value="<?php if(isset($_GET['entry_date'])){echo $_GET['entry_date'];} ?>" name="entry_date">
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Trip Status:</label>
        <?php
        $trip_status = (isset($_GET['trip_status']))?$_GET['trip_status']:'';
        ?>
        <select name="trip_status" style="height: 30px">
            <option value="" <?= (($trip_status == '')?'selected':'') ?> >All</option>
            <option value="2" <?= (($trip_status == '2')?'selected':'') ?> >Closed</option>
            <option value="1" <?= (($trip_status == '1')?'selected':'') ?> >Open</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Product:</label>
        <?php
        $selected_product = (isset($_GET['product']))?$_GET['product']:'';
        ?>
        <select name="product[]" multiple class="product_select">
            <option value="">All Of Them</option>
            <?php foreach($products as $product): ?>
                <?php
                $selected = ($selected_product == $product->id)?'selected':'';
                ?>
                <option value="<?= $product->id ?>" <?= $selected ?>><?= $product->productName ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Source:</label>
        <?php
        $selected_source = (isset($_GET['source']))?$_GET['source']:'';
        ?>
        <select name="source[]" multiple class="source_city_select">
            <option value="">All Of Them</option>
            <?php foreach($cities as $city): ?>
                <?php
                $selected = ($selected_source == $city->id)?'selected':'';
                ?>
                <option value="<?= $city->id ?>" <?= $selected ?>><?= $city->cityName ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Destination:</label>
        <?php
        $selected_destination = (isset($_GET['destination']))?$_GET['destination']:'';
        ?>
        <select name="destination[]" multiple class="destination_city_select">
            <option value="">All Of Them</option>
            <?php foreach($cities as $city): ?>
                <?php
                $selected = ($selected_destination == $city->id)?'selected':'';
                ?>
                <option value="<?= $city->id ?>" <?= $selected ?>><?= $city->cityName ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-lg-12">
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
    </div>
    <div class="col-md-6">
        <label class="lable" style="margin-top: 10px;">Tanker:</label>
        <?php
        $selected_tanker = (isset($_GET['tanker']))?$_GET['tanker']:'';
        ?>
        <select name="tanker[]" multiple class="tankers_select">
            <option value="">All Of Them</option>
            <?php foreach($tankers as $tanker): ?>
                <?php
                $selected = ($selected_tanker == $tanker->id)?'selected':'';
                ?>
                <option value="<?= $tanker->id ?>" <?= $selected ?>><?= $tanker->truck_number ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</fieldset>

<div id="advance_options">

    <fieldset>
        <legend>Company Accounts Info:</legend>
        <div class="col-md-6">
            <label class="lable">Name:</label>
            <?php
            $selected_company = (isset($_GET['company']))?$_GET['company']:'';
            ?>
            <select name="company[]" multiple class="companies_select">
                <option value="">All Of Them</option>
                <?php foreach($companies as $company): ?>
                    <?php
                    $selected = ($selected_company == $company->id)?'selected':(($company->id == 1)?'selected':'');
                    ?>
                    <option value="<?= $company->id ?>" <?= $selected ?>><?= $company->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="lable">Freight/Unit:</label> <input type="number" min="0" step="any" placeholder="company freight unit.." value="<?php if(isset($_GET['company_freight_unit'])){echo $_GET['company_freight_unit'];} ?>" name="company_freight_unit">
        </div>
        <div class="col-md-6">
            <label class="lable" style="margin-top: 10px;">W.H.T:</label> <input type="number" min="0" step="any" placeholder="withholding tax.." value="<?php if(isset($_GET['wht'])){echo $_GET['wht'];} ?>" name="wht">
        </div>
        <div class="col-md-6">
            <label class="lable" style="margin-top: 10px;">Commission:</label> <input type="number" min="0" step="any" placeholder="company commission.." value="<?php if(isset($_GET['company_commission'])){echo $_GET['company_commission'];} ?>" name="company_commission">
        </div>

    </fieldset>

    <fieldset>
        <legend>Contractor Info:</legend>
        <div class="col-md-6">
            <label class="lable">Name:</label>
            <?php
            $selected_contractor = (isset($_GET['contractor']))?$_GET['contractor']:'';
            ?>
            <select name="contractor[]" multiple class="contractors_select">
                <option value="">All Of Them</option>
                <?php foreach($contractors as $contractor): ?>
                    <?php
                    $selected = ($selected_contractor == $contractor->id)?'selected':(($contractor->id == 1)?'selected':'');
                    ?>
                    <option value="<?= $contractor->id ?>" <?= $selected ?>><?= $contractor->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="lable">Commission:</label> <input type="number" min="0" step="any" placeholder="contractor commission.." value="<?php if(isset($_GET['contractor_commission'])){echo $_GET['contractor_commission'];} ?>" name="contractor_commission">
        </div>

    </fieldset>

    <fieldset>
        <legend>Customer Info:</legend>
        <div class="col-md-6">
            <label class="lable">Name:</label>
            <?php
            $selected_customer = (isset($_GET['customer']))?$_GET['customer']:'';
            ?>
            <select name="customer[]" multiple class="customers_select">
                <option value="">All Of Them</option>
                <?php foreach($customers as $customer): ?>
                    <?php
                    $selected = ($selected_customer == $customer->id)?'selected':'';
                    ?>
                    <option value="<?= $customer->id ?>" <?= $selected ?>><?= $customer->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="lable">Freight/Unit: </label> <input type="number" min="0" step="any" placeholder="customer freight/unit.." value="<?php if(isset($_GET['cst_freight_unit'])){echo $_GET['cst_freight_unit'];} ?>" name="cst_freight_unit">
        </div>
    </fieldset>

    <fieldset>
        <legend>Search By Account Titles:</legend>
        <div class="col-md-6">
            <label class="lable">Account:</label>
            <?php
            $selected_title = (isset($_GET['account_title']))?$_GET['account_title']:'';
            ?>
            <select name="account_title" class="titles_select">
                <option value="">All Of Them</option>
                <?php foreach($account_titles as $title): ?>
                    <?php
                    $selected = ($selected_title == $title->id)?'selected':'';
                    ?>
                    <option value="<?= $title->id ?>" <?= $selected ?>><?= $title->title ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="lable" style="">Dr / Cr:</label>
            <?php
            $selected_dr_cr = (isset($_GET['dr_cr']))?$_GET['dr_cr']:'';
            ?>
            <select name="dr_cr">
                <option value="" <?= (($selected_dr_cr == '')?'selected':'') ?>>Any</option>
                <option value="1" <?= (($selected_dr_cr == '1')?'selected':'') ?>>Debit</option>
                <option value="2" <?= (($selected_dr_cr == '2')?'selected':'') ?>>Not Debit</option>
                <option value="0" <?= (($selected_dr_cr == '0')?'selected':'') ?>>Credit</option>
                <option value="3" <?= (($selected_dr_cr == '3')?'selected':'') ?>>Not Credit</option>
            </select>
        </div>
    </fieldset>
    <fieldset>
        <legend>Search By Trips Billing:</legend>
        <div class="col-md-6">
            <label class="lable">From:</label> <input style="width: 100%;" type="date" placeholder="" value="<?php if(isset($_GET['billed_from'])){echo $_GET['billed_from'];} ?>" name="billed_from">
        </div>
        <div class="col-md-6">
            <label class="lable">To:</label>  <input type="date" placeholder="" value="<?php if(isset($_GET['billed_to'])){echo $_GET['billed_to'];} ?>" name="billed_to">
        </div>
        <div class="col-md-6">
            <label class="lable" style="margin-top: 10px;">Bill Status:</label>
            <?php
            $selected_bill_status = (isset($_GET['bill_status']))?$_GET['bill_status']:'';
            ?>
            <select name="bill_status">
                <option value="" <?= (($selected_bill_status == '')?'selected':'') ?>>All</option>
                <option value="1" <?= (($selected_bill_status == '1')?'selected':'') ?>>Billed</option>
                <option value="0" <?= (($selected_bill_status == '0')?'selected':'') ?>>Not Billed</option>
            </select>
        </div>
    </fieldset>
</div>
<hr>
<div class="col-sm-12 form-group" style="margin-top: 5px;">
    <div class="col-md-6" style="font-weight: bold;">
        <?php $pagination =(isset($_GET['pagination']))?$_GET['pagination']:'' ?>
        <label for="pagination"><input type="checkbox" style="width: 15px; height: 13px; margin-top: 5px;" value="false" <?= (($pagination == 'false')?'checked':'') ?> name="pagination" id="pagination"> No Pagination</label>
    </div>
    <div class="col-lg-4">
        <input type="submit" name="generate" style="width: 100%; font-weight: bold; height: 30px;" value="Generate!">
    </div>
</div>
<div id="test_div"></div>
</div>
</div>
</form>
</div>
<!--***********************************************************************-->