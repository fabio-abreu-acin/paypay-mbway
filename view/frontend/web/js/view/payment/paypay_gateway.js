/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'paypay_gateway',
                component: 'Magento_Paypay/js/view/payment/method-renderer/paypay_gateway'
            },
            {
                type: 'paypay_gateway_1',
                component: 'Magento_Paypay/js/view/payment/method-renderer/paypay_gateway_1'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
