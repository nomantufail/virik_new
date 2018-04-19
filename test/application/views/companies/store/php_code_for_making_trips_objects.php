$previous_trip_id = -1;
$previous_trip_product_detail_id = -1;
/*$previous_customer_id = -1;
$previous_contractor_id = -1;
$previous_company_id = -1;
$previous_driver_1_id = -1;
$previous_driver_2_id = -1;
$previous_driver_3_id = -1;*/
$previous_customer_account_id = -1;
$previous_contractor_account_id = -1;
$previous_company_account_id = -1;

$previous_trip_obj = new Trip();
$temp_trip = new Trip();
$previous_trip_product_detail_obj = new Trip_Product_Detail();
$temp_trip_product_detail = new Trip_Product_Detail();
$temp_customer_account = new Customer_Account();

$count = 0;
foreach($rawTrips as $record){
$count++;

//setting the parent details
if($record->trip_id != $previous_trip_id)
{
if($previous_trip_id != -1){
//echo $temp_trip->trip_id." <br>";
array_push($final_trips_array, $temp_trip);
}

$previous_trip_id = $record->trip_id;

//$previous_trip_obj = $temp_trip;
$temp_trip = new trip();

//setting data in the parent object
$temp_trip->trip_id = $record->trip_id;
$temp_trip->customer = new Customer($record->customer_id, $record->customerName, (100 - $record->contractor_commission) );
$temp_trip->contractor = new Contractor($record->contractor_id, $record->contractorName, $record->contractor_commission);
$temp_trip->company = new Company($record->company_id, $record->companyName, $record->company_commission_1, $record->wht);
$temp_trip->driver_1 = new Driver($record->driver_id_1, $record->driver_1_name);
$temp_trip->driver_2 = new Driver($record->driver_id_2, $record->driver_2_name);
$temp_trip->driver_3 = new Driver($record->driver_id_3, $record->driver_3_name);

$temp_trip->tanker = new Tanker($record->tanker_id, $record->tanker_number);

//setting trip dates
$temp_trip->dates = new TripDates(
$record->email_date,
$record->filling_date,
$record->receiving_date,
$record->stn_receiving_date,
$record->decanding_date,
$record->invoice_date,
$record->entryDate
);

$temp_trip->invoice_number = $record->invoice_number;

}/////////////////////////////////////////////////

/////////////////////////////////////////////////
if($record->trips_details_id != $previous_trip_product_detail_id)
{
if($previous_trip_product_detail_id != -1){
//echo $temp_trip->trip_id." <br>";
array_push($temp_trip->trip_related_details, $temp_trip_product_detail);
}

// echo $temp_trip->trip_id." <br>";
$previous_trip_product_detail_id = $record->trips_details_id;
// echo $temp_trip->trip_id." ".$previous_trip_product_detail_id."<br>";

//$previous_trip_product_detail_obj = $temp_trip_product_detail;
$temp_trip_product_detail = new Trip_Product_Detail();

//setting data in the Trip_Product_Data object
$temp_trip_product_detail->product_detail_id = $record->trips_details_id;
$temp_trip_product_detail->product = new Product($record->productId, $record->productName);
$temp_trip_product_detail->source = new City($record->source_id, $record->sourceCityName);
$temp_trip_product_detail->destination = new City($record->destination_id, $record->destinationCityName);

$temp_trip_product_detail->product_quantity = $record->product_quantity;
$temp_trip_product_detail->quantity_at_destination = $record->qty_at_destination;
$temp_trip_product_detail->quantity_after_decanding = $record->qty_after_decanding;
$temp_trip_product_detail->price_unit = $record->price_unit;
$temp_trip_product_detail->customer_freight_unit = $record->freight_unit;
$temp_trip_product_detail->company_freight_unit = $record->company_freight_unit;
$temp_trip_product_detail->stn_number = $record->stn_number;
}/////////////////////////////////////////////////

////////************Setting Customer Accounts**********////////////
if($record->customer_account_id != $previous_customer_account_id)
{
if($previous_customer_account_id != -1){
array_push($temp_trip_product_detail->customer_accounts, $temp_customer_account);
}

$previous_customer_account_id = $record->customer_account_id;
$temp_customer_account = new Customer_Account();

//setting data in the parent object
$temp_customer_account->account_id = $record->customer_account_id;
$temp_customer_account->amount_paid = $record->paid_to_customer;
$temp_customer_account->payment_date = $record->payment_to_customer_date;
}/////////////////////////////////////////////////

////////************Setting Contractor Accounts**********////////////
if($record->contractor_account_id != $previous_contractor_account_id)
{
if($previous_contractor_account_id != -1){
array_push($temp_trip_product_detail->contractor_accounts, $temp_contractor_account);
}

$previous_contractor_account_id = $record->contractor_account_id;
$temp_contractor_account = new Contractor_Account();

//setting data in the parent object
$temp_contractor_account->account_id = $record->contractor_account_id;
$temp_contractor_account->amount_paid = $record->paid_to_contractor;
$temp_contractor_account->payment_date = $record->payment_to_contractor_date;
}/////////////////////////////////////////////////

////////************Setting Company Accounts**********////////////
if($record->company_account_id != $previous_company_account_id)
{
if($previous_company_account_id != -1){
array_push($temp_trip_product_detail->company_accounts, $temp_company_account);
}

$previous_company_account_id = $record->company_account_id;
$temp_company_account = new Company_Account();

//setting data in the parent object
$temp_company_account->account_id = $record->company_account_id;
$temp_company_account->amount_paid = $record->paid_to_company;
$temp_company_account->payment_date = $record->payment_to_company_date;
}/////////////////////////////////////////////////



//checking if the record is final
if($count == sizeof($rawTrips))
{
//$temp_trip->customer = $temp_customer;
array_push($previous_trip_product_detail_obj->customer_accounts, $temp_customer_account);
array_push($previous_trip_product_detail_obj->company_accounts, $temp_company_account);
array_push($previous_trip_product_detail_obj->contractor_accounts, $temp_contractor_account);
array_push($previous_trip_obj->trip_related_details, $temp_trip_product_detail);
array_push($final_trips_array, $temp_trip);
}
}