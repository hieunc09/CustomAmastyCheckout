/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'mage/translate',
    'jquery',
    'ko',
    'underscore',
    'sidebar'
], function (Component, customerData, $t, $, ko, _) {
    'use strict';

    var sidebarInitialized = false,
        addToCartCalls = 0,
        miniCart;

    miniCart = $('[data-block=\'minicart\']');
    miniCart.on('dropdowndialogopen', function () {
        $('body').addClass('fixed_open');
        var $container = $('#minicart-items-wrapper');
        if ($(window).width() <= 768) {
            $container.bind('touchstart', function (ev) {
                var $this = $(this);
                var scroller = $container.get(0);

                if ($this.scrollTop() === 0) $this.scrollTop(1);
                var scrollTop = scroller.scrollTop;
                var scrollHeight = scroller.scrollHeight;
                var offsetHeight = scroller.offsetHeight;
                var contentHeight = scrollHeight - offsetHeight;
                if (contentHeight === scrollTop) {
                    $this.scrollTop(scrollTop - 1);
                }
            });
        }
        if ($(".minicart-wrapper .counter-number").html() === "<!-- ko text: getCartParam('summary_count') -->0<!-- /ko -->") {
            window.location.href = jQuery(this).find(".showcart").attr("href");
        }
        initSidebar();
    });

    miniCart.on('dropdowndialogclose', function () {
        $('body').removeClass('fixed_open');
    });

    /**
     * @return {Boolean}
     */
    function initSidebar() {
        if (miniCart.data('mageSidebar')) {
            miniCart.sidebar('update');
        }

        if (!$('[data-role=product-item]').length) {
            return false;
        }
        miniCart.trigger('contentUpdated');

        if (sidebarInitialized) {
            return false;
        }
        sidebarInitialized = true;
        miniCart.sidebar({
            'targetElement': 'div.block.block-minicart',
            'url': {
                'checkout': window.checkout.checkoutUrl,
                'update': window.checkout.updateItemQtyUrl,
                'remove': window.checkout.removeItemUrl,
                'loginUrl': window.checkout.customerLoginUrl,
                'isRedirectRequired': window.checkout.isRedirectRequired
            },
            'button': {
                'checkout': '#top-cart-btn-checkout',
                'remove': '#mini-cart a.action.delete',
                'close': '#btn-minicart-close'
            },
            'showcart': {
                'parent': 'span.counter',
                'qty': 'span.counter-number',
                'label': 'span.counter-label'
            },
            'minicart': {
                'list': '#mini-cart',
                'content': '#minicart-content-wrapper',
                'qty': 'div.items-total',
                'subtotal': 'div.subtotal span.price',
                'maxItemsVisible': window.checkout.minicartMaxItemsVisible
            },
            'item': {
                'qty': ':input.cart-item-qty',
                'button': ':button.update-cart-item'
            },
            'confirmMessage': $.mage.__(
                'Are you sure you would like to remove this item from the shopping bag?'
            )
        });
    }

    return Component.extend({
        shoppingCartUrl: window.checkout.shoppingCartUrl,
        maxItemsToDisplay: window.checkout.maxItemsToDisplay,
        cart: {},

        /**
         * @override
         */
        initialize: function () {
            var self = this,
                cartData = customerData.get('cart');

            this.update(cartData());
            cartData.subscribe(function (updatedCart) {
                addToCartCalls--;
                this.isLoading(addToCartCalls > 0);
                sidebarInitialized = false;
                this.update(updatedCart);
                initSidebar();
            }, this);
            $('[data-block="minicart"]').on('contentLoading', function (event) {
                addToCartCalls++;
                self.isLoading(true);
            });
            if (cartData().website_id !== window.checkout.websiteId) {
                customerData.reload(['cart'], false);
            }

            return this._super();
        },
        isLoading: ko.observable(false),
        initSidebar: initSidebar,

        /**
         * @return {Boolean}
         */
        closeSidebar: function () {
            var minicart = $('[data-block="minicart"]');
            minicart.on('click', '[data-action="close"]', function (event) {
                event.stopPropagation();
                minicart.find('[data-role="dropdownDialog"]').dropdownDialog('close');
            });

            return true;
        },

        /**
         * @param {String} productType
         * @return {*|String}
         */
        getItemRenderer: function (productType) {
            return this.itemRenderer[productType] || 'defaultRenderer';
        },

        /**
         * Update mini shopping cart content.
         *
         * @param {Object} updatedCart
         * @returns void
         */
        update: function (updatedCart) {
            _.each(updatedCart, function (value, key) {
                if (!this.cart.hasOwnProperty(key)) {
                    this.cart[key] = ko.observable();
                }
                this.cart[key](value);
            }, this);
            this.toggleFreeShipping();
        },

        /**
         * Toggle free shipping counter in cart based on items
         */
        toggleFreeShipping: function () {
            var itemInCart = this.getCartParam('summary_count'),
                $minicartWrapper = $('#minicart-content-wrapper');

            $minicartWrapper.find('.free-shipping-countdown').hide();

            if (itemInCart > 0) {
                $minicartWrapper.find('.free-shipping-countdown').show();
            }
        },

        /**
         * Get cart param by name.
         * @param {String} name
         * @returns {*}
         */
        getCartParam: function (name) {
            if (!_.isUndefined(name)) {
                if (!this.cart.hasOwnProperty(name)) {
                    this.cart[name] = ko.observable();
                }
            }

            return this.cart[name]();
        },

        /**
         * Returns array of cart items, limited by 'maxItemsToDisplay' setting.
         *
         * @returns []
         */
        getCartItems: function () {
            var items = this.getCartParam('items') || [];
            items = items.slice(parseInt(-this.maxItemsToDisplay, 10));

            return items;
        },

        /**
         * Returns count of cart line items.
         *
         * @returns {Number}
         */
        getCartLineItemsCount: function () {
            var items = this.getCartParam('items') || [];

            return parseInt(items.length, 10);
        }
    });
});
