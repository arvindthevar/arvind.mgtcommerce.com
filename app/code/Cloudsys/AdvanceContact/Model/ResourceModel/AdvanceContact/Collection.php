<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cloudsys\AdvanceContact\Model\ResourceModel\AdvanceContact;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @package Cloudsys\AdvanceContact\Model\ResourceModel\AdvanceContact
 *
 * Cloudsys_AdvanceContact
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
	
	
	/**
     * Define model & resource model
     */
	protected function _construct()
	{
		$this->_init('Cloudsys\AdvanceContact\Model\AdvanceContact', 'Cloudsys\AdvanceContact\Model\ResourceModel\AdvanceContact');
	}
}
?>
