<?php
namespace Tigren\SimpleBlog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function _construct()
    {
        $this->_init(
            \Tigren\SimpleBlog\Model\Post::class,
            \Tigren\SimpleBlog\Model\ResourceModel\Post::class
        );
    }
}