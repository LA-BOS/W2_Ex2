<?php
namespace Tigren\SimpleBlog\Model\Repository;

use Tigren\SimpleBlog\Model\CategoryFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Category as CategoryResource;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class CategoryRepository
{
    protected $categoryFactory;
    protected $categoryResource;
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param CategoryFactory $categoryFactory
     * @param CategoryResource $categoryResource
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryResource $categoryResource,
        CollectionFactory $collectionFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryResource = $categoryResource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get all enabled categories
     *
     * @return \Tigren\SimpleBlog\Model\ResourceModel\Category\Collection
     */
    public function getAllCategories()
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('status', 1)
            ->setOrder('created_at', 'DESC');
    }

    /**
     * Get category by ID
     *
     * @param int $id
     * @return \Tigren\SimpleBlog\Model\Category
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $category = $this->categoryFactory->create();
        $this->categoryResource->load($category, $id);
        
        if (!$category->getId()) {
            throw new NoSuchEntityException(
                __('Category with id "%1" does not exist.', $id)
            );
        }
        
        return $category;
    }

    /**
     * Save category
     *
     * @param array|object $data
     * @return \Tigren\SimpleBlog\Model\Category
     * @throws CouldNotSaveException
     */
    public function save($data)
    {
        try {
            if (is_array($data)) {
                // If data is array, check if updating existing category
                if (isset($data['category_id']) && $data['category_id']) {
                    $category = $this->getById($data['category_id']);
                    $category->addData($data);
                } else {
                    $category = $this->categoryFactory->create();
                    $category->setData($data);
                }
            } else {
                // If data is object
                $category = $data;
            }
            
            $this->categoryResource->save($category);
            return $category;
            
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save the category: %1', $e->getMessage())
            );
        }
    }

    /**
     * Delete category by ID
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($id)
    {
        try {
            $category = $this->getById($id);
            $this->categoryResource->delete($category);
            return true;
            
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the category: %1', $e->getMessage())
            );
        }
    }

    /**
     * Get all categories (including disabled)
     *
     * @return \Tigren\SimpleBlog\Model\ResourceModel\Category\Collection
     */
    public function getList()
    {
        return $this->collectionFactory->create();
    }
}