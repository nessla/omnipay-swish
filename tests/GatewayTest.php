<?php

namespace Omnipay\Swish;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '100.00'));

        $this->assertInstanceOf('Omnipay\Swish\Message\PurchaseRequest', $request);
        $this->assertSame('100.00', $request->getAmount());
    }
}
