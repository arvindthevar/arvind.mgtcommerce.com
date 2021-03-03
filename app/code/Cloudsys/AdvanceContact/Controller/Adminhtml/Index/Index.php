<?php
namespace Cloudsys\AdvanceContact\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
 

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) 
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }
 
   
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
     
        $resultPage->getConfig()->getTitle()->prepend(__('Contact Us List'));
 
        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Cloudsys_AdvanceContact::index_list');
    }
}