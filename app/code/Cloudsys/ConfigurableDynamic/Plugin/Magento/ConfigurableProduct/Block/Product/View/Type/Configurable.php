<?php
/**
 * Created by PhpStorm.
 * User: thomasnordkvist
 * Date: 17-01-30
 * Time: 08:15
 */

namespace Cloudsys\ConfigurableDynamic\Plugin\Magento\ConfigurableProduct\Block\Product\View\Type;

use Cloudsys\ConfigurableDynamic\Helper\ConfigurableDynamicHelper;
use Magento\Catalog\Model\Product;

class Configurable
{

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    private $dynamicHelper;

    public function __construct(
        ConfigurableDynamicHelper $dynamicHelper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        $this->dynamicHelper = $dynamicHelper;
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;

    }

    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $result) {

        $jsonResult = $this->dynamicHelper->unserialize($result);
        $jsonResult['shortdis'] = [];

        foreach ($subject->getAllowProducts() as $simpleProduct) {
            //$jsonResult = $this->addVisibleAttributes($simpleProduct, $jsonResult);
        	$jsonResult = $this->addProductName($jsonResult, $simpleProduct);
            $jsonResult['shortdis'][$simpleProduct->getId()] = $this->getContentFromStaticBlock($simpleProduct->getShortDescription());
        }
        $result = $this->dynamicHelper->serialize($jsonResult);
        return $result;
    }

    private function addProductName(array $config, Product $product): array
    {
        $config['dynamic']['product_name'][$product->getId()] = [
            'value' => $product->getName(),
        ];

        return $config;
    }

    public function getContentFromStaticBlock($content)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($content);
    }
    private function addVisibleAttributes(Product $simpleProduct, $jsonResult)
    {
        foreach ($simpleProduct->getAttributes() as $attribute) {
            if (($attribute->getIsVisible() && $attribute->getIsVisibleOnFront()) || in_array($attribute->getAttributeCode(),
                    ['sku', 'description'])) {
                $code = $attribute->getAttributeCode();
                $value = (string)$attribute->getFrontend()->getValue($simpleProduct);
                $jsonResult['dynamic'][$code][$simpleProduct->getId()] = [
                    'value' => $value
                ];
            }
        }

        return $jsonResult;
}
}
