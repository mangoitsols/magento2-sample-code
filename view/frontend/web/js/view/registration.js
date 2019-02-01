/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'Magento_Ui/js/model/messageList'
    ],
    function ($, Component, ko, messageList) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Mangoit_Onepagecheckout/registration',
                accountCreated: false,
                creationStarted: false,
                isFormVisible: true,
                customerRegistered: true
            },

            /**
             * Initialize observable properties
             */
            initObservable: function () {
                this._super()
                    .observe('accountCreated')
                    .observe('isFormVisible')
                    .observe('creationStarted')
                    .observe('customerRegistered');

                //customer registration
                var customerInfo = Object.values($.cookieStorage.get('newcustomer'));
                console.log(customerInfo);
                if (customerInfo.length) {
                    if (customerInfo[3]) {
                        //delete
                        $.cookieStorage.set('newcustomer', null);
                        var flag = false;
                        $.ajax({
                            type:"GET",
                            data:{"email":customerInfo[0],"fname":customerInfo[1],"lname":customerInfo[2],"password":customerInfo[3]},
                            url: this.accountCreateUrl,
                            async: false,
                            contentType: "application/json; charset=utf-8"
                        }).done(function (result) {
                            if (result != 0) {
                                flag = true;
                            }
                        }).fail(function (result) {
                            /*var response = $.parseJSON(result);
                            if (response = 'object') {
                                var message = response.message;
                                if (jQuery.trim(message) == "Unable to send mail.") {
                                    flag = true;
                                }
                            }*/
                        });

                        //hide default form
                        if (flag) {
                            this.customerRegistered(false);
                        }
                    }
                }
                
                return this;
            },

            /**
             * @return {*}
             */
            getEmailAddress: function () {
                return this.email;
            },

            /**
             * Create new user account
             */
            createAccount: function () {
                this.creationStarted(true);
                $.post(
                    this.registrationUrl
                ).done(
                    function (response) {

                        if (response.errors == false) {
                            this.accountCreated(true)
                        } else {
                            messageList.addErrorMessage(response);
                        }
                        this.isFormVisible(false);
                    }.bind(this)
                ).fail(
                    function (response) {
                        this.accountCreated(false)
                        this.isFormVisible(false);
                        messageList.addErrorMessage(response);
                    }.bind(this)
                );
            }
        });
    }
);
