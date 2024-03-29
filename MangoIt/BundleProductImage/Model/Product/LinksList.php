<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MangoIt\BundleProductImage\Model\Product;

class LinksList extends \Magento\Bundle\Model\Product\LinksList
{
    /**
     * @var \Magento\Bundle\Api\Data\LinkInterfaceFactory
     */
    protected $linkFactory;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @param \Magento\Bundle\Api\Data\LinkInterfaceFactory $linkFactory
     * @param Type $type
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        \Magento\Bundle\Api\Data\LinkInterfaceFactory $linkFactory,
        \Magento\Bundle\Model\Product\Type $type,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    ) {
        $this->linkFactory = $linkFactory;
        $this->type = $type;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Bundle Product Items Data
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param int $optionId
     * @return \Magento\Bundle\Api\Data\LinkInterface[]
     */
    public function getItems(\Magento\Catalog\Api\Data\ProductInterface $product, $optionId)
    {
        $selectionCollection = $this->type->getSelectionsCollection([$optionId], $product);

        $productLinks = [];
        /** @var \Magento\Catalog\Model\Product $selection */
        foreach ($selectionCollection as $selection) {
            $bundledProductPrice = $selection->getSelectionPriceValue();
            if ($bundledProductPrice < 0) {
                $bundledProductPrice = $selection->getPrice();
            }
            $selectionPriceType = $product->getPriceType() ? $selection->getSelectionPriceType() : null;
            $selectionPrice = $bundledProductPrice ? $bundledProductPrice : null;

            /** @var \Magento\Bundle\Api\Data\LinkInterface $productLink */
            $productLink = $this->linkFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $productLink,
                $selection->getData(),
                \Magento\Bundle\Api\Data\LinkInterface::class
            );
            $productLink->setIsDefault($selection->getIsDefault())
                ->setId($selection->getSelectionId())
                ->setQty($selection->getSelectionQty())
                ->setCanChangeQuantity($selection->getSelectionCanChangeQty())
                ->setPrice($selectionPrice)
                ->setPriceType($selectionPriceType);
            $productLinks[] = $productLink;
        }
        return $productLinks;
    }
}
