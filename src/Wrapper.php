<?php

namespace Tokenly\Vault;

use Error;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    protected $code = 500;

    /**
     * Wrapper constructor.
     *
     * @param $serviceDelegate
     */
    public function __construct($serviceDelegate)
    {
        $this->serviceDelegate = $serviceDelegate;
    }

    /**
     * @param string $method
     * @param array $args
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __call($method, array $args)
    {
        try {
            $this->response = call_user_func_array([$this->serviceDelegate, $method], $args);
        } catch (Exception $exception) {
            $this->exception = $exception;
        }

        return $this->getResponse();
    }

    /**
     * @return bool
     */
    protected function isSuccessful()
    {
        return count($this->errors) == 0;
    }

    /**
     * Setting response from exception if method response is avalibale
     */
    protected function setResponseFromException()
    {
        try {
            $this->response = $this->exception->response();
        } catch (Error $exception) {
            //
        }
    }

    /**
     * Setting data and code from response if methods are avalibale
     */
    protected function setPropertiesFromResponse()
    {
        try {
            $this->data = json_decode($this->response->getBody(), true);
            $this->code = $this->response->getStatusCode();
        } catch (Error $exception) {
            $this->data = (array)$this->response;
        }
    }

    /**
     * Trying to find errors in response, exeption or bad json
     */
    protected function setErrors()
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errors = ['Error trying to decode response: ' . json_last_error_msg()];
        } elseif (!empty($this->data['errors'])) {
            $this->errors = $this->data['errors'];
        } elseif ($this->exception) {
            $this->errors = [$this->exception->getMessage()];
        }
    }

    /**
     *
     */
    protected function logErrors()
    {
        Log::warning("Vault Error ($this->code): " . implode(", ", $this->errors));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getResponse()
    {
        $this->setResponseFromException();
        $this->setPropertiesFromResponse();
        $this->setErrors();

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

        return new JsonResponse($data, $this->code);
    }
}
