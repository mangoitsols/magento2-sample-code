<?php
namespace MangoIt\BundleProductImage\Model\Config\Source;

class User implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $_collectionFactory;
   
    public function __construct(
        \Magento\Authorization\Model\RoleFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
    }

	public function toOptionArray()
	{
        $rolesCollection = $this->_collectionFactory->create()->getCollection();

		$roles = [];
	    foreach ($rolesCollection as $role) {
	    	if($role->getRoleType() == 'G'){
	    		$roles[] = [
		            'value' => $role->getRoleName(),
		            'label' => $role->getRoleName()
		        ];
	    	}
	    }
	    return $roles;
	}
}
?>