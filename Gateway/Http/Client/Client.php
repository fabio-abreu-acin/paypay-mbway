<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paypay\Multibanco\Gateway\Http\Client;

use Magento\Framework\Webapi\Soap\ClientFactory;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ConverterInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Paypay\Multibanco\Structure;

/**
 * Class Soap
 * @package Magento\Payment\Gateway\Http\Client
 * @api
 */
class Client implements ClientInterface
{
    const SUCCESS = [
        'paga',
        'transferida',
        'em processamento'
    ];
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var ConverterInterface | null
     */
    private $converter;
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @param Logger $logger
     * @param ClientFactory $clientFactory
     * @param ConverterInterface | null $converter
     */
    public function __construct(Logger $logger, ClientFactory $clientFactory, ConverterInterface $converter = null)
    {
        $this->logger = $logger;
        $this->converter = $converter;
        $this->clientFactory = $clientFactory;
    }

    /**
     * Places request to gateway. Returns result as ENV array
     *
     * @param TransferInterface $transferObject
     * @return array
     * @throws \Magento\Payment\Gateway\Http\ClientException
     * @throws \Magento\Payment\Gateway\Http\ConverterException
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $config = \PayPay\Configuration::setup(
            array(
                'environment' => 'testing', // or production
                'platformCode' => '0004',
                'privateKey' => 'Y1JgnTGN2lMOz8OXLs0s',
                'clientId' => '503129445', // usually the client NIF
                'langCode' => 'PT'
            )
        );

        $client = \PayPay\PayPayWebservice::init($config);
        
        try {
            $order = new \PayPay\Multibanco\Structure\RequestPaymentOrder(
                array(
                    'amount' => 1000,
                    'productCode' => 'REF123', // Optional 
                    'productDesc' => 'Product description' // Optional
                )
            );
            $requestPayment = new \PayPay\Multibanco\Structure\RequestCreditCardPayment(
                $order,
                'http://www.your_store_url.com/return',
                'http://www.your_store_url.com/cancel', /// optional 
                \PayPay\Multibanco\Structure\RequestCreditCardPayment::METHOD_CREDIT_CARD // default is credit card, other methods are available
            );

            $result = $client->doWebPayment($requestPayment);
            // save $response->token and $response->idTransaction
            // redirect to $response->url
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }

}
