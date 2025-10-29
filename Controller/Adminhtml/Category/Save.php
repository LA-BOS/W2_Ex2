<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Tigren\SimpleBlog\Model\Repository\CategoryRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends Action implements HttpPostActionInterface
{
    protected $resultPageFactory;
    protected $categoryRepository;
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CategoryRepository $categoryRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoryRepository = $categoryRepository;
        $this->dataPersistor = $dataPersistor;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $category = $this->categoryRepository->save($data);
                $this->messageManager->addSuccessMessage(__('You saved the category.'));
                $this->dataPersistor->clear('tigren_simpleblog_category');
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('tigren_simpleblog_category', $data);
                return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $data['id']]);
            }
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}