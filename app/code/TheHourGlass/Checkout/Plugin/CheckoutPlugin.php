<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TheHourGlass\Checkout\Plugin;

/**
 * Class CheckoutPlugin
 *
 * @package TheHourGlass\Checkout\Plugin
 *
 */
class CheckoutPlugin
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Framework\View\Layout\BuilderFactory
     */
    protected $_layoutBuilderFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * CheckoutPlugin constructor.
     *
     * @param \Magento\Framework\Registry                   $registry
     * @param \Magento\Framework\View\LayoutFactory         $layoutFactory
     * @param \Magento\Framework\View\Layout\BuilderFactory $layoutBuilderFactory
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\View\Layout\BuilderFactory $layoutBuilderFactory
    ) {
        $this->_registry = $registry;
        $this->_layoutFactory = $layoutFactory;
        $this->_layoutBuilderFactory = $layoutBuilderFactory;
    }

    /**
     * @param \Amasty\Checkout\Helper\Item        $subject
     * @param \Closure                            $proceed
     * @param \Magento\Quote\Model\Quote          $quote
     * @param int|\Magento\Quote\Model\Quote\Item $item
     *
     * @return array
     */
    public function aroundGetItemOptionsConfig(
        \Amasty\Checkout\Helper\Item $subject, \Closure $proceed, \Magento\Quote\Model\Quote $quote, $item
    ) {
        /** @var \Magento\Catalog\Block\Product\View\Options $optionsBlock */
        $optionsBlock = $this->getLayout()->getBlock('amcheckout.options.prototype');

        $quoteItem = is_object($item) ? $item : $quote->getItemById($item);

        $additionalConfig = [];

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $quoteItem->getProduct();

        $product->setPreconfiguredValues(
            $product->processBuyRequest($quoteItem->getBuyRequest())
        );

        // Fix issue in vendor/magento/module-tax/Observer/GetPriceConfigurationObserver.php
        $oldRegistryProduct = $this->_registry->registry('current_product');
        if ($oldRegistryProduct) {
            $this->_registry->unregister('current_product');
        }
        $this->_registry->register('current_product', $product);

        if ($quoteItem->getData('product_type') == 'configurable') {
            $buyRequest = $quoteItem->getBuyRequest();

            /** @var \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $configurableAttributesBlock */
            $configurableAttributesBlock = $this->getLayout()->getBlock('amcheckout.super.prototype');

            /** @var \Magento\Swatches\Block\Product\Renderer\Configurable $swatchesBlock */
            $swatchesBlock = $this->getLayout()->getBlock('product.info.options.swatches');
            $swatchesBlock->setProduct($product);

            $configurableAttributesBlock->unsetData('allow_products');
            $configurableAttributesBlock->addData(
                [
                    'product'    => $product,
                    'quote_item' => $quoteItem,
                ]
            );

            $configurableAttributesConfig = [
                'selectedAttributes' => $buyRequest['super_attribute'],
                'template'           => $configurableAttributesBlock->toHtml(),
                'spConfig'           => $configurableAttributesBlock->getJsonConfig(),
                'jsonSwatchConfig'   => $swatchesBlock->getJsonSwatchConfig(),
            ];

            $additionalConfig['configurableAttributes'] = $configurableAttributesConfig;
        }

        if ($quoteItem->getProduct()->getOptions()) {
            $optionsBlock->setProduct($product);

            $customOptionsConfig = [
                'template'     => $optionsBlock->toHtml(),
                'optionConfig' => $optionsBlock->getJsonConfig(),
            ];

            $additionalConfig['customOptions'] = $customOptionsConfig;
        }

        $this->_registry->unregister('current_product');
        if ($oldRegistryProduct) {
            $this->_registry->register('current_product', $oldRegistryProduct);
        }

        return $additionalConfig;
    }

    /**
     * @return \Magento\Framework\View\LayoutInterface
     */
    protected function getLayout()
    {
        if ($this->layout === null) {
            $layout = $this->_layoutFactory->create();

            $this->_layoutBuilderFactory->create(
                \Magento\Framework\View\Layout\BuilderFactory::TYPE_LAYOUT, ['layout' => $layout]
            );
            $layout->getUpdate()->addHandle(['default', 'amasty_checkout_prototypes']);

            /** @var \Magento\Framework\View\Element\AbstractBlock $block */
            foreach ($layout->getAllBlocks() as $block) {
                $block->setData('area', 'frontend');
            }

            $this->layout = $layout;
        }

        return $this->layout;
    }
}