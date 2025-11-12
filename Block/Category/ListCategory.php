<?php
namespace Tigren\SimpleBlog\Block\Category;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class ListCategory extends Template
{
    protected $categoryCollectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function getCategories()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addFieldToFilter('status', 1);
        return $collection;
    }

    public function getCategoryUrl($categoryId)
    {
        return $this->getUrl('simpleblog/index/category', ['id' => $categoryId]);
    }
}