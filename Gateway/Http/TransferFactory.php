<?php

/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paypay\Multibanco\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Paypay\Multibanco\Gateway\Request\DataRequest;
use Magento\Framework\Exception\PaymentException;

class TransferFactory implements TransferFactoryInterface {

    /**
     * @var TransferBuilder
     */
    const SERVER = [
        'teste' => 'https://paypay.acin.pt/paypaybeta/index.php/paypayservices/paypayservices_c/wsdl',
        'producao' => 'https://www.paypay.pt/paypay/index.php/paypayservices/paypayservices_c/wsdl'
    ];

    const URL = [
        'teste' => 'https://paypay.acin.pt/paypaybeta/index.php/paypayservices/paypayservices_c/server',
        'producao' => 'https://www.paypay.pt/paypay/index.php/paypayservices/paypayservices_c/server'
    ];

    private $transferBuilder;
    private $api_key;

    /**
     * @param TransferBuilder $transferBuilder
     */
    public function __construct(
    TransferBuilder $transferBuilder
    ) {
        $this->transferBuilder = $transferBuilder;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request) {
        $this->api_key = $request['data_request']['chave'];
        return $this->transferBuilder
                        ->setBody($request['data_request'])
                        ->setMethod($request['method'])
                        ->setClientConfig(
                                [
                                    'wsdl' => $this->getServerURI()
                                ]
                        )
                        ->build();
    }

    private function getServerURI() {
        $api_key = stripos($this->api_key, 'demo');
        if ($api_key !== false) {
            return self::SERVER['dev'];
        }
        return self::SERVER['production'];
    }

    public function geraPagamento()
    {
        $classmap = array(
            'RequestEntity' => 'RequestEntity',
            'ResponseIntegrationState' => 'ResponseIntegrationState',
            'RequestInterval' => 'RequestInterval',
            'RequestPaymentReference' => 'RequestPaymentReference',
            'ResponseEntityPayments' => 'ResponseEntityPayments',
            'ResponseEntityPaymentReferences' => 'ResponseEntityPaymentReferences',
            'RequestCreditCardPayment' => 'RequestCreditCardPayment',
            'ResponseCreditCardPayment' => 'ResponseCreditCardPayment',
            'RequestReferenceDetails' => 'RequestReferenceDetails',
            'ResponseGetPayment' => 'ResponseGetPayment',
            'RequestPaymentOrder' => 'RequestPaymentOrder',
            'RequestWebhook' => 'RequestWebhook',
            'RequestPayment' => 'RequestPayment'

        );

        $options = array(
            'classmap' => $classmap,
            'location' => Configuration::get("WEBSERVICEURL"),
            'cache_wsdl' => WSDL_CACHE_NONE
        );


        
    }

}
