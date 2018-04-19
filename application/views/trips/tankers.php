<div id="page-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="active">
                        <i class="fa fa-dashboard"></i> Customers
                    </li>
                </ol>
                <section class="col-md-6">
                    <h1 class="page-header">
                        <?= $data->name; ?> <small></small>
                    </h1>
                </section>
                <section class="col-md-6">
                    <ul id="myTab" class="nav nav-pills page-header">
                        <li><a href="<?= base_url()."customers/show/profile/".$data->id;?>">Profile</a></li>
                        <li class="active"><a href="<?= base_url()."customers/show/tankers/".$data->id;?>">Tankers</a></li>
                        <li><a href="<?= base_url()."customers/show/accounts/".$data->id;?>">Accounts</a></li>
                        <li><a href="<?= base_url()."customers/show/contact/".$data->id;?>">Contact</a></li>
                    </ul>
                </section>
            </div>
        </div>
        <section>
            <div id="myTabContent" class="tab-content" style="min-height: 500px;">
                <div class="tab-pane fade" id="trucks">
                    <br><br>
                    <div class="panel-body">
                        <div class="text-left" style="font-size: 18px;">
                            <a href="#addTruck" data-toggle="tab"><i class="fa fa-plus-circle"> Add a tanker </i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>Truck #</th>
                                    <th>Engine #</th>
                                    <th>Chase #</th>
                                    <th>Fitness Certificate</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //Showing Customers Data
                                foreach($customers as $customer){
                                ?>
                                    <tr>
                                        <td><?= $data->truck_number ?></td>
                                        <td><?= $data->engine_number ?></td>
                                        <td><?= $data->chase_number ?></td>
                                        <td><?= $data->fitness_certificate ?></td>
                                        <td><a href="#"><i class="fa fa-minus-circle" style="color: red"></i> remove</a> </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="addTruck">
                    <br><br>
                    <section style="width: 500px; background-color: ; font-size: 16px;" class="center-block">
                        Here you can add a new truck for this customer...
                        But this area is under Construction.<br><br>
                        <div class="text-left">
                            <a href="#trucks" data-toggle="tab"><i class="fa fa-arrow-circle-left"></i> Back</a>
                        </div>
                    </section>
                </div>
            </div>
        </section>

    </div>
</div>