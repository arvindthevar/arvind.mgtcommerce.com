<?php
namespace Cloudsys\GoodyearTheme\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface {

    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), "1.0.1", "<")) {

            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'product_cms_block',
                [
                    'group' => 'Static Block Setting',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Block List',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'Cloudsys\GoodyearTheme\Model\Options',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'is_used_in_grid' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]
            );
        }
        $setup->endSetup();
    }
}