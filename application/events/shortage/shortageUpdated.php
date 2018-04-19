<?php
/**
 * Created by PhpStorm.
 * User: noman_2
 * Date: 2/8/2016
 * Time: 7:05 AM
 */

class ShortageUpdated extends Event implements EventsInterface
{
    public $shortageId;
    public $shortage;

    public function __construct($shortageId = 0){
        parent::__construct();
        $this->shortageId = $shortageId;
        $this->setShortage($this->fetchShortage());
    }

    public function fire(){
        //$this->updateShortageVouchers();
        $this->updateFreightOnShortageVouchers();
        return true;
    }

    public function updateFreightOnShortageVouchers(){
        $shortage = $this->getShortage();
        $freightOnShortageVoucherId = $this->getFreightOnShortageVoucherId();

        $this->db->trans_start();
        //update voucher_journal
        $this->db->where('id',$freightOnShortageVoucherId);
        $voucher_jouranl_updated = $this->db->update('voucher_journal',[ 'voucher_date'=>$shortage->date ]);

        //update debit entries
        $this->db->where('journal_voucher_id',$freightOnShortageVoucherId);
        $this->db->where('dr_cr', 1);
        $debit_entry_updated = $this->db->update('voucher_entry',[ 'debit_amount'=>round($shortage->quantity * $shortage->rate, 2) ]);
        //update credit entries
        $this->db->where('journal_voucher_id',$freightOnShortageVoucherId);
        $this->db->where('dr_cr', 0);
        $credit_entry_updated = $this->db->update('voucher_entry',[ 'credit_amount'=>round($shortage->quantity * $shortage->rate, 2) ]);
        return $this->db->trans_complete();
    }

    public function updateShortageVouchers(){
        //
    }

    public function getFreightOnShortageVoucherId(){
        $shortage = $this->getShortage();
        $voucher_type = ($shortage->type == 1)?'dest_freight_on_shortage':'decnd_freight_on_shortage';
        $trip_detail_id = $shortage->trip_detail_id;

        // get vouchers
        $this->db->select('id');
        $this->db->where('voucher_type',$voucher_type);
        $this->db->where('trip_product_detail_id',$trip_detail_id);
        $this->db->where('active', 1);
        $result = $this->db->get('voucher_journal')->result();
        $voucher_id = (sizeof($result) > 0)?property_to_array('id',$result)[0]:0;
        return $voucher_id;

        //$vouchers = $this->accounts_model->journal("users", "1", $voucher_ids, "", "");

    }

    public function fetchShortage(){
        $this->db->select('*');
        $this->db->where('id',$this->shortageId);
        $result = $this->db->get('shortages')->result();
        return (sizeof($result) > 0)?$result[0]:null;
    }

    public function setShortage($shortage){
        $this->shortage = $shortage;
    }
    public function getShortage(){
        return $this->shortage;
    }
}