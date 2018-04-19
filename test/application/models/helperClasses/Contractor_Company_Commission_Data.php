<?php
/**
 * Created by PhpStorm.
 * User: Zeeshan Tufail
 * Date: 10/27/14
 * Time: 5:43 AM
 */


class Contractor_Company_Commission_Data {

    public $contractorName;
    public $companyName;
    public $contractorId;
    public $companyId;
    public $commission_1;
    public $commission_2;
    public $commission_3;
    public $id;
    public $ci;

    function Contractor_Company_Commission_Data($commission){
        $this->ci =& get_instance();

        $this->set_data($commission);

    }

    private function  set_data($commission){
        $this->id = $commission->id;
        $this->commission_1 = $commission->commission_1;
        $this->commission_2 = $commission->commission_2;
        $this->commission_3 = $commission->commission_3;
        $company = $this->ci->companies_model->company($commission->company_id);
        $this->companyName = $company->name;
        $contractor = $this->ci->carriageContractors_model->carriageContractor($commission->contractor_id);
        $this->contractorName = $contractor->name;
        $this->id = $commission->id;
        $this->contractorId = $commission->contractor_id;
        $this->companyId = $commission->company_id;
    }

} 