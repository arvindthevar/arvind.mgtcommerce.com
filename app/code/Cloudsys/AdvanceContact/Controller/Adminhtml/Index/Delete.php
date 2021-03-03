<?php

namespace Cloudsys\AdvanceContact\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $_model;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, 
        Filter $filter, 
        \Cloudsys\AdvanceContact\Model\AdvanceContact $model
    ){
        $this->filter = $filter;
        $this->_model = $model;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        
        $entity_id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entity_id) {
            try {
                $model = $this->_model;
                $model->load($entity_id);
                $model->delete();
                $this->messageManager->addSuccess(__(' 1 Enquiry deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/', ['id' => $entity_id]);
            }
        }
        $this->messageManager->addError(__('Enquiry not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}