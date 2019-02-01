<?php
/**
 * File is used for Onepagecheckout module in Magento 2
 * Mangoit Onepagecheckout
 *
 * @category Onepagecheckout
 * @package  Mangoit
 */
namespace Mangoit\Onepagecheckout\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ENABLE_ONEPAGECHECKOUT = 'mangoit_onepagecheckout/general/enable_in_frontend';
    const META_TITLE = 'mangoit_onepagecheckout/general/onepagecheckout_title';

    public function getEnable()
    {
        return $this->scopeConfig->getValue(self::ENABLE_ONEPAGECHECKOUT);
    }

    public function getMetaTitle()
    {
        return $this->scopeConfig->getValue(self::META_TITLE);
    }
}
