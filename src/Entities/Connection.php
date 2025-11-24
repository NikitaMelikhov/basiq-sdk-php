<?php

namespace Basiq\Entities;

use Basiq\Services\ConnectionService;
use DateTimeImmutable;

class Connection extends Entity
{
    public $status;
    public ?\DateTime $lastUsed = null;
    public $institution;
    public $accounts;
    public ?DateTimeImmutable $expiryDate = null;
    public $user;
    private $connectionService;

    public function __construct(ConnectionService $connectionService, $user, $data)
    {
        $this->id = $data['id'];
        $this->status = isset($data['status']) ? (string)$data['status'] : null;
        $this->lastUsed = isset($data['lastUsed']) && $data['lastUsed'] ? new \DateTime($data['lastUsed']) : null;
        $this->institution = isset($data['institution']) ? $data['institution'] : null;
        $this->accounts = isset($data['accounts']) ? (array)$data['accounts'] : [];
        $this->expiryDate = isset($data['expiryDate']) && $data['expiryDate'] ? new DateTimeImmutable($data['expiryDate']) : null;

        $this->user = $user;
        $this->connectionService = $connectionService;
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
