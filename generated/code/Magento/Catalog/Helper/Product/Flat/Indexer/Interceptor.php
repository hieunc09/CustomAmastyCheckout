<?php
namespace Magento\Catalog\Helper\Product\Flat\Indexer;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Product\Flat\Indexer
 */
class Interceptor extends \Magento\Catalog\Helper\Product\Flat\Indexer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\ResourceConnection $resource, \Magento\Eav\Model\Config $eavConfig, \Magento\Catalog\Model\Attribute\Config $attributeConfig, \Magento\Catalog\Model\ResourceModel\ConfigFactory $configFactory, \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Mview\View\Changelog $changelog, $addFilterableAttrs = false, $addChildData = false, $flatAttributeGroups = array())
    {
        $this->___init();
        parent::__construct($context, $resource, $eavConfig, $attributeConfig, $configFactory, $attributeFactory, $storeManager, $changelog, $addFilterableAttrs, $addChildData, $flatAttributeGroups);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatColumnsDdlDefinition()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatColumnsDdlDefinition');
        if (!$pluginInfo) {
            return parent::getFlatColumnsDdlDefinition();
        } else {
            return $this->___callPlugins('getFlatColumnsDdlDefinition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAddFilterableAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isAddFilterableAttributes');
        if (!$pluginInfo) {
            return parent::isAddFilterableAttributes();
        } else {
            return $this->___callPlugins('isAddFilterableAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAddChildData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isAddChildData');
        if (!$pluginInfo) {
            return parent::isAddChildData();
        } else {
            return $this->___callPlugins('isAddChildData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatColumns()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatColumns');
        if (!$pluginInfo) {
            return parent::getFlatColumns();
        } else {
            return $this->___callPlugins('getFlatColumns', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntityType');
        if (!$pluginInfo) {
            return parent::getEntityType();
        } else {
            return $this->___callPlugins('getEntityType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityTypeId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntityTypeId');
        if (!$pluginInfo) {
            return parent::getEntityTypeId();
        } else {
            return $this->___callPlugins('getEntityTypeId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributes');
        if (!$pluginInfo) {
            return parent::getAttributes();
        } else {
            return $this->___callPlugins('getAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeCodes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributeCodes');
        if (!$pluginInfo) {
            return parent::getAttributeCodes();
        } else {
            return $this->___callPlugins('getAttributeCodes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatIndexes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatIndexes');
        if (!$pluginInfo) {
            return parent::getFlatIndexes();
        } else {
            return $this->___callPlugins('getFlatIndexes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTablesStructure(array $attributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTablesStructure');
        if (!$pluginInfo) {
            return parent::getTablesStructure($attributes);
        } else {
            return $this->___callPlugins('getTablesStructure', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTable($name)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTable');
        if (!$pluginInfo) {
            return parent::getTable($name);
        } else {
            return $this->___callPlugins('getTable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatTableName($storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatTableName');
        if (!$pluginInfo) {
            return parent::getFlatTableName($storeId);
        } else {
            return $this->___callPlugins('getFlatTableName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute($attributeCode);
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAbandonedStoreFlatTables()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteAbandonedStoreFlatTables');
        if (!$pluginInfo) {
            return parent::deleteAbandonedStoreFlatTables();
        } else {
            return $this->___callPlugins('deleteAbandonedStoreFlatTables', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
