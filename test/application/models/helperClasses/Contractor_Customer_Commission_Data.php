<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Contractor_Customer_Commission_Data {

    public $contractorName;
    public $customerName;
    public $contractorId;
    public $customerId;
    public $freight_commission;
    public $id;
    public $ci;

    function Contractor_Customer_Commission_Data($commission){
        $this->ci =& get_instance();

        $this->set_data($commission);

    }

    private function  set_data($commission){
        $this->id = $commission->id;
        $this->freight_commission = $commission->freight_commission;
        $customer = $this->ci->customers_model->customer($commission->customer_id);
        $this->customerName = $customer->name;
        $contractor = $this->ci->carriageContractors_model->carriageContractor($commission->contractor_id);
        $this->contractorName = $contractor->name;
        $this->id = $commission->id;
        $this->contractorId = $commission->contractor_id;
        $this->customerId = $commission->customer_id;
    }

} 