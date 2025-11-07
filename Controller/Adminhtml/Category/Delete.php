<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Tigren\SimpleBlog\Model\CategoryFactory;

class Delete extends Action
{
    protected $categoryFactory;

    public function __construct(
        Context $context,
        CategoryFactory $categoryFactory
    ) {
        parent::__construct($context);
        $this->categoryFactory = $categoryFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('category_id');

        if ($id) {
            try {
                $model = $this->categoryFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The category has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a category to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_SimpleBlog::category_delete');
    }
}