<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/20/15
 * Time: 11:19 PM
 */
?>
<script>

    function fetch_agents(agent, callback){
        var agents_area = document.getElementById("agents_area");
        $.get("<?=base_url()."accounts/fetch_agents/"?>"+agent,function(data,status){
            agents_area.innerHTML= data;
            callback();
        });
    }

    function agent_type_changed()
    {
        var agent_type_selected_index = document.getElementById("agent_type").selectedIndex;
        var agent_type = document.getElementById("agent_type").options[agent_type_selected_index].value;
        if(agent_type != ''){
            document.getElementById('agents_area').innerHTML = '<span style="font-style: italic; color: gray;">Loading...</span>';
            fetch_agents(agent_type,function(){

            });
        }else{
            document.getElementById('agents_area').innerHTML = '';
        }
    }

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
        if(customer_id != 'all'){
            fetch_tankers(customer_id,'full','reports');
        }

        return true

    }

</script>

<div id="page-wrapper" >

<div class="container-fluid">



<div class="row">
    <?php
    include_once(APPPATH."views/reports/components/heading.php");
    ?>
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

                <div class="col-md-12" style="height: 70px; text-align: center; color: #2a6496;">
                    <h3>Shortage Report</h3>
                </div>
                <form class="form-horizontal" role="form" action="<?= base_url()."reports/shortage_report/generate/" ?>" target="_blank" method="get">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Company</label>
                        <div class="col-sm-8">
                            <select class="form-control companies_select" style="" required="required" id="companies" name="companies">
                                <option value="all"> -- All of them -- </option>
                                <?php
                                foreach($companies as $company){
                                    echo "<option value=$company->id >$company->name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Contractor</label>
                        <div class="col-sm-8">
                            <select class="form-control contractors_select" style="" required="required" id="contractors" name="contractors">
                                <option value="all"> -- All of them -- </option>
                                <?php
                                foreach($contractors as $contractor){
                                    echo "<option value=$contractor->id >$contractor->name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Products</label>
                        <div class="col-sm-8">
                            <select class="form-control select_box products_select" style="" required="required" id="contractors" name="product" >

                                <option value="all">All Of Them</option>
                                <?php
                                foreach($products as $product){
                                    echo "<option value=$product->id >$product->productName</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Product Type</label>
                        <div class="col-sm-8">
                            <select class="form-control select_box" style="" required="required" id="products" name="product_type" >

                                <option value="all">All Of Them</option>
                                <option value="black oil">Black Oil</option>
                                <option value="white oil">White Oil</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Agent Type</label>
                        <div class="col-sm-8">
                            <select class="form-control select_box" style="" required="required" id="agent_type" name="agent_type" onchange="agent_type_changed()">

                                <option value="all">All Of Them</option>
                                <option value="other_agents">Other Agent</option>
                                <option value="customers">Customer</option>
                                <option value="carriage_contractors">Contractor</option>
                                <option value="companies">Company</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Agent</label>
                        <div class="col-sm-8" id="agents_area">

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
    $(".companies_select").select2();
    $(".contractors_select").select2();

    $(document).ajaxComplete(function(){
        $(".agent_select").select2();
    });

</script>