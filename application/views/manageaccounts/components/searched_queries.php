<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/5/15
 * Time: 10:05 AM
 */
?>

<!--------------------Searched Queries----------------------------->
<div class="col-lg-12" style="border-radius: 5px; border: 1px solid lightblue; background-color: lightblue;">
    <div class="col-lg-12" style="margin:10px; padding:10px;">
        <?php
        if(isset($_GET['from']) && $_GET['from'] != '')
        {
            echo"<b> From: </b>".Carbon::createFromFormat("Y-m-d",$_GET['from'])->toFormattedDateString();
        }
        if(isset($_GET['to']) && $_GET['to'] != '')
        {
            echo"<b> To: </b>".Carbon::createFromFormat("Y-m-d",$_GET['to'])->toFormattedDateString()." | ";
        }
        if(isset($_GET['trip_type']) && $_GET['trip_type'] != '')
        {
            echo"<b> Trip Type: </b>";
            switch($_GET['trip_type'])
            {
                case 1:
                    echo" Self/Mail";
                    break;
                case 2:
                    echo" General Trip";
                    break;
                case 3:
                    echo" Local Company";
                    break;
                case 4:
                    echo" Local Self";
                    break;

            }

        }
        if(isset($_GET['trip_status']) && $_GET['trip_status'] != '')
        {
            echo"<b> Trip Status: </b>";
            switch($_GET['trip_status'])
            {
                case 0:
                    echo" Closed";
                    break;
                case 1:
                    echo" Open";
                    break;
            }
        }
        if(isset($_GET['product']) && $_GET['product'] != '')
        {
            $products = $this->routes_model->products_by_ids($_GET['product']);
            echo"<b> Products</b> ";
            foreach($products as $product)
            {
                echo $product->productName.", ";
            }
        }
        if(isset($_GET['source']) && $_GET['source'] != '')
        {
            $sources = $this->routes_model->cities_by_ids($_GET['source']);
            echo"<b> Sources</b> ";
            foreach($sources as $source)
            {
                echo $source->cityName.", ";
            }
        }
        if(isset($_GET['destination']) && $_GET['destination'] != '')
        {
            $destinations = $this->routes_model->cities_by_ids($_GET['destination']);
            echo"<b> Destinations</b> ";
            foreach($destinations as $destination)
            {
                echo $destination->cityName.", ";
            }
        }
        if(isset($_GET['trips_route']) && $_GET['trips_route'] != '')
        {
            $routes = $this->routes_model->trips_routes_by_ids($_GET['trips_route']);
            echo"<b> Routes</b> ";
            foreach($routes as $route)
            {
                echo $route->source_city." To ".$route->destination_city." <b>|</b> ";
            }
        }
//        if(isset($_GET['tanker']) && $_GET['tanker'] != '')
//        {
//            echo"<b> Tanker: </b>".join(', ',$_GET['tanker']);
//        }
//        if(isset($_GET['company']) && $_GET['company'] != '')
//        {
//            $company = $this->companies_model->company($_GET['company']);
//            echo"<b> Company: </b>".$company->name;
//        }
//        if(isset($_GET['contractor']) && $_GET['contractor'] != '')
//        {
//            $contractor = $this->carriageContractors_model->carriageContractor($_GET['contractor']);
//            echo"<b> Contractor: </b>".$contractor->name;
//        }
//        if(isset($_GET['customer']) && $_GET['customer'] != '')
//        {
//            $customer = $this->customers_model->customer($_GET['customer']);
//            echo"<b> Customer: </b>".$customer->name;
//        }
        if(isset($_GET['account_title']) && $_GET['account_title'] != '')
        {
            $title = $this->accounts_model->account_title($_GET['account_title']);
            echo"<b> Account Title: </b>".$title->title;
        }
        if(isset($_GET['dr_cr']) && $_GET['dr_cr'] != '')
        {
            echo"<b> Dr/Cr: </b>";
            switch($_GET['dr_cr'])
            {
                case 1:
                    echo" Debit";
                    break;
                case 2:
                    echo" Not Debit";
                    break;
                case 0:
                    echo" Credit";
                    break;
                case 3:
                    echo" Not Credit";
                    break;
            }
        }

        if(isset($_GET['billed_from']) && $_GET['billed_from'] != '')
        {
            echo"<b> Billed From: </b>".Carbon::createFromFormat("Y-m-d",$_GET['billed_from'])->toFormattedDateString();
        }
        if(isset($_GET['billed_to']) && $_GET['billed_to'] != '')
        {
            echo"<b> Billed To: </b>".Carbon::createFromFormat("Y-m-d",$_GET['billed_to'])->toFormattedDateString()." | ";
        }
        if(isset($_GET['bill_status']) && $_GET['bill_status'] != '')
        {
            echo"<b> Bill Status: </b>";
            switch($_GET['bill_status'])
            {
                case 1:
                    echo" Billed";
                    break;
                case 0:
                    echo" Not Billed";
                    break;

            }

        }

        ?>
    </div>
</div>
<!----------------------------------------------------------------->
