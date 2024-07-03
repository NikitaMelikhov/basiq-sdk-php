<?php

namespace Basiq\Entities;

use Basiq\Services\ConnectionService;
use Basiq\Services\UserService;

class User extends Entity
{

    public $email;
    public $mobile;
    public $connections;

    private $connectionService;
    private $userService;

    public function __construct(UserService $service, $data)
    {
        $this->id = $data["id"];
        $this->email = isset($data["email"]) ? (string)$data["email"] : null;
        $this->mobile = isset($data["mobile"]) ? (string)$data["mobile"] : null;
        $this->connections = isset($data["connections"]) ? (array)$data["connections"] : [];

        $this->userService = $service;
        $this->connectionService = new ConnectionService($service->session, $this);
    }

    public function update($data)
    {
        return $this->userService->update($this->id, $data);
    }

    public function delete()
    {
        return $this->userService->delete($this->id);
    }

    /**
     * @param $institutionId
     * @param $userId
     * @param $password
     * @param string|null $securityCode
     * @param string|null $secondaryLoginId
     *
     * @return Job|void
     */
    public function createConnection($institutionId, $userId, $password, $securityCode = null, $secondaryLoginId = null)
    {
        $data = ["institutionId" => $institutionId, "loginId" => $userId, "password" => $password];
        if ($securityCode) {
            $data["securityCode"] = $securityCode;
        }
        if ($secondaryLoginId) {
            $data['secondaryLoginId'] = $secondaryLoginId;
        }

        return $this->connectionService->create($data);
    }

    public function getAllConnections()
    {
        return $this->userService->getAllConnections($this->connectionService, $this);
    }

    public function getAccounts($connectionId = null)
    {
        return $this->userService->getAccounts($this->id, null, $connectionId);
    }

    public function getAccount($accountId)
    {
        return $this->userService->getAccounts($this->id, $accountId);
    }

    public function getTransactions($filterBuilder = null, $limit = null)
    {
        return $this->userService->getTransactions($this->id, null, $filterBuilder, $limit);
    }

    public function getTransaction($transactionId)
    {
        return $this->userService->getTransactions($this->id, $transactionId);
    }

    public function refreshAllConnections()
    {
        return $this->userService->refreshAllConnections($this->id);
    }
}
