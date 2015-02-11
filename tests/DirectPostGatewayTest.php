<?php
namespace Omnipay\NMI;

use Omnipay\Tests\GatewayTestCase;

class DirectPostGatewayTest extends GatewayTestCase
{
    protected $purchaseOptions;
    protected $captureOptions;
    protected $voidOptions;
    protected $refundOptions;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new DirectPostGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'card'   => $this->getValidCard()
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '2577708057'
        );

        $this->voidOptions = array(
            'transactionReference' => '2577708057'
        );

        $this->refundOptions = array(
            'transactionReference' => '2577725848'
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('DirectPostAuthSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577708057', $response->getTransactionReference());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('DirectPostAuthFailure.txt');

        $this->purchaseOptions['amount'] = '0.00';

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('2577711599', $response->getTransactionReference());
        $this->assertSame('DECLINE', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('DirectPostSaleSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577715564', $response->getTransactionReference());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('DirectPostSaleFailure.txt');

        $this->purchaseOptions['amount'] = '0.00';

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('2577715978', $response->getTransactionReference());
        $this->assertSame('DECLINE', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('DirectPostCaptureSuccess.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577708057', $response->getTransactionReference());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('DirectPostCaptureFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('2577708057', $response->getTransactionReference());
        $this->assertSame('A capture requires that the existing transaction be an AUTH REFID:143498124', $response->getMessage());
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('DirectPostVoidSuccess.txt');

        $response = $this->gateway->void($this->voidOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577708057', $response->getTransactionReference());
        $this->assertSame('Transaction Void Successful', $response->getMessage());
    }

    public function testVoidFailure()
    {
        $this->setMockHttpResponse('DirectPostVoidFailure.txt');

        $response = $this->gateway->void($this->voidOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('2577708057', $response->getTransactionReference());
        $this->assertSame('Only transactions pending settlement can be voided REFID:143498494', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('DirectPostRefundSuccess.txt');

        $response = $this->gateway->void($this->refundOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577725848', $response->getTransactionReference());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function testRefundFailure()
    {
        $this->setMockHttpResponse('DirectPostRefundFailure.txt');

        $response = $this->gateway->void($this->refundOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getTransactionReference());
        $this->assertSame('Refund amount may not exceed the transaction balance REFID:143498703', $response->getMessage());
    }

    public function testCreditSuccess()
    {
        $this->setMockHttpResponse('DirectPostCreditSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2577728141', $response->getTransactionReference());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function testCreditFailure()
    {
        $this->setMockHttpResponse('DirectPostCreditFailure.txt');

        $this->purchaseOptions['amount'] = '0.00';

        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getTransactionReference());
        $this->assertSame('Invalid amount REFID:143498834', $response->getMessage());
    }

    public function testCreateCard()
    {

    }

    public function testUpdateCard()
    {

    }

    public function testDeleteCard()
    {
        
    }
}
