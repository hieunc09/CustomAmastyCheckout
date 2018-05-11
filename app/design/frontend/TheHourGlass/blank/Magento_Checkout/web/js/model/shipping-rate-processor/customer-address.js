/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/customer-data',
        'Amasty_Checkout/js/action/set-shipping-information'
    ],
    function (resourceUrlManager, quote, storage, shippingService, rateRegistry, fullScreenLoader, errorProcessor, customerData, setShippingInformationAction) {
        "use strict";
        return {
            getRates: function (address) {
                shippingService.isLoading(true);
                var cache = rateRegistry.get(address.getKey());
                if (cache) {
                    shippingService.setShippingRates(cache);
                    shippingService.isLoading(false);
                } else {
                    fullScreenLoader.startLoader();
                    storage.post(
                        resourceUrlManager.getUrlForEstimationShippingMethodsByAddressId(),
                        JSON.stringify({
                            addressId: address.customerAddressId
                        }),
                        false
                    ).done(
                        function (result) {
                            rateRegistry.set(address.getKey(), result);
                            shippingService.setShippingRates(result);
                            customerData.reload(['shipping-counter'], true);
                            fullScreenLoader.stopLoader();
                            if (result.length > 0) {
                                setShippingInformationAction();
                            }
                        }
                    ).fail(
                        function (response) {
                            shippingService.setShippingRates([]);
                            errorProcessor.process(response);
                            fullScreenLoader.stopLoader();
                        }
                    ).always(
                        function () {
                            shippingService.isLoading(false);
                        }
                    );
                }
            }
        };
    }
);
