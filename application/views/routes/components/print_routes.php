<html>
<head>
    <title>Routes</title>
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">
</head>
<body>

<style>
    table{
        font-size: 12px;
    }
</style>
<div id="page-wrapper" style="min-height: 700px;">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-12 text-center">
                    <h1>
                        Routes <small>
                        </small>
                    </h1>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped sortable">
                    <thead>
                    <tr>
                        <?= ((in_array('id', $columns) == true)?"<th>ID</th>":"") ?>
                        <?= ((in_array('source', $columns) == true)?"<th>Source</th>":"") ?>
                        <?= ((in_array('destination', $columns) == true)?"<th>Destination</th>":"") ?>
                        <?= ((in_array('product', $columns) == true)?"<th>Product</th>":"") ?>
                        <?= ((in_array('freight', $columns) == true)?"<th>Freight(Rs)</th>":"") ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($routes as $route){
                        ?>
                        <tr>

                            <?= ((in_array('id', $columns) == true)?"<td>$route->id</td>":"") ?>
                            <?= ((in_array('source', $columns) == true)?"<td>$route->source</td>":"") ?>
                            <?= ((in_array('destination', $columns) == true)?"<td>$route->destination </td>":"") ?>
                            <?= ((in_array('product', $columns) == true)?"<td>$route->product</td>":"") ?>
                            <?= ((in_array('freight', $columns) == true)?"<td>$route->freight</td>":"") ?>

                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


<script src="<?= js()?>sorttable.js"></script>
</body>
</html>
