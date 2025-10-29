<?php
namespace Tigren\SimpleBlog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        // Table name and primary key
        $this->_init('tigren_simpleblog_post', 'post_id');
    }

    /**
     * Perform actions before object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Set published_at if not set
        if (!$object->hasPublishedAt()) {
            $object->setPublishedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }
        
        return parent::_beforeSave($object);
    }
}