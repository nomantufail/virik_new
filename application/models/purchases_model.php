<?php
class Purchases_Model extends CI_Model {

    public $table;
    public function __construct(){
        parent::__construct();
        
        $this->table = "purchase_invoices";
    }

    public function get(){

    }

    public function cash()
    {
        include_once(APPPATH."models/helperClasses/Purchase_Invoice.php");
        include_once(APPPATH."models/helperClasses/Purchase_Invoice_Entry.php");
        include_once(APPPATH."models/helperClasses/Supplier.php");
        include_once(APPPATH."models/helperClasses/Product.php");

        $this->db->select("
            purchase_invoices.id as invoice_id, purchase_invoices.invoice_date, purchase_invoices.extra_info,
            agents.id as supplier_id, agents.name as supplier_name,
            purchase_invoice_items.id as entry_id, purchase_invoice_items.quantity as product_quantity,
            purchase_invoice_items.cost_per_item,
            products.id as product_id, products.name as product_name,
        ");
        $this->db->from($this->table);
        $this->db->join('purchase_invoice_items','purchase_invoice_items.invoice_id = purchase_invoices.id','left');
        $this->db->join('agents','agents.id = purchase_invoices.supplier_id','left');
        $this->db->join('products','products.id = purchase_invoice_items.product_id','left');

        $this->db->where(array(
            'purchase_invoices.deleted'=>0,
            'purchase_invoices.transaction_type'=>1,
        ));
        $this->db->order_by('purchase_invoices.id, purchase_invoice_items.id');
        $raw_invoices = $this->db->get()->result();

        $final_invoices_array = array();

        $previous_invoice_id = -1;
        $previous_entry_id = -1;

        $temp_invoice = new Purchase_Invoice();
        $temp_invoice_item = new Purchase_Invoice_Entry($temp_invoice);

        $count = 0;
        foreach($raw_invoices as $record){
            $count++;

            //setting the parent details
            if($record->invoice_id != $previous_invoice_id)
            {
                $previous_invoice_id = $record->invoice_id;

                $temp_invoice = new Purchase_Invoice();

                //setting data in the parent object
                $temp_invoice->id = $record->invoice_id;
                $temp_invoice->date = $record->invoice_date;
                $temp_invoice->supplier = new Supplier($record->supplier_id, $record->supplier_name);
                $temp_invoice->extra_info = $record->extra_info;

            }/////////////////////////////////////////////////

            /////////////////////////////////////////////////
            if($record->entry_id != $previous_entry_id)
            {
                $previous_entry_id = $record->entry_id;

                $temp_invoice_item = new Purchase_Invoice_Entry($temp_invoice);

                //setting data in the Trip_Product_Data object
                $temp_invoice_item->id = $record->entry_id;
                $temp_invoice_item->product = new Product($record->product_id, $record->product_name);
                $temp_invoice_item->costPerItem = $record->cost_per_item;
                $temp_invoice_item->quantity = $record->product_quantity;
            }/////////////////////////////////////////////////

            //pushing particals
            if($count != sizeof($raw_invoices)){
                if($raw_invoices[$count]->entry_id != $record->entry_id){
                    array_push($temp_invoice->entries, $temp_invoice_item);
                }
                if($raw_invoices[$count]->invoice_id != $record->invoice_id){
                    array_push($final_invoices_array, $temp_invoice);
                }
            }else{

                array_push($temp_invoice->entries, $temp_invoice_item);
                array_push($final_invoices_array, $temp_invoice);
            }
        }

        return $final_invoices_array;
    }

    public function credit()
    {
        include_once(APPPATH."models/helperClasses/Purchase_Invoice.php");
        include_once(APPPATH."models/helperClasses/Purchase_Invoice_Entry.php");
        include_once(APPPATH."models/helperClasses/Supplier.php");
        include_once(APPPATH."models/helperClasses/Product.php");

        $this->db->select("
            purchase_invoices.id as invoice_id, purchase_invoices.invoice_date, purchase_invoices.extra_info,
            agents.id as supplier_id, agents.name as supplier_name,
            purchase_invoices.paid as paid,
            purchase_invoice_items.id as entry_id, purchase_invoice_items.quantity as product_quantity,
            purchase_invoice_items.cost_per_item,
            products.id as product_id, products.name as product_name,
        ");
        $this->db->from($this->table);
        $this->db->join('purchase_invoice_items','purchase_invoice_items.invoice_id = purchase_invoices.id','left');
        $this->db->join('agents','agents.id = purchase_invoices.supplier_id','left');
        $this->db->join('products','products.id = purchase_invoice_items.product_id','left');

        $this->db->where(array(
            'purchase_invoices.deleted'=>0,
            'purchase_invoices.transaction_type'=>0,
        ));
        $this->db->order_by('purchase_invoices.id, purchase_invoice_items.id');
        $raw_invoices = $this->db->get()->result();

        $final_invoices_array = array();

        $previous_invoice_id = -1;
        $previous_entry_id = -1;

        $temp_invoice = new Purchase_Invoice();
        $temp_invoice_item = new Purchase_Invoice_Entry($temp_invoice);

        $count = 0;
        foreach($raw_invoices as $record){
            $count++;

            //setting the parent details
            if($record->invoice_id != $previous_invoice_id)
            {
                $previous_invoice_id = $record->invoice_id;

                $temp_invoice = new Purchase_Invoice();

                //setting data in the parent object
                $temp_invoice->id = $record->invoice_id;
                $temp_invoice->date = $record->invoice_date;
                $temp_invoice->supplier = new Supplier($record->supplier_id, $record->supplier_name);
                $temp_invoice->extra_info = $record->extra_info;
                $temp_invoice->paid = $record->paid;

            }/////////////////////////////////////////////////

            /////////////////////////////////////////////////
            if($record->entry_id != $previous_entry_id)
            {
                $previous_entry_id = $record->entry_id;

                $temp_invoice_item = new Purchase_Invoice_Entry($temp_invoice);

                //setting data in the Trip_Product_Data object
                $temp_invoice_item->id = $record->entry_id;
                $temp_invoice_item->product = new Product($record->product_id, $record->product_name);
                $temp_invoice_item->costPerItem = $record->cost_per_item;
                $temp_invoice_item->quantity = $record->product_quantity;
            }/////////////////////////////////////////////////

            //pushing particals
            if($count != sizeof($raw_invoices)){
                if($raw_invoices[$count]->entry_id != $record->entry_id){
                    array_push($temp_invoice->entries, $temp_invoice_item);
                }
                if($raw_invoices[$count]->invoice_id != $record->invoice_id){
                    array_push($final_invoices_array, $temp_invoice);
                }
            }else{

                array_push($temp_invoice->entries, $temp_invoice_item);
                array_push($final_invoices_array, $temp_invoice);
            }
        }

        return $final_invoices_array;
    }


    public function get_limited($limit, $start, $keys, $sort) {

        $this->db->order_by($sort['sort_by'], $sort['order']);
        if($keys['agent_id'] != '')
        {
            $this->db->where('id',$keys['agent_id']);
        }
        
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        return $query->result();
    }
    public function count($keys = "") {
        if($keys != "")
        {
            if($keys['agent_id'] != '')
            {
                $this->db->where('id',$keys['agent_id']);
            }
        }
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function find($id){
        $result = $this->db->get_where($this->table, array('id'=>$id))->result();
        if($result){
            $record = $result[0];
            return $record;
        }else{
            return null;
        }
    }

    public function insert_cash_purchase(){
        $pannel_count = $this->input->post('pannel_count');
        $supplier  = $this->input->post('supplier');
        $invoice_date = $this->input->post('invoice_date');
        $extra_info = $this->input->post('extra_info');
        $transaction_type = 1;

        $invoice_data = array(
            'supplier_id'=>$supplier,
            'invoice_date'=>$invoice_date,
            'transaction_type'=>$transaction_type,
            'extra_info'=>$extra_info,
        );
        $invoice_entries = array();
        $stock_entries = array();
        for($i = 1; $i<$pannel_count; $i++)
        {
            $invoice_entry = array();

            $product = $this->input->post('product_'.$i);
            $quantity = $this->input->post('quantity_'.$i);
            $cost_per_item = $this->input->post('costPerItem_'.$i);

            $stock_entry['product_id']=$product;
            $stock_entry['quantity']=$quantity;


            $invoice_entry['product_id']=$product;
            $invoice_entry['quantity'] = $quantity;
            $invoice_entry['cost_per_item']= $cost_per_item;


            if($invoice_entry['product_id'] != '')
            {
                array_push($stock_entries, $stock_entry);
                array_push($invoice_entries, $invoice_entry);
            }

        }

        $invoice_id = 0;
        $this->db->trans_start();

        if(sizeof($invoice_entries) > 0)
        {
            $this->db->insert($this->table, $invoice_data);
            $invoice_id = $this->db->insert_id();

            $modified_invoice_entries = array();
            foreach($invoice_entries as $entry)
            {
                $entry['invoice_id'] = $invoice_id;
                array_push($modified_invoice_entries, $entry);
            }

            $this->db->insert_batch('purchase_invoice_items',$modified_invoice_entries);

            $this->stock_model->increase($stock_entries);
        }


        if($this->db->trans_complete() == true)
        {
            return $invoice_id;
        }
        return false;
    }

    public function insert_credit_purchase(){
        $pannel_count = $this->input->post('pannel_count');
        $supplier  = $this->input->post('supplier');
        $invoice_date = $this->input->post('invoice_date');
        $extra_info = $this->input->post('extra_info');
        $paid = $this->input->post('paid');
        $transaction_type = 0;

        $invoice_data = array(
            'supplier_id'=>$supplier,
            'invoice_date'=>$invoice_date,
            'transaction_type'=>$transaction_type,
            'extra_info'=>$extra_info,
            'paid'=>$paid,
        );
        $invoice_entries = array();
        $stock_entries = array();
        for($i = 1; $i<$pannel_count; $i++)
        {
            $invoice_entry = array();

            $product = $this->input->post('product_'.$i);
            $quantity = $this->input->post('quantity_'.$i);
            $cost_per_item = $this->input->post('costPerItem_'.$i);

            $stock_entry['product_id']=$product;
            $stock_entry['quantity']=$quantity;


            $invoice_entry['product_id']=$product;
            $invoice_entry['quantity'] = $quantity;
            $invoice_entry['cost_per_item']= $cost_per_item;


            if($invoice_entry['product_id'] != '')
            {
                array_push($stock_entries, $stock_entry);
                array_push($invoice_entries, $invoice_entry);
            }

        }

        $invoice_id = 0;
        $this->db->trans_start();

        if(sizeof($invoice_entries) > 0)
        {
            $this->db->insert($this->table, $invoice_data);
            $invoice_id = $this->db->insert_id();

            $modified_invoice_entries = array();
            foreach($invoice_entries as $entry)
            {
                $entry['invoice_id'] = $invoice_id;
                array_push($modified_invoice_entries, $entry);
            }

            $this->db->insert_batch('purchase_invoice_items',$modified_invoice_entries);

            $this->stock_model->increase($stock_entries);
        }


        if($this->db->trans_complete() == true)
        {
            return $invoice_id;
        }
        return false;
    }

    public function next_invoice()
    {
        $this->db->select_max('id');
        $result = $this->db->get('purchase_invoices')->result();
        return $result[0]->id +1;
    }

}