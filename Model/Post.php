<?php
namespace Tigren\SimpleBlog\Model;

use Magento\Framework\Model\AbstractModel;
use Tigren\SimpleBlog\Model\ResourceModel\Post as PostResource;

class Post extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(PostResource::class);
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
     * Get post ID
     *
     * @return int|null
     */
    public function getPostId()
    {
        return $this->getData('post_id');
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Get post image
     *
     * @return string|null
     */
    public function getPostImage()
    {
        return $this->getData('post_image');
    }

    /**
     * Get list image
     *
     * @return string|null
     */
    public function getListImage()
    {
        return $this->getData('list_image');
    }

    /**
     * Get full content
     *
     * @return string
     */
    public function getFullContent()
    {
        return $this->getData('full_content');
    }

    /**
     * Get short content
     *
     * @return string|null
     */
    public function getShortContent()
    {
        return $this->getData('short_content');
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->getData('author');
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

    /**
     * Get published at
     *
     * @return string
     */
    public function getPublishedAt()
    {
        return $this->getData('published_at');
    }

    /**
     * Get category ID
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->getData('category_id');
    }
}