<?php
/**
 * @category Mangoit
 * @package Mangoit_HideVendorAttrFromProductEdit
 */
namespace Mangoit\HideVendorAttrFromProductEdit\Plugin\Magento\Catalog\Ui\DataProvider\Product\Form\Modifier;

use \Psr\Log\LoggerInterface;
use \Mangoit\VendorAttribute\Model\Attributemodel;

/**
 * Class CustomOptions
 */
class CustomOptions
{
    /**
     * @var logger
     */
    private $logger;

    /**
     * @var Attributemodel
     */
    private $vendorAttribute;

    /**
     * @param LoggerInterface $logger
     * @param Attributemodel  $vendorAttribute
     */
    public function __construct(
        LoggerInterface $logger,
        Attributemodel $vendorAttribute
    ) {
        $this->logger          = $logger;
        $this->vendorAttribute = $vendorAttribute;
    }

    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterModifyMeta($subject, $result)
    {
        $attribute_to_filter = [];

        $collection = $this->vendorAttribute->getCollection()->addFieldToSelect('attribute_code');
        
        /*
        * Hiding assign seller section/group from product edit page
        *
        */
        if (isset($result['assign_seller'])) {
            if ($result['assign_seller']['children']['assignseller_field']['arguments']['data']['config']['value']) {
                $seller_id = $result['assign_seller']['children']['assignseller_field']['arguments']['data']['config']['value'];
                $collection->addFieldToFilter('vendor_id', ['neq'=> $seller_id]);
            }
        }

        /*
        * Hiding attribute of the sellers
        */
        foreach ($collection as $attribute) {
            $attribute_code = 'container_'.$attribute->getAttributeCode();
            if (isset($result['product-details']['children'][$attribute_code])) {
                unset($result['product-details']['children'][$attribute_code]);
            }
        }     

        return $result;
    }
}