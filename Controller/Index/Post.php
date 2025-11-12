<?php
namespace Tigren\SimpleBlog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\PostFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Post extends Action implements HttpGetActionInterface
{
    protected $resultPageFactory;
    protected $postFactory;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PostFactory $postFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory = $postFactory;
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

        $postId = $this->getRequest()->getParam('id');
        $post = $this->postFactory->create()->load($postId);

        if (!$post->getId() || $post->getStatus() != 1) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('simpleblog');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($post->getTitle());
        return $resultPage;
    }
}