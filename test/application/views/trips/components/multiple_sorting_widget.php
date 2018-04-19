

<!--this area is hedden from the view and is used for searching-->
<div id="multiple_sorting_popup" class="multiple_sorting_popup mfp-hide">

    <?php
    $sorting_module = 'primary_trips';
    if($this->uri->segment(3) == 'secondary')
    {
        $sorting_module = 'secondary_trips';
    }
    else if($this->uri->segment(3) == 'secondary_local')
    {
        $sorting_module = 'secondary_local_trips';
    }

    $sorting_columns = Sort::$viewDrivers['primary_trips']['columns'];
    $pre_saved_sorting_columns = $this->helper_model->pre_saved_sorting_columns($sorting_module);

    ?>
    <div style="height: 50px; background-color: #2a6496; color: white;">
        <div style="text-align: center">
            <h3 style="line-height: 50px;"> Sort Trips </h3>
        </div>
    </div>
    <?php
//    $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
//    $url = 'http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0];
    ?>
    <form action="<?= page_url() ?>" method="post">

        <input type="hidden" name="sorting_module" value="<?= $sorting_module ?>">

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Column</th>
                <th>Priority</th>
                <th>Order</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 0; ?>
            <?php foreach($sorting_columns as $column): ?>
            <?php
                $count++;
                $sorting_column_data = null;
                if(isset($pre_saved_sorting_columns[$column['slug']])){
                    $sorting_column_data = $pre_saved_sorting_columns[$column['slug']];
                }
                $checked = '';
                $priority = '';
                $order = 'asc';

                if($sorting_column_data != null)
                {
                    $checked = 'checked';
                    $priority = $sorting_column_data[0]->priority;
                    $order = $sorting_column_data[0]->order_by;
                }
            ?>
            <tr>
                <td>
                    <input type="checkbox" <?= $checked ?> name="column_<?= $count ?>" value="<?= $column['slug'] ?>">
                    <?= ucwords($column['column_name']) ?>
                    <input type="hidden" name="type_<?= $count ?>" value="<?= $column['type'] ?>">
                </td>
                <td>
                    <input type="number" value="<?= $priority ?>" min="0" name="priority_<?= $count ?>">
                </td>
                <td>
                    <input type="radio" value="asc" name="order_<?= $count ?>" <?= (($order == 'asc')?'checked':'') ?>> Asc
                    <input style="margin-left: 10px;" type="radio" value="desc" name="order_<?= $count ?>" <?= (($order == 'desc')?'checked':'') ?>> Desc
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" name="num_of_columns" value="<?= sizeof($sorting_columns) ?>">
        <div style="text-align: center">
            <button class="btn btn-primary btn-lg" name="multiple_sort"> Sort Records </button>
        </div>
    </form>

</div>
<!--***********************************************************************-->