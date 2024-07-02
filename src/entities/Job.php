<?php

namespace Basiq\Entities;

use Basiq\Services\ConnectionService;

class Job extends Entity
{

    public $id;
    public $created;
    public $updated;
    public $steps;
    public $links;
    private $service;

    public function __construct(ConnectionService $service, $data)
    {
        $this->id = $data["id"];
        $this->created = isset($data["created"]) ? new \DateTime($data["created"]) : null;
        $this->updated = isset($data["updated"]) ? new \DateTime($data["updated"]) : null;
        $this->steps = isset($data["steps"]) ? (array)$data["steps"] : [];
        $this->links = isset($data["links"]) ? $data["links"] : [];

        $this->service = $service;
    }

    public function getConnectionId()
    {
        if (count($this->links) === 0 || !isset($this->links["source"])) {
            return "";
        }

        return substr($this->links["source"], strrpos($this->links["source"], "/") + 1);
    }

    public function getConnection()
    {
        if (count($this->links) === 0 || !isset($this->links["source"])) {
            $job = $this->service->getJob($this->id);

            $connectionId = $job->getConnectionId();
        } else {
            $connectionId = $this->getConnectionId();
        }

        return $this->service->get($connectionId);
    }

    public function waitForCredentials($interval, $timeout)
    {
        $start = time();

        while (true) {
            $job = $this->service->getJob($this->id);

            for ($i = 0; $i < count($job->steps); $i++) {
                if (time() - $start > $timeout) {
                    throw new Exception("Polling operation timed out");
                }
                $step = $job->steps[$i];
                if ($step["title"] === "verify-credentials") {
                    if ($step["status"] === "success") {
                        return $this->service->get($job->getConnectionId());
                    }
                    if ($step["status"] === "failed") {
                        return null;
                    }
                }
                $i++;
            }

            sleep($interval / 1000);
        }
    }

    public function waitForTransactions($interval, $timeout)
    {
        $start = time();

        while (true) {
            $job = $this->service->getJob($this->id);

            for ($i = 0; $i < count($job->steps); $i++) {
                if (time() - $start > $timeout) {
                    throw new Exception("Polling operation timed out");
                }
                $step = $job->steps[$i];
                if ($step["title"] === "retrieve-transactions") {
                    if ($step["status"] === "success") {
                        return $this->service->get($job->getConnectionId());
                    }
                    if ($step["status"] === "failed") {
                        return null;
                    }
                }
                $i++;
            }

            sleep($interval / 1000);
        }
    }
}
