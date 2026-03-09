<?php

namespace Basiq;

use Basiq\Services\UserService;
use Basiq\Utilities\ResponseParser;
use GuzzleHttp\Client;

class Session
{
    private $apiKey;
    private $accessToken = null;
    public $apiClient;
    private $sessionTimestamp;
    private $tokenValidity;
    private $apiVersion;

    public function __construct($apiKey, $apiVersion = "1.0")
    {
        $this->apiClient = new Client([
            // Base URI is used with relative requests
            'base_uri'    => 'https://au-api.basiq.io',
            // You can set any number of default request options.
            "headers"     => [
                "Content-Type" => "application/json",
            ],
            'timeout'     => 30.0,
            "http_errors" => false,
        ]);

        $this->tokenValidity = 3600;
        $this->apiKey = $apiKey;
        $this->apiVersion = $apiVersion;
        $this->accessToken = $this->getAccessToken();
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    public function getAccessToken()
    {
        if ($this->accessToken && (time() - $this->sessionTimestamp < $this->tokenValidity)) {
            return $this->accessToken;
        }

        $this->refreshAccessToken();

        return $this->accessToken;
    }

    public function refreshAccessToken(string $userId = null)
    {
        $this->sessionTimestamp = time();
        $response = $this->apiClient->post("/token", [
            "headers"     => [
                "Content-type"  => "application/json",
                "Authorization" => "Basic " . $this->apiKey,
                "basiq-version" => $this->apiVersion,
            ],
            'form_params' => [
                'scope' => 'SERVER_ACCESS',
            ],
        ]);

        $body = ResponseParser::parse($response);
        $this->tokenValidity = $body["expires_in"];
        $this->accessToken = $body["access_token"];
    }

    public function getInstitutions()
    {
        $response = $this->apiClient->get("/institutions", [
            "headers" => [
                "Authorization" => "Bearer " . $this->getAccessToken(),
            ],
        ]);

        return ResponseParser::parse($response);
    }

    public function getInstitution($id)
    {
        $response = $this->apiClient->get("/institutions/" . $id, [
            "headers" => [
                "Authorization" => "Bearer " . $this->getAccessToken(),
            ],
        ]);

        return ResponseParser::parse($response);
    }

    public function getUser($id)
    {
        return (new UserService($this))->get($id);
    }

    public function forUser($id)
    {
        return (new UserService($this))->forUser($id);
    }

    public function getEvent(string $eventId)
    {
        $response = $this->apiClient->get("/events/" . $eventId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
        ]);

        return ResponseParser::parse($response);
    }
}
