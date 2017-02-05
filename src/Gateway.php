<?php

namespace Omnipay\Swish;

use Omnipay\Common\AbstractGateway;

/**
 * Swish Class.
 *
 *
 * The Swish API uses a REST-like structure. Certificates are used
 * as the API Authorization framework. Request and response payloads are formatted as JSON.
 *
 * ### Test Mode
 *
 * In order to use the test mode you need to download the test certificate and convert it to PEM files.
 * Download the certificate from https://www.getswish.se/handel/ under the section "Testa din uppkoppling"
 * In the zip you will find the .p12 that needs to be converted with openssl (or similar);
 *
 * $ openssl pkcs12 -in Swish\ Merchant\ Test\ Certificate\ 1231181189.p12 -nocerts -out certificate.key
 * $ openssl pkcs12 -in Swish\ Merchant\ Test\ Certificate\ 1231181189.p12 -nokeys -out certificate.pem
 *
 * More info about converting PKCS#12 files with openssl at https://www.openssl.org/docs/manmaster/apps/pkcs12.html
 *
 * ### Example
 *
 * #### Initialize Gateway
 *
 * <code>
 *   // Create a gateway for the Swish gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('Swish');
 *
 *   // Initialize the gateway
 *   $gateway->initialize(array(
 *               'cert' => 'certificate.pem',
 *               'privateKey' => 'certificate.key', // Or array('certificate.key', 'password')
 *               'caCert' => 'root_cert_from_swish.pem',
 *               'testMode' => true
 *   ));
 * </code>
 *
 * #### Payment
 *
 *   $transaction = $gateway->purchase(array(
 *       'amount'                   => '10.00',
 *       'currency'                 => 'SEK',
 *       'notifyUrl'                => 'https://example.com/api/swishcb/paymentrequests',
 *       'payerAlias'               => '46701234567',
 *       'payeeAlias'               => '1234760039',
 *       'message'                  => 'Kingston USB Flash Drive 8 GB'
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase request was successfully sent!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 *
 * @link https://www.getswish.se/handel/
 * @link https://www.openssl.org/docs/manmaster/apps/pkcs12.html
 * @see Omnipay\Swish\Message\AbstractRequest
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Swish';
    }

    public function getDefaultParameters()
    {
        return array(
            'cert'       => null,
            'privateKey' => null,
            'caCert'     => null,
            'testMode'   => false,
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
