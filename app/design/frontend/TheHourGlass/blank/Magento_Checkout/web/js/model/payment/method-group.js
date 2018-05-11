/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
        'uiElement',
        'mage/translate'
], function (Element, $t) {
    'use strict';

    var DEFAULT_GROUP_ALIAS = 'default';

    return Element.extend({
        defaults: {
            alias: DEFAULT_GROUP_ALIAS,
            title: $t('Pay By'),
            sortOrder: 100,
            displayArea: 'payment-methods-items-${ $.alias }'
        },

        /**
         * Checks if group instance is default
         *
         * @returns {Boolean}
         */
        isDefault: function () {
            return this.alias === DEFAULT_GROUP_ALIAS;
        }
    });
});
