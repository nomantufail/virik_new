<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/28/2015
 * Time: 9:19 PM
 */
include_once(APPPATH."serviceProviders/Forms/Form.php");
Class Shortages_Search_Form extends Form
{

    public $cities;
    public $products;
    public $tankers;
    public function __construct($cities, $products, $tankers){
        parent::__construct();

        $this->cities = $cities;
        $this->products = $products;
        $this->tankers = $tankers;
    }

    public function Create(){
        return $this->createForm();
    }

    public function cities()
    {
        $cities = ['all'=>'All Of Them'];
        foreach($this->cities as $city)
        {
            $cities[$city->id] = $city->cityName;
        }
        return $cities;
    }
    public function tankers()
    {
        $tankers = ['all'=>'All Of Them'];
        foreach($this->tankers as $tanker)
        {
            $tankers[$tanker->id] = $tanker->truck_number;
        }
        return $tankers;
    }
    public function products()
    {
        $products = ['all'=>'All Of Them'];
        foreach($this->products as $product)
        {
            $products[$product->id] = $product->productName;
        }

        return $products;
    }

    public $config = [
        'heading'=>'Search Trips',
        'fields_per_line'=>'2',
        'params'=>[
            'name'=>'search_destination_shortages',
            'action' => '',
            'method' => 'get',
            'id' => '',
            'class' => ''
        ],
        'fieldSets' => [
            [
                'legend'=>[
                    'text'=>'',
                    'params' => [],
                ],
                'fields'=>[
                    [
                        'label'=>[
                            'text'=>'Shortage#',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'number',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'shortage_id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'shortage_id',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'shortage_id',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Trip#',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'number',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'trip_id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'trip_id',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'trip_id',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Trip From',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'date',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'trip_entry_date',
                                'comparison_operator'=>'>',
                            ],
                            'params'=>[
                                'name' => 'from',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'trip_date',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Trip To',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'date',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'trip_entry_date',
                                'comparison_operator'=>'<',
                            ],
                            'params'=>[
                                'name' => 'to',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'trip_date',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Shortage From',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'date',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'date',
                                'comparison_operator'=>'>',
                            ],
                            'params'=>[
                                'name' => 'from',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'shortage_date',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Shortage To',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'date',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'date',
                                'comparison_operator'=>'<',
                            ],
                            'params'=>[
                                'name' => 'to',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'shortage_date',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Source',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => 'cities',
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'source_id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'source_id',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'destination',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Destination',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => 'cities',
                            'selected' => '_get',
                            'db'=> [
                                'name'=>'destination_id',
                                'comparison_operator' => '=',
                            ],
                            'params'=>[
                                'name' => 'destination_id',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'destination',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Product',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => 'products',
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'product_id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'product_id',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'product',
                            ],
                        ]
                    ],

                    [
                        'label'=>[
                            'text'=>'Tanker',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => 'tankers',
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'tanker_id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'tanker_id',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'tanker_number',
                            ],
                        ]
                    ],

                ],
            ],
        ],
        'submit'=>[
            'params'=>[
                'name'=>'searchTrips',
                'class' => "btn btn-primary",
                'id'=>'',
                'value'=>'Search Trips'
            ]
        ],
        'pagination'=>false,
    ];

}