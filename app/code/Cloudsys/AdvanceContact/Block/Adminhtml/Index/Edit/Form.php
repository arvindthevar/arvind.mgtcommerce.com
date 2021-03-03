<?php
namespace Cloudsys\AdvanceContact\Block\Adminhtml\Index\Edit;
/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Cloudsys\AdvanceContact\Model\Source\Status $options,        
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) 
    {
        
        $this->_options = $options;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form', 
                            'enctype' => 'multipart/form-data', 
                            'action' => $this->getData('action'), 
                            'method' => 'post'
                        ]
            ]
        );

        if ($model->getId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Enquiry'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'firstname',
            'label',
            [
                'name' => 'firstname',
                'label' => __('Firstname'),
                'id' => 'firstname',
                'title' => __('Firstname')
            ]
        );
        $fieldset->addField(
            'lastname',
            'label',
            [
                'name' => 'lastname',
                'label' => __('Lastname'),
                'id' => 'lastname',
                'title' => __('Lastname')
            ]
        );
        $fieldset->addField(
            'email',
            'label',
            [
                'name' => 'email',
                'label' => __('Email'),
                'id' => 'email',
                'title' => __('Email')
            ]
        );
        $fieldset->addField(
            'phone_no',
            'label',
            [
                'name' => 'phone_no',
                'label' => __('Phone No'),
                'id' => 'phone_no',
                'title' => __('Phone No')
            ]
        );
        $fieldset->addField(
            'title',
            'label',
            [
                'name' => 'title',
                'label' => __('Title'),
                'id' => 'title',
                'title' => __('Title')
            ]
        );
        $fieldset->addField(
            'company_name',
            'label',
            [
                'name' => 'company_name',
                'label' => __('Company Name'),
                'id' => 'company_name',
                'title' => __('Company Name')
            ]
        );
        $fieldset->addField(
            'industry_served',
            'label',
            [
                'name' => 'industry_served',
                'label' => __('Industry Served'),
                'id' => 'industry_served',
                'title' => __('Industry Served')
            ]
        );
        $fieldset->addField(
            'message',
            'label',
            [
                'name' => 'message',
                'label' => __('Message'),
                'id' => 'message',
                'title' => __('Message')
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'id' => 'status',
                'title' => __('Status'),
                'values' => $this->_options->getOptionArray(),
                'class' => 'status',
                'required' => true,
            ]
        );
        

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}