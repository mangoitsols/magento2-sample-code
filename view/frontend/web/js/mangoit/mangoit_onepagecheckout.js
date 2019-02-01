define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'jquery/ui',
        'jquery/validate',
        'Mangoit_Onepagecheckout/js/mangoit/plugins/jquery.nicescroll.min'
    ],
    function ($, modal) {
        'use strict';
        $.widget('mage.mangoitOnepagecheckout', {
            popup: null,
            init: function () {
                this.showModal();
                this.inputText();
                this.cvvText();
                this.sendForm();
                this.newModal();
            },

            inputText: function () {
                $(document).off('blur', '#authorizenet_directpost_cc_number');
                $(document).on('blur', '#authorizenet_directpost_cc_number', function (e) {

                    if ($('#authorizenet_directpost_cc_number').val() == 0) {
                        $(this).closest('div.number').find('label').removeClass('focus');
                    }
                });

                $(document).off('focus', '#authorizenet_directpost_cc_number');
                $(document).on('focus', '#authorizenet_directpost_cc_number', function (e) {

                    $(this).closest('div.number').find('label').addClass('focus');


                });
            },
            cvvText: function () {
                $(document).off('blur', '#authorizenet_directpost_cc_cid');
                $(document).on('blur', '#authorizenet_directpost_cc_cid', function (e) {

                    if ($('#authorizenet_directpost_cc_cid').val() == 0) {
                        $(this).closest('div.cvv').find('label').removeClass('focus');
                    }
                });

                $(document).off('focus', '#authorizenet_directpost_cc_cid');
                $(document).on('focus', '#authorizenet_directpost_cc_cid', function (e) {

                    $(this).closest('div.cvv').find('label').addClass('focus');


                });
            },
            showModal: function () {
                var _self = this;
                $(document).off('click touchstart', '.actions-toolbar .remind');
                $(document).on('click touchstart', '.actions-toolbar .remind', function (e) {
                    e.preventDefault();
                    $('.mangoit-onepagecheckout-forgot-response-message').hide();
                    _self.displayModal();
                });
            },

            newModal: function () {
                var _self = this;
                $(document).on('click touchstart', '.actions-toolbar .remind', function (e) {
                    e.preventDefault();
                    _self.reopenModal();
                });
            },

            reopenModal: function () {
                $(".mangoit-onepagecheckout-forgot-main-wrapper").modal('openModal');
            },

            displayModal: function () {
                var modalContent = $(".mangoit-onepagecheckout-forgot-main-wrapper");
                this.popup = modalContent.modal({
                    autoOpen: true,
                    type: 'popup',
                    modalClass: 'mangoit-onepagecheckout-forgot-wrapper',
                    title: '',
                    buttons: [{
                        class: "mangoit-hidden-button-for-popup",
                        text: 'Back to Login',
                        click: function () {
                            $('.mangoit-onepagecheckout-forgot-response-message').hide();
                            this.closeModal();
                        }
                    }]
                });
            },

            sendForm: function () {
                $('.mangoit-forgot-password-submit').click(function (e) {
                    e.preventDefault();
                    var email = $('.mangoit-onepagecheckout-forgot-email').val();
                    var validator = $(".mangoit-onepagecheckout-forgot-form").validate();
                    var status = validator.form();
                    if (!status) {
                        return;
                    }
                    if (typeof(postUrl) != "undefined") {
                        var sendUrl = postUrl;
                    } else {
                        console.log("post url error.");
                    }
                    $.ajax({
                        type: "POST",
                        data: {email: email},
                        url: sendUrl,
                        showLoader: true
                    }).done(function (response) {
                        if (typeof(response.message != "undefined")) {
                            $('.mangoit-onepagecheckout-forgot-response-message').html(response.message);
                            $('.mangoit-onepagecheckout-forgot-email').val('');
                            $('.mangoit-onepagecheckout-forgot-response-message').show();
                        }
                    });
                });
            }
        });

        return $.mage.mangoitOnepagecheckout;

    }
);