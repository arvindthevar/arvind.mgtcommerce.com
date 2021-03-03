<?php

namespace Cloudsys\AdvanceContact\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Ecomteck\AdvancedContact\Helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_CONTACTUS = 'contactus/';
    /**
     * getConfig function
     *
     * @param $field
     * @param int $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeCode = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeCode);
    }
    /**
     * check active
     *
     *
     */
    public function getActiveConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/active', $storeCode);
    }

    /**
     * get Admin name
     *
     *
     */
    public function getAdminnameConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/admin_name', $storeCode);
    }

    /**
     * get Admin email
     *
     *
     */
    public function getAdminemailConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/admin_email', $storeCode);
    }

    /**
     * check can send email customer
     *
     *
     */
    public function getSendtoCustomerConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/send_to_customer', $storeCode);
    }

    /**
     * CUstomer email template
     *
     *
     */
    public function getCustomermailTemplateConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/email_template_customer', $storeCode);
    }

    /**
     * send email to admin
     *
     *
     */
    public function getSendtoAdminConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/send_to_admin', $storeCode);
    }

    /**
     * admin Email Template
     *
     */
    public function getAdminmailTemplateConfig($storeCode = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONTACTUS.'general/email_template_admin', $storeCode);
    }
    
}
