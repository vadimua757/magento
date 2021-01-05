/**
 * Copyright © magebig.com - All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'underscore',
    'jquery/jquery-storageapi'
], function ($, Component, customerData, _) {
    'use strict';

    return Component.extend({
        defaults: {
            cookieMessages: [],
            messages: []
        },

        /**
         * Extends Component object by storage observable messages.
         */
        initialize: function () {
            this._super();

            this.cookieMessages = _.unique($.cookieStorage.get('mage-messages'), 'text');
            this.messages = customerData.get('messages').extend({
                disposableCustomerData: 'messages'
            });

            // Force to clean obsolete messages
            if (!_.isEmpty(this.messages().messages)) {
                customerData.set('messages', {});
            }

            $.cookieStorage.set('mage-messages', '');

            $(window).on('beforeunload', function(){
                $.cookieStorage.set('mage-messages', '');
            });
        },

        showHideMess: function () {
            $('.page .messages').slideDown();
            setTimeout(function () {
                $('.page .messages').slideUp();
                $.cookieStorage.set('mage-messages', '');
            }, 15000);
            $('.close-message, .close-message-bg').on('click', function () {
                $('.page .messages').slideUp();
                $.cookieStorage.set('mage-messages', '');
            });
        }
    });
});
