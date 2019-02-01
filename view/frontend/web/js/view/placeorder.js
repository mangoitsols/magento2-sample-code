/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'mage/translate',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/select-billing-address'
    ],
    function (
        $,
        Component,
        ko,
        $t,
        quote,
        selectBillingAddress
    ) {
        'use strict';
        return Component.extend({
            initialize: function () {
                this._super();
            },
            prepareToPlaceOrder: function () {
                //set data in cookie to create account later
                $.cookieStorage.set('newcustomer', null);
                if ($('#create-account-checkbox').is(':visible')) {
                    if ($('#create-account-checkbox').prop('checked')) {
                        var email = $("input[id='customer-email']").val();
                        var firstname = $("input[name='firstname']").val();
                        var lastname = $("input[name='lastname']").val();
                        var password = $("input[id='osc-password']").val();
                        var customerInfo = [email, firstname, lastname, password];
                        if (password) {
                            var jsonData = JSON.stringify(customerInfo);
                            $.cookieStorage.set('newcustomer', jsonData); //set cookie
                        }
                    }
                }

                var paymentInfo = quote.paymentMethod();
                $('.alwMsg').remove();
                if (paymentInfo == null) {
                    var paymentMessage = '<div data-role="checkout-messages" class="messages alwMsg"><div class="message message-error error"><div data-ui-id="checkout-cart-validationmessages-message-error">Please select payment. method</div></div></div>';
                    $(paymentMessage).insertAfter($('#co-payment-form'));
                }

                //use billing address is same as shipping address
                /*var i = 0;
                $('input[name="billing-address-same-as-shipping"]').each(function() {
                    if ($(this).prop('checked') && (i == 0)) {
                        selectBillingAddress(quote.shippingAddress());
                        i++;
                    }
                });*/

                //shipping address
                var countryIdShip = quote.shippingAddress().countryId;
                var regionIdShip = quote.shippingAddress().regionId;
                var regionCodeShip = quote.shippingAddress().regionCode;
                var regionShip = quote.shippingAddress().region;
                var streetShip = quote.shippingAddress().street;
                var companyShip = quote.shippingAddress().company;
                var telephoneShip = quote.shippingAddress().telephone;
                var postcodeShip = quote.shippingAddress().postcode;
                var cityShip = quote.shippingAddress().city;
                var firstnameShip = quote.shippingAddress().firstname;
                var lastnameShip = quote.shippingAddress().lastname;

                //shipping address
                /*var countryIdBill = quote.billingAddress().countryId;
                var regionIdBill = quote.billingAddress().regionId;
                var regionCodeBill = quote.billingAddress().regionCode;
                var regionBill = quote.billingAddress().region;
                var streetBill = quote.billingAddress().street;
                var companyBill = quote.billingAddress().company;
                var telephoneBill = quote.billingAddress().telephone;
                var postcodeBill = quote.billingAddress().postcode;
                var cityBill = quote.billingAddress().city;
                var firstnameBill = quote.billingAddress().firstname;
                var lastnameBill = quote.billingAddress().lastname;*/

                //shiping info
                var shippingInfoUrl = window.checkoutConfig.shippingInfoUrl;
                var shippinMethodCarrierCode = window.checkoutConfig.shippinMethodCarrierCode;
                var shippinMethodCode = window.checkoutConfig.shippinMethodCode;

                //check if non logged in user
                if (shippinMethodCarrierCode) {
                    var shippingParams = {
                        "addressInformation":{
                            "shipping_address":{
                                "countryId":countryIdShip,
                                "regionId":regionIdShip,
                                "regionCode":regionCodeShip,
                                "region":regionShip,
                                "street":streetShip,
                                "company":companyShip,
                                "telephone":telephoneShip,
                                "postcode":postcodeShip,
                                "city":cityShip,
                                "firstname":firstnameShip,
                                "lastname":lastnameShip
                            },
                            "billing_address":{
                                "countryId":"US",
                                "regionId":"0",
                                "postcode":null,
                                "saveInAddressBook":null
                            },
                            "shipping_method_code":shippinMethodCode,
                            "shipping_carrier_code":shippinMethodCarrierCode
                        }
                    };
                    $.ajax({
                        type:"POST",
                        data:JSON.stringify(shippingParams),
                        url: shippingInfoUrl,
                        async: false,
                        contentType: "application/json; charset=utf-8"
                    }).done(function (result){});
                }

                //trigger actual place order
                $("#co-payment-form ._active button[type='submit']").click();
            }
        });
    }
);