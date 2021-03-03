<?php
namespace Cloudsys\GoodyearTheme\Block;


class Sitemap extends \Magento\Framework\View\Element\Template
{
    public $_categoryFactory;

    public $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    )
    {
       $this->_storeManager = $storeManager;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

     public function getStore()
    {
        $store = $this->_storeManager->getStore();
        return $store;
    }

    public function getRootCategoryId()
    {
        return $this->getStore()->getRootCategoryId();
    }
    public function getSitemapItems()
    {
        $category = $this->_categoryFactory->create()->load($this->getRootCategoryId());
        if($category->hasChildren())
        {
            foreach ($category->getChildrenCategories() as $mainCategory) {
                $this->categoryItems[] = $this->getCategoryList($mainCategory);
            }
        }
        return $this->categoryItems;
    }

    public function getCategoryList($category)
    {
        $categoryList = [];
        $categoryList["id"] = $category->getId();
        $categoryList["name"] = $category->getName();
        $categoryList["url"] = $category->getUrl();
        if($category->hasChildren())
        {
            foreach ($category->getChildrenCategories() as $subcategory) {
                $categoryList["items"][] = $this->getCategoryList($subcategory);                
            }
        }
        return $categoryList;
    }
}