<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paypay\Multibanco\Block\Checkout\Onepage\Success;

Class Response extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * Prepares block data
     *
     * @return void
     */
    protected $order;

    protected function _construct()
    {
        $this->setModuleName('Magento_Checkout');
        parent::_construct();
    }

    protected function prepareBlockData()
    {
        $this->order = $this->_checkoutSession->getLastRealOrder();
        $payment = $this->order->getPayment();
        $this->addData(
            [
                'link' => $payment->getAdditionalInformation('url'),
            ]
        );
    }

    public function isMethodPaypay()
    {
        if ($this->order->getPayment()->getMethod() == \Paypay\Multibanco\Model\Ui\ConfigProvider::CODE) {
            return true;
        }
        return false;
    }
}