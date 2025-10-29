<?php
namespace Tigren\SimpleBlog\Block\Post;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\Repository\PostRepository;

class List extends Template
{
    protected $postRepository;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param PostRepository $postRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PostRepository $postRepository,
        array $data = []
    ) {
        $this->postRepository = $postRepository;
        parent::__construct($context, $data);
    }

    /**
     * Get posts (filtered by category if category_id param exists)
     *
     * @return \Tigren\SimpleBlog\Model\ResourceModel\Post\Collection
     */
    public function getPosts()
    {
        $categoryId = $this->getRequest()->getParam('id');
        
        if ($categoryId) {
            return $this->postRepository->getByCategory($categoryId);
        }
        
        return $this->postRepository->getList();
    }

    /**
     * Get post URL
     *
     * @param \Tigren\SimpleBlog\Model\Post $post
     * @return string
     */
    public function getPostUrl($post)
    {
        return $this->getUrl('simpleblog/post/view', ['id' => $post->getId()]);
    }
}