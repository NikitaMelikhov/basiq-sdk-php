<?php

namespace Basiq\Entities;

class Account extends Entity
{

    public $id;
    public $accountNo;
    public $name;
    public $currency;
    public $class;
    public $balance;
    public $availableFunds;
    public $lastUpdated;
    public $institution;
    public $connection;
    public $status;
    public $meta;
    public ?string $accountHolder;

    public function __construct($data)
    {
        $this->id = $data["id"];
        $this->accountNo = $data["accountNo"];
        $this->name = $data["name"];
        $this->currency = $data["currency"];
        $this->class = $data["class"];
        $this->balance = $data["balance"];
        $this->availableFunds = $data["availableFunds"];
        $this->lastUpdated = $data["lastUpdated"];
        $this->institution = $data["institution"];
        $this->connection = $data["connection"];
        $this->status = $data["status"];
        $this->meta = $data["meta"];
        $this->accountHolder = $data['accountHolder'];
    }
}
