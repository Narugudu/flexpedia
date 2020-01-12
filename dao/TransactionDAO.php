<?php

class TransactionDAO{
    private $db=NULL;
    private $username="flexuser";
    private $password="flexpassword";
    private $dsnString="mysql:host=localhost;dbname=FLEXPEDIA";

    function __construct(){
        $this->db=new PDO("mysql:host=localhost;dbname=FLEXPEDIA","flexuser","flexpassword");
        $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
    }

    public function getInvoces($limit,$pageIndex){
        $this->statement=$this->db
        ->prepare("SELECT * FROM invoices LIMIT :limit OFFSET :offset");
        $this->statement->bindValue(":limit",(int)$limit,PDO::PARAM_INT);
        $this->statement->bindValue(":offset",(int)$pageIndex,PDO::PARAM_INT);
        if($this->statement->execute())
            return $this->statement->fetchAll();
        else 
            return NULL;
    }


    public function getInvoicesCount(){
        $this->statement=$this->db
        ->prepare("SELECT count(*) FROM invoices");
        if($this->statement->execute())
            return $this->statement->fetch()[0];
        else
            return -1;
    }

    public function getClientsReport(){
        $this->statement=$this->db
        ->prepare("select client,invoice_amount_plus_vat , 
        case when (invoice_status='paid')then invoice_amount_plus_vat  else 0 end paid_amount,
        case when (invoice_status='paid')then 0 else invoice_amount_plus_vat end outstanding 
        from invoices;");
        if($this->statement->execute())
            return $this->statement->fetchAll();
        else 
            return NULL;
    }


    public function updateStatus($id,$status){
        $this->db->beginTransaction();
        $this->statement=$this->db
        ->prepare("update invoices set invoice_status=:status where id=:idVal");
        
        $this->statement->bindValue(":status",$status);
        $this->statement->bindValue(":idVal",(int)$id,PDO::PARAM_INT);
        echo("status".$this->statement->execute());
        $this->db->commit();
        
    }

}

?>