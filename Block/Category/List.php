<?php
namespace Tigren\SimpleBlog\Block\Category;

use Magento\Framework\View\Element\Template;
use Tigren\SimpleBlog\Model\Repository\CategoryRepository;

class List extends Template
{
    protected $categoryRepository;

    public function __construct(
        Template\Context $context,
        CategoryRepository $categoryRepository,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }

    public function getCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }
}