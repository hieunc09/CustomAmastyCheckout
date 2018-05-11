/**
 * Created by bachlee on 12/09/2017.
 */
/*
 * Copyright © 2017 Balance Internet Pty., Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Customer/js/customer-data',
    'jquery',
    'ko',
    'underscore'
], function (Component, customerData, $, ko, _) {
    'use strict';

    return Component.extend({
        shippingCounter: {},

        /**
         * @override
         */
        initialize: function () {
            var shippingCounterData = customerData.get('shipping-counter');

            this.update(shippingCounterData());
            shippingCounterData.subscribe(function (updatedContent) {
                this.update(updatedContent);
            }, this);
            return this._super();
        },

        /**
         * Init sidebar
         *
         * @return {Boolean}
         */
        getFreeShippingHtml: function () {

            if (this.isFreeShipping() === false && this.getData('freeShippingHtml') === '') {
                return this.getData('remainingAmountHtml');
            } else {
                return this.getData('freeShippingHtml');
            }
        },

        /**
         * Update free shipping counter content.
         *
         * @param {Object} updatedContent
         * @returns void
         */
        update: function (updatedContent) {
            _.each(updatedContent, function (value, key) {
                if (!this.shippingCounter.hasOwnProperty(key)) {
                    this.shippingCounter[key] = ko.observable();
                }
                this.shippingCounter[key](value);
            }, this);
        },

        /**
         * Get shipping counter param by name.
         * @param {String} name
         * @returns {*}
         */
        getData: function (name) {
            if (!_.isUndefined(name)) {
                if (!this.shippingCounter.hasOwnProperty(name)) {
                    this.shippingCounter[name] = ko.observable();
                }
            }

            return this.shippingCounter[name]();
        },

        /**
         * Returns free shipping state
         *
         * @returns {Boolean}
         */
        isFreeShipping: function () {
            return this.getData('isFreeShipping');
        }
    });
});
