<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class Index extends Action
{
    protected $resultPageFactory;
    protected $categoryCollectionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CollectionFactory $categoryCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_SimpleBlog::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Categories'));
        return $resultPage;
    }
}