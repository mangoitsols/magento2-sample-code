<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MangoIt\BundleProductImage\Model\ResourceModel\User;

/**
 * Admin user collection
 *
 * @api
 * @since 100.0.2
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magento\User\Model\User::class, \Magento\User\Model\ResourceModel\User::class);
    }

    /**
     * Collection Init Select
     *
     * @return $this
     * @since 101.1.0
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
            $this->getSelect()->joinLeft(
                ['user_role' => $this->getTable('authorization_role')],
                'main_table.user_id = user_role.user_id AND user_role.parent_id != 0',
                []
            )->joinLeft(
                ['detail_role' => $this->getTable('authorization_role')],
                'user_role.parent_id = detail_role.role_id',
                ['role_name']
            );
            return $this;
        }elseif($roleName == $administratorConfig){
            $this->getSelect()->joinLeft(
                ['user_role' => $this->getTable('authorization_role')],
                'main_table.user_id = user_role.user_id AND user_role.parent_id != 0',
                []
            )->joinLeft(
                ['detail_role' => $this->getTable('authorization_role')],
                'user_role.parent_id = detail_role.role_id',
                ['role_name']
            )->where('detail_role.role_name != "'.$superAdminConfig.'"');
            return $this;
        }elseif(!empty($roleName)){
            $this->getSelect()->joinLeft(
                ['user_role' => $this->getTable('authorization_role')],
                'main_table.user_id = user_role.user_id AND user_role.parent_id != 0',
                []
            )->joinLeft(
                ['detail_role' => $this->getTable('authorization_role')],
                'user_role.parent_id = detail_role.role_id',
                ['role_name']
            )->where('detail_role.role_name != "'.$superAdminConfig.'" AND detail_role.role_name != "'.$administratorConfig.'"');
            return $this;
        }else{
            $this->getSelect()->joinLeft(
                ['user_role' => $this->getTable('authorization_role')],
                'main_table.user_id = user_role.user_id AND user_role.parent_id != 0',
                []
            )->joinLeft(
                ['detail_role' => $this->getTable('authorization_role')],
                'user_role.parent_id = detail_role.role_id',
                ['role_name']
            );
            return $this;
        }
        
        //die("test");
    }
}
