<?php

namespace Basiq\Entities;

class TransactionV2 extends Entity
{

    public $id;
    public $type;
    public $status;
    public $description;
    public $amount;
    public $account;
    public $balance;
    public $class;
    public $institution;
    public $connection;
    public $postDate;
    public $transactionDate;
    public $direction;
    public $subclass;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->type = $data["type"];
        $this->status = $data["status"];
        $this->description = $data["description"];
        $this->amount = $data["amount"];
        $this->account = $data["account"];
        $this->balance = $data["balance"];
        $this->class = $data["class"];
        $this->institution = $data["institution"];
        $this->connection = $data["connection"];
        $this->postDate = $data["postDate"];
        $this->transactionDate = $data["transactionDate"];
        $this->direction = $data["direction"];
        $this->subclass = $data["subClass"];
    }
}
