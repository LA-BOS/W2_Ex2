<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\PostFactory;
use Magento\Framework\Registry;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $postFactory;
    protected $coreRegistry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PostFactory $postFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory = $postFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        $model = $this->postFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('simpleblog_post', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_SimpleBlog::post');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit Post') : __('New Post')
        );
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_SimpleBlog::post_save');
    }
}