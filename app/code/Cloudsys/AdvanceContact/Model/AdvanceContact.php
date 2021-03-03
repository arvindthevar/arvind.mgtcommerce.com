<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cloudsys\AdvanceContact\Model;
use Magento\Framework\Model\AbstractModel;

/**
 * @package Cloudsys\AdvanceContact\Model
 *
 * Cloudsys_AdvanceContact
 */
class AdvanceContact extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Cloudsys\AdvanceContact\Model\ResourceModel\AdvanceContact::class);
    }   
}