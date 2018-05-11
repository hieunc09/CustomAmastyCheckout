/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/translate',
        'ko',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/checkout-data'
    ],
    function ($, quote ,$t, ko, checkoutDataResolver, selectShippingMethodAction, checkoutData) {
        "use strict";
        var shippingRates = ko.observableArray([]);

        return {
            isLoading: ko.observable(false),
            /**
             * Set shipping rates
             *
             * @param ratesData
             */
            setShippingRates: function (ratesData) {
                var self = this;
                /****/
                self.setDefaultShippingMethodByAmasty(ratesData);
                shippingRates(ratesData);
                shippingRates.valueHasMutated();
                checkoutDataResolver.resolveShippingRates(ratesData);
            },

            /**
             * Get shipping rates
             *
             * @returns {*}
             */
            getShippingRates: function () {
                return shippingRates;
            },

            setDefaultShippingMethodByAmasty: function (ratesData) {
                var shippingConfig = window.defaultShippingMethod;
                if(shippingConfig !== ''){
                    $.each(ratesData, function (key, data) {
                        var method = data.carrier_code +'_'+data.method_code;
                        if(method === shippingConfig){
                            selectShippingMethodAction(data);
                            checkoutData.setSelectedShippingRate(data['carrier_code'] + '_' + data['method_code']);
                        }
                    });
                }
            }
        };
    }
);