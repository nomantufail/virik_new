<html><head>    <title>Contractors</title>    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet"></head><body><style>    table{        font-size: 12px;    }    .multiple_entites{        border-bottom: 1px dashed lightgray;    }</style><div id="page-wrapper" style="min-height: 700px;">    <div class="container-fluid">        <div class="row">            <div class="col-lg-12">                <section class="col-md-12">                    <h3 class="">                      Contractors<small></small>                    </h3>                </section>            </div>        </div>        <div class="row">            <div class="col-md-12">                <table class="table table-bordered table-hover table-striped sortable">                    <thead>                    <tr>                        <?= ((in_array('id', $columns) == true)?"<th>ID</th>":"") ?>                        <?= ((in_array('name', $columns) == true)?"<th>Name</th>":"") ?>                        <?= ((in_array('id_card', $columns) == true)?"<th>ID Card #</th>":"") ?>                    </tr>                    </thead>                    <tbody>                    <?php                    //Showing Customers Data                    foreach($contractors as $contractor){                        ?>                        <tr>                            <?= ((in_array('id', $columns) == true)?"<td>$contractor->id</td>":"") ?>                            <?= ((in_array('name', $columns) == true)?"<td> $contractor->name </td>":"") ?>                            <?= ((in_array('id_card', $columns) == true)?"<td>$contractor->idCard</td>":"") ?>                        </tr>                    <?php                    }                    ?>                    </tbody>                </table>            </div>        </div>    </div></div><script src="<?= js()?>sorttable.js"></script></body></html>