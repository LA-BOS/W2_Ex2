<?php
namespace Tigren\SimpleBlog\Block\Category;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Post\CollectionFactory;

class View extends Template
{
    protected $categoryFactory;
    protected $postCollectionFactory;

    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        CollectionFactory $postCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory;
        $this->postCollectionFactory = $postCollectionFactory;
    }

    public function getCategory()
    {
        $categoryId = $this->getRequest()->getParam('id');
        return $this->categoryFactory->create()->load($categoryId);
    }

    public function getPosts()
    {
        $categoryId = $this->getRequest()->getParam('id');
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('category_id', $categoryId);
        $collection->addFieldToFilter('status', 1);
        $collection->setOrder('published_at', 'DESC');
        return $collection;
    }

    public function getPostUrl($postId)
    {
        return $this->getUrl('blog/index/post', ['id' => $postId]);
    }

    public function getPostImageUrl($imagePath)
    {
        if ($imagePath) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $imagePath;
        }
        return '';
    }
}