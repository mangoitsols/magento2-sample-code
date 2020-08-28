<?php
/**
 * File is used for Backend module in Magento 2 MIS171051
 * MangoIt Backend
 *
 * Created 'product_not_bundle' variable which is used in theme 
 * Magento_Checkout/web/template/minicart/item/default.html
 *
 * @category Backend MIS171051
 * @package  MangoIt
 */
namespace MangoIt\Backend\CustomerData;

/**
 * Class DefaultItem  MIS171051
 * @package MangoIt\Backend\CustomerData
 */
class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{   
    /**
     * {@inheritdoc}
     */
    protected function doGetItemData()
    {
        $imageHelper = $this->imageHelper->init($this->getProductForThumbnail(), 'mini_cart_product_thumbnail');
        $isSoruceProdInCart = $this->checkIfSourceProductInCart($this->item);
        return [
            'options' => $this->getOptionList(),
            'qty' => $this->item->getQty() * 1,
            'item_id' => $this->item->getId(),
            'configure_url' => $this->getConfigureUrl(),
            'is_visible_in_site_visibility' => $this->item->getProduct()->isVisibleInSiteVisibility(),
            'product_id' => $this->item->getProduct()->getId(),
            'product_name' => $this->item->getProduct()->getName(),
            'product_sku' => $this->item->getProduct()->getSku(),
            'product_not_bundle' => ($this->item->getProduct()->getTypeId() != 'bundle') ? 1 : 0,
            'product_url' => $this->getProductUrl(),
            'product_has_url' => $this->hasProductUrl(),
            'product_price' => $this->checkoutHelper->formatPrice($this->item->getCalculationPrice()),
            'product_price_value' => $this->item->getCalculationPrice(),
            'is_soruce_prod_in_cart' => $isSoruceProdInCart,
            'product_image' => [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ],
            'canApplyMsrp' => $this->msrpHelper->isShowBeforeOrderConfirm($this->item->getProduct())
                && $this->msrpHelper->isMinimalPriceLessMsrp($this->item->getProduct()),
        ];
    }
}