<?php
class Stock_Model extends CI_Model {

    public $table;
    public function __construct(){
        parent::__construct();
        
        $this->table = "stock";
    }

    public function get(){
        $this->db->select("stock.id as stock_id, stock.product_id, products.name as product_name,
        stock.quantity,
        ");
        $this->db->from('stock');
        $this->db->join('products','products.id = stock.product_id','left');
        $records = $this->db->get()->result();
        //var_dump($records);die();
        return $records;
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
    public function increase($invoice_entries)
    {
        $product_ids = array();
        foreach($invoice_entries as $entry)
        {
            array_push($product_ids, $entry['product_id']);
        }

        if(sizeof($product_ids) > 0)
        {
            $this->db->trans_start();

            $this->db->select('product_id, quantity');
            $this->db->where_in("product_id", $product_ids);
            $result = $this->db->get($this->table)->result();

            $modified_invoice_entries = array();
            foreach($result as $record)
            {
                foreach($invoice_entries as $entry)
                {
                    if($entry['product_id'] == $record->product_id)
                    {
                        $modified_invoice_entry = array(
                            'product_id' => $entry['product_id'],
                            'quantity' => $entry['quantity'] + $record->quantity,
                            'updated_at' => date('m/d/Y h:i:s', time()),
                        );
                        array_push($modified_invoice_entries, $modified_invoice_entry);
                    }
                }
            }

            if(sizeof($modified_invoice_entries) > 0)
            {
                $this->db->update_batch($this->table, $modified_invoice_entries, 'product_id');
            }

            return $this->db->trans_complete();
        }
        return false;


    }
    public function decrease($invoice_entries)
    {
        $product_ids = array();
        foreach($invoice_entries as $entry)
        {
            array_push($product_ids, $entry['product_id']);
        }

        if(sizeof($product_ids) > 0)
        {
            $this->db->trans_start();

            $this->db->select('product_id, quantity');
            $this->db->where_in("product_id", $product_ids);
            $result = $this->db->get($this->table)->result();

            $modified_invoice_entries = array();
            foreach($result as $record)
            {
                foreach($invoice_entries as $entry)
                {
                    if($entry['product_id'] == $record->product_id)
                    {
                        $modified_invoice_entry = array(
                            'product_id' => $entry['product_id'],
                            'quantity' => $record->quantity - $entry['quantity'],
                            'updated_at' => date('Y-m-d h:i:s', time()),
                        );
                        array_push($modified_invoice_entries, $modified_invoice_entry);
                    }
                }
            }

            //var_dump($modified_invoice_entries); die();
            if(sizeof($modified_invoice_entries) > 0)
            {
                $this->db->update_batch($this->table, $modified_invoice_entries, 'product_id');
            }

            return $this->db->trans_complete();
        }
        return false;
    }



    public function insert($product_id, $qty){
       $data = array(
           'product_id'=>$product_id,
           'quantity'=>$qty,
        );
        $result = $this->db->insert($this->table, $data);
        if($result == true){
            return true;
        }else{
            return false;
        }
    }

}