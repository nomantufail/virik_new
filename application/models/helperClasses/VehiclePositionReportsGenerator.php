<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 3/13/2016
 * Time: 7:55 AM
 */

namespace App\models\helperClasses;


class VehiclePositionReportsGenerator {
    private $from;
    private $to;
    private $trip_types;
    private $db;
    public function __construct($from, $to, $trip_types){
        $this->from = $from;
        $this->to = $to;
        $this->trip_types = $trip_types;

        $this->db = & get_instance()->db;
    }

    public function generate(){
        $reports = [];
        foreach($this->trip_types as $trip_type) {
            $report = [];
            $income_details = $this->fetch_income_details($trip_type);
            $shortage_details = $this->fetch_shortage_expense($trip_type);
            $installment_fees = $this->fetch_installment_expense($trip_type);
            $salaries = $this->fetch_salary_expense($trip_type);
            $meter_info = $this->fetch_meter_info($trip_type);
            $repair_maintainence = $this->fetch_repair_maintainence_expense($trip_type);
            $all_other_expenses_including_trips = $this->fetch_other_expenses_including_trip_expenses($trip_type);
            $trip_expenses = $this->fetch_trip_expenses($trip_type);

            foreach($meter_info as $vehicle => $record){
                $report[] = new VehiclePosition($vehicle,[
                    'income'=>(isset($income_details[$vehicle]))?$income_details[$vehicle]:null,
                    'shortage'=>(isset($shortage_details[$vehicle]))?$shortage_details[$vehicle]:null,
                    'installment'=>(isset($installment_fees[$vehicle]))?$installment_fees[$vehicle]:null,
                    'salary'=>(isset($salaries[$vehicle]))?$salaries[$vehicle]:null,
                    'meter'=>(isset($meter_info[$vehicle]))?$meter_info[$vehicle]:null,
                    'repair_maintainence'=>(isset($repair_maintainence[$vehicle]))?$repair_maintainence[$vehicle]:null,
                    'trip_expenses'=>(isset($trip_expenses[$vehicle]))?$trip_expenses[$vehicle]:null,
                    'all_other_expense_including_tips' => (isset($all_other_expenses_including_trips[$vehicle]))?$all_other_expenses_including_trips[$vehicle]:null,
                ]);
            }
            $reports[$trip_type] = $report;
        }

        return $reports;
    }

    public function fetch_income_details($trip_type){
        $this->db->select("COUNT(trips.id) as num_of_trips, tankers.truck_number as vehicle_number,
            SUM(trips_details.product_quantity * trips_details.freight_unit) as total_income
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);
        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $result = $this->db->get()->result();
        $tankers_income = [];
        foreach($result as $record){
            $tankers_income[$record->vehicle_number] = $record;
        }
        return $tankers_income;
    }

