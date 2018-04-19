<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/28/2015
 * Time: 11:08 PM
 */

class Form {
    private $framework;
    public function __construct($framework = ''){
        $this->framework = $framework;
    }

    /*   Making Raw Inputs */
    public function createSelectInput($input)
    {
        $options = $input['options'];
        if(!is_array($input['options']))
        {
            $options = $this->$input['options']();
        }

        $selected = "";
        if($input['selected'] == '_get')
        {
            if(isset($_GET[$input['params']['name']])){
                $selected = $_GET[$input['params']['name']];
            }
        }

        $selectInput = "";
        $params = "";
        foreach($input['params'] as $key=>$value)
        {
            $params.=" ".$key."='".$value."'";
        }
        $selectInput.='<select style="width:100%;" '.$params.'>';

        foreach($options as $key=>$value)
        {
            $selectInput.= '<option value="'.$key.'"'.(($key == $selected)?"selected":"").'>';
            $selectInput.= $value;
            $selectInput.= '</option>';
        }

        $selectInput.='</select>';
        return $selectInput;
    }

    public function createTextInput($input)
    {
        $textInput = "";

        $oldValue = "";
        if($input['value'] == '_get')
        {
            if(isset($_GET[$input['params']['name']])){
                $oldValue = $_GET[$input['params']['name']];
            }
        }

        $params = "";
        foreach($input['params'] as $key=>$value)
        {
            $params.=" ".$key."='".$value."'";
        }
        $textInput .= '<input type="text" value="'.$oldValue.'" '.$params.'>';

        return $textInput;
    }

    public function createDateInput($input)
    {
        $dateInput = "";

        $oldValue = "";
        if($input['value'] == '_get')
        {
            if(isset($_GET[$input['params']['name']])){
                $oldValue = $_GET[$input['params']['name']];
            }
        }

        $params = "";
        foreach($input['params'] as $key=>$value)
        {
            $params.=" ".$key."='".$value."'";
        }
        $dateInput .= '<input type="date" value="'.$oldValue.'" '.$params.'>';

        return $dateInput;
    }

    public function createNumberInput($input)
    {
        $numberInput = "";

        $oldValue = "";
        if($input['value'] == '_get')
        {
            if(isset($_GET[$input['params']['name']])){
                $oldValue = $_GET[$input['params']['name']];
            }
        }

        $params = "";
        foreach($input['params'] as $key=>$value)
        {
            $params.=" ".$key."='".$value."'";
        }
        $numberInput .= '<input type="number" value="'.$oldValue.'" '.$params.'>';

        return $numberInput;
    }
    /*----------------------------------------*/

    public function createLabel($label)
    {
        $labelMarkup = "<label class='label' style='color: #555555; font-size: 16px;'>";
        $labelMarkup.= $label['text'];
        $labelMarkup.= "</label>";

        return $labelMarkup;
    }
    public function createInput($input)
    {
        $inputMarkup = "";
        switch($input['type'])
        {
            case "select":
                $inputMarkup.= $this->createSelectInput($input);
                break;
            case "text":
                $inputMarkup.= $this->createTextInput($input);
                break;
            case "number":
                $inputMarkup.= $this->createNumberInput($input);
                break;
            case "date":
                $inputMarkup.= $this->createDateInput($input);
                break;
            default:
                $inputMarkup.= $this->createTextInput($input);
                break;
        }
        return $inputMarkup;
    }
    public function createField($field)
    {
        $fieldMarkup = "<div class='col-md-".(12/$this->config['fields_per_line'])."'>";
        $fieldMarkup.=$this->createLabel($field['label']);
        $fieldMarkup.=$this->createInput($field['input']);
        $fieldMarkup.= "</div>";
        return $fieldMarkup;
    }
    public function createFieldset($fieldset)
    {
        $fieldsetMarkup  = "<fieldset>";
        $fieldsetMarkup .= "<legend>".$fieldset['legend']['text']."</legend>";

        $fieldCounter = 0;
        foreach($fieldset['fields'] as $field){
            $div_started = false;
            if($fieldCounter % $this->config['fields_per_line'] == 0){
                $div_started = true;
                $fieldsetMarkup.= "<div class='row' style='margin-top:5px;'>";
            }
            $fieldsetMarkup.=$this->createField($field);

            if($fieldCounter % $this->config['fields_per_line'] != 0 && ($fieldCounter % $this->config['fields_per_line']) == ($this->config['fields_per_line']-1) && $div_started == false){
                $fieldsetMarkup.= "</div>";
            }else if($this->config['fields_per_line'] == '1')
                $fieldsetMarkup.= "</div>";
            else if (sizeof($fieldset['fields']) == ($fieldCounter+1))
                $fieldsetMarkup.= "</div>";

            $fieldCounter++;
        }

        $fieldsetMarkup.= "</fieldset>";

        return $fieldsetMarkup;
    }
    public function createForm($config = '')
    {
        $config = (is_array($config)?$config:$this->config);

        $params = "";
        foreach($config['params'] as $key=>$value)
        {
            $params.=" ".$key."='".$value."'";
        }
        $form = "<div class='row' style='text-align: center;'>";
        $form .= "<h3>".$config['heading']."</h3>";
        $form .= "</div>";
        $form .= "<form ".$params.">";
            $form .= "<div class='row'>";
                $form .= "<div class='col-lg-12 center-block'>";

                    $fieldSets = $config['fieldSets'];
                    foreach($fieldSets as $fieldset)
                    {
                        $form.= $this->createFieldSet($fieldset);
                        $form.= "<hr>";
                    }

                    $form.= "<div class='col-sm-12 form-group' style='margin-top: 5px;'>";

                        if($config['pagination'] == true)
                        {
                            $form.= "<div class='col-md-4' style='font-weight:bold;'>";
                            $pagination =(isset($_GET['pagination']))?$_GET['pagination']:'';
                            $form.= '<label for="pagination"><input type="checkbox" style="width: 15px; height: 13px; margin-top: 5px;" value="false"'.(($pagination == "false")?"checked":"").' name="pagination" id="pagination"> No Pagination</label>';
                            $form.= "</div>";
                        }

                        $form.= "<div class='col-lg-4'>";
                            $params = "";
                            foreach($config['submit']['params'] as $key=>$value)
                            {
                                $params.=" ".$key."='".$value."'";
                            }
                            $form.= '<input type="submit" '.$params.' style="width: 100%; font-weight: bold; height: 30px;">';
                        $form.= "</div>";

                    $form.= "</div>";
                $form .= "</div>";
            $form .= "</div>";
        $form .= "</form>";

        return $form;
    }




