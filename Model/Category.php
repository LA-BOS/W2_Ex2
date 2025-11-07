<?php
namespace Tigren\SimpleBlog\Model;

use Magento\Framework\Model\AbstractModel;

class Category extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\Tigren\SimpleBlog\Model\ResourceModel\Category::class);
    }

    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }
}