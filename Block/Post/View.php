<?php
namespace Tigren\SimpleBlog\Block\Post;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\Repository\PostRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Template
{
    protected $postRepository;
    protected $post = null;

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
     * Get current post
     *
     * @return \Tigren\SimpleBlog\Model\Post|null
     */
    public function getPost()
    {
        if ($this->post === null) {
            $postId = $this->getRequest()->getParam('id');
            
            if ($postId) {
                try {
                    $this->post = $this->postRepository->getById($postId);
                } catch (NoSuchEntityException $e) {
                    $this->post = null;
                }
            }
        }
        
        return $this->post;
    }
}