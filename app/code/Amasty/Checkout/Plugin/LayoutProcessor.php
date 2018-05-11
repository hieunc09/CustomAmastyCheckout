<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin;

use Magento\Store\Model\ScopeInterface;

class LayoutProcessor
{
    const AMASTY_REWARD_POINTS_MODULE_NAMESPACE = 'Amasty_Rewards';

    /**
     * @var array
     */
    protected $orderFixes = [];

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    private $onepageHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;


    function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Amasty\Checkout\Helper\Onepage\Proxy $onepageHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->moduleManager = $moduleManager;
        $this->onepageHelper = $onepageHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $field
     * @param $order
     */
    public function setOrder($field, $order)
    {
        $this->orderFixes[$field] = $order;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param $result
     * @return mixed
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        $result
    ) {
        if ($this->scopeConfig->isSetFlag('amasty_checkout/general/enabled', ScopeInterface::SCOPE_STORE)) {
            $layoutRoot = &$result['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children'];

            foreach ($this->orderFixes as $code => $order) {
                $layoutRoot[$code]['sortOrder'] = $order;
            }

            foreach ($result['components']['checkout']['children']['steps']['children']['billing-step']['children']
                     ['payment']['children']['payments-list']['children'] as &$paymentMethod) {
                $paymentMethod['template'] = 'Amasty_Checkout/billing-address';
            }

            $renderPaymentMethods = &$result['components']['checkout']['children']['steps']['children']['billing-step']['children']
                                    ['payment']['children']['renders']['children'];

            if (isset($renderPaymentMethods['braintree'])) {
                $renderPaymentMethods['braintree']['component'] = 'Amasty_Checkout/js/view/checkout/payment/braintree';
            }

            if (!$this->moduleManager->isEnabled(self::AMASTY_REWARD_POINTS_MODULE_NAMESPACE)) {
                unset($result['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['afterMethods']['children']['rewards']);
            } else {
                $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
                ['children']['afterMethods']['children']['rewards'] += $this->onepageHelper->getRewardsData();
            }
        }

        return $result;
    }
}
