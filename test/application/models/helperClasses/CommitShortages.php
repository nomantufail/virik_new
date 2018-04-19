<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 7/11/2015
 * Time: 1:30 AM
 */

class CommitShortages {

    public $shortageIds = [];
    public $shortagesData = [];

    public $commitDate;
    public $creditAgentType;
    public $creditAgent;

    public $ci;
    public $db;
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->db = $this->ci->db;

        $this->setInputData();

        $this->setShortagesData();
    }

    public function setInputData()
    {

        $shortage_ids = explode('_',$_POST['shortage_ids']);
        $shortage_ids = arr_val_del(0, $shortage_ids);

        $this->setShortageIds($shortage_ids);

        $this->setCommitDate($_POST['commit_date']);
        $this->setCreditAgentType($_POST['agent_type']);
        $this->setCreditAgent($_POST['agent_id']);
    }

    public function letTheGameBegin()
    {
        $this->db->trans_start();

        $this->saveShortageVouchers();
        $this->ignoreShortageVouchers();

        $this->saveShortageAmountDeductionVouchers();
        $this->ignoreShortageAmountDeductionVouchers();

        return $this->db->trans_complete();
    }

    public function ignoreShortageAmountDeductionVouchers()
    {
        $this->ignoreShortageDeductionByDecandingData();
        $this->ignoreShortageDeductionByDestinationData();
    }

    public function ignoreShortageDeductionByDecandingData()
    {
        $decanding_trip_details = $this->getTripDetailIdsWithDecandingShortage();
        if(sizeof($decanding_trip_details) == 0)
            return;

        $where_statement = "(voucher_journal.trip_product_detail_id IN (".join(', ',$decanding_trip_details).") AND voucher_journal.voucher_type = 'dest_shortage_deduction')";
        $this->ci->helper_model->ignore_voucher_where($where_statement);
    }

    public function ignoreShortageDeductionByDestinationData()
    {
        $destinationShortageData = $this->getDestinationShortageData();
        if(sizeof($destinationShortageData) == 0)
            return;

        $trip_detail_ids = property_to_array('trip_detail_id',$destinationShortageData);
        $this->db->select('voucher_journal.trip_product_detail_id');
        $this->db->where_in('voucher_journal.trip_product_detail_id',$trip_detail_ids);
        $this->db->where('voucher_journal.voucher_type','decnd_shortage_deduction');
        $result = $this->db->get('voucher_journal')->result();

        if(sizeof($result) == 0)
            return;

        $ignorable_trip_detail_ids = property_to_array('trip_product_detail_id',$result);
        $where_statement = "(voucher_journal.trip_product_detail_id IN (".join(', ',$ignorable_trip_detail_ids).") AND voucher_journal.voucher_type = 'dest_shortage_deduction')";
        $this->ci->helper_model->ignore_voucher_where($where_statement);
    }

    public function getTripDetailIdsWithDecandingShortage()
    {
        $detail_ids = [];
        $shortageData = $this->getDecandingShortageData();
        if(sizeof($shortageData) > 0)
            $detail_ids = property_to_array('trip_detail_id',$shortageData);

        return $detail_ids;
    }

    public function getShortagesData()
    {
        return $this->shortagesData;
    }

    public function setShortagesData()
    {
        $this->ci->db->select('
             shortages.type as shortage_type, shortages.quantity, shortages.rate,
            (shortages.quantity * shortages.rate) as shortage_amount,
             trips.customer_id as customerId, shortages.id as shortage_id,
             trips_details.id as trip_detail_id, trips.id as trip_id,
             trips.tanker_id, trips.contractor_id, products.type as product_type, products.productName,
             trips.company_id,
        ');
        $this->db->join('trips_details','trips_details.id = shortages.trip_detail_id','inner');
        $this->db->join('trips','trips.id = trips_details.trip_id','inner');
        $this->db->join('products','products.id = trips_details.product','inner');
        $this->db->where_in('shortages.id',$this->getShortageIds());
        $result = $this->db->get('shortages')->result();

        $this->shortagesData = $result;
    }

    public function saveShortageVouchers()
    {
        $vouchers_array = $this->makeShortageVouchersArray();
        $this->db->insert_batch('voucher_journal',$vouchers_array);
        $entries_array = $this->makeShortageVouchersEntriesArray();
        $this->db->insert_batch('voucher_entry',$entries_array);
    }

    public function saveShortageAmountDeductionVouchers()
    {
        $vouchers_array = $this->makeShortageAmountDeductionVouchersArray();
        if(sizeof($vouchers_array) == 0)
            return;

        $this->db->insert_batch('voucher_journal',$vouchers_array);
        $entries_array = $this->makeShortageAmountDeductionVouchersEntriesArray();
        $this->db->insert_batch('voucher_entry',$entries_array);
    }

    public function makeShortageVouchersArray()
    {
        $vouchers = [];

        $voucher = [];

        $shortagesData = $this->getShortagesData();
        foreach($shortagesData as $data)
        {
            $voucher['voucher_date']  = $this->getCommitDate();
            $voucher['detail'] = 'shortage commiting';
            $voucher['person_tid'] = 'users.1';
            $voucher['trip_id'] = $data->trip_id;
            $voucher['trip_product_detail_id'] = $data->trip_detail_id;
            $voucher['tanker_id'] = $data->tanker_id;
            $voucher['voucher_type'] =(($data->shortage_type == 1)?'shortage_voucher_dest':'shortage_voucher_decnd');
            $voucher['shortage_id'] = $data->shortage_id;
            $voucher['active'] = 1;

            $vouchers[] = $voucher;
        }

        return $vouchers;
    }

    public function makeShortageVouchersEntriesArray()
    {
        $grouped_shortage_data = $this->groupShortageDataById();

        $voucher_entries = [];

        $shortageIds = $this->getShortageIds();
        $this->db->select('voucher_journal.id as voucher_id, voucher_journal.shortage_id');
        $this->db->where_in('shortage_id',$shortageIds);
        $saved_vouchers_data = $this->db->get('voucher_journal')->result();

        foreach($saved_vouchers_data as $voucher_data)
        {
            $shortageData = $grouped_shortage_data[$voucher_data->shortage_id];
            $shortageData = $shortageData[0];

            $entry = [];
            $entry['dr_cr'] = 0;
            $entry['account_title_id'] = 42;
            $entry['description'] = "Shortage_quantity => ".$shortageData->quantity." Price/Unit => ".$shortageData->rate." Product =>".$shortageData->productName."";
            $entry['related_other_agent'] = (($this->getCreditAgentType() == 'other_agents')?$this->getCreditAgent():0);
            $entry['related_customer'] = (($this->getCreditAgentType() == 'customers')?$this->getCreditAgent():0);
            $entry['related_contractor'] = (($this->getCreditAgentType() == 'carriage_contractors')?$this->getCreditAgent():0);
            $entry['related_company'] = (($this->getCreditAgentType() == 'companies')?$this->getCreditAgent():0);
            $entry['credit_amount'] = round($shortageData->shortage_amount, 3);
            $entry['debit_amount'] = 0;
            $entry['journal_voucher_id'] = $voucher_data->voucher_id;

            $voucher_entries[] = $entry;

            $entry = [];
            $entry['dr_cr'] = 1;
            $entry['account_title_id'] = (($shortageData->shortage_type == 1)?37:38);
            $entry['description'] = "Shortage_quantity => ".$shortageData->quantity." Price/Unit => ".$shortageData->rate." Product =>".$shortageData->productName."";
            $entry['related_other_agent'] = 0;
            $entry['related_customer'] = $shortageData->customerId;
            $entry['related_contractor'] = 0;
            $entry['related_company'] = 0;
            $entry['credit_amount'] = 0;
            $entry['debit_amount'] = round($shortageData->shortage_amount, 3);
            $entry['journal_voucher_id'] = $voucher_data->voucher_id;

            $voucher_entries[] = $entry;


        }

        return $voucher_entries;
    }

    public function makeShortageAmountDeductionVouchersArray()
    {
        $vouchers = [];

        $voucher = [];

        $shortagesData = $this->getBlackOilShortageData();
        if(sizeof($shortagesData) == 0)
            return [];

        foreach($shortagesData as $data)
        {
            $voucher['voucher_date']  = $this->getCommitDate();
            $voucher['detail'] = 'Shortage deduction voucher for black oil';
            $voucher['person_tid'] = 'users.1';
            $voucher['trip_id'] = $data->trip_id;
            $voucher['trip_product_detail_id'] = $data->trip_detail_id;
            $voucher['tanker_id'] = $data->tanker_id;
            $voucher['voucher_type'] =(($data->shortage_type == 1)?'dest_shortage_deduction':'decnd_shortage_deduction');
            $voucher['active'] = 1;

            $vouchers[] = $voucher;
        }

        return $vouchers;
    }

    public function makeShortageAmountDeductionVouchersEntriesArray()
    {

        $grouped_shortage_data = $this->groupShortageDataByTripDetailId();
        $voucher_entries = [];

        $trip_detail_ids = property_to_array('trip_detail_id',$this->getShortagesData());
        $this->db->select('voucher_journal.id as voucher_id, voucher_journal.trip_product_detail_id');
        $this->db->where_in('trip_product_detail_id',$trip_detail_ids);
        $where = "(voucher_type = 'dest_shortage_deduction' OR voucher_type = 'decnd_shortage_deduction')";
        $this->db->where($where);
        $saved_vouchers_data = $this->db->get('voucher_journal')->result();

        foreach($saved_vouchers_data as $voucher_data)
        {
            $shortageData = $grouped_shortage_data[$voucher_data->trip_product_detail_id];
            $shortageData = $shortageData[0];

            $entry = [];
            $entry['dr_cr'] = 0;
            $entry['description'] = 'shortage deduction black oil';
            $entry['account_title_id'] = 54;
            $entry['related_other_agent'] = 0;
            $entry['related_customer'] = 0;
            $entry['related_contractor'] = 0;
            $entry['related_company'] = $shortageData->company_id;
            $entry['credit_amount'] = round($shortageData->shortage_amount, 3);
            $entry['debit_amount'] = 0;
            $entry['journal_voucher_id'] = $voucher_data->voucher_id;

            $voucher_entries[] = $entry;

            $entry = [];
            $entry['dr_cr'] = 1;
            $entry['description'] = 'shortage deduction black oil';
            $entry['account_title_id'] = 49;
            $entry['related_other_agent'] = 0;
            $entry['related_customer'] = 0;
            $entry['related_contractor'] = $shortageData->contractor_id;
            $entry['related_company'] = 0;
            $entry['credit_amount'] = 0;
            $entry['debit_amount'] = round($shortageData->shortage_amount, 3);
            $entry['journal_voucher_id'] = $voucher_data->voucher_id;

            $voucher_entries[] = $entry;


        }

        return $voucher_entries;
    }

    public function ignoreShortageVouchers()
    {
        $this->ignoreShortageVoucherByDecandingData();

        $this->ignoreShortageVoucherByDestinationData();
    }

    public function ignoreShortageVoucherByDecandingData()
    {
        $decandingShortageData = $this->getDecandingShortageData();
        if(sizeof($decandingShortageData) == 0)
            return;

        $ors = [];
        foreach($decandingShortageData as $record)
        {
            $str = "(voucher_journal.trip_product_detail_id  = ".$record->trip_detail_id." AND voucher_journal.voucher_type = 'shortage_voucher_dest')";
            $ors[] = $str;
        }
        $where = "(".join(' OR ',$ors).")";
        $this->ci->helper_model->ignore_voucher_where($where);
    }

    public function ignoreShortageVoucherByDestinationData()
    {
        $destinationShortageData = $this->getDestinationShortageData();
        if(sizeof($destinationShortageData) == 0)
            return;

        $trip_detail_ids = property_to_array('trip_detail_id',$destinationShortageData);
        $this->db->select('voucher_journal.trip_product_detail_id');
        $this->db->where_in('voucher_journal.trip_product_detail_id',$trip_detail_ids);
        $this->db->where('voucher_journal.voucher_type','shortage_voucher_decnd');
        $result = $this->db->get('voucher_journal')->result();
        if(sizeof($result) == 0)
            return [];
        $ignorable_trip_detail_ids = property_to_array('trip_product_detail_id',$result);
        $where_statement = "(voucher_journal.trip_product_detail_id IN (".join(', ',$ignorable_trip_detail_ids).") AND voucher_journal.voucher_type = 'shortage_voucher_dest')";
        $this->ci->helper_model->ignore_voucher_where($where_statement);

    }

    public function getBlackOilShortageData()
    {
        $shortagesData = Arrays::groupBy($this->getShortagesData(), Functions::extractField('product_type'));
        return (isset($shortagesData['black oil'])?$shortagesData['black oil']:null);
    }
    public function getDecandingShortageData()
    {
        $shortagesData = Arrays::groupBy($this->getShortagesData(), Functions::extractField('shortage_type'));
        return (isset($shortagesData['2'])?$shortagesData['2']:null);
    }
    public function getDestinationShortageData()
    {
        $shortagesData = Arrays::groupBy($this->getShortagesData(), Functions::extractField('shortage_type'));
        return (isset($shortagesData['1'])?$shortagesData['1']:null);
    }
    public function groupShortageDataById()
    {
        $shortagesData = Arrays::groupBy($this->getShortagesData(), Functions::extractField('shortage_id'));

        return $shortagesData;
    }
    public function groupShortageDataByTripDetailId()
    {
        $shortagesData = Arrays::groupBy($this->getShortagesData(), Functions::extractField('trip_detail_id'));
        return $shortagesData;
    }

    /**
     * @return array
     */
    public function getShortageIds()
    {
        return $this->shortageIds;
    }

    /**
     * @param array $shortageIds
     */
    public function setShortageIds($shortageIds)
    {
        $this->shortageIds = $shortageIds;
    }

    /**
     * @return mixed
     */
    public function getCommitDate()
    {
        return $this->commitDate;
    }

    /**
     * @param mixed $commitDate
     */
    public function setCommitDate($commitDate)
    {
        $this->commitDate = $commitDate;
    }

    /**
     * @return mixed
     */
    public function getCreditAgent()
    {
        return $this->creditAgent;
    }

    /**
     * @param mixed $creditAgent
     */
    public function setCreditAgent($creditAgent)
    {
        $this->creditAgent = $creditAgent;
    }

    /**
     * @return mixed
     */
    public function getCreditAgentType()
    {
        return $this->creditAgentType;
    }

    /**
     * @param mixed $creditAgentType
     */
    public function setCreditAgentType($creditAgentType)
    {
        $this->creditAgentType = $creditAgentType;
    }


}
