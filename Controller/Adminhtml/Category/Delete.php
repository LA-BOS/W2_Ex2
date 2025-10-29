<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Tigren\SimpleBlog\Model\Repository\CategoryRepository;

class Delete extends Action implements HttpPostActionInterface
{
    protected $categoryRepository;

    public function __construct(
        Context $context,
        CategoryRepository $categoryRepository
    ) {
        parent::__construct($context);
        $this->categoryRepository = $categoryRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->categoryRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The category has been deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error deleting category: %1', $e->getMessage()));
            }
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find a category to delete.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}