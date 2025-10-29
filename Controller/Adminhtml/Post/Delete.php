<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Tigren\SimpleBlog\Model\PostRepository;

class Delete extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    protected $postRepository;

    public function __construct(
        Action\Context $context,
        PostRepository $postRepository
    ) {
        parent::__construct($context);
        $this->postRepository = $postRepository;
    }

    public function execute()
    {
        $postId = $this->getRequest()->getParam('id');
        if (!$postId) {
            $this->messageManager->addErrorMessage(__('Post ID is missing.'));
            return $this->_redirect('*/*/');
        }

        try {
            $this->postRepository->deleteById($postId);
            $this->messageManager->addSuccessMessage(__('The post has been deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting the post.'));
        }

        return $this->_redirect('*/*/');
    }
}