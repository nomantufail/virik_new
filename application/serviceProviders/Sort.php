<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/24/2015
 * Time: 11:14 PM
 */


class Sort {

    /*----- Install Your View Drivers Here ----------*/
    public static $viewDrivers = [
        'primary_trips'=>[
            'table'=>'trips_view',
            'default'=>[
                'sort_by'=>'trip_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'trip id',
                    'slug'=>'trip_id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'trip type',
                    'slug'=>'trip_sub_type',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'entryDate',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'tanker_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'capacity',
                    'slug'=>'capacity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'contractor',
                    'slug'=>'contractor_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'company',
                    'slug'=>'company_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'source',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'destination',
                    'slug'=>'destination',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product quantity',
                    'slug'=>'product_quantity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'stn number',
                    'slug'=>'stn_number',
                    'type'=>'string',
                ],
            ],
        ],
        'secondary_trips'=>[
            'table'=>'trips_view',
            'default'=>[
                'sort_by'=>'trip_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'trip id',
                    'slug'=>'trip_id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'trip type',
                    'slug'=>'trip_sub_type',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'entryDate',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'tanker_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'capacity',
                    'slug'=>'capacity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'contractor',
                    'slug'=>'contractor_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'company',
                    'slug'=>'company_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'source',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'destination',
                    'slug'=>'destination',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product quantity',
                    'slug'=>'product_quantity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'stn number',
                    'slug'=>'stn_number',
                    'type'=>'string',
                ],
            ],
        ],
        'secondary_local_trips'=>[
            'table'=>'trips_view',
            'default'=>[
                'sort_by'=>'trip_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'trip id',
                    'slug'=>'trip_id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'trip type',
                    'slug'=>'trip_sub_type',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'entryDate',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'tanker_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'capacity',
                    'slug'=>'capacity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'contractor',
                    'slug'=>'contractor_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'company',
                    'slug'=>'company_name',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'source',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'destination',
                    'slug'=>'destination',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product quantity',
                    'slug'=>'product_quantity',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'stn number',
                    'slug'=>'stn_number',
                    'type'=>'string',
                ],
            ],
        ],
        'manage_accounts_white_oil'=>[
            'table'=>'manage_accounts_white_oil_view',
            'default'=>[
                'sort_by'=>'trip_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'trip id',
                    'slug'=>'trip_id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'trip type',
                    'slug'=>'trip_sub_type',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'trip_date',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'tanker_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'productName',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product quantity',
                    'slug'=>'product_quantity',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'shortage amount',
                    'slug'=>'shortage_amount',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'route',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'stn number',
                    'slug'=>'stn_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'company',
                    'slug'=>'company',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'company frt / unit',
                    'slug'=>'company_freight_unit',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'total freight cmp',
                    'slug'=>'total_freight_cmp',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'wht',
                    'slug'=>'wht_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'company commission',
                    'slug'=>'company_commission_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor',
                    'slug'=>'contractor',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor_freight',
                    'slug'=>'contractor_freight',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor freight without shortage',
                    'slug'=>'contractor_freight_without_shortage',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor_net_freight',
                    'slug'=>'contractor_net_freight',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor commission',
                    'slug'=>'contractor_commission_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'customer frt / unit',
                    'slug'=>'customer_freight_unit',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'total freight cst',
                    'slug'=>'total_freight_cst',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer_net_freight',
                    'slug'=>'customer_net_freight',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer net freight without shortage',
                    'slug'=>'customer_net_freight_without_shortage',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'service charges',
                    'slug'=>'service_charges',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'billed',
                    'slug'=>'billed',
                    'type'=>'string',
                ],
            ],
        ],
        'manage_accounts_black_oil'=>[
            'table'=>'manage_accounts_black_oil_view',
            'default'=>[
                'sort_by'=>'trip_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'trip id',
                    'slug'=>'trip_id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'trip type',
                    'slug'=>'trip_sub_type',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'trip_date',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'source',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'destination',
                    'slug'=>'destination',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'invoice_date',
                    'slug'=>'invoice_date',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'invoice_number',
                    'slug'=>'invoice_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'stn number',
                    'slug'=>'stn_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'tanker_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'Dis Qty',
                    'slug'=>'dis_qty',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'Rec Qty',
                    'slug'=>'rec_qty',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'shortage qty',
                    'slug'=>'shortage_qty',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'freight on shortage (cmp)',
                    'slug'=>'freight_on_shortage_qty_cmp',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'freight on shortage (cst)',
                    'slug'=>'freight_on_shortage_qty_cst',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'Net frt on shrt (cst)',
                    'slug'=>'net_freight_on_shortage_qty_cst',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'company frt / unit',
                    'slug'=>'company_freight_unit',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'total freight cmp',
                    'slug'=>'total_freight_cmp',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'freight amount cmp',
                    'slug'=>'freight_amount_cmp',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'company',
                    'slug'=>'company',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'shortage rate',
                    'slug'=>'shortage_rate',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'shortage amount',
                    'slug'=>'shortage_amount',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'payable before tax',
                    'slug'=>'payable_before_tax',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'wht',
                    'slug'=>'wht_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'net paybles',
                    'slug'=>'net_payables',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor_net_freight',
                    'slug'=>'contractor_net_freight',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'company commission',
                    'slug'=>'company_commission_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor commission',
                    'slug'=>'contractor_commission_amount',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'contractor',
                    'slug'=>'contractor',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer frt / unit',
                    'slug'=>'customer_freight_unit',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'total freight cst',
                    'slug'=>'total_freight_cst',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'freight amount cst',
                    'slug'=>'freight_amount_cst',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer freight',
                    'slug'=>'customer_freight',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'service charges',
                    'slug'=>'service_charges',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'billed',
                    'slug'=>'billed',
                    'type'=>'string',
                ],
            ],
        ],
        'shortages'=>[
            'table'=>'destination_shortages_view',
            'default'=>[
                'sort_by'=>'shortage_id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'shortage#',
                    'slug'=>'shortage_id',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'trip#',
                    'slug'=>'trip_id',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'trip date',
                    'slug'=>'trip_entry_date',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'source',
                    'slug'=>'source',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'destination',
                    'slug'=>'destination',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'shortage date',
                    'slug'=>'date',
                    'type'=>'date',
                ],
                [
                    'column_name'=>'shortage qty',
                    'slug'=>'quantity',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'shortage rate',
                    'slug'=>'rate',
                    'type'=>'numeric',
                ],
                [
                    'column_name'=>'shortage amount',
                    'slug'=>'shortage_amount',
                    'type'=>'num',
                ],
            ],
        ],
        'tankers'=>[
            'table'=>'tankers_status_view',
            'default'=>[
                'sort_by'=>'id',
                'order_by'=>'desc'
            ],
            'columns'=>[
                [
                    'column_name'=>'id',
                    'slug'=>'id',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'tanker',
                    'slug'=>'truck_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'customer',
                    'slug'=>'customer',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'engine number',
                    'slug'=>'engine_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'chase number',
                    'slug'=>'chase_number',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'fitness certificate',
                    'slug'=>'fitness_certificate',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'capacity',
                    'slug'=>'capacity',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'product',
                    'slug'=>'product',
                    'type'=>'string',
                ],
                [
                    'column_name'=>'status',
                    'slug'=>'status',
                    'type'=>'num',
                ],
                [
                    'column_name'=>'route',
                    'slug'=>'source',
                    'type'=>'string',
                ],
            ],
        ],
    ];
    /*-----------------------------------------------*/




    public static function columns($module)
    {
        $ci = &get_instance();
        $sorting_info = [];
        if(isset($_GET['sort_by'])){
            $requested_column = $_GET['sort_by'];
            $fields = $ci->db->list_fields(self::$viewDrivers[$module]['table']);
            if(in_array($requested_column, $fields)){
                $column_data['sort_by'] = $requested_column;
                $column_data['order_by'] = 'asc';
                if(isset($_GET['order'])){
                    if($_GET['order'] == 'desc')
                        $column_data['order_by'] = 'desc';
                }
                $sorting_info[] = $column_data;
                return $sorting_info;
            }
        }

        $db = $ci->db;
        $db->select('*');
        $db->where('view',$module);
        $db->order_by('priority','asc');
        $result = $db->get('sort')->result();

        foreach($result as $record)
        {
            $column_data['sort_by'] = $record->sort_by;
            $column_data['order_by'] = $record->order_by;
            $sorting_info[] = $column_data;
        }

        if(sizeof($sorting_info) > 0){
            return $sorting_info;
        }else{
            $column_data['sort_by'] = self::$viewDrivers[$module]['default']['sort_by'];
            $column_data['order_by'] = self::$viewDrivers[$module]['default']['order_by'];
            $sorting_info[] = $column_data;

            return $sorting_info;
        }
    }

    public static function createSortableHeader($module)
    {
        $columns = self::$viewDrivers[$module]['columns'];
        $markup = "";
        foreach($columns as $column)
        {
            $markup.= sortable_header($column['slug'], $column['type'],ucwords($column['column_name']));
        }
        return $markup;
    }

    public static function createCheckBoxes($module)
    {
        //<th><div><input id="" type="checkbox" name="column[]" style="" value="id" checked></div></th>
        $columns = self::$viewDrivers[$module]['columns'];
        $markup = "";
        foreach($columns as $column)
        {
            $markup.= '<th><div><input id="" type="checkbox" name="column[]" style="" value="'.$column['slug'].'" checked></div></th>';
        }
        return $markup;
    }

    public static function createPrintableHeaders($module, $selected_columns)
    {
        $columns = self::$viewDrivers[$module]['columns'];
        $markup = "";
        foreach($columns as $column)
        {
            $markup.= ((in_array($column['slug'], $selected_columns) == true)?"<th>".ucwords($column['column_name'])."</th>":"");
        }
        return $markup;
    }

    public static function createPrintableBody($module, $selected_columns, $object)
    {
        $columns = self::$viewDrivers[$module]['columns'];
        $markup = "";
        foreach($columns as $column)
        {
            $markup.=printable_column($column['slug'], $selected_columns, $object->$column['slug']);
        }
        return $markup;
    }
} 