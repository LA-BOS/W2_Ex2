<?php
namespace Tigren\SimpleBlog\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'category_id';

    protected function _construct()
    {
        $this->_init(
            \Tigren\SimpleBlog\Model\Category::class,
            \Tigren\SimpleBlog\Model\ResourceModel\Category::class
        );
    }
}