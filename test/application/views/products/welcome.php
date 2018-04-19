<style>
    .insert_table td input{
        width: 100%;
    }
    .insert_table td select{
        width: 100%;
        height: 25px;
    }
    .insert_table button
    {
        width: 100%;
    }
    .insert_table .lable{

    }
</style>
<div class="row page_heading_container">
    <div class="col-lg-12">
        <section class="col-md-6">
            <h3 class="">
                Products <small></small>
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

            <div class="row" style="background-color: ;">
                <div class="col-lg-12">
                    <form method="post">
                        <table style="width: 100%;" class="insert_table">

                            <td>
                                <label>Name:</label><br>
                                <input type="text" required="required" maxlength="30" name="name">
                            </td>
                            <td>
                                <label>Description:</label><br>
                                <input type="text" maxlength="30" name="description">
                            </td>

                            <td style="width: 10%;">
                                <label></label>
                                <button name="addProduct" style="margin-top: 5px;"><i class="fa fa-plus-circle"> </i> Add</button>
                            </td>
                        </table>
                    </form>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12">

                    <table class="my_table list_table table table-bordered">
                        <thead class="table_header">
                        <tr class="table_row table_header_row">
                            <th class="column_heading">ID</th>
                            <th class="column_heading">Name</th>
                            <th class="column_heading">Description</th>
                        </tr>
                        </thead>
                        <tbody class="table_body">
                        <?php foreach($products as $product):?>
                            <tr class="table_row table_body_row">
                                <td class="table_td"><?= ucwords($product->id)?></td>
                                <td class="table_td"><?= ucwords($product->name)?></td>
                                <td class="table_td"><?= ucwords($product->description)?></td>
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