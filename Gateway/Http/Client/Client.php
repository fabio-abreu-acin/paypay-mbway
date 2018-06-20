<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paypay\Multibanco\Gateway\Http\Client;

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ConverterInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

require 'vendor/autoload.php';

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
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface
     * @param Logger $logger
     * @param ClientFactory $clientFactory
     * @param ConverterInterface | null $converter
     */
    public function __construct(UrlInterface $urlBuilder, Logger $logger, ClientFactory $clientFactory, ConverterInterface $converter = null)
    {
        $this->urlBuilder = $urlBuilder;
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
                'environment' => $transferObject->getClientConfig()['environment'], 
                'platformCode' => $transferObject->getClientConfig()['code'],
                'privateKey' => $transferObject->getClientConfig()['key'],
                'clientId' => $transferObject->getClientConfig()['vat'], // usually the client NIF
                'langCode' => 'PT'
            )
        );

        $client = \PayPay\PayPayWebservice::init($config);
        
        try {
            $order = new \PayPay\Structure\RequestPaymentOrder(
                array(
                    'amount' => $transferObject->getClientConfig()['amount'],
                    'productCode' => $transferObject->getClientConfig()['idOrder'], // Optional 
                )
            );
            $requestPayment = new \PayPay\Structure\RequestCreditCardPayment(
                $order,
                $this->urlBuilder->getUrl('checkout', ['_secure' => true]),
                '', /// optional 
                \PayPay\Structure\RequestCreditCardPayment::METHOD_CREDIT_CARD // default is credit card, other methods are available
            );

            $response = $client->doWebPayment($requestPayment);
            $result = $this->converter ? $this->converter->convert($response) : [$response];
            // save $response->token and $response->idTransaction
            // redirect to $response->url
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
}
