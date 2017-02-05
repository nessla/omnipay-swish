<?php

namespace Omnipay\Swish\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'payeePaymentReference' => '0123456789',
                'notifyUrl'             => 'https://example.com/api/swishcb/paymentrequests',
                'payerAlias'            => '46701234567',
                'payeeAlias'            => '1234760039',
                'amount'                => '100.00',
                'currency'              => 'SEK',
                'message'               => 'Kingston USB Flash Drive 8 GB',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('https://example.com/api/swishcb/paymentrequests', $data['callbackUrl']);
        $this->assertSame('46701234567', $data['payerAlias']);
        $this->assertSame('1234760039', $data['payeeAlias']);
        $this->assertSame('100.00', $data['amount']);
        $this->assertSame('SEK', $data['currency']);
    }

    public function testSend()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
    }
}
