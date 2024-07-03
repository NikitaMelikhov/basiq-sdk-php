<?php

namespace Basiq\Entities;

class Transaction extends Entity
{

    public $id;
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

    public function __construct($data)
    {
        $this->id = $data["id"];
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
    }
}
