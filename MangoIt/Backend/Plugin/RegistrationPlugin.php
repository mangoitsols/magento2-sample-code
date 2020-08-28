<?php
/**
 * Copyright (c) 2020 Mangoit. All rights reserved.
 */

namespace MangoIt\Backend\Plugin;

use Magento\Customer\Model\Registration;

class RegistrationPlugin
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    const XML_PATH_DISABLE_CUSTOMER_REGISTRATION = 'customer/create_account/disable_customer_registration';

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function afterIsAllowed(Registration $subject)
    {
        return false;
    }
}
