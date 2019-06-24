<?php

namespace Tokenly\Vault;

use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Jippi\Vault\Exception\ClientException;
use Jippi\Vault\Exception\ServerException;
use Jippi\Vault\ServiceFactory;

/**
 * Class Wrapper.
 */
class Wrapper
{
    /** @var ServiceFactory */
    protected $serviceDelegate;

    /** @var Response|array */
    protected $response;

    /** @var Exception */
    protected $exception;

    /** @var array */
    protected $errors = [];

    /** @var array */
    protected $data = [];

    /** @var int */
    protected $code = null;

    public function __construct($serviceDelegate)
    {
        $this->serviceDelegate = $serviceDelegate;
    }

    public function __call($method, $args)
    {
        try {
            $this->response = call_user_func_array([$this->serviceDelegate, $method], $args);
        } catch (Exception $exception) {
            $this->exception = $exception;
        }

        return $this->getResponse();
    }

    protected function isSuccessful()
    {
        return count($this->errors) == 0;
    }

    protected function setResponseFromException()
    {
        if (($this->exception instanceof ClientException) ||
            ($this->exception instanceof ServerException)) {
            $this->response = $this->exception->response();
        }
    }

    protected function setDataFromResponse()
    {
        $this->data = (array)$this->response;

        if ($this->response instanceof Response) {
            $this->data = json_decode($this->response->getBody()->getContents(), true) ?: [];
        }
    }

    protected function setErrors()
    {
        if ($this->exception) {
            $this->errors[] = $this->exception->getMessage();
        }

        if (isset($this->data['errors']) && is_array($this->data['errors'])) {
            $this->errors = array_merge($this->errors, $this->data['errors']);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errors[] = 'Error trying to decode response: ' . json_last_error_msg();
        }
    }

    protected function setCode()
    {
        if ($this->response instanceof Response) {
            $this->code = $this->response->getStatusCode();
        }
    }

    protected function logErrors()
    {
        Log::warning("Vault Error ($this->code): " . implode(", ", $this->errors));
    }

    protected function getResponse()
    {
        $this->setResponseFromException();
        $this->setDataFromResponse();
        $this->setErrors();
        $this->setCode();

        $data = [
            'success' => $this->isSuccessful(),
            'code' => $this->code,
        ];

        if ($this->isSuccessful()) {
            $data['data'] = $this->data;
        } else {
            $data['errors'] = $this->errors;
        }

        $this->logErrors();

        return $data;
    }
}
