<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paypay\Multibanco\Gateway\Response;
use Paypay\Multibanco\Gateway\Http\Client\Client;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;

class TxnIdHandler implements HandlerInterface
{
    /**
     * Handles transaction id
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */

    public function handle(array $handlingSubject, array $response)
    {
        $response = $response[0];

        if (!isset($handlingSubject['payment'])
            || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }
        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();
        $payment->setAdditionalInformation('url', $response->url);
        $payment->setAdditionalInformation('token', $response->token);
        $payment->setAdditionalInformation('idTransaction', $response->idTransaction);
        /*if (isset($response->referencia)) {
            $payment->setAdditionalInformation('entidade', 'testee');
            $payment->setAdditionalInformation('referencia', 'testee');
            $payment->setAdditionalInformation('data_limite', 'testee');
            // $payment->setTransactionAdditionalInfo(\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS, $this->getDetails($response));
            $payment->setTransactionId($payment->getAdditionalInformation('referencia'));
        }*/
    }
}
