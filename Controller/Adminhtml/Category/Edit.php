<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\Repository\CategoryRepository;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $categoryRepository;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CategoryRepository $categoryRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_SimpleBlog::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Blog Category'));

        // Load the category data if an ID is provided
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $category = $this->categoryRepository->getById($id);
            if (!$category) {
                $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                return $this->_redirect('*/*/');
            }
            // Set the category data to the form
            $resultPage->getLayout()->getBlock('category_form')->setCategory($category);
        }

        return $resultPage;
    }
}