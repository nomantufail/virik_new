
<div class="row page_heading_container">
    <div class="col-lg-12">
        <section class="col-md-6">
            <h3 class="">
                Stock <small></small>
            </h3>
        </section>
    </div>
</div>
<div id="page-wrapper" class="whole_page_container">

    <div class="container-fluid">

        <!--Notifications Area-->
        <div class="row">
            <?php echo validation_errors('<div class="alert alert-danger alert-dismissible" role="alert">

                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                                            <strong>Error! </strong>', '</div>'); ?>



        </div>
        <!--notifications area ends-->

        <div class="row actual_body_contents">


            <div class="row">

                <div class="col-lg-12">

                    <table class="my_table list_table table table-bordered">
                        <thead class="table_header">
                        <tr class="table_row table_header_row">
                            <th class="column_heading">Product</th>
                            <th class="column_heading">Quantity</th>
                        </tr>
                        </thead>
                        <tbody class="table_body">
                        <?php foreach($stock as $entry):?>
                            <tr class="table_row table_body_row">
                                <td class="table_td"><?= ucwords($entry->product_name)?></td>
                                <td class="table_td"><?= ucwords($entry->quantity)?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table_footer">
                        <tr class="table_footer_row">

                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>



    </div>

</div>