<?php

namespace Omnipay\Swish\Message;

class PurchaseRequest extends AbstractRequest
{
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/paymentrequests';
    }
}
