<?php
namespace Cloudsys\GoodyearTheme\Block;

class Video extends \Magento\Framework\View\Element\Template
{
    /**
     * GoodyearTheme helper
     *
     * @var \Cloudsys\GoodyearTheme\Helper\Data
     */
    public $themeHelper;

    /**
     * Video constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context context
     * @param array $data data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Cloudsys\GoodyearTheme\Helper\Data $themeHelper,
        array $data = []
    ) {
        $this->themeHelper = $themeHelper;
        parent::__construct($context, $data);
    }

    /**
     * Return Media Path
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getFullMediaUrl($mediaItem)
    {
        return $this->getMediaPath().$mediaItem;
    }

    public function getHelper()
    {
        return $this->themeHelper;
    }
}