/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Osc
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Customer/js/model/customer'
], function ($, Component, storage, customer) {
    'use strict';
    if (!customer.isLoggedIn()) {
        var cacheKey = 'osc-data';

        var getData = function () {
            return storage.get(cacheKey)();
        };

        var saveData = function (checkoutData) {
            storage.set(cacheKey, checkoutData);
        };

        return {
            setData: function (key, data) {
                var obj = getData();
                obj[key] = data;
                saveData(obj);
            },

            getData: function (key) {
                if (typeof key === 'undefined') {
                    return getData();
                }

                return getData()[key];
            }
        }
    } else {
        return Component.extend({
            initialize: function () {
                this._super();
            }
        });
    }
});