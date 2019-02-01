define(
    [
        'jquery',
        'ko',
        'uiComponent',
    ],
    function ($, ko, Component) {
        'use strict';
        var show_hide_custom_blockConfig = window.checkoutConfig.show_hide_custom_block;
        return Component.extend({
            defaults: {
                template: 'Mangoit_Onepagecheckout/checkout/comment'
            },
            canVisibleBlock: show_hide_custom_blockConfig
        });
    }
);
