<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cloudsys\AdvanceContact\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @package Cloudsys\AdvanceContact\Model\ResourceModel
 *
 * Cloudsys_AdvanceContact
 */
class AdvanceContact extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cloudsys_contact', 'id');
    }
}