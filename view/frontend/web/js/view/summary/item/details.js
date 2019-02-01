/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'uiComponent',
        'mage/storage',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/get-totals',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/quote',
        'Mangoit_Onepagecheckout/js/action/reload-shipping-method',
        'Magento_Checkout/js/action/get-payment-information',
        'Mangoit_Onepagecheckout/js/model/gift-wrap',
        'Magento_Ui/js/modal/confirm',
        'Magento_Ui/js/modal/alert',
        'mage/translate',
        'Magento_Catalog/js/price-utils'

    ],
    function (
        $,
        Component,
        storage,
        customerData,
        getTotalsAction,
        totals,
        quote,
        reloadShippingMethod,
        getPaymentInformation,
        giftWrapModel,
        confirm,
        alertPopup,
        Translate,
        priceUtils
    ) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mangoit_Onepagecheckout/summary/item/details'
            },
            getValue: function (quoteItem) {
                return quoteItem.name;
            },

            addQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty + 1);
            },

            minusQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty - 1);
            },

            updateNewQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty);
            },
            
            deleteItem: function (data) {
                var self = this;
                confirm({
                    content: Translate('Do you want to remove the item from cart?'),
                    actions: {
                        confirm: function () {
                            self.updateQty(data.item_id, 'delete', 0);
                        },
                        always: function (event) {
                            event.stopImmediatePropagation();
                        }
                    }
                });

            },

            showOverlay: function () {
                jQuery('#ajax-loader3').show();
                jQuery('#control_overlay_review').show();
            },

            hideOverlay: function () {
                jQuery('#ajax-loader3').hide();
                jQuery('#control_overlay_review').hide();
            },

            showPaymentOverlay: function () {
                jQuery('#control_overlay_payment').show();
                jQuery('#ajax-payment').show();
            },

            hidePaymentOverlay: function () {
                jQuery('#control_overlay_payment').hide();
                jQuery('#ajax-payment').hide();
            },

            updateTotal: function (point) {
                var listReward = {
                    '0': 'rewardpoint-earning',
                    '1': 'rewardpoint-spending',
                    '2': 'rewardpoint-use_point'
                };
                totals.isLoading(true);
                jQuery.ajax({
                    url: rewardpointConfig.urlUpdateTotal,
                    type: 'POST',
                    data: {'reward_sales_rule': 'rate', 'reward_sales_point': point},
                    complete: function (data) {
                        var arrDataReward = jQuery.map(jQuery.parseJSON(data.responseText), function (value, index) {
                            return [value];
                        });
                        jQuery.dataReward = arrDataReward;
                        var deferred = jQuery.Deferred();
                        getPaymentInformation(deferred);
                        jQuery.when(deferred).done(function () {
                            jQuery.each(listReward, function (key, val) {
                                jQuery('tr.' + val).show();
                                jQuery('tr.' + val + ' td.amount span').text(jQuery.dataReward[key]);
                            })
                            totals.isLoading(false);
                        });
                    },
                });
            },

            updateQty: function (itemId, type, qty) {
                var params = {
                    itemId: itemId,
                    qty: qty,
                    updateType: type
                };
                var self = this;
                this.showOverlay();
                storage.post(
                    'onepage/quote/update',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                        var miniCart = jQuery('[data-block="minicart"]');
                        miniCart.trigger('contentLoading');
                        customerData.reload('cart', true);
                        miniCart.trigger('contentUpdated');
                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                        if (result.error) {
                            alertPopup({
                                content: Translate(result.error),
                                autoOpen: true,
                                clickableOverlay: true,
                                focus: "",
                                actions: {
                                    always: function () {

                                    }
                                }
                            });
                        }

                        if (result.cartEmpty || result.is_virtual) {
                            window.location.reload();
                        } else {
                            if (result.giftwrap_amount && !result.error) {
                                giftWrapModel.setGiftWrapAmount(result.giftwrap_amount);
                            }
                            if (result.rewardpointsEarning) {
                                jQuery('tr.rewardpoint-earning td.amount span').text(result.rewardpointsEarning);
                            }
                            if (result.rewardpointsSpending) {
                                jQuery('tr.rewardpoint-spending td.amount span').text(result.rewardpointsSpending);
                            }
                            if (result.rewardpointsUsePoint) {
                                jQuery('tr.rewardpoint-use_point td.amount span').text(result.rewardpointsUsePoint);
                            }
                            if (result.affiliateDiscount) {
                                jQuery('tr td.amount span').each(function () {
                                    if (jQuery(this).data('th') == Translate('Affiliateplus Discount')) {
                                        if (result.affiliateDiscount) {
                                            jQuery(this).text(priceUtils.formatPrice(result.affiliateDiscount, quote.getPriceFormat()));
                                            jQuery(this).show();
                                        } else {
                                            jQuery(this).hide();
                                        }
                                    }
                                })
                            }
                            if (result.getRulesJson && window.checkoutConfig.isCustomerLoggedIn) {
                                var rewardSliderRules = jQuery.parseJSON(result.getRulesJson).rate;
                                var jQueryrange = jQuery("#range_reward_point");
                                var rewardpointConfig = result;
                                rewardpointConfig.checkMaxpoint = parseInt(rewardpointConfig.checkMaxpoint);
                                if (rewardpointConfig.checkMaxpoint) {
                                    self.updateTotal(rewardSliderRules.sliderOption.maxPoints);
                                    jQuery('#reward_sales_point').val(rewardSliderRules.sliderOption.maxPoints);
                                }
                                var slider = jQueryrange.data("ionRangeSlider");
                                slider.update({
                                    grid:true,
                                    grid_num:((rewardSliderRules.sliderOption.maxPoints<4)?rewardSliderRules.sliderOption.maxPoints:4),
                                    min:rewardSliderRules.sliderOption.minPoints,
                                    max:rewardSliderRules.sliderOption.maxPoints,
                                    step:rewardSliderRules.sliderOption.pointStep,
                                    from:((rewardpointConfig.checkMaxpoint)?rewardSliderRules.sliderOption.maxPoints:rewardpointConfig.usePoint),
                                    onUpdate: function (data) {
                                        if (rewardSliderRules.sliderOption.maxPoints == data.from) {
                                            jQuery('#reward_max_points_used').attr('checked','checked');
                                        } else {
                                            jQuery('#reward_max_points_used').removeAttr('checked');
                                        }
                                        jQuery("#reward_sales_point").val(data.from);
                                        self.updateTotal(data.from);
                                    }
                                });
                            }
                            reloadShippingMethod();
                            self.showPaymentOverlay();
                            getPaymentInformation().done(function () {

                                self.hidePaymentOverlay();
                                self.hideOverlay();
                            });
                        }

                    }
                );
            }


        });
    }
);
