<?php
namespace Tigren\SimpleBlog\Model\Repository;

use Tigren\SimpleBlog\Model\PostFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Post as PostResource;
use Tigren\SimpleBlog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class PostRepository
{
    protected $postFactory;
    protected $postResource;
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param PostFactory $postFactory
     * @param PostResource $postResource
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        PostFactory $postFactory,
        PostResource $postResource,
        CollectionFactory $collectionFactory
    ) {
        $this->postFactory = $postFactory;
        $this->postResource = $postResource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get all enabled posts
     *
     * @return \Tigren\SimpleBlog\Model\ResourceModel\Post\Collection
     */
    public function getList()
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('status', 1)
            ->setOrder('published_at', 'DESC');
    }

    /**
     * Get posts by category ID
     *
     * @param int $categoryId
     * @return \Tigren\SimpleBlog\Model\ResourceModel\Post\Collection
     */
    public function getByCategory($categoryId)
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('category_id', $categoryId)
            ->addFieldToFilter('status', 1)
            ->setOrder('published_at', 'DESC');
    }

    /**
     * Get post by ID
     *
     * @param int $postId
     * @return \Tigren\SimpleBlog\Model\Post
     * @throws NoSuchEntityException
     */
    public function getById($postId)
    {
        $post = $this->postFactory->create();
        $this->postResource->load($post, $postId);
        
        if (!$post->getId()) {
            throw new NoSuchEntityException(
                __('Post with id "%1" does not exist.', $postId)
            );
        }
        
        return $post;
    }

    /**
     * Save post
     *
     * @param \Tigren\SimpleBlog\Model\Post $post
     * @return \Tigren\SimpleBlog\Model\Post
     * @throws CouldNotSaveException
     */
    public function save($post)
    {
        try {
            $this->postResource->save($post);
            return $post;
            
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the post: %1', $e->getMessage())
            );
        }
    }

    /**
     * Delete post by ID
     *
     * @param int $postId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($postId)
    {
        try {
            $post = $this->getById($postId);
            $this->postResource->delete($post);
            return true;
            
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the post: %1', $e->getMessage())
            );
        }
    }
}