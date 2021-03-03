<?php
namespace Cloudsys\AdvanceContact\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;


class Save extends \Magento\Backend\App\Action
{
    
    protected $dataPersistor;

    
    private $contactFactory;

    
    public function __construct(
        Action\Context $context,
        \Cloudsys\AdvanceContact\Model\AdvanceContactFactory $contactFactory 
    ) {
        $this->contactFactory = $contactFactory;
        parent::__construct($context);
    }
	
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Cloudsys_AdvanceContact::save');
	}

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('contact/index/edit');
            return;
        }
        try {
            $rowData = $this->contactFactory->create();
            if (isset($data['id'])) {
                $rowData = $rowData->load($data['id']);
                
            }
            $rowData->setData($data);
            $rowData->save();
            $this->messageManager->addSuccess(__('Enquiry data has been Update successfully .'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('contact/index/index');
    }
}
?>
