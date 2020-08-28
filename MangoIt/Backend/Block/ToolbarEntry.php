<?php
/**
 * File is used Backend module in Magento 2 MIS171051
 * MangoIt Backend
 *
 * @category Backend MIS171051
 * @package  MangoIt
 */
namespace MangoIt\Backend\Block;

/**
 * Class ToolbarEntry  MIS171051
 * @package MangoIt\Backend\Block\ToolbarEntry
 */
class ToolbarEntry extends \Magento\AdminNotification\Block\ToolbarEntry
{
    /**
     * @var \Magento\Framework\AuthorizationInterface MIS171051
     */
    protected $_authorization;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\AdminNotification\Model\ResourceModel\Inbox\Collection\Unread $notificationList
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\AdminNotification\Model\ResourceModel\Inbox\Collection\Unread $notificationList,
        \Magento\Framework\AuthorizationInterface $authorization,
        array $data = []
    ) {
        parent::__construct($context, $notificationList, $data);
        $this->_authorization = $authorization;
    }

    //Removing the notification bell icon for the customer admin MIS171051
    protected function _toHtml()
    {
        if ($this->_authorization->isAllowed('Magento_AdminNotification::show_toolbar')) {
            return parent::_toHtml();
        }
        return '';
    }
}
