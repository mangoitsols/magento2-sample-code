<?php
	namespace MangoIt\BundleProductImage\Observer;
 
	use Magento\Framework\Event\ObserverInterface;
	use Magento\Framework\App\RequestInterface;
 
	class CustomUpdatePrice implements ObserverInterface
	{
		public function execute(\Magento\Framework\Event\Observer $observer) {
			$item = $observer->getEvent()->getData('quote_item');			
			$item = ( $item->getParentItem() ? $item->getParentItem() : $item );

			if($item->getProductType() == 'bundle'){
				$product = $item->getProduct();
				$productOptions = $product->getTypeInstance(true)->getOrderOptions($product);
				$customOptionPriceVal = 0;

				if(array_key_exists('options', $productOptions)){
					foreach ($productOptions['options'] as $key => $value) {
				        foreach ($product->getOptions() as $o) {
				            $values = $o->getValues();
			                foreach ($values as $v) {
			                	$priceType = $v->getPriceType();
			                	if($priceType == 'pp'){
				                    if ($value['option_value'] == $v->getOptionTypeId()) {
				                        $customOptionPriceVal = $customOptionPriceVal + $v->getprice(); /* get price of custom option*/
				                    }
				                }
			                }
				        }
				    }
				}

				$originalPrice = $item->getProduct()->getPrice();
				$priceWithCustomOption = $item->getPrice() - $customOptionPriceVal;
				$qty = $item->getQty();
				$customOptionPrice = $priceWithCustomOption - $originalPrice;
				$customPrice = ($originalPrice * $qty) + $customOptionPrice;
				$customPrice = $customPrice + ($customOptionPriceVal * $qty);

				$finalPrice = $customPrice/$qty;
				$item->setCustomPrice(number_format($finalPrice,2));
			
				$item->setOriginalCustomPrice(number_format($finalPrice,2));
				$item->getProduct()->setIsSuperMode(true);
				$item->save();
			}
		}
 
	}
?>