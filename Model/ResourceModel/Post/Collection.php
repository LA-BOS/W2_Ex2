<?php
namespace Tigren\SimpleBlog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tigren\SimpleBlog\Model\Post as PostModel;
use Tigren\SimpleBlog\Model\ResourceModel\Post as PostResource;

class Collection extends AbstractCollection
{
    /**
     * ID field name
     *
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(PostModel::class, PostResource::class);
    }
}