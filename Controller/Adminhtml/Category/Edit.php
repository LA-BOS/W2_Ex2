<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Magento\Framework\Registry;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $categoryFactory;
    protected $coreRegistry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CategoryFactory $categoryFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryFactory = $categoryFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $model = $this->categoryFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('simpleblog_category', $model);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_SimpleBlog::category');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit Category') : __('New Category')
        );
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_SimpleBlog::category_save');
    }
}