<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cloudsys\AdvanceContact\Block;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
/**
 * Contact us 
 *
 */
class Index extends \Magento\Framework\View\Element\Template
{
	public $_storeManager;
	/**
     * Construct
     *
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Magento\Framework\App\Action\Context $context
     * @param array $data data
     */
	public function __construct(
		StoreManagerInterface $storeManager,
		Context $context,
		array $data = []
	){
		$this->_storeManager=$storeManager;
		return parent::__construct($context,$data);
	}
	/**
     * Get form action URL for POST contact request
     *
     * @return string
     */
    public function getFormAction()
    {
    	
        return $this->_storeManager->getStore()->getBaseUrl().'contact-us/index/save';
        
    }
}