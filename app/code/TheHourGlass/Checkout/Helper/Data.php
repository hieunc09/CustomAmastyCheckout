<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TheHourGlass\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * TheHourGlass Checkout Helper
 *
 * @package TheHourGlass\Core\Helper
 * 
 */
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    protected $_reponsitory;

    /**
     * @var \Magento\Swatches\Model\Swatch
     */
    protected $_swatch;

    /**
     * Data constructor.
     *
     * @param Context  $context
     * @param $eavConfig  $eavConfig
     * @param $repository $repository
     * @param $swatch $swatch
     */
    public function __construct(
        Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Attribute\Repository $repository,
        \Magento\Swatches\Model\Swatch $swatch
    ) {
        parent::__construct($context);
        $this->_eavConfig = $eavConfig;
        $this->_reponsitory = $repository;
        $this->_swatch = $swatch;
    }

    /**
     * Get Attribute Options
     * @return array
     */
    public function getAttributeOptions()
    {
        $attributes = $this->_eavConfig->getAttribute('catalog_product','color');
        $color = $this->_reponsitory->get('color')->getOptions();
        $attributeId= $attributes->getId();
        $data  = array();
        if($color) {
            foreach ($color as $item) {
                if ($item->getValue() !== '') {
                    $swatches = $this->_swatch->getCollection()->addFieldToFilter('option_id', $item->getValue())->load();
                    foreach ($swatches as $swatch) {
                        $data[$swatch->getOptionId()] = array('label' => $item->getLabel(), 'value' => $swatch->getValue(),'id' => $swatch->getOptionId());
                    }
                }
            }
        }
        $options[$attributeId] = $data;

        return $options;
    }

}
