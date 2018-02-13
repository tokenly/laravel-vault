<?php

namespace Tokenly\Vault;

use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Jippi\Vault\Client as VaultClient;
use Jippi\Vault\Exception\ClientException;
use Jippi\Vault\Exception\ServerException;
use RuntimeException;

/**
 * Class VaultWrapper
 */
class VaultWrapper 
{

    public function __construct($service_delegate) {
        $this->service_delegate = $service_delegate;
    }

    public function __call($method, $args) {
        try {
            if (!$this->service_delegate) { throw new Exception("Undefined service", 1); }
            $response = call_user_func_array([$this->service_delegate, $method], $args);
            $exception = null;
        } catch (Exception $e) {
            $exception = $e;
            $response = null;
        }

        return $this->decodeResponse($response, $exception);
    }

    public function raw(VaultClient $client, $method, $url, $params) {
        try {
            $response = $client->{$method}($url, $params);
            $exception = null;
        } catch (Exception $e) {
            $exception = $e;
            $response = null;
        }

        return $this->decodeResponse($response, $exception);
    }

    protected function decodeResponse($response, $exception) {
        $json_data          = null;
        $http_response_code = null;
        $success            = null;
        $error              = null;

        if ($exception) {
            $success = false;
            $error = $exception->getMessage();
            if (($exception instanceof ClientException) OR ($exception instanceof ServerException)) {
                $response = $exception->response();
            }
        }

        if ($response instanceof Response) {
            $body = (string) $response->getBody();
            $http_response_code = $response->getStatusCode();
            if ($body !== '') {
                // try to decode a JSON object
                $json_data = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if ($success === null) {
                        $success = true;
                    }

                    if ($success === false) {
                        // try replacing the error message
                        if ($json_data AND isset($json_data['errors']) AND is_array($json_data['errors'])) {
                            $error = implode(", ", $json_data['errors']);
                        }
                    }
                } else {
                    $success = false;
                    $error = 'Error trying to decode response: ' .json_last_error_msg();

                }
            } else {
                // empty body
                if (substr($http_response_code, 0, 1) == '2') {
                    $success = true;
                }
            }
        } else if ($success === null) {
            $body = $response;
            $success = true;
        }

        if (!$success) {
            Log::warning("Vault Error ($http_response_code): ".$error);
        }

        return [
            'success' => $success,
            'code'    => $http_response_code,
            'data'    => $json_data,
            'error'   => $error,
            'raw'     => $body,
        ];
    }

}
