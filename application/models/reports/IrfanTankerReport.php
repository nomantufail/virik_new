<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 4/8/2016
 * Time: 7:28 PM
 */


class IrfanTankerReport {
    private $ci;
    private $db;
    private $tankerId;
    private $trips_expenses;
    private $from;
    private $to;
    public function __construct($tankerId, $from, $to)
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
        $this->tankerId = $tankerId;
        $this->from = $from;
        $this->to = $to;
    }



    function tanker_expense_titles()
    {
        $this->db->select('account_titles.title');
        $this->db->distinct();
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        $this->db->where('voucher_journal.tanker_id', $this->tankerId);
        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('account_titles.secondary_type', 'other_expense');
        $result = $this->db->get('voucher_journal')->result();
        var_dump($result);
    }

    function tanker_trips_income()
    {
        $this->db->select('
             trips.id as trip_id, source_city.cityName as source,
             destination_city.cityName as destination,
             products.productName as product,
             sum((trips_details.product_quantity * company_freight_unit)) as income,
             trips.entryDate as tripDate
        ');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');

        $this->db->where('trips.tanker_id', $this->tankerId);
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);

        $this->db->join('cities as source_city','source_city.id = trips_details.source','left');
        $this->db->join('cities as destination_city','destination_city.id = trips_details.destination','left');
        $this->db->join('products','products.id = trips_details.product','left');
        $this->db->where('trips.active',1);
        $this->db->order_by('trips.id');
        $this->db->group_by('trips.id, trips_details.product, trips_details.source, trips_details.destination');
        $result = $this->db->get('trips')->result();
        return Arrays::groupBy($result, Functions::extractField('trip_id'), 'trip_id');
    }

    function tanker_trips_expenses()
    {
        $this->db->select('voucher_journal.trip_id as trip_id, sum(voucher_entry.debit_amount) as amount, account_titles.title as title');
        $this->db->join('voucher_journal','voucher_journal.trip_id = trips.id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        $this->db->where('voucher_journal.tanker_id', $this->tankerId);
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);

        $this->db->where('account_titles.secondary_type', 'other_expense');
        $this->db->where('voucher_journal.trip_id !=', 0);
        $this->db->where('voucher_journal.active',1);
        $this->db->where('trips.active',1);
        $this->db->order_by('voucher_journal.trip_id');
        $this->db->group_by('voucher_journal.trip_id, voucher_entry.account_title_id');
        $result = $this->db->get('trips')->result();
        return Arrays::groupBy($result, Functions::extractField('trip_id'), 'trip_id');
    }

    function tanker_other_expenses()
    {
        $this->db->select('voucher_journal.id, sum(voucher_entry.debit_amount) as amount, account_titles.title as title');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');
        $this->db->where('voucher_journal.tanker_id', $this->tankerId);
        $this->db->where('account_titles.secondary_type', 'other_expense');
        $this->db->where([
            'voucher_journal.trip_id'=> 0,
            'voucher_journal.trip_product_detail_id'=> 0
        ]);

        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('voucher_journal.active',1);
        $this->db->group_by('voucher_entry.account_title_id');
        $result = $this->db->get('voucher_journal')->result();
        return $result;
    }

    public function sortByExpenses($trip1, $trip2)
    {
        if (sizeof($trip1)==sizeof($trip2)) return 0;
        return (sizeof($trip1)<sizeof($trip2))?1:-1;
    }
    public function sortedTrips($income, $expenses)
    {
        usort($expenses, [$this,'sortByExpenses']);
        $sorted_trip_ids = [];
        foreach($expenses as $expense)
            $sorted_trip_ids[] = $expense[0]->trip_id;

        $final_trips = [];
        foreach($sorted_trip_ids as $tripId)
        {
            $final_trips[$tripId] = $income[$tripId];
            unset($income[$tripId]);
        }
        foreach($income as $tripId => $record)
        {
            $final_trips[$tripId] = $record;
        }
        return $final_trips;
    }

    public function report()
    {
        $trips_expenses = $this->tanker_trips_expenses($this->tankerId);
        $income = $this->tanker_trips_income();
        $sortedIncomeByExpenses = $this->sortedTrips($income, $trips_expenses);
        return [
            'trips_expenses' =>  $trips_expenses,
            'income' => $sortedIncomeByExpenses,
            'other_expenses' => $this->tanker_other_expenses(),
        ];
    }
} 