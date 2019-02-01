
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Mangoit_Onepagecheckout/js/model/place-order-with-comments-mixin': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Ui/js/core/renderer/layout':'Mangoit_Onepagecheckout/js/core/renderer/layout'
        }
    }
};