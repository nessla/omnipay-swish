<?php

namespace Omnipay\Swish\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();

        $data['message'] = $this->getDescription();

        return $data;
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint().'/paymentrequests';
    }
}
