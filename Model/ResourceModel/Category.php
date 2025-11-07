<?php
namespace Tigren\SimpleBlog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Category extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('tigren_blog_category', 'category_id');
    }
}