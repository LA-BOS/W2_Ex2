<?php
namespace Tigren\SimpleBlog\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class Category implements OptionSourceInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 1);

        foreach ($collection as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => $category->getName()
            ];
        }

        return $options;
    }
}