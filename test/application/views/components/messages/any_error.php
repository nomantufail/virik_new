
<div id="page-wrapper">

    <div class="container-fluid">

        <!--including editable links-->
        <?php include_editable_libs(); ?>


        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-4">
                    <h3 class="page-header">
                        Error Message
                    </h3>

                </section>

                <section class="col-md-8">

                </section>

            </div>

        </div>

        <section>
            <section class="col-lg-12" id="form_errors">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <?= $errorMessage ?>
                </div>
            </section>
        </section>



    </div>

</div>