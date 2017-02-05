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
        return $this->getCode() == 200 || $this->getCode() == 201;
    }

    public function getTransactionReference()
    {
        $location = $this->response->getHeader('location');
        if (!empty($location)) {
            $urlParts = explode('/', $location);

            return end($urlParts);
        }
    }

    public function getMessage()
    {
        if (isset($this->data[0]['errorMessage'])) {
            return $this->data[0]['errorMessage'];
        }
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
