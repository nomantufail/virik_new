<script>    function show_monthly_results(month, path){        var path = path+month;        window.location.href = path;    }</script><style>    .search-table2 input{        width:100%;    }    .search-table1 input{        width:100%;    }    .search-table2 input{        height: 30px;    }    .search-table1 input{        height: 30px;    }</style><div id="page-wrapper" style="min-height: 700px;">    <div class="container-fluid">        <div class="row">            <div class="col-lg-12">                <section class="col-md-4">                    <h1 class="page-header">                        Tanker <small> (<?= $profile->truck_number; ?>)</small>                    </h1>                </section>                <section class="col-md-8">                    <ul id="myTab" class="nav nav-pills page-header">                        <li class=""><a href="<?= base_url()."tankers/profile/".$profile->id;?>">Profile</a></li>                        <li class=""><a href="<?= base_url()."tankers/trips/".$profile->id."/".$month."/";?>">Trips</a></li>                        <li><a href="<?= base_url()."tankers/trips_expenses/".$profile->id."/".$month."/";?>">Trips Expenses</a></li>                        <li class="active"><a href="<?= base_url()."tankers/other_expenses/".$profile->id."/".$month."/";?>">Other Expenses</a></li>                        <li><a href="<?= base_url()."tankers/profit_loss/".$profile->id."/".$month."/";?>">Profit & Loss</a></li>                    </ul>                </section>            </div>        </div>        <div class="row">            <div class="col-lg-12">                    <section class="col-lg-12" id="form_errors">                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                            <strong>Error! </strong>', '</div>');                        ?>                        <?php if(is_array($someMessage)){ ?>                            <div class="alert <?= $someMessage['type']; ?> alert-dismissible" role="alert">                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                <?= $someMessage['message']; ?>                            </div>                        <?php } ?>                    </section>                    <section class="col-lg-12" id="add_expense_form_template">                        <form action="#" method="post">                        <table class="table" style="display:; background-color: lightgray;">                            <tr style="border-top: 2px solid #ffffff;">                                <td>                                    <div class="form-group">                                        <div class="col-md-12">                                             <input class="form-control" type="date" value="" required="required" placeholder="Expense Date" name='expense_date' id='expense_date' "">                                        </div>                                    </div>                                </td>                                <td>                                    <div class="form-group">                                        <div class="col-md-12">                                            <input class="" type="hidden" value="<?= $form_id; ?>" name='form_id' id=''>                                            <input class="form-control" type="text" value="" required="required" placeholder="Expense Title Here..." name='expense_title' id='expense_title' "">                                        </div>                                    </div>                                </td>                                <td>                                    <div class="form-group">                                        <div class="col-md-12">                                            <input class="form-control" value="" required="required" placeholder="Enter amount.." name='amount' id='amount' "">                                        </div>                                    </div>                                </td>                                <td><button class="btn btn-success" style="width: 100%;">Pay</button></td>                            </tr>                        </table>                        </form>                    </section>                <div class="panel-body">                    <div id="myTabContent" class="tab-content" style="min-height: 500px;">                        <div class="tab-pane fade in active" id="trips">                            <div class="table-responsive">                                <form action="<?= base_url()."tankers/trips_expenses/".$profile->id; ?>" method="get">                                    <table class="search-table1" border="0">                                        <tr>                                            <th style="">From</th>                                            <td><input type="date" placeholder="" value="<?php if(isset($_GET['from'])){echo $_GET['from'];} ?>" name="from"></td>                                            <th>To</th>                                            <td><input type="date" placeholder="" value="<?php if(isset($_GET['to'])){echo $_GET['to'];} ?>" name="to"></td>                                        </tr>                                    </table>                                    <table class="search-table2" border="1" style="">                                        <tr>                                            <td style="width: 25%;"><input type="date" placeholder="" value="<?php if(isset($_GET['expenseDate'])){echo $_GET['expenseDate'];} ?>" name="expenseDate"></td>                                            <td style="width: 50%;"><input type="text" placeholder="expense_title" value="<?php if(isset($_GET['expense_title'])){echo $_GET['expense_title'];} ?>" name="expense_title"></td>                                            <td style="width: 10%;"><input type="number" placeholder="amount.." value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>" name="amount"></td>                                            <td><button style="width: 100%;">Search</button></td>                                        </tr>                                    </table>                                </form>                                <form name="selection_form" id="selection_form" method="post" action="<?php                                if(strpos($this->helper_model->page_url(),'?') == false){                                    echo $this->helper_model->page_url()."?";                                }else{echo $this->helper_model->page_url()."&";}                                ?>print">                                <table class="table table-bordered table-hover table-striped sortable">                                    <thead style="border-top: 4px solid lightgray;">                                    <tr>                                        <th></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="expense_date" style="" checked></div></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="expense_title" style="" checked></div></th>                                        <th><div><input id="" type="checkbox" name="column[]" value="expense_amount" style="" checked></div></th>                                        <th></th>                                    </tr>                                    <tr>                                        <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>                                        <th>Expense Date</th>                                        <th>Expense Title</th>                                        <th>Expense Amount</th>                                        <th></th>                                    </tr>                                    </thead>                                    <tbody>                                        <?php                                        $total_expense = 0;                                            foreach($expenses as $expense){                                                $total_expense+=$expense->amount;                                                ?>                                                <tr>                                                    <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $expense->id; ?>"></td>                                                    <td><?= $this->carbon->createFromFormat('Y-m-d', $expense->expense_date)->toFormattedDateString(); ?></td>                                                    <td><?= $expense->description ?></td>                                                    <td><?= $this->helper_model->money($expense->amount) ?></a></td>                                                    <td><a href="#" style="display: block; height: 100%; width: 100%;"><li class="fa fa-minus-circle" style="color: red;"></li> remove</a> </td>                                                </tr>                                                <?php                                            }                                        ?>                                    </tbody>                                    <tfoot>                                    <tr>                                        <th colspan="3">Totals</th>                                        <th colspan="2"><?= $this->helper_model->money($total_expense); ?></th>                                    </tr>                                    </tfoot>                                </table>                               </form>                            </div>                        </div>                    </div>                </div>            </div>        </div>    </div></div>