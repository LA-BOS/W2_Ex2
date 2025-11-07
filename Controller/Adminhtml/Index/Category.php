<?php
namespace Tigren\SimpleBlog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Category extends Action
{
    protected $resultPageFactory;
    protected $categoryFactory;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CategoryFactory $categoryFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryFactory = $categoryFactory;
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

        $categoryId = $this->getRequest()->getParam('id');
        $category = $this->categoryFactory->create()->load($categoryId);

        if (!$category->getId() || $category->getStatus() != 1) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('blog');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($category->getName());
        return $resultPage;
    }
}