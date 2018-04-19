<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/28/2015
 * Time: 9:19 PM
 */
include_once(APPPATH."serviceProviders/Forms/Form.php");
Class Tankers_Search_Form extends Form
{

    public $cities;
    public $products;
    public $customers;
    public $tankers;
    public function __construct($cities=null, $products=null, $customers=null, $tankers=null){
        parent::__construct();

        $this->cities = $cities;
        $this->products = $products;
        $this->tankers = $tankers;
        $this->customers = $customers;
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
    public function products()
    {
        $products = ['all'=>'All Of Them'];
        foreach($this->products as $product)
        {
            $products[$product->id] = $product->productName;
        }

        return $products;
    }
    public function customers()
    {
        return $this->makeOptions($this->customers, 'id','name');
    }
    public function tankers()
    {
        return $this->makeOptions($this->tankers, 'id','truck_number');
    }

    public $config = [
        'heading'=>'Search Tankers',
        'fields_per_line'=>'2',
        'params'=>[
            'name'=>'search_tankers_form',
            'action' => '',
            'method' => 'get',
            'id' => '',
            'class' => ''
        ],
        'fieldSets' => [
            [
                'legend'=>[
                    'text'=>'Tanker Info',
                    'params' => [],
                ],
                'fields'=>[
                    [
                        'label'=>[
                            'text'=>'Tankers:',
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
                                'name'=>'id',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'id',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'tankers',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Customers:',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => 'customers',
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'customerId',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'customerId',
                                'class'=>'form-control select_box', // bootstrap css class
                                'id' => 'customers',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Chase#',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'text',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'chase_number',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'chase_number',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'chase_number',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Engine#',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //input box syntax
                        'input'=>[
                            'type'=>'text',
                            'value' => '_get',
                            'placeholder'=>'',
                            'db'=>[
                                'name'=>'engine_number',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'engine_number',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'engine_number',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Capacity',
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
                                'name'=>'capacity',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'capacity',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'capacity',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Certificate:',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => ['all'=>'All','yes'=>'Yes','no'=>'No'],
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'fitness_certificate',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'fitness_certificate',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'fitness_certificate',
                            ],
                        ]
                    ],

                ],
            ],
            [
                'legend'=>[
                    'text'=>'Status Info',
                    'params' => [],
                ],
                'fields'=>[
                    [
                        'label'=>[
                            'text'=>'Status:',
                            'params'=>[
                                'class'=>'label'
                            ],
                        ],
                        //select box syntax
                        'input'=>[
                            'type'=>'select',
                            'options' => ['all'=>'All','on_move'=>'On Move','free'=>'Free'],
                            'selected' => '_get',
                            'db'=>[
                                'name'=>'status',
                                'comparison_operator'=>'=',
                            ],
                            'params'=>[
                                'name' => 'status',
                                'class'=>'form-control', // bootstrap css class
                                'id' => 'status',
                            ],
                        ]
                    ],
                    [
                        'label'=>[
                            'text'=>'Products:',
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
                                'id' => 'product_id',
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
                            'text'=>'From',
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
                            'text'=>'To',
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
                ],
            ],
        ],
        'submit'=>[
            'params'=>[
                'name'=>'searchTankers',
                'class' => "btn btn-primary",
                'id'=>'',
                'value'=>'Search'
            ]
        ],
        'pagination'=>true,
    ];

}