    public function makeOptions($records, $value, $text)
    {
        $result = ['all'=>'All Of Them'];
        foreach($records as $record)
        {
            $result[$record->$value] = $record->$text;
        }

        return $result;

    }





    /*         A config sample          */

//    public $config = [
//        'heading'=>'Search Trips',
//        'params'=>[
//            'name'=>'myForm',
//            'action' => '',
//            'method' => 'get',
//            'id' => '',
//            'class' => ''
//        ],
//        'fieldSets' => [
//            [
//                'legend'=>[
//                    'text'=>'',
//                    'params' => [],
//                ],
//                'fields'=>[
//                    [
//                        'label'=>[
//                            'text'=>'Trip#',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //input box syntax
//                        'input'=>[
//                            'type'=>'number',
//                            'value' => '_get',
//                            'placeholder'=>'',
//                            'db'=>[
//                                'name'=>'trip_id',
//                                'comparison_operator'=>'=',
//                            ],
//                            'params'=>[
//                                'name' => 'trip_id',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'trip_id',
//                            ],
//                        ]
//                    ],
//
//                    [
//                        'label'=>[
//                            'text'=>'Product',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //select box syntax
//                        'input'=>[
//                            'type'=>'select',
//                            'options' => 'products',
//                            'selected' => '_get',
//                            'db'=>[
//                                'name'=>'product_id',
//                                'comparison_operator'=>'=',
//                            ],
//                            'params'=>[
//                                'name' => 'product_id',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'product',
//                            ],
//                        ]
//                    ],
//
//                    [
//                        'label'=>[
//                            'text'=>'From',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //input box syntax
//                        'input'=>[
//                            'type'=>'date',
//                            'value' => '_get',
//                            'placeholder'=>'',
//                            'db'=>[
//                                'name'=>'trip_entry_date',
//                                'comparison_operator'=>'>',
//                            ],
//                            'params'=>[
//                                'name' => 'from',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'trip_date',
//                            ],
//                        ]
//                    ],
//                    [
//                        'label'=>[
//                            'text'=>'To',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //input box syntax
//                        'input'=>[
//                            'type'=>'date',
//                            'value' => '_get',
//                            'placeholder'=>'',
//                            'db'=>[
//                                'name'=>'trip_entry_date',
//                                'comparison_operator'=>'<',
//                            ],
//                            'params'=>[
//                                'name' => 'to',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'trip_date',
//                            ],
//                        ]
//                    ],
//
//                    [
//                        'label'=>[
//                            'text'=>'Source',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //select box syntax
//                        'input'=>[
//                            'type'=>'select',
//                            'options' => 'cities',
//                            'selected' => '_get',
//                            'db'=>[
//                                'name'=>'source_id',
//                                'comparison_operator'=>'=',
//                            ],
//                            'params'=>[
//                                'name' => 'source_id',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'destination',
//                            ],
//                        ]
//                    ],
//
//                    [
//                        'label'=>[
//                            'text'=>'Destination',
//                            'params'=>[
//                                'class'=>'label'
//                            ],
//                        ],
//                        //select box syntax
//                        'input'=>[
//                            'type'=>'select',
//                            'options' => 'cities',
//                            'selected' => '_get',
//                            'db'=> [
//                                'name'=>'destination_id',
//                                'comparison_operator' => '=',
//                            ],
//                            'params'=>[
//                                'name' => 'destination_id',
//                                'class'=>'form-control', // bootstrap css class
//                                'id' => 'destination',
//                            ],
//                        ]
//                    ],
//
//                ],
//            ],
//        ],
//        'submit'=>[
//            'params'=>[
//                'name'=>'searchTrips',
//                'class' => "btn btn-primary",
//                'id'=>'',
//                'value'=>'Search Trips'
//            ]
//        ],
//        'pagination'=>false,
//    ];
} 