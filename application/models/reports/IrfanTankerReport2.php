<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 4/8/2016
 * Time: 7:28 PM
 */


class IrfanTankerReport2 {
    private $ci;
    private $db;
    private $rawReport;
    public $reportGroupedByRoute;
    private $tankerOtherExpenseReport;
    private $routes;
    private $tankers;
    private $tanker_fuel;
    private $given_account_ids = [];
    private $from;
    private $to;
    public function __construct($tankerIds, $from, $to, $account_ids)
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
        $this->from = $from;
        $this->to = $to;
        $this->given_account_ids = $account_ids;

        $this->rawReport = $this->generateReport($tankerIds, $from, $to, $account_ids);
        $this->reportGroupedByRoute = $this->groupRawReportByRoute($this->rawReport);
        $this->tankers = $this->fetchTankers($tankerIds);

        $this->tankerOtherExpenseReport = $this->generateOtherExpenseReport($tankerIds, $from, $to, $account_ids);
    }

    public function totalTripsExpenseOfTanker($tankerId)
    {
        $total_expense = 0;
        foreach($this->reportGroupedByRoute as $route_key => $records)
        {
            $total_expense+=$this->expense_by_title($route_key, $tankerId, 0);
        }
        return $total_expense;
    }
    public function getTankerOtherExpense($tankerId)
    {
        return (isset($this->tankerOtherExpenseReport[$tankerId]))?$this->tankerOtherExpenseReport[$tankerId][0]->amount:0;
    }

    public function tanker_expense_titles($tanker_id)
    {
        if(sizeof($this->given_account_ids) == 0)
            return [0=>'exp'];

        $result = Arrays::groupBy($this->rawReport, Functions::extractField('tanker_id'));
        $result = (isset($result[$tanker_id]))?$result[$tanker_id]: [];
        $result = Arrays::groupBy($result, Functions::extractField('account_title_id'));
        $expense_titles = [];
        foreach($result as $key => $records)
        {
            $expense_titles[$key] = $records[0]->title;
        }
        return $expense_titles;
    }

    public function short_title($title)
    {
        $title_parts = explode(' ',$title);
        if(sizeof($title_parts) == 0)
            return $title[0];

        $final_title = "";
        foreach($title_parts as $part){
            $final_title.= (isset($part[0]))?$part[0]:'';
        }
        return $final_title;
    }

    public function getTankers()
    {
        return $this->tankers;
    }

    public function fetchTankers($tankerIds)
    {
        $tankers = [];
        $this->db->select('*');
        $this->db->where_in('id', $tankerIds);
        $result = $this->db->get('tankers')->result();

        foreach($result as $record)
        {
            $tankers[$record->id] = $record->truck_number;
        }

        return $tankers;
    }

    public function getTankerNumber($tankerId)
    {
        return $this->tankers[$tankerId];
    }
    public function getRouteTextByIds($routeIds)
    {
        $record = $this->reportGroupedByRoute[$routeIds][0];
        return $record->source." to ".$record->destination;
    }

    public function tanker_by_route($route, $tanker)
    {
        if(!isset($this->reportGroupedByRoute[$route]))
            return [];

        $route_report = $this->reportGroupedByRoute[$route];

        $grouped_by_tanker = Arrays::groupBy($route_report, Functions::extractField('tanker_id'));

        return (isset($grouped_by_tanker[$tanker]))?$grouped_by_tanker[$tanker]:[];
    }

    public function count_trips_of_tanker_by_route($route, $tanker)
    {
        $result = $this->tanker_by_route($route, $tanker);
        return sizeof($this->groupTankerRecordsByTripId($result));
    }

    public function groupTankerRecordsByTripId($tankerRecords)
    {
        return $grouped = Arrays::groupBy($tankerRecords, Functions::extractField('trip_id'));
    }

    public function expense_by_title($route, $tanker, $expense_title_id)
    {
        $result = $this->tanker_by_route($route, $tanker);
        $expense = 0;
        foreach($result as $record)
        {
            if($expense_title_id != 0){
                if($record->account_title_id == $expense_title_id){
                    $expense += $record->amount;
                }
            }else{
                $expense+= $record->amount;
            }
        }
        return $expense;
    }

    public function fuel_consumed($route, $tanker)
    {
        $result = $this->tanker_by_route($route, $tanker);
        $groupedByTripId = $this->groupTankerRecordsByTripId($result);
        $fuel = 0;
        foreach($groupedByTripId as $records)
        {
            $fuel += $records[0]->fuel_consumed;
        }
        return $fuel;
    }

    private function groupRawReportByRoute($report)
    {
        //adding extra_column
        foreach($report as &$record)
        {
            $record->route_product_key = $record->source_id."_".$record->destination_id."_".$record->product_id;
        }
        // grouping by route_product_key and triming
        return $this->trimm_expense_report(Arrays::groupBy($report, Functions::extractField('route_product_key')));
    }

    /**
     * ITS WRONG WRONG WRONG ((((((((((((((((((((((((((((((((((((((((((((((((((
     */
    private function oldGenerateReport($tankerIds, $from, $to)
    {
        $this->db->select('
                trips.id as trip_id, tankers.id as tanker_id, trips.fuel_consumed,
                      tankers.truck_number as tanker_number,source_city.cityName as source,
                      source_city.id as source_id,destination_city.cityName as destination,
                       destination_city.id as destination_id, products.productName as product,
                        products.id as product_id, account_titles.title as title,
                        account_titles.id as account_title_id,
                         sum(voucher_entry.debit_amount) as amount
        ');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','left');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','left');
        $this->db->join('products','products.id = trips_details.product','left');

        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        $this->db->join('voucher_journal','voucher_journal.trip_id = trips_details.trip_id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        $this->db->where_in('trips.tanker_id', $tankerIds);
        $this->db->where('trips.entryDate >=',$from);
        $this->db->where('trips.entryDate <=',$to);

        $this->db->where('account_titles.secondary_type', 'other_expense');
        $this->db->where('voucher_journal.active',1);
        $this->db->where('trips.active',1);
        $this->db->order_by('voucher_journal.trip_id');
        $this->db->group_by('trips_details.trip_id, trips_details.source,
                             trips_details.destination, trips_details.product,
                              voucher_entry.account_title_id');
        $result = $this->db->get('trips')->result();

        return $result;
    }

    private function sortByTripId($trip1, $trip2)
    {
        if($trip1->trip_id == $trip2->trip_id)
            return 0;
        return ($trip1->trip_id > $trip2->trip_id)?-1:1;
    }
    private function generateReport($tankerIds, $from, $to, $account_ids)
    {
        $trips_area = $this->fetch_trips_area($tankerIds, $from, $to);

        $result = Arrays::groupBy($trips_area, Functions::extractField('tanker_id'));

        $tankersGroupedByTripId = [];
        foreach($result as $tankerId => $trips){
            $tankersGroupedByTripId[$tankerId] = Arrays::groupBy($trips, Functions::extractField('trip_id'));
        }

        $vouchers_area = $this->fetch_voucher_area($tankerIds, $from, $to, $account_ids);
        $expense_grouped_by_trip = Arrays::groupBy($vouchers_area, Functions::extractField('trip_id'));

        $final_report = [];
        $counter = 0;
        foreach($tankersGroupedByTripId as $tankerId => $trips){
            foreach($trips as $tripId => $tripDetails){
                foreach($tripDetails as $detail){
                    if(isset($expense_grouped_by_trip[$detail->trip_id])){
                        foreach($expense_grouped_by_trip[$detail->trip_id] as $expense){
                            $expense->fuel_consumed = $detail->fuel_consumed;
                            $expense->tanker_number = $detail->tanker_number;
                            $expense->source = $detail->source;
                            $expense->source_id = $detail->source_id;
                            $expense->destination = $detail->destination;
                            $expense->destination_id = $detail->destination_id;
                            $expense->product = $detail->product;
                            $expense->product_id = $detail->product_id;

                            $final_report[] = clone($expense);
                            $counter++;
                        }
                    }
                }
            }
        }

        return $final_report;
    }

    private function fetch_trips_area($tankerIds, $from, $to)
    {
        $this->db->select('
                trips.id as trip_id, tankers.id as tanker_id, trips.fuel_consumed,
                      tankers.truck_number as tanker_number,source_city.cityName as source,
                      source_city.id as source_id,destination_city.cityName as destination,
                       destination_city.id as destination_id, products.productName as product,
                        products.id as product_id
        ');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('cities as source_city','source_city.id = trips_details.source','left');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','left');
        $this->db->join('products','products.id = trips_details.product','left');

        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        $this->db->where_in('trips.tanker_id', $tankerIds);
        $this->db->where('trips.entryDate >=',$from);
        $this->db->where('trips.entryDate <=',$to);

        $this->db->where('trips.active',1);
        $this->db->group_by('
                            trips_details.trip_id, trips_details.source,
                            trips_details.destination, trips_details.product
                           ');
        $result = $this->db->get('trips')->result();
        return $result;
    }

    private function fetch_voucher_area($tankerIds, $from, $to, $account_ids)
    {
        $this->db->select('
                trips.id as trip_id, trips.tanker_id as tanker_id,
                account_titles.title as title,
                account_titles.id as account_title_id,
                sum(voucher_entry.debit_amount) as amount
        ');
        $this->db->join('voucher_journal','voucher_journal.trip_id = trips.id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        if(sizeof($account_ids) > 0)
            $this->db->where_in('voucher_entry.account_title_id', $account_ids);

        $this->db->where_in('trips.tanker_id', $tankerIds);
        $this->db->where('trips.entryDate >=',$from);
        $this->db->where('trips.entryDate <=',$to);

        $this->db->where('account_titles.secondary_type', 'other_expense');
        $this->db->where('voucher_journal.active',1);
        $this->db->where('trips.active',1);
        $this->db->group_by('voucher_journal.trip_id, voucher_entry.account_title_id');
        $result = $this->db->get('trips')->result();

        return $result;
    }

    public function generateOtherExpenseReport($tankerIds, $from, $to, $account_ids)
    {
        $this->db->select('voucher_journal.tanker_id as tanker_id, sum(voucher_entry.debit_amount) as amount');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        $this->db->where_in('voucher_journal.tanker_id', $tankerIds);
        $this->db->where('voucher_journal.voucher_date >=',$from);
        $this->db->where('voucher_journal.voucher_date <=',$to);

        if(sizeof($account_ids) > 0)
            $this->db->where_in('voucher_entry.account_title_id', $account_ids);

        $this->db->where('voucher_journal.trip_id', 0);
        $this->db->where('account_titles.secondary_type', 'other_expense');
        $this->db->where('voucher_journal.active',1);
        $this->db->group_by('voucher_journal.tanker_id');
        $result = $this->db->get('voucher_journal')->result();
        $result = Arrays::groupBy($result, Functions::extractField('tanker_id'));
        return $result;
    }

    private function trimm_expense_report($report)
    {
        $trimmed_report = [];
        foreach($report as $key => $records)
        {
            $key_parts = explode('_',$key);
            $new_key = $key_parts[0].'_'.$key_parts[1];

            if(!isset($trimmed_report[$new_key]))
                $trimmed_report[$new_key] = $records;
        }
        return $trimmed_report;
    }

} 