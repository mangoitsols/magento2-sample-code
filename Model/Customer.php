<?php
/**
 * File is used for Onepagecheckout module in Magento 2
 * Mangoit Onepagecheckout
 *
 * @category Onepagecheckout
 * @package  Mangoit
 */
namespace Mangoit\Onepagecheckout\Model;

use Mangoit\Onepagecheckout\Api\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Config\Share as ConfigShare;

class Customer implements CustomerInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var ConfigShare
     */
    private $configShare;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param ConfigShare $configShare
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        ConfigShare $configShare
    ) {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->configShare = $configShare;
    }

    /**
     *
     * @api
     * @return
     */
    public function createAccount()
    {
        $userData = filter_input_array(INPUT_GET);
        $customerId = 0;
        $countUserdata = count($userData);
        if ($countUserdata) {
            // Get Website ID
            $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
            // Instantiate object (this is the most important part)
            $customer   = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            // Preparing data for new customer
            $customer->setEmail($userData['email']);
            $customer->setFirstname($userData['fname']);
            $customer->setLastname($userData['lname']);
            $customer->setPassword($userData['password']);
            try {
                // Save data
                $customer->save();
                $customerId = $customer->getId();
            } catch (\Exception $e) {
                $this->getResponse()->setBody($e);
            }
        }

        return $customerId;
    }
}
