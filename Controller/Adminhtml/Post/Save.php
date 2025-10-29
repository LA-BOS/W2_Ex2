<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Tigren\SimpleBlog\Model\PostFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Post as PostResource;

class Save extends Action
{
    protected $postFactory;
    protected $postResource;

    public function __construct(
        Action\Context $context,
        PostFactory $postFactory,
        PostResource $postResource
    ) {
        parent::__construct($context);
        $this->postFactory = $postFactory;
        $this->postResource = $postResource;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->messageManager->addErrorMessage(__('Unable to find post to save.'));
            return $this->_redirect('*/*/');
        }

        $postId = isset($data['post_id']) ? $data['post_id'] : null;
        $post = $this->postFactory->create();

        if ($postId) {
            $this->postResource->load($post, $postId);
            if (!$post->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $post->setData($data);

        try {
            $this->postResource->save($post);
            $this->messageManager->addSuccessMessage(__('You saved the post.'));
            $this->_getSession()->setFormData(false);
            return $this->_redirect('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the post.'));
        }

        $this->_getSession()->setFormData($data);
        return $this->_redirect('*/*/edit', ['post_id' => $post->getId()]);
    }
}