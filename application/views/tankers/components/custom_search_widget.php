<div id="custom-search-popup" class="custom-search-popup mfp-hide">

    <style>
        .custom_accounts_inputs select{
            height: 30px;
            width: 100%;
            font-size:12px;
        }
        .custom_accounts_inputs input{
            height: 30px;
            width: 100%;
            font-size: ;
        }
        .custom_accounts_inputs .lable{
            color: gray;
            font-weight: bold;
        }
        .custom_accounts_inputs fieldset{
            margin-top: 10px;
        }
        .custom_accounts_inputs table tr{
            border-top: 2px solid white;
        }
    </style>

    <?php
    include_once(APPPATH."serviceProviders/Forms/tankers/Tankers_Search_Form.php");
    
        $form = new Tankers_Search_Form($cities, $products, $customers, $all_tankers);
        $form = $form->Create();
        echo $form;
    ?>

</div>