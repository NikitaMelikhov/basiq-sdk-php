<?php

namespace Basiq\Entities;

use Basiq\Services\ConnectionService;

class Connection extends Entity
{

    public $status;
    public $lastUsed;
    public $institution;
    public $accounts;
    private $service;
    private $user;

    public function __construct(ConnectionService $service, $user, $data)
    {
        $this->id = $data["id"];
        $this->status = isset($data["status"]) ? (string)$data["status"] : null;
        $this->lastUsed = isset($data["lastUsed"]) ? new \DateTime($data["lastUsed"]) : null;
        $this->institution = isset($data["institution"]) ? $data["institution"] : null;
        $this->accounts = isset($data["accounts"]) ? (array)$data["accounts"] : [];

        $this->user = $user;

        $this->connectionService = $service;
    }

    public function update($data)
    {
        return $this->connectionService->update($this->id, $data);
    }

    public function refresh()
    {
        return $this->connectionService->refresh($this->id);
    }

    public function delete()
    {
        return $this->connectionService->delete($this->id);
    }
}
