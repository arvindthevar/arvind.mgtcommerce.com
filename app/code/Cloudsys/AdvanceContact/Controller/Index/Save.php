<?php
namespace Cloudsys\AdvanceContact\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Cloudsys\AdvanceContact\Model\AdvanceContactFactory;
use Cloudsys\AdvanceContact\Helper\Data;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
/**
 * Index 
 *
 * @package Cloudsys\AdvanceContact\Controller\Index 
 */
class Save extends \Magento\Framework\App\Action\Action
{
    protected $AdvanceContactFactory; 

    protected $_inlineTranslation;

    protected $_transportBuilder;

    /**
     * helper data Cloudsys\AdvanceContact\Helper\Data
     *
     */
    protected $helperData;
    /**
     * Construct
     *
     * @param \Cloudsys\AdvanceContact\Helper\Data  $helperData
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Cloudsys\AdvanceContact\Model\AdvanceContactFactory
     * @param array $data data
     */
    public function __construct(
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        data $helperData,
        AdvanceContactFactory $AdvanceContactFactory,
        \Magento\Framework\App\Action\Context $context
    ){
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->helperData=$helperData;
        $this->AdvanceContactFactory=$AdvanceContactFactory;
        return parent::__construct($context);
    }
	/**
     * contact-us action
     *
     * @return void
     */
	public function execute()
    {
        // contact-us data
        $post = (array) $this->getRequest()->getPost();

        $contactData=$this->AdvanceContactFactory->create();
        
        if ($contactData->save())
        {
            if($this->helperData->getActiveConfig())
            {
                $sender = ['name'=>$this->helperData->getAdminnameConfig(),
                             'email'=>$this->helperData->getAdminemailConfig()];

                if(!empty($sender) && $this->helperData->getSendtoCustomerConfig())
                {
                    
                    $sentto = ['name'=>$post['firstname'],
                             'email'=>$post['email']];
                    $template = $this->helperData->getCustomermailTemplateConfig();
                    $vars =[];
                    $this->sendEmail($sender,$sentto,$template,$vars);

                }
                if(!empty($sender) && $this->helperData->getSendtoAdminConfig())
                {
                    $sentto = $sender;
                    $template = $this->helperData->getAdminmailTemplateConfig();
                    $vars = $post;

                    $this->sendEmail($sender,$sentto,$template,$vars);

                }
            }
            $this->messageManager->addSuccessMessage('Your enquiry is submited ');
        }else
        {
            $this->messageManager->addError('Your enquiry is Not submited ');
        }

        // Redirect to your form page on contact-us 
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl('index');

        return $resultRedirect;
        
    }

    public function sendEmail(array $sender,array $sentto,$template,array $vars)
    {
        if(empty($vars))
        {
            $vars=[];
        }
        try
        {
            // Send Mail
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
             
            $sender = $sender;
             
            $sentToEmail = $sentto['email'];
             
            $sentToName = $sentto['name'];
             
             
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier($template)
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )
                ->setTemplateVars($vars)
                ->setFrom($sender)
                ->addTo($sentToEmail,$sentToName)
                ->getTransport();
                 
                $transport->sendMessage();
                 
                $this->_inlineTranslation->resume();
               // $this->messageManager->addSuccess('Email sent successfully');
        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }
    }
}