<?php

namespace Omnipay\Swish\Message;

class FetchTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return array();
    }

    protected function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return parent::getEndpoint().'/paymentrequests/'.$this->getTransactionReference();
    }
}
