<?php

namespace Basiq\Exceptions;

class HttpResponseException extends \Exception {

    public $response;
    public $statusCode;
    public $message;

    public function __construct($body, $statusCode)
    {
        if (isset($body["data"])) {
            $error = trim(array_reduce($body["data"], function ($sum, $error) {
                return $sum .= $error["detail"];
            }, ""));
        } else {
            $error = "Unexpected error from server";
        }

        $this->response = $body;
        $this->statusCode = $statusCode;
        $this->message = $error;
    }


}