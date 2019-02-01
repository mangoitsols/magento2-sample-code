/**
 * Mangoit
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the anylinux.com license that is
 * available through the world-wide-web at this URL:
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mangoit
 * @package     Mangoit_Onepagecheckout
 * @copyright   Copyright (c) 2018 Mangoit (http://www.anylinux.com/)
 * @license     https://www.anylinux.com/LICENSE.txt
 */

define([
    'jquery',
    'ko',
    'Mangoit_Onepagecheckout/js/view/form/element/email',
    'Magento_Customer/js/model/customer',
    'Mangoit_Onepagecheckout/js/view/onepagecheckout-data',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Customer/js/action/check-email-availability',
    'rjsResolver',
    'mage/validation'
], function ($, ko, Component, customer, oscData, additionalValidators, checkEmailAvailability, resolver) {
    'use strict';

    var cacheKey = 'form_register_chechbox',
        allowGuestCheckout = true,
        passwordMinLength = "8",
        passwordMinCharacter = "3";

    if (!customer.isLoggedIn()) {
        if (!customer.isLoggedIn() && !allowGuestCheckout) {
            oscData.setData(cacheKey, true);
        }
        return Component.extend({
            defaults: {
                template: 'Mangoit_Onepagecheckout/form/element/email',
                isLoginVisible: false,
                isLoading: false,
                isPasswordVisible: ko.observable(false),
                listens: {
                    email: 'emailHasChanged',
                    emailFocused: 'validateEmail'
                }
            },
            dataPasswordMinLength: passwordMinLength,
            dataPasswordMinCharacterSets: passwordMinCharacter,

            initialize: function () {
                this._super();

                if (!!this.email()) {
                    resolver(this.emailHasChanged.bind(this));
                }

                additionalValidators.registerValidator(this);
            },

            initObservable: function () {
                this._super()
                    .observe({
                        isCheckboxRegisterVisible: allowGuestCheckout,
                        isRegisterVisible: oscData.getData(cacheKey)
                    });

                this.isRegisterVisible.subscribe(function (newValue) {
                    oscData.setData(cacheKey, newValue);
                });

                return this;
            },

            validateEmail: function (focused) {
                var loginFormSelector = 'form[data-role=email-with-possible-login]',
                    usernameSelector = loginFormSelector + ' input[name=username]',
                    loginForm = $(loginFormSelector),
                    validator;

                loginForm.validation();

                if (focused === false) {
                    return !!$(usernameSelector).valid();
                }

                validator = loginForm.validate();

                return validator.check(usernameSelector);
            },

            validate: function (type) {
                if (customer.isLoggedIn() || !this.isRegisterVisible() || this.isPasswordVisible()) {
                    oscData.setData('register', false);
                    return true;
                }

                if (typeof type !== 'undefined') {
                    var selector = $('#osc-' + type);

                    selector.parents('form').validation();

                    return !!selector.valid();
                }

                var passwordSelector = $('#osc-password');
                passwordSelector.parents('form').validation();

                var password = !!passwordSelector.valid();
                var confirm = !!$('#osc-password-confirmation').valid();

                var result = password && confirm;
                if (result) {
                    oscData.setData('register', true);
                    oscData.setData('password', passwordSelector.val());
                }

                return result;

            }
        });
    } else {
        return Component.extend({
            initialize: function () {
                this._super();
            }
        });
    }
    
});
