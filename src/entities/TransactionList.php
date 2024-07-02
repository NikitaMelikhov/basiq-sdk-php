<?php

namespace Basiq\Entities;

use Basiq\Utilities\ResponseParser;

class TransactionList extends Entity
{

    public $data;
    public $links;
    public $session;

    public function __construct($data, $session, $limit)
    {
        $this->data = $this->parseData($data["data"]);
        $this->links = $data["links"];
        $this->session = $session;
        $this->limit = $limit;
    }

    public function next()
    {
        if (!isset($this->links["next"])) {
            return false;
        }

        $next = substr($this->links["next"], strpos($this->links["next"], ".io/") + 4);

        if ($this->limit !== null) {
            $next .= "&limit=" . $this->limit;
        }

        $response = $this->session->apiClient->get(
            $next,
            [
                "headers" => [
                    "Content-type"  => "application/json",
                    "Authorization" => "Bearer " . $this->session->getAccessToken(),
                ],
            ]
        );

        $body = ResponseParser::parse($response);

        $this->data = $body["data"];
        $this->links = $body["links"];

        return true;
    }

    private function parseData($data)
    {
        return array_map(
            function ($transaction) {
                return new Transaction($transaction);
            },
            $data
        );
    }
}
