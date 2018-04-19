<div id="page-wrapper">
<div class="container-fluid">

<div class="row">
    <div class="col-lg-12">
        <section class="col-md-6">
            <h1 class="page-header">
                <?php echo ucwords($profile->name); ?> <small></small>
            </h1>
        </section>
        <section class="col-md-6">
            <ul id="myTab" class="nav nav-pills page-header">
                <li><a href="<?= base_url()."drivers/profile/".$profile->id;?>">Profile</a></li>
                <li class="active"><a href="<?= base_url()."drivers/accounts/".$profile->id;?>">Accounts</a></li>
                <li><a href="<?= base_url()."drivers/contact/".$profile->id;?>">Contact</a></li>
            </ul>
        </section>
    </div>
</div>
<section class="row">
    <section class="col-lg-12">
        <div class="panel-body">
            <div id="myTabContent" class="tab-content" style="min-height: 500px;">
                <div class="tab-pane fade in <?php if($section == 'all'){echo "active";} ?>" id="accounts">
                    <div class="text-left" style="font-size: 16px;">
                        <a href="#addExpense" data-toggle="tab"><i class="fa fa-plus-circle"></i> Add Expense</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                                <tr style="border-top: 2px solid #ffffff;">
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Expense</th>
                                    <th></th>
                                </tr>
                                <?php
                                //Showing Customers Data
                                foreach($accounts as $account){
                                    ?>
                                    <tr>
                                        <td><?= $account->expense_date ?></td>
                                        <td><?= $account->description ?></td>
                                        <td><?= $account->expense ?></td>
                                        <td><a href="#"><i class="fa fa-minus-circle" style="color: red"></i> remove</a> </td>
                                    </tr>
                                <?php
                                }
                                ?>

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade in <?php if($section == 'add'){echo "active";} ?>" id="addExpense">
                    <section style="" class="col-lg-12 center-block">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Add Expense</h3>
                            </div>
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

                                    <?php
                                    //opening the form
                                    $attributes = array('class' => 'form-horizontal', 'role' => 'form');
                                    echo form_open(base_url().'drivers/accounts/'.$profile->id.'/add', $attributes);
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Date</label>
                                        <div class="col-md-8">
                                            <?php
                                            $data = array(
                                                'name' => 'date',
                                                'class'=>'form-control',
                                                'data-date-format'=> 'yyyy-mm-dd',
                                                'data-provide'=> '',
                                                'value'=>$this->carbon->now(new DateTimeZone('Asia/Karachi'))->toDateString(),
                                                'placeholder'=>'please click here...',
                                                'maxlength'=>'100',
                                                'type' => 'date',
                                            );
                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Description</label>
                                        <div class="col-md-8">
                                            <?php
                                            $data = array(
                                                'name' => 'description',
                                                'class'=>'form-control',
                                                'value'=>set_value('description'),
                                                'placeholder'=>'',
                                                'maxlength'=>'100',
                                            );
                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Expense</label>
                                        <div class="col-md-8">
                                            <?php
                                            $data = array(
                                                'name' => 'expense',
                                                'class'=>'form-control',
                                                'value'=>set_value('expense'),
                                                'placeholder'=>'',
                                                'maxlength'=>'10',
                                            );
                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label"></label>
                                        <div class="col-md-8">
                                            <?php
                                            $data = array(
                                                'name' => 'addExpense',
                                                'class'=>'btn btn-success center-block',
                                                'value'=>'Add Expense',
                                            );
                                            echo form_submit($data);
                                            ?>
                                        </div>
                                    </div>

                                    <?php
                                    //closing form
                                    form_close();
                                    ?>
                                </div>
                            </div>
                        </div>

                        <br><br>
                        <div class="text-left" style="font-size: 18px;">
                            <a href="#accounts" data-toggle="tab"><i class="fa fa-arrow-circle-left"> Back</i></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</section>

</div>
</div>