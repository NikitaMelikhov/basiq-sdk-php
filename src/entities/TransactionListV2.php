<?php

namespace Basiq\Entities;

use Basiq\Exceptions\HttpResponseException;
use Basiq\Utilities\ResponseParser;

class TransactionListV2 extends Entity
{

    public $data;
    public $links;
    public $session;
    public ?int $limit;

    public function __construct($data, $session, $limit)
    {
        $this->data = $this->parseData($data["data"]);
        $this->links = $data["links"];
        $this->session = $session;
        $this->limit = $limit;
    }

    /**
     * @return bool
     *
     * @throws HttpResponseException
     */
    public function next(): bool
    {
        if (!isset($this->links['next'])) {
            return false;
        }

        $next = substr($this->links['next'], strpos($this->links['next'], '.io/') + 4);

        if ($this->limit !== null) {
            $next .= '&limit=' . $this->limit;
        }

        $response = $this->session->apiClient->get(
            $next,
            [
                'headers' => [
                    'Content-type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->session->getAccessToken(),
                ],
            ]
        );

        $body = ResponseParser::parse($response);

        $this->data = $this->parseData($body['data']);
        $this->links = $body['links'];

        return true;
    }

    /**
     * @param $data
     *
     * @return TransactionV2[]
     */
    private function parseData($data): array
    {
        return array_map(
            static function ($transaction) {
                return new TransactionV2($transaction);
            },
            $data
        );
    }
}
