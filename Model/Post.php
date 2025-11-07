<?php
namespace Tigren\SimpleBlog\Model;

use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 0;

    protected function _construct()
    {
        $this->_init(\Tigren\SimpleBlog\Model\ResourceModel\Post::class);
    }

    public function getAvailableStatuses()
    {
        return [
            self::STATUS_PUBLISHED => __('Published'),
            self::STATUS_DRAFT => __('Draft')
        ];
    }
}