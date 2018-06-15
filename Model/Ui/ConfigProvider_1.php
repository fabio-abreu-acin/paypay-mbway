<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Paypay\Model\Ui;

use Magento\Payment\Model\MethodInterface;
use Magento\Paypay\Gateway\Http\Client\ClientMock;

/**
 * Class ConfigProvider_1
 */
final class ConfigProvider_1 implements MethodInterface
{
    const CODE = 'paypay_gateway_1';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}
