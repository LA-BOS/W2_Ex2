<?php
namespace Tigren\SimpleBlog\Controller\Category;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\Repository\CategoryRepository;

class View extends Action
{
    protected $resultPageFactory;
    protected $categoryRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CategoryRepository $categoryRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('id');
        
        try {
            $category = $this->categoryRepository->getById($categoryId);
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($category->getName());
            return $resultPage;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('This category does not exist.'));
            return $this->_redirect('simpleblog/index/index');
        }
    }
}