<?php
namespace Tigren\SimpleBlog\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tigren\SimpleBlog\Model\Category as CategoryModel;
use Tigren\SimpleBlog\Model\ResourceModel\Category as CategoryResource;

class Collection extends AbstractCollection
{
    /**
     * ID field name
     *
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(CategoryModel::class, CategoryResource::class);
    }
}