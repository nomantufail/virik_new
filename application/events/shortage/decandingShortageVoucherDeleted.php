<?php
/**
 * Created by PhpStorm.
 * User: noman_2
 * Date: 2/8/2016
 * Time: 7:05 AM
 */

class DecandingShortageVoucherDeleted extends Event implements EventsInterface
{
    public $tripDetailId;
    public $shortage;

    public function __construct($tripDetailId = 0){
        parent::__construct();
        $this->tripDetailId = $tripDetailId;
    }

    public function fire(){
        $this->activateIgnoredDestinationShortageVoucher();
    }

    public function activateIgnoredDestinationShortageVoucher(){
        $this->db->where('voucher_journal.trip_product_detail_id',$this->tripDetailId);
        $this->db->where('voucher_type', 'shortage_voucher_dest');
        return $this->db->update('voucher_journal', ['ignored' => 0]);
    }
}