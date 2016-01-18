<?php

namespace Omnipay\Swish\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse, $httpResponse->json(), $httpResponse->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('AB23D7406ECE4542A80152D909EF9F6B', $response->getTransactionReference());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse, $httpResponse->json(), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Amount value is missing or not a valid number', $response->getMessage());
    }
}
