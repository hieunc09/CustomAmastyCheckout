/**
 * Copyright © 2018 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
	'jquery',
	'jquery/ui',
	'js/jcf',
	'domReady!'
], function ($) {
	'use strict';

	// replace all form elements with modified default options
    if ($('body').hasClass('checkout-cart-index')) {
        jcf.replaceAll();
	}
});