    public function fetch_shortage_expense($trip_type){
        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(shortages.quantity) as shortage_dip,
            SUM(shortages.quantity * shortages.rate) as shortage_amount
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');
        $this->db->join('shortages','shortages.trip_detail_id = trips_details.id','left');
        $this->db->join('voucher_journal as shrt_vj','shrt_vj.shortage_id = shortages.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);
        $this->db->where('shrt_vj.ignored','0');
        $this->db->where('trips.active','1');
        $this->db->where('shrt_vj.active','1');

        $this->db->group_by('tankers.id');
        $result = $this->db->get()->result();

        $tankers_shortage = [];
        foreach($result as $record){
            $tankers_shortage[$record->vehicle_number] = $record;
        }

        return $tankers_shortage;
    }

    public function fetch_installment_expense($trip_type){
        $this->db->select("
            tankers.truck_number as vehicle_number, trips.tanker_id as tanker_id
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);

        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $tankers_result = $this->db->get()->result();
        $tanker_sockets = [];
        foreach($tankers_result as $record){
            $tanker_sockets[$record->vehicle_number] = 0;
        }



        $tanker_ids = property_to_array('tanker_id',$tankers_result);
        $tanker_ids = (sizeof($tanker_ids) > 0)?$tanker_ids:[0];
        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(voucher_entry.debit_amount) as installment_expense
        ");

        $this->db->from('voucher_journal');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('voucher_entry.account_title_id','32');
        $this->db->where_in('voucher_journal.tanker_id',$tanker_ids);
        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('voucher_journal.ignored','0');
        $this->db->where('voucher_journal.active','1');

        $this->db->group_by('voucher_journal.tanker_id');
        $result = $this->db->get()->result();
        foreach($result as $record){
            $tanker_sockets[$record->vehicle_number] = round($record->installment_expense, 2);
        }

        $tankers_installment_fee = [];
        foreach($tanker_sockets as $key => $value){
            if($value > 0){
                $tankers_installment_fee[$key] = $value;
            }
        }

        return $tankers_installment_fee;
    }
    public function fetch_salary_expense($trip_type){
        $this->db->select("
            tankers.truck_number as vehicle_number, trips.tanker_id as tanker_id
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);

        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $tankers_result = $this->db->get()->result();
        $tanker_sockets = [];
        foreach($tankers_result as $record){
            $tanker_sockets[$record->vehicle_number] = 0;
        }



        $tanker_ids = property_to_array('tanker_id',$tankers_result);
        $tanker_ids = (sizeof($tanker_ids) > 0)?$tanker_ids:[0];
        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(voucher_entry.debit_amount) as installment_expense
        ");

        $this->db->from('voucher_journal');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('voucher_entry.account_title_id','41');
        $this->db->where_in('voucher_journal.tanker_id',$tanker_ids);
        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('voucher_journal.ignored','0');
        $this->db->where('voucher_journal.active','1');

        $this->db->group_by('voucher_journal.tanker_id');
        $result = $this->db->get()->result();
        foreach($result as $record){
            $tanker_sockets[$record->vehicle_number] = round($record->installment_expense, 2);
        }

        $tankers_salries = [];
        foreach($tanker_sockets as $key => $value){
            if($value > 0){
                $tankers_salries[$key] = $value;
            }
        }

        return $tankers_salries;
    }

    public function fetch_repair_maintainence_expense($trip_type){
        $this->db->select("
            tankers.truck_number as vehicle_number, trips.tanker_id as tanker_id
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);

        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $tankers_result = $this->db->get()->result();
        $tanker_sockets = [];
        foreach($tankers_result as $record){
            $tanker_sockets[$record->vehicle_number] = 0;
        }



        $tanker_ids = property_to_array('tanker_id',$tankers_result);
        $tanker_ids = (sizeof($tanker_ids) > 0)?$tanker_ids:[0];
        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(voucher_entry.debit_amount) as repair_expense
        ");

        $this->db->from('voucher_journal');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('voucher_entry.account_title_id','110');
        $this->db->where_in('voucher_journal.tanker_id',$tanker_ids);
        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('voucher_journal.ignored','0');
        $this->db->where('voucher_journal.active','1');

        $this->db->group_by('voucher_journal.tanker_id');
        $result = $this->db->get()->result();
        foreach($result as $record){
            $tanker_sockets[$record->vehicle_number] = round($record->repair_expense, 2);
        }

        $tankers_repair = [];
        foreach($tanker_sockets as $key => $value){
            if($value > 0){
                $tankers_repair[$key] = $value;
            }
        }

        return $tankers_repair;
    }

    public function fetch_other_expenses_including_trip_expenses($trip_type){
        $this->db->select("
            tankers.truck_number as vehicle_number, trips.tanker_id as tanker_id
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);

        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $tankers_result = $this->db->get()->result();
        $tanker_sockets = [];
        foreach($tankers_result as $record){
            $tanker_sockets[$record->vehicle_number] = 0;
        }



        $tanker_ids = property_to_array('tanker_id',$tankers_result);
        $tanker_ids = (sizeof($tanker_ids) > 0)?$tanker_ids:[0];
        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(voucher_entry.debit_amount) as repair_expense
        ");

        $this->db->from('voucher_journal');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = voucher_journal.tanker_id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');

        /*---- applying conditions ------------*/
        $this->db->where_not_in('voucher_entry.account_title_id',[110,41,32]);
        $this->db->where_in('voucher_journal.tanker_id',$tanker_ids);
        $this->db->where('voucher_journal.voucher_date >=',$this->from);
        $this->db->where('voucher_journal.voucher_date <=',$this->to);

        $this->db->where('voucher_journal.ignored','0');
        $this->db->where('voucher_journal.active','1');

        $this->db->group_by('voucher_journal.tanker_id');
        $result = $this->db->get()->result();
        foreach($result as $record){
            $tanker_sockets[$record->vehicle_number] = round($record->repair_expense, 2);
        }

        $all_other_expenses = [];
        foreach($tanker_sockets as $key => $value){
            if($value > 0){
                $all_other_expenses[$key] = $value;
            }
        }

        return $all_other_expenses;
    }

    public function fetch_trip_expenses($trip_type){

        $this->db->select("
            tankers.truck_number as vehicle_number,
            SUM(voucher_entry.debit_amount) as expense
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->join('voucher_journal','voucher_journal.trip_id = trips.id','left');
        $this->db->join('voucher_entry','voucher_entry.journal_voucher_id = voucher_journal.id','left');
        $this->db->join('account_titles','account_titles.id = voucher_entry.account_title_id','left');

        /*---- applying conditions ------------*/
        $this->db->where_not_in('voucher_entry.account_title_id',[110,41,32]);
        $this->db->where('account_titles.secondary_type','other_expense');
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);

        $this->db->where('voucher_journal.ignored','0');
        $this->db->where('voucher_journal.active','1');

        $this->db->group_by('trips.tanker_id');
        $result = $this->db->get()->result();
        $tanker_sockets = [];
        foreach($result as $record){
            $tanker_sockets[$record->vehicle_number] = round($record->expense, 2);
        }

        $tankers_expense = [];
        foreach($tanker_sockets as $key => $value){
            if($value > 0){
                $tankers_expense[$key] = $value;
            }
        }

        return $tankers_expense;
    }

    public function fetch_meter_info($trip_type){
        $this->db->select("COUNT(trips.id) as num_of_trips, tankers.truck_number as vehicle_number,
            SUM(trips.end_meter - trips.start_meter) as total_kilometers, SUM(trips.fuel_consumed) as total_fuel,
            tankers.capacity as capacity
        ");

        $this->db->from('trips');

        /*------- applying joins ----------*/
        $this->db->join('tankers','tankers.id = trips.tanker_id','left');
        $this->db->join('trips_details','trips_details.trip_id = trips.id','left');

        /*---- applying conditions ------------*/
        $this->db->where('trips.entryDate >=',$this->from);
        $this->db->where('trips.entryDate <=',$this->to);
        $this->db->where('trips.type',$trip_type);
        $this->db->where('trips.active','1');
        $this->db->group_by('trips.tanker_id');
        $result = $this->db->get()->result();
        $tankers_fuel_info = [];
        foreach($result as $record){
            $tankers_fuel_info[$record->vehicle_number] = $record;
        }

        return $tankers_fuel_info;
    }
} 