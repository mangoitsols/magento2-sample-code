<?php
/**
 * File is used for Onepagecheckout module in Magento 2
 * Mangoit Onepagecheckout
 *
 * @category Onepagecheckout
 * @package  Mangoit
 */
namespace Mangoit\Onepagecheckout\Model\Comment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\ScopeInterface;

class CustomBlockConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfiguration;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
    ) {
        $this->scopeConfiguration = $scopeConfiguration;
    }

    /**
     * @return array() $showHide
     */
    public function getConfig()
    {
        /**
         * @var array() $showHide
         */
        $showHide = [];
        /**
         * @var boolean $enabled
         */
        $enabled = $this->scopeConfiguration
            ->getValue(
                'mangoit_onepagecheckout/general/onepage_checkout_comments_enabled',
                ScopeInterface::SCOPE_STORE
            );
        $showHide['show_hide_custom_block'] = ($enabled) ? true:false;
        return $showHide;
    }
}
