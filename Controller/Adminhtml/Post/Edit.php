<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\PostRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Edit extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    protected $resultPageFactory;
    protected $postRepository;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        PostRepository $postRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postRepository = $postRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->postRepository->getById($id);

        if (!$post) {
            $this->messageManager->addErrorMessage(__('This post no longer exists.'));
            return $this->_redirect('*/*/');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_SimpleBlog::post');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Post'));
        return $resultPage;
    }
}