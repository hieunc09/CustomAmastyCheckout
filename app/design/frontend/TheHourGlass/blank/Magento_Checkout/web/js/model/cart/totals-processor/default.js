/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'jquery',
        'underscore',
        'Magento_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/error-processor'
    ],
    function ($, _, resourceUrlManager, quote, storage, totalsService, errorProcessor) {
        'use strict';

        return {
            requiredFields: ['countryId', 'region', 'regionId', 'postcode'],

            /**
             * Get shipping rates for specified address.
             */
            estimateTotals: function (address) {
                var serviceUrl, payload, self = this;
                totalsService.isLoading(true);
                serviceUrl = resourceUrlManager.getUrlForTotalsEstimationForNewAddress(quote),
                    payload = {
                        addressInformation: {
                            address: _.pick(address, this.requiredFields)
                        }
                    };

                if (quote.shippingMethod() && quote.shippingMethod()['method_code']) {
                    payload.addressInformation['shipping_method_code'] = quote.shippingMethod()['method_code'];
                    payload.addressInformation['shipping_carrier_code'] = quote.shippingMethod()['carrier_code'];
                }

                storage.post(
                    serviceUrl, JSON.stringify(payload), false
                ).done(
                    function (result) {
                        quote.setTotals(result);
                        var grandTotalPrice = self.getGrandTotalPrice(result);
                        self.fixingTotalMobile(grandTotalPrice);
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                    }
                ).always(
                    function () {
                        totalsService.isLoading(false);
                    }
                );
            },
			
            getGrandTotalPrice: function (result) {
                var total = result.base_grand_total / 1;
                var baseCurrencyCode = result.base_currency_code;
                var quoteCurrencyCode = result.quote_currency_code;
                var format = window.checkoutConfig.priceFormat.pattern;

                if (baseCurrencyCode !== quoteCurrencyCode) {
                    total = (parseFloat(result.grand_total) + parseFloat(result.tax_amount)) / 1;
                }

                // return with the currency code ($) and decimal setting (default: 2)
                return {
                    total: format.replace(/%s/g, total.toFixed(window.checkoutConfig.priceFormat.precision))
                };
            },

            fixingTotalMobile: function (price) {
                var $checkoutPaymentTotal = $('.button-cart-holder .total-price-fixed span.price');

                if ($checkoutPaymentTotal.length > 0) {
                    $checkoutPaymentTotal.text(price.total);
                }
            }			
        };
    }
);
