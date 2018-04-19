<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/20/15
 * Time: 11:19 PM
 */
?>
<script>

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
                    <h3>Vehicle Position Report</h3>
                </div>
                <form class="form-horizontal" role="form" action="<?= base_url()."reports/vehicle_position_report/generate/" ?>" target="_blank" method="get">

                    <div class="form-group">
                        <label class="col-md-4 control-label">From</label>
                        <div class="col-sm-8">
                            <input type="date" name="from" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">To</label>
                        <div class="col-sm-8">
                            <input type="date" name="to" style="width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Trip Type</label>
                        <div class="col-sm-8">
                            <select multiple class="form-control trip_types_select" style="" required="required" id="trip_types" name="trip_types[]">
                                <option value="all" selected> -- All of them -- </option>
                                <?php
                                foreach($trip_types as $type){
                                    echo "<option value=$type->id >$type->trip_sub_type</option>";
                                }
                                ?>
                            </select>
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
    $(".trip_types_select").select2();
</script>