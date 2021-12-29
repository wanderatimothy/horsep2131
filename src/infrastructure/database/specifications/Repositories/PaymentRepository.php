<?php

namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\payment;
use core\aggregates\rent_due;
use core\aggregates\tenant_due;
use core\aggregates\transaction;
use infrastructure\database\specifications\Repository;

class PaymentRepository extends Repository {

    public function __construct()
    {
        parent::__construct(payment::class);
    }


    public function reference_exists($reference_no):transaction{
        $sql = "SELECT  *  FROM `transactions` WHERE  reference_no = :reference_no ";
        $this->conn->runOperation($sql , ['reference_no'=> $reference_no]);
        $results = $this->conn->results();
        return $results[0];

    }

    public function getTransaction($reference_no):transaction{
        $sql = "SELECT * FROM `transactions` WHERE  reference_no = :reference_no ";
        $this->conn->runOperation($sql , ['reference_no'=> $reference_no]);
        $results = $this->conn->results(transaction::class);
        return $results[0];
    }


    public function getTenantDueRef($id):tenant_due {
        $sql = "select * from tenant_dues where id = :id";
        $this->conn->runOperation($sql , ['id'=> $id]);
        $results = $this->conn->results(tenant_due::class);
        return $results[0];
    }

    public function getTenantDues($id , $paid = 0) {
        $sql = "select * from tenant_dues where tenant_id = :id  and is_paid = :is_paid";
        $this->conn->runOperation($sql , ['id'=> $id , 'is_paid' => $paid]);
        $results = $this->conn->results(tenant_due::class);
        return $results;
    }
}