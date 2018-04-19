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
    .commit-shortages-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 500px;
        margin: 20px auto;
    }
    .white-popup{
        position: relative;
        background-color: #FFF;
        padding: 20px;
        width: auto;
        max-width: 500px;
        margin: 20px auto;
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
<script>
    function set_multiple_shortage_ids_for_commit()
    {
        var check_boxes = document.getElementsByClassName("filter_check_box");
        var trip_details_ids = '0';
        for(var count = 0; count < check_boxes.length; count++){
            if(check_boxes[count].checked == true){
                trip_details_ids = trip_details_ids+"_"+check_boxes[count].value;
            }
        }
        alert(trip_details_ids);

        document.getElementById("shortage_ids_for_commiting").value = trip_details_ids;
        if(document.getElementById("shortage_ids_for_commiting").value != '')
        {
            return true;
        }
        return false;
    }
    function set_shortage_id_for_commit(id)
    {
        document.getElementById("shortage_ids_for_commiting").value = id;
    }
</script>

<!--this area is hedden from the view and is used for commiting-->
<div id="commit-shortages-popup" class="commit-shortages-popup mfp-hide">
    <script>
        function fetch_agents(agent, callback){
            var agents_area = document.getElementById("black_oil_agents_area");
            $.get("<?=base_url()."accounts/fetch_agents_for_shortage_chit/"?>"+agent+"/black_oil_",function(data,status){
                agents_area.innerHTML= data;
                callback();
            });
        }

        function agent_type_changed()
        {
            var agent_type_selected_index = document.getElementById("black_oil_agent_type").selectedIndex;
            var agent_type = document.getElementById("black_oil_agent_type").options[agent_type_selected_index].value;
            if(agent_type != ''){
                document.getElementById('black_oil_agents_area').innerHTML = '<span style="font-style: italic; color: gray;">Loading...</span>';
                fetch_agents(agent_type,function(){

                });
            }
        }
    </script>
    <style></style>
    <script>
    </script>
    <div><h3 style="text-align: center;">Commit Shortages</h3></div><hr>
    <form action="" method="post">
        <table class="table">
            <tr>
                <th style="width: 100px;">Date: </th>
                <td><input type="date" name="commit_date" value="<?= date('Y-m-d') ?>" required="required" class="form-control"></td>
            </tr>
            <tr>
                <th>Credit Agent:</th>
                <td>
                    <select class="form-control" id="black_oil_agent_type" onchange="agent_type_changed()" name="agent_type">
                        <option value="">None</option>
                        <option value="other_agents">Other Agent</option>
                        <option value="customers">Customer</option>
                        <option value="carriage_contractors">Contractor</option>>
                        <option value="companies">Company</option>
                    </select>
                    <div id="black_oil_agents_area">

                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="shortage_ids" id="shortage_ids_for_commiting">
        <hr>
        <input type="submit" name="commit_shortages" value="Commit" class="center-block btn btn-success">
    </form>
</div>
<!--***********************************************************************-->

<?php include_once(APPPATH."views/shortages/components/custom_shortages_search_widget.php"); ?>

<div id="page-wrapper" class="whole_page_container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php include_once(APPPATH."views/shortages/components/nav_bar.php"); ?>
            </div>
        </div>
        <div class="row">

            <?php
            echo $this->helper_model->display_flash_errors();
            echo $this->helper_model->display_flash_success();
            ?>

            <div class="table-responsive">
                <div>
                    <a href="#custom-search-popup" class="open-custom-search-popup btn btn-success"  style="float: right;" ><i class="fa fa-search"></i> Search</a>
                </div>
                <form name="selection_form" id="selection_form" method="post" action="<?php
                if(strpos($this->helper_model->page_url(),'?') == false){
                    echo $this->helper_model->page_url()."?";
                }else{echo $this->helper_model->page_url()."&";}
                ?>print">
                    <table class="table table-bordered table-hover table-striped" style="font-size: 11px;">

                        <thead>
                        <tr>
                            <th></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="shortage_id" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="trip_id" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="entry_date" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="source" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="destiation" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="product" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="shrt_date" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="shrt_qty" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="shrt_rate" checked></div></th>
                            <th><div><input id="" type="checkbox" name="column[]" style="" value="shrt_amount" checked></div></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
                            <?= Sort::createSortableHeader("shortages"); ?>
                            <th><i class="fa fa-gear"></i> Actions</th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php

                        //Showing Customers Data

                        foreach($shortages as $shortage){

                            ?>

                            <tr>
                                <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $shortage->shortage_id; ?>"></td>
                                <td><?= $shortage->shortage_id; ?></td>
                                <td><?= $shortage->trip_id; ?></td>
                                <td><?= Carbon::createFromFormat('Y-m-d',$shortage->trip_entry_date)->toFormattedDateString(); ?></td>
                                <td><?= $shortage->source; ?></td>
                                <td style="width: 5%"><?= $shortage->destination; ?></td>
                                <td><?= $shortage->product_name; ?></td>
                                <td><?= Carbon::createFromFormat('Y-m-d',$shortage->date)->toFormattedDateString();; ?></td>
                                <td><?= $shortage->quantity; ?></td>
                                <td><?= $shortage->rate; ?></td>
                                <td><?= rupee_format($shortage->shortage_amount); ?></td>
                                <td>
                                    <a href="<?= base_url()."shortages/edit/".$shortage->shortage_id ?>" class="edit_shortage btn btn-xs btn-warning">Edit</a>
                                    <a class="btn btn-xs btn-danger" onclick="return confirm_deleting()" href="<?= url_path()."?".merge_query($_SERVER['QUERY_STRING'], array('del'=>$shortage->shortage_id)) ?>">Del</a>
                                    <?php if($shortage->committed == false): ?>
                                        <a  href="#commit-shortages-popup" onclick="set_shortage_id_for_commit(<?= $shortage->shortage_id ?>)" class="open-commit-shortages-popup btn btn-xs btn-primary"> Commit</a>
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
        </div>
    </div>

    <script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>
    <script>
        $('.open-commit-shortages-popup').magnificPopup({
            type: 'inline',
            showCloseBtn:true
        });

        $(document).ajaxComplete(function(){
            $(".agent_select").select2();
        });

        $('.edit_shortage').magnificPopup({
            type: 'ajax',
            showCloseBtn:false
        });
    </script>

    <script>
        $('.open-custom-search-popup').magnificPopup({
            type: 'inline',
            showCloseBtn:false
        });
    </script>
</div>
