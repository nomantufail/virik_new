<style>    .sortable-table-heading{        display: block;        width: 100%;        color: #0088cc;    }    .sortable-table-heading:hover{        color: #0088cc;        text-decoration: underline;    }</style><div id="page-wrapper">    <div class="container-fluid">        <div class="row">            <div class="col-lg-12">                <section class="col-md-6">                    <h1 class="page-header">                        Drivers <small></small>                    </h1>                </section>            </div>        </div>        <div class="row">            <div class="col-lg-12">                <div class="panel-body">                    <div id="myTabContent" class="tab-content" style="min-height: 500px;">                        <div class="tab-pane fade in <?php if($section == 'all'){echo "active";} ?>" id="drivers">                            <?php if(isset($_GET['del'])): ?>                                <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                            <strong>Error! </strong>', '</div>');                                ?>                                <?php if(is_array($someMessage)){ ?>                                    <div class="alert <?= $someMessage['type']; ?> alert-dismissible" role="alert">                                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                        <?= $someMessage['message']; ?>                                    </div>                                <?php } ?>                            <?php endif; ?>                            <div class="text-left" style="font-size: 16px;">                                <a href="#addDriver" data-toggle="tab"><i class="fa fa-plus-circle"></i> Add a Driver</a>                            </div>                            <div class="table-responsive">                                <form method="get" action="<?= base_url()."drivers/index?"?>">                                    <table class="search-table2" border="1" style="">                                        <tr>                                            <td style="width: 15%;"><input type="text" placeholder="driver" value="<?php if(isset($_GET['driver'])){echo $_GET['driver'];} ?>" name="driver"></td>                                            <td><button style="width: 100%;">Search</button></td>                                        </tr>                                    </table>                                </form>                                <form name="selection_form" id="selection_form" method="post" action="<?php                                if(strpos($this->helper_model->page_url(),'?') == false){                                    echo $this->helper_model->page_url()."?";                                }else{echo $this->helper_model->page_url()."&";}                                ?>print">                                <table class="table table-bordered table-hover table-striped">                                    <thead>                                    <tr>                                        <th></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="id" style="" checked></div></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="name" style="" checked></div></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="id_card" style="" checked></div></th>                                        <th></th>                                    </tr>                                    <tr>                                        <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>                                        <th><a href="<?php echo $this->helper_model->sorting_info('id'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('id', 'numeric'); ?>"> </i> ID</a></th>                                        <th><a href="<?php echo $this->helper_model->sorting_info('name'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('name', 'string'); ?>"> </i> Name</a></th>                                        <th><a href="<?php echo $this->helper_model->sorting_info('idCard'); ?>" class="sortable-table-heading"><i class="<?= $this->helper_model->sorting_icon('idCard', 'string'); ?>"> </i> ID Card#</a></th>                                        <th></th>                                    </tr>                                    </thead>                                    <tbody>                                    <?php                                    //Showing Customers Data                                    foreach($drivers as $driver){                                        ?>                                        <tr>                                            <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $driver->id; ?>"></td>                                            <td><?= $driver->id ?></td>                                            <td><a href="<?= base_url()."drivers/profile/".$driver->id; ?>"><?= ucwords($driver->name) ?></a></td>                                            <td><?= $driver->idCard; ?></td>                                            <td>                                            <?php if($this->privilege_model->allow_removing() == true): ?>                                                <?php                                                $query_string = $this->helper_model->merge_query($_SERVER['QUERY_STRING'],array('del'=>$driver->id));                                                $url = $this->helper_model->url_path()."?".$query_string;                                                ?>                                                <a href="<?= $url ?>" onclick="return confirm_deleting()"><i class="fa fa-minus-circle" style="color: red"></i> remove</a>                                            <?php endif; ?>                                            </td>                                        </tr>                                    <?php                                    }                                    ?>                                    </tbody>                                </table>                               </form>                            </div>                            <div class="col-lg-12 text-center">                                <?php                                echo $pages;                                ?>                            </div>                        </div>                        <div class="tab-pane fade in <?php if($section == 'add'){echo "active";} ?>" id="addDriver">                            <section style="" class="col-lg-12 center-block">                                <div class="panel panel-default">                                    <div class="panel-heading">                                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Add Customer</h3>                                    </div>                                    <div class="panel-body">                                        <div class="list-group">                                        <?php if(isset($_POST['addDriver'])): ?>                                            <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                            <strong>Error! </strong>', '</div>');                                            ?>                                            <?php if(is_array($someMessage)){ ?>                                                <div class="alert <?= $someMessage['type']; ?> alert-dismissible" role="alert">                                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                                    <?= $someMessage['message']; ?>                                                </div>                                            <?php } ?>                                        <?php endif; ?>                                            <?php                                            //opening the form                                            $attributes = array('class' => 'form-horizontal', 'role' => 'form');                                            echo form_open(base_url().'drivers/index/add', $attributes);                                            ?>                                            <div class="form-group">                                                <label class="col-md-2 control-label">Name</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'name',                                                        'class'=>'form-control',                                                        'value'=>set_value('name'),                                                        'placeholder'=>'customer name here...',                                                        'maxlength'=>'100',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label">Phone</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'phone',                                                        'class'=>'form-control',                                                        'value'=>set_value('email'),                                                        'placeholder'=>'customer phone here...',                                                        'maxlength'=>'15',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label">Email</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'email',                                                        'class'=>'form-control',                                                        'value'=>set_value('email'),                                                        'placeholder'=>'customer email here...',                                                        'maxlength'=>'100',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label">ID Card#</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'idCard',                                                        'class'=>'form-control',                                                        'value'=>set_value('idCard'),                                                        'placeholder'=>'customer ID Card# here...',                                                        'maxlength'=>'17',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label">Address</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'address',                                                        'class'=>'form-control',                                                        'value'=>set_value('address'),                                                        'placeholder'=>'customer address here...',                                                        'maxlength'=>'198',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label">Image</label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'image',                                                        'class'=>'form-control',                                                        'value'=>set_value('image'),                                                        'placeholder'=>'customer image here...',                                                        'maxlength'=>'198',                                                        'type'=>'file',                                                    );                                                    echo form_input($data);                                                    ?>                                                </div>                                            </div>                                            <div class="form-group">                                                <label class="col-md-2 control-label"></label>                                                <div class="col-md-8">                                                    <?php                                                    $data = array(                                                        'name' => 'addDriver',                                                        'class'=>'btn btn-success center-block',                                                        'value'=>'Add Driver',                                                    );                                                    echo form_submit($data);                                                    ?>                                                </div>                                            </div>                                            <?php                                            //closing form                                            form_close();                                            ?>                                        </div>                                    </div>                                </div>                                <br><br>                                <div class="text-left" style="font-size: 18px;">                                    <a href="#drivers" data-toggle="tab"><i class="fa fa-arrow-circle-left"> Back</i></a>                                </div>                            </section>                        </div>                    </div>                </div>            </div>        </div>    </div></div>