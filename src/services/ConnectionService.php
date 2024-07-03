<?php

namespace Basiq\Services;

use Basiq\Entities\Connection;
use Basiq\Entities\Job;
use Basiq\Utilities\ResponseParser;


class ConnectionService extends Service
{

    protected $user;

    public function __construct($session, $user)
    {
        parent::__construct($session);
        $this->user = $user;
    }

    public function create($data = [])
    {
        if (!isset($data["institutionId"]) || !isset($data["loginId"]) || !isset($data["password"])) {
            throw new \InvalidArgumentException("Invalid parameters provided");
        }

        $data = array_filter($data, function ($key) {
            return $key === "institutionId" || $key === "loginId" || $key === "password" || $key === "securityCode" || $key === 'secondaryLoginId';
        }, ARRAY_FILTER_USE_KEY);

        $data["institution"] = [
            "id" => $data["institutionId"],
        ];
        unset($data["institutionId"]);

        $response = $this->session->apiClient->post("users/" . $this->user->id . "/connections", [
            "headers" => [
                "Content-type"  => "application/json",
                "Authorization" => "Bearer " . $this->session->getAccessToken(),
            ],
            "json"    => $data,
        ]);

        $body = ResponseParser::parse($response);

        return (new Job($this, $body));
    }

    public function update($connectionId, $password)
    {
        if (!isset($password)) {
            throw new \InvalidArgumentException("Invalid parameters provided");
        }

        $response = $this->session->apiClient->post("users/" . $this->user->id . "/connections/" . $connectionId, [
            "headers" => [
                "Content-type"  => "application/json",
                "Authorization" => "Bearer " . $this->session->getAccessToken(),
            ],
            "json"    => ["password" => $password],
        ]);

        $body = ResponseParser::parse($response);

        return (new Job($this, $body));
    }

    public function get($connectionId)
    {
        $response = $this->session->apiClient->get("users/" . $this->user->id . "/connections/" . $connectionId, [
            "headers" => [
                "Content-type"  => "application/json",
                "Authorization" => "Bearer " . $this->session->getAccessToken(),
            ],
        ]);

        $body = ResponseParser::parse($response);

        return new Connection($this, $this->user, $body);
    }

    public function getJob($jobId)
    {
        $response = $this->session->apiClient->get("jobs/" . $jobId, [
            "headers" => [
                "Content-type"  => "application/json",
                "Authorization" => "Bearer " . $this->session->getAccessToken(),
            ],
        ]);

        $body = ResponseParser::parse($response);

        return (new Job($this, $body));
    }

    public function refresh($connectionId)
    {
        $response = $this->session->apiClient->post(
            "users/" . $this->user->id . "/connections/" . $connectionId . "/refresh",
            [
                "headers" => [
                    "Content-type"  => "application/json",
                    "Authorization" => "Bearer " . $this->session->getAccessToken(),
                ],
            ]
        );

        $body = ResponseParser::parse($response);

        return (new Job($this, $body));
    }

    public function delete($connectionId)
    {
        $response = $this->session->apiClient->delete("users/" . $this->user->id . "/connections/" . $connectionId, [
            "headers" => [
                "Content-type"  => "application/json",
                "Authorization" => "Bearer " . $this->session->getAccessToken(),
            ],
        ]);

        ResponseParser::parse($response);

        return null;
    }

    public function forConnection($id)
    {
        return (new Connection($this, $this->user, [
            "id" => $id,
        ]));
    }
}
