/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals',
        'Mangoit_Onepagecheckout/js/mangoit/plugins/jquery.nicescroll.min'
    ],
    function ($, Component, quote, riceUtils, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'Mangoit_Onepagecheckout/summary/grand-total-button'
            },
            totals: quote.getTotals(),
            //isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function () {
                return this.isFullMode();
            },
            getValue: function () {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('grand_total').value;
                }
                return this.getFormattedPrice(price);
            },
            getBaseValue: function () {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_grand_total;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            },
            getGrandTotalExclTax: function () {
                var totals = this.totals();
                if (!totals) {
                    return 0;
                }
                return this.getFormattedPrice(totals.grand_total);
            },
            isBaseGrandTotalDisplayNeeded: function () {
                var totals = this.totals();
                if (!totals) {
                    return false;
                }
                return totals.base_currency_code != totals.quote_currency_code;
            },
            showDetails:function () {
                $('.mangoit-minicart-items-wrapper-two').niceScroll({cursorcolor:"#e5e5e5",railpadding: { top: 0, right: 7, left: 0, bottom: 0 }});
                var summary_button = $('.mangoit-summary-cart');
                var arrow_button = $('.mangoit-arrow-down');
                //var table = $('#onepagecheckout-sidebar .table-totals, #onepagecheckout-sidebar .items-in-cart');
                var table = $('.mangoit-onepagecheckout-summary-wrapper');

                if (table.hasClass('show') && summary_button.hasClass('mangoit-summary-cart-arrow') && arrow_button.hasClass('mangoit-arrow-down')) {
                    table.removeClass('show');
                    summary_button.removeClass('mangoit-summary-cart-arrow');
                    arrow_button.removeClass('mangoit-arrow-up');
                } else {
                    table.addClass('show');
                    summary_button.addClass('mangoit-summary-cart-arrow');
                    arrow_button.addClass('mangoit-arrow-up');
                }
            }
        });
    }
);

