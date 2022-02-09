<?php
namespace MangoIt\BundleProductImage\Plugin\Product\View\Options;

use \Magento\Catalog\Model\Config\Source\Product\Options\Price;

/**
 * Class AbstractOptions
 * @package MangoIt\BundleProductImage\Plugin\Product\View\Options
 */
class PricePlugin
{
    const VALUE_FIXED = 'fixed';
    const VALUE_PERCENT = 'percent';
    const VALUE_PERPERSON = 'pp';

    /**
     * @param Price $price
     * @return array
     */
    public function aftertoOptionArray(Price $price)
    {
        return [
            ['value' => self::VALUE_FIXED, 'label' => __('Fixed')],
            ['value' => self::VALUE_PERCENT, 'label' => __('Percent')],
            ['value' => self::VALUE_PERPERSON, 'label' => __('PP')],
        ];
    }

}