<div id="page-wrapper">
<div class="container-fluid">

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Companies/company
            </li>
        </ol>
        <section class="col-md-6">
            <h1 class="page-header">
                Company Name <small></small>
            </h1>
        </section>
        <section class="col-md-6">
            <ul id="myTab" class="nav nav-pills page-header">
                <li class="active">
                    <a href="#profile" data-toggle="tab">
                        Profile
                    </a>
                </li>
                <li><a href="#accounts" data-toggle="tab">Accounts</a></li>
                <li><a href="#contact" data-toggle="tab">Contact</a></li>
            </ul>
        </section>
    </div>
</div>
<section>
<div id="myTabContent" class="tab-content" style="min-height: 500px;">
<div class="tab-pane fade in active" id="profile">
    <br><br>
    <div class="col-lg-12">
        <section style="background-color: ;" class="col-md-3 visible-lg visible-md">
            <img src="<?= images()?>profile.jpg" width="100%" height="100%">
        </section>
        <section style="background-color: ;" class="col-md-3 visible-sm">
            <img src="<?= images()?>profile.jpg" width="200px" height="270px">
        </section>
        <section style="background-color: ;" class="col-md-6">
            <table class="table">
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Name</th>
                    <td>Company Name Here..</td>
                </tr>
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Phone 1</th>
                    <td>03154379760</td>
                </tr>
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Phone 2</th>
                    <td>03154379760</td>
                </tr>
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Email 1</th>
                    <td>zeeshan_gn@gmail.com</td>
                </tr>
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Email 2</th>
                    <td>zeeshantufail_86@yahoo.com</td>
                </tr>
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Address</th>
                    <td>DHA Lahore Punjab, Pakistan</td>
                </tr>
            </table>
            <div class="text-left">
                <a href="#editProfile" data-toggle="tab">Edit <i class="fa fa-edit"></i></a>
            </div>
        </section>
        <section style="background-color: ;" class="col-md-12">
            <hr>
            And other details goes here if needed...
        </section>
    </div>
</div>
<div class="tab-pane fade" id="editProfile">
    <br><br>
    <section style="width: 500px; background-color: ; font-size: 16px;" class="center-block">
        You can edit your customer profile here but this area is under construction...<br><br>
        <div class="text-left">
            <a href="#profile" data-toggle="tab"><i class="fa fa-arrow-circle-left"></i> Back</a>
        </div>
    </section>
</div>
    <div class="tab-pane fade" id="accounts">
        <br>
        <section class="col-lg-12">
            <table class="table">
                <tr style="border-top: 2px solid #ffffff;">
                    <th>Trip #</th>
                    <th>Trip Date</th>
                    <th>Commission (%)</th>
                    <th>Commission (Rs)</th>
                    <th>Total Paid</th>
                    <th>Balance</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr style="">
                    <th>01</th>
                    <td>10-Jan-2014</td>
                    <td>5</td>
                    <td>1000</td>
                    <td>600</td>
                    <td>400</td>
                    <td><a href="#"><li class="fa fa-edit"></li></a></td>
                    <td><a href="#accountsDetails" data-toggle="tab"><li class="fa fa-eye"></li></a></td>
                </tr>
                <tr style="">
                    <th>01</th>
                    <td>10-Jan-2014</td>
                    <td>5</td>
                    <td>1000</td>
                    <td>600</td>
                    <td>400</td>
                    <td><a href="#"><li class="fa fa-edit"></li></a></td>
                    <td><a href="#accountsDetails" data-toggle="tab"><li class="fa fa-eye"></li></a></td>
                </tr>
                <tr style="">
                    <th>01</th>
                    <td>10-Jan-2014</td>
                    <td>5</td>
                    <td>1000</td>
                    <td>600</td>
                    <td>400</td>
                    <td><a href="#"><li class="fa fa-edit"></li></a></td>
                    <td><a href="#accountsDetails" data-toggle="tab"><li class="fa fa-eye"></li></a></td>
                </tr>
                <tr style="">
                    <th>01</th>
                    <td>10-Jan-2014</td>
                    <td>5</td>
                    <td>1000</td>
                    <td>600</td>
                    <td>400</td>
                    <td><a href="#"><li class="fa fa-edit"></li></a></td>
                    <td><a href="#accountsDetails" data-toggle="tab"><li class="fa fa-eye"></li></a></td>
                </tr>
            </table>
        </section>
    </div>

    <div class="tab-pane fade" id="accountsDetails">
        <br>
        <div class="col-lg-12">
            <h3>Payment Details For Trip #01</h3><br><br>
            <section class="col-md-12">
                <table class="table table-bordered">
                    <tr style="">
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>20-June-2013</td>
                        <td>200</td>
                        <td><a href="#"><li style="font-size: 18px;" class="fa fa-edit"></li></a></td>
                        <td><a href="#"><li style="color: red; font-size: 18px;" class="fa fa-minus-circle"></li></a></td>
                    </tr>
                    <tr>
                        <td>20-June-2013</td>
                        <td>200</td>
                        <td><a href="#"><li style="font-size: 18px;" class="fa fa-edit"></li></a></td>
                        <td><a href="#"><li style="color: red; font-size: 18px;" class="fa fa-minus-circle"></li></a></td>
                    </tr>
                    <tr>
                        <td>20-June-2013</td>
                        <td>200</td>
                        <td><a href="#"><li style="font-size: 18px;" class="fa fa-edit"></li></a></td>
                        <td><a href="#"><li style="color: red; font-size: 18px;" class="fa fa-minus-circle"></li></a></td>
                    </tr>
                </table>
                <form>
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input class="form-control datepicker"data-date-format="yyyy-mm-dd" data-provide="datepicker" value="" placeholder="Please click here to choose a date.." name='lastName' id='lastName' "">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input class="form-control" value="" placeholder="Enter amount.." name='lastName' id='lastName' "">
                                    </div>
                                </div>
                            </td>
                            <td><button class="btn btn-success">Pay</button></td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </section>
            <div class="text-left">
                <br>
                <a href="#accounts" data-toggle="tab"><li class="fa fa-arrow-circle-left"></li> Back</a>
            </div>
        </div>
    </div>

<div class="tab-pane fade" id="contact">
    <br><br>
    <section style="width: 500px; background-color: ;font-size: 16px;" class="center-block">
        This area is under construction...
    </section>
</div>
</div>
</section>

</div>
</div>