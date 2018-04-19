<?php
/**
 * Created by PhpStorm.
 * User: noman_2
 * Date: 2/8/2016
 * Time: 7:05 AM
 */

class ShortageDeleted extends Event implements EventsInterface
{
    public $shortage;

    public function __construct($shortage = null){
        parent::__construct();
        $this->setShortage($shortage);
    }

    public function fire(){
        $this->deleteShortageVouchers();
        $this->deleteFreightOnShortageVouchers();
    }

    public function deleteFreightOnShortageVouchers(){
        $shortage = $this->getShortage();
        $this->db->where('voucher_journal.trip_product_detail_id',$shortage->trip_detail_id);
        $voucher_type = ($shortage->type == 1)?'dest_freight_on_shortage':'decnd_freight_on_shortage';
        $this->db->where('voucher_type', $voucher_type);
        $this->db->delete('voucher_journal');

        if($shortage->type != 1)
            (new FreightOnDecandingShortageVoucherDeleted($shortage->trip_detail_id))->fire();
    }

    public function deleteShortageVouchers(){
        $shortage = $this->getShortage();

        $this->db->where('voucher_journal.shortage_id',$this->getShortage()->id);
        $this->db->delete('voucher_journal');

        if($shortage->type != 1)
            (new DecandingShortageVoucherDeleted($shortage->trip_detail_id))->fire();
    }

    public function setShortage($shortage){
        $this->shortage = $shortage;
    }
    public function getShortage(){
        return $this->shortage;
    }
}