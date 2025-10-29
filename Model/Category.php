<?php
namespace Tigren\SimpleBlog\Model;

use Magento\Framework\Model\AbstractModel;
use Tigren\SimpleBlog\Model\ResourceModel\Category as CategoryResource;

class Category extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(CategoryResource::class);
    }

    /**
     * Get available statuses
     *
     * @return array
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled'),
        ];
    }

    /**
     * Get category ID
     *
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->getData('category_id');
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getData('status');
    }
}