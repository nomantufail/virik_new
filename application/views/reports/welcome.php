<script>
    function fetch_tankers(customer_id,width, area) {
        var tankers_area = document.getElementById("tanker_area");
        tankers_area.innerHTML = "please wait...";

        if (customer_id=="") {
            document.getElementById("txtHint").innerHTML="";
            return;
        }

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var response =xmlhttp.responseText;
                tankers_area.innerHTML = response;

            }
        }
        xmlhttp.open("GET","<?=base_url()."trips/fetch_tankers/"?>"+customer_id+"/"+width+"/"+area,true);
        xmlhttp.send();
    }

    function customer_changed(dropdown){
        var myindex  = dropdown.selectedIndex;
        var customer_id = dropdown.options[myindex].value;
        fetch_tankers(customer_id,'full','reports');
        return true
    }
</script>
<div id="page-wrapper" >
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-6">
                    <h3 class="">
                        Reports <small></small>
                    </h3>
                </section>
            </div>
        </div>

        <div class="row">
            <?php
                include_once(APPPATH."views/reports/components/nav_bar.php");
            ?>
        </div>

        <div id="login-form" class="col-lg-12" style="min-height: 600px;">
            <div class="loginBox center-block" style="width: 400px; height: 300px; background-color: ; color: ">
                    <div class="panel-body">
                        <div class="list-group">

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
                            <div class="col-md-12 visible-lg visible-md" style="height: 70px;"></div>
                            <form class="form-horizontal" role="form" action="<?= base_url()."reports/generate_customer_reports" ?>" target="_blank" method="get">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Contractor</label>
                                    <div class="col-sm-8">
                                        <select class="form-control contractors_select" style="" required="required" id="contractors" name="contractors">

                                            <?php
                                            foreach($contractors as $contractor){
                                                echo "<option value=$contractor->id >$contractor->name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Customer</label>
                                    <div class="col-sm-8">
                                        <select class="form-control customers_select" style="" id="customers" name="customers" onchange="customer_changed(this.form.customers);" required="required">
                                            <?php
                                            foreach($customers as $customer){
                                                echo "<option value=$customer->id >$customer->name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Tankers</label>
                                    <div class="col-sm-8" id="tanker_area">
                                        <select class="form-control tankers_select" style="" required="required" id="tankers" name="tankers">

                                            <?php
                                            echo '<option value="all">';
                                            echo "--All--";
                                            echo '</option>';
                                            foreach($tankers as $tanker){
                                                echo "<option value=$tanker->id >$tanker->truck_number</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">From</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" required="required" type="date" name='from_date'>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">To</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="date" placeholder="" name='to_date' required="required">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-sm-8">
                                        <input class="form-control btn-success" type="submit" value="Generate Reports!" name="loginUser">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>

    </div>
</div>
<script>
    $(".contractors_select").select2();
    $(".customers_select").select2();
    $(".tankers_select").select2();
</script>