<?php

namespace Omnipay\Swish;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Swish';
    }

    public function getDefaultParameters()
    {
        return array(
            'cert' => null,
            'privateKey' => null,
            'caCert' => null,
            'testMode' => false
        );
    }

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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Swish\Message\PurchaseRequest', $parameters);
    }

    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Swish\Message\FetchTransactionRequest', $parameters);
    }
}
