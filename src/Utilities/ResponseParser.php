<?php

namespace Basiq\Utilities;

use Basiq\Exceptions\HttpResponseException;

class ResponseParser {

    public static function parse(\Psr\Http\Message\ResponseInterface $response)
    {
        $body = $response->getBody();

        if ($body->getSize() > 0) {
            $contents = $body->__toString();
            $body = json_decode($contents, true);
        
            if ($body === null) {
                throw new \Exception("Invalid response received from server. Check log for the response");
            }

            if ($response->getStatusCode() > 299) {
                throw new HttpResponseException($body, $response->getStatusCode());
            }

            return $body;
        }

        return null;
    }

}