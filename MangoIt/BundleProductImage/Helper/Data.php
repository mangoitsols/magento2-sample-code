<?php
namespace MangoIt\BundleProductImage\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
/**
     * @var StoreManagerInterface
     */
    private $storeConfig;

    /**
     * @var CurrencyFactory
     */
    private $currencyCode;

    protected $resourceConnection;

    protected $scopeConfig;

    protected $categoryFactory;
    protected $_stockItemRepository;

    /**
     * Currency constructor.
     *
     * @param StoreManagerInterface $storeConfig
     * @param CurrencyFactory $currencyFactory
     */
    public function __construct(
        StoreManagerInterface $storeConfig,
        CurrencyFactory $currencyFactory,
        ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Category $categoryFactory,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->storeConfig = $storeConfig;
        $this->currencyCode = $currencyFactory->create();
        $this->resourceConnection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->categoryFactory = $categoryFactory;
        $this->_stockItemRepository = $stockItemRepository;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        $currentCurrency = $this->storeConfig->getStore()->getCurrentCurrencyCode();
        $currency = $this->currencyCode->load($currentCurrency);
        return $currency->getCurrencySymbol();
    }

    public function getBaseUrl()
    {
        $baseUrl = $this->storeConfig->getStore()->getBaseUrl();
        return $baseUrl;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeConfig->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    public function getStoreId()
    {
        $storeId = $this->storeConfig->getStore()->getStoreId();
        return $storeId;
    }

    public function getRoleName($userId)
    {
        $connection = $this->resourceConnection->getConnection();

        $sql = "SELECT `main_table`.*, `detail_role`.`role_name` FROM `admin_user` AS `main_table` LEFT JOIN `authorization_role` AS `user_role` ON main_table.user_id = user_role.user_id AND user_role.parent_id != 0 LEFT JOIN `authorization_role` AS `detail_role` ON user_role.parent_id = detail_role.role_id WHERE (main_table.user_id = ".$userId.")";

        $result = $connection->fetchAll($sql);
        $roleName = $result[0]['role_name'];

        return $roleName;
    }

    public function getStoreConfigValue($configName)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $configValue = $this->scopeConfig->getValue($configName, $storeScope);

        return $configValue;
    }

    public function getChildCategoryName($catId)
    {
        $childCategoryName = $this->categoryFactory->load($catId)->getName();
        return $childCategoryName;
    }
    
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}
