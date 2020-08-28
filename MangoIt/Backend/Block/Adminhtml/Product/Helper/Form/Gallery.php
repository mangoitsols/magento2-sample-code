<?php
/**
 * File is used for Backend module in Magento 2 MIS171051
 * MangoIt Backend
 *
 * Fix applied for product issue of image not found after
 * Any error arrives while creating a new product
 *
 * @category Backend MIS171051
 * @package  MangoIt
 */

namespace MangoIt\Backend\Block\Adminhtml\Product\Helper\Form;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Class Gallery  MIS171051
 * @package MangoIt\Backend\Block\Adminhtml\Product\Helper\Form
 */
class Gallery extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery
{
    /**
     * @var DataPersistorInterface MIS171051
     */
    private $dataPersistor;

    /**
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Registry $registry
     * @param \Magento\Framework\Data\Form $form
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Registry $registry,
        \Magento\Framework\Data\Form $form,
        $data = [],
        DataPersistorInterface $dataPersistor = null
    ) {
        $this->dataPersistor = $dataPersistor ?: ObjectManager::getInstance()->get(DataPersistorInterface::class);
        parent::__construct($context, $storeManager, $registry, $form, $data);
    }
    /**
     * Get product images
     *
     * @return array|null
     */
    public function getImages()
    {
        $images = $this->registry->registry('current_product')->getData('media_gallery') ?: null;
        if ($images === null) {
            $images = ((array)$this->dataPersistor->get('catalog_product'))['product']['media_gallery'] ?? null;
        }
        
        return $images;
    }
    /**
     * Get value for given type.
     *
     * @param string $type
     * @return string|null
     */
    public function getImageValue(string $type)
    {
        $product = (array)$this->dataPersistor->get('catalog_product');
        return $product['product'][$type] ?? null;
    }

}
