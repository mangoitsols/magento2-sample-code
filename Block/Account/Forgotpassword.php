<?php
/**
 * File is used for Onepagecheckout module in Magento 2
 * Mangoit Onepagecheckout
 *
 * @category Onepagecheckout
 * @package  Mangoit
 */
namespace Mangoit\Onepagecheckout\Block\Account;

use Magento\Customer\Model\Url;
use Magento\Framework\View\Element\Template;

class Forgotpassword extends Template
{

    public $customerurl;

    public function __construct(
        Template\Context $context,
        Url $customerUrl,
        array $data = []
    ) {
        $this->customerurl = $customerUrl;
        parent::__construct($context, $data);
    }

    public function getLoginUrl()
    {
        return $this->customerurl->getLoginUrl();
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('customer/account/forgotpasswordpost', ['_secure' => true]);
    }

    public function getPostUrl()
    {
        return $this->getUrl('onepage/account/forgotpassword', ['_secure' => true]);
    }
}
