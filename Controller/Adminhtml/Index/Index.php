<?php
namespace Tigren\SimpleBlog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Index extends Action
{
    protected $resultPageFactory;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $isEnabled = $this->scopeConfig->getValue(
            'simpleblog/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('noroute');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Blog Categories'));
        return $resultPage;
    }
}