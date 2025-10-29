<?php
namespace Tigren\SimpleBlog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\Repository\PostRepository;

class View extends Action
{
    protected $resultPageFactory;
    protected $postRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PostRepository $postRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postRepository = $postRepository;
    }

    public function execute()
    {
        $postId = $this->getRequest()->getParam('id');
        $post = $this->postRepository->getById($postId);

        if (!$post) {
            $this->messageManager->addErrorMessage(__('This post no longer exists.'));
            return $this->_redirect('*/*/');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($post->getTitle());
        return $resultPage;
    }
}