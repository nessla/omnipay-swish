<?php

namespace Omnipay\Swish\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class PurchaseResponse extends AbstractResponse
{
    protected $statusCode;
    protected $response;

    public function __construct(RequestInterface $request, $response, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
        $this->response = $response;
    }

    public function isSuccessful()
    {
        return ($this->getCode() == 200 || $this->getCode() == 201);
    }

    public function getTransactionReference()
    {
        if (!empty($location = $this->response->getHeader('location'))) {
            $urlParts = explode('/', $location);
            return end($urlParts);
        }

        return null;
    }

    public function getMessage()
    {
        if (isset($this->data[0]['errorMessage'])) {
            return $this->data[0]['errorMessage'];
        }

        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
