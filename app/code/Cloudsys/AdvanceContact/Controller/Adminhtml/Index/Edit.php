<?php
namespace Cloudsys\AdvanceContact\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
	
	
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Cloudsys_AdvanceContact::save');
	}

   
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Cloudsys_AdvanceContact::row_data')
            ->addBreadcrumb(__('Manage Enquiry'), __('Manage Enquiry'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Cloudsys\AdvanceContact\Model\AdvanceContact::class);
        
        if ($id) {
            $model->load($id);
            
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Enquiry no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->_coreRegistry->register('row_data', $model);
        
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Enquiry') : __('Add Enquiry'),
            $id ? __('Edit Enquiry') : __('Add Enquiry')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Enquiry Deatils'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getFirstname() : __('Enquiry Deatils'));

        return $resultPage;
    }
}
?>
