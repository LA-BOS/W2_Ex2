<?php
namespace Tigren\SimpleBlog\Block\Post;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\PostFactory;
use Tigren\SimpleBlog\Model\CategoryFactory;

class View extends Template
{
    protected $postFactory;
    protected $categoryFactory;

    public function __construct(
        Template\Context $context,
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function getPost()
    {
        $postId = $this->getRequest()->getParam('id');
        return $this->postFactory->create()->load($postId);
    }

    public function getCategory()
    {
        $post = $this->getPost();
        return $this->categoryFactory->create()->load($post->getCategoryId());
    }

    public function getPostImageUrl($imagePath)
    {
        if ($imagePath) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $imagePath;
        }
        return '';
    }

    public function getListImages()
    {
        $post = $this->getPost();
        $listImage = $post->getListImage();
        if ($listImage) {
            return json_decode($listImage, true);
        }
        return [];
    }

    public function getCategoryUrl()
    {
        $category = $this->getCategory();
        return $this->getUrl('blog/index/category', ['id' => $category->getId()]);
    }
}