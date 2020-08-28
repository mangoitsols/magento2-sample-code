<?php
/**
 * File is used Backend module in Magento 2 MIS171051
 * MangoIt Backend
 * @category Backend MIS171051
 * @package  MangoIt
 */
namespace MangoIt\Backend\Model\Product\Option;

use Magento\Catalog\Api\ProductCustomOptionRepositoryInterface as OptionRepository;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class SaveHandler  MIS171051
 * @package MangoIt\Backend\Model\Product\Option
 */
class SaveHandler extends \Magento\Catalog\Model\Product\Option\SaveHandler
{
    /**
     * @param object $entity
     * @param array $arguments
     * @return \Magento\Catalog\Api\Data\ProductInterface|object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        $options = $entity->getOptions();
        $optionIds = [];

        if ($options) {
            $optionIds = array_map(function ($option) {
                /** @var \Magento\Catalog\Model\Product\Option $option */
                return $option->getOptionId();
            }, $options);
        }

        /** @var \Magento\Catalog\Api\Data\ProductInterface $entity */
        foreach ($this->optionRepository->getProductOptions($entity) as $option) {
            if (!in_array($option->getOptionId(), $optionIds)) {
                $this->optionRepository->delete($option);
            }
        }
        if ($options) {
            $hasChangedSku = $entity->dataHasChangedFor('sku');
            foreach ($options as $option) {
                if ($hasChangedSku) {
                    $option->setProductSku($entity->getSku());
                }

                $this->optionRepository->save($option);
            }
        }

        return $entity;
    }
}
