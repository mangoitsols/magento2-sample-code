<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MangoIt\BundleProductImage\Model\ResourceModel\Role\Grid;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;

/**
 * Admin role data grid collection
 */
class Collection extends \Magento\Authorization\Model\ResourceModel\Role\Collection
{
    /**
     * Prepare select for load
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $userAuthorization = $objectManager->create('Magento\Authorization\Model\UserContextInterface');
        $helper = $objectManager->get('MangoIt\BundleProductImage\Helper\Data');

        $userId = $userAuthorization->getUserId();

        $superAdminConfig = $helper->getStoreConfigValue("special-user-group/general/super_admin_role");
        $administratorConfig = $helper->getStoreConfigValue("special-user-group/general/administrator_role");
        //$superAdminConfig = $scopeConfig->getValue("special-user-group/general/super_admin_role", $storeScope);
        //$administratorConfig = $scopeConfig->getValue("special-user-group/general/administrator_role", $storeScope);

        $roleName = $helper->getRoleName($userId);

        if($roleName == $superAdminConfig){
            $this->addFieldToFilter('role_type', RoleGroup::ROLE_TYPE);
            return $this;
        }elseif($roleName == $administratorConfig){
            $this->addFieldToFilter('role_type', RoleGroup::ROLE_TYPE)->addFieldToFilter('role_name', ['nin' => "$superAdminConfig"]);
            return $this;
        }else{
            $this->addFieldToFilter('role_type', RoleGroup::ROLE_TYPE)->addFieldToFilter('role_name', ['nin' => ["$superAdminConfig","$administratorConfig"]]);
            return $this;
        }

        //$this->addFieldToFilter('role_type', RoleGroup::ROLE_TYPE);
        //return $this;
    }
}
