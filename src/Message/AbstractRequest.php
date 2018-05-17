<?php

namespace Omnipay\Swish\Message;

use Omnipay\Common\Exception\InvalidResponseException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    protected $liveEndpoint = 'https://cpc.getswish.net/swish-cpcapi/api';
    protected $testEndpoint = 'https://mss.cpc.getswish.net/swish-cpcapi/api';

    public function getCert()
    {
        return $this->getParameter('cert');
    }

    public function setCert($value)
    {
        return $this->setParameter('cert', $value);
    }

    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    public function getCaCert()
    {
        return $this->getParameter('caCert');
    }

    public function setCaCert($value)
    {
        return $this->setParameter('caCert', $value);
    }

    public function getPayeePaymentReference()
    {
        return $this->getParameter('payeePaymentReference');
    }

    public function setPayeePaymentReference($value)
    {
        return $this->setParameter('payeePaymentReference', $value);
    }

    public function getPayerAlias()
    {
        return $this->getParameter('payerAlias');
    }

    public function setPayerAlias($value)
    {
        return $this->setParameter('payerAlias', $value);
    }

    public function getPayeeAlias()
    {
        return $this->getParameter('payeeAlias');
    }

    public function setPayeeAlias($value)
    {
        return $this->setParameter('payeeAlias', $value);
    }

    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getEndpoint()
    {
        $url = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;

        return $url.'/'.self::API_VERSION;
    }

    public function getData()
    {
        $this->validate('notifyUrl', 'amount', 'currency', 'payeeAlias');

        $data = array(
            'callbackUrl' => $this->getNotifyUrl(),
            'amount'      => $this->getAmount(),
            'currency'    => $this->getCurrency(),
            'payerAlias'  => $this->getPayerAlias(),
            'payeeAlias'  => $this->getPayeeAlias(),
        );

        return $data;
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() == 'GET') {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint().'?'.http_build_query($data),
                array(
                    'Content-type' => 'application/json',
                ),
                null,
                array(
                    'cert'    => $this->getCert(),
                    'ssl_key' => $this->getPrivateKey(),
                    'verify'  => $this->getCaCert(),
                )
            );
        } else {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                array(
                    'Content-type' => 'application/json',
                ),
                json_encode($data),
                array(
                    'cert'    => $this->getCert(),
                    'ssl_key' => $this->getPrivateKey(),
                    'verify'  => $this->getCaCert(),
                )
            );
        }

        try {
            $httpResponse = $httpRequest->send();

            return $this->response = $this->createResponse($httpResponse);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: '.$e->getMessage(),
                $e->getCode()
            );
        }
    }

    protected function createResponse($response)
    {
        $data = $response->getBody(true);
        $data = json_decode($data, true);
        $statusCode = $response->getStatusCode();

        return $this->response = new PurchaseResponse($this, $response, $data, $statusCode);
    }
}
