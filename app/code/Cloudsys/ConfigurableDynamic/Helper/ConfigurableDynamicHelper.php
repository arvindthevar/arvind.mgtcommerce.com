<?php


namespace Cloudsys\ConfigurableDynamic\Helper;


use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use Magento\Framework\View\LayoutInterface;

class ConfigurableDynamicHelper
{

    private $layout;

    private $serializer;

    public function __construct(Serializer $serializer, LayoutInterface $layout)
    {
        $this->layout = $layout;
        $this->serializer = $serializer;
    }

    public function serialize($data): string
    {
        return $this->serializer->serialize($data);
    }

    public function unserialize(string $string)
    {
        return $this->serializer->unserialize($string);
    }
}