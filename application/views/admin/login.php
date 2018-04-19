<html>
<head>
    <!-- Bootstrap Core CSS -->
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">

    <script src="<?= js()?>jquery.js"></script>

</head>
<body>
<div class="col-lg-12">
    <?php
    echo validation_errors('<div class="alert alert-warning alert-dismissible center-block" role="alert" style="  width: 300px; height: auto; background-color: ; color: ">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4>Error!</h4>','</div>');
    ?>
    <?php
    if(isset($message)){
        ?>
        <div class="alert <?= $type ?> alert-dismissible center-block" role="alert" style="  width: 300px; height: auto; background-color: ; color: ">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4><?= $message ?></h4></div>
        <?php
    }
    ?>

</div>

<div id="login-form" class="col-lg-12">
    <div class="loginBox" style="position: absolute; left: 0; right: 0px; top: 10; margin: auto; width: 300px; height: 300px; background-color: ; color: ">
        <h2 style="text-align: center; color:; ">Login</h2>
        <hr>
        <form class="form-horizontal" role="form" action="<?= base_url()."admin/login";?>" method="post">
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="form-control" placeholder="Username" size="50" name='username' id='username' required="required">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="form-control" type="password" placeholder="Password"size="50" name='password' id='password' required="required">

                </div>
            </div>
            <!--<div class="form-group">
                <div class="col-sm-12">
                    <input class="form-control" type="text" placeholder="Security Code"size="50" name='confirmCaptcha' id='confirmSecurityCode' required="required">
                    <?php /*$attrs = array(
                        'captcha' => $captcha_word,
                    );
                    echo form_hidden($attrs)
                    */?>
                    <?/*= $captcha; */?>
                </div>
            </div>-->
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="form-control btn-success" type="submit" value="Login!" name="loginAdmin">
                </div>
            </div>
        </form>

        <a href="?req=forgotPassword"></a>
    </div>
</div>

<div class="row col-lg-12 text-center" style="color: gray; font-size: 12px; height: 35px; line-height: 25px; position: absolute; margin:auto; bottom: 0;">
    This product is powered by zeenomlabs (2012 - 2014)
</div>

<script src="<?= js()?>myjquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?= js()?>bootstrap.min.js"></script>
</body>
</html>

