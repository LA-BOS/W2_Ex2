<?php
namespace Tigren\SimpleBlog\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Tigren\SimpleBlog\Model\CategoryFactory;
use Tigren\SimpleBlog\Model\PostFactory;
use Tigren\SimpleBlog\Model\ResourceModel\Category as CategoryResource;
use Tigren\SimpleBlog\Model\ResourceModel\Post as PostResource;

class InitialData implements DataPatchInterface
{
    protected $categoryFactory;
    protected $categoryResource;
    protected $postFactory;
    protected $postResource;

    /**
     * Constructor
     *
     * @param CategoryFactory $categoryFactory
     * @param CategoryResource $categoryResource
     * @param PostFactory $postFactory
     * @param PostResource $postResource
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryResource $categoryResource,
        PostFactory $postFactory,
        PostResource $postResource
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryResource = $categoryResource;
        $this->postFactory = $postFactory;
        $this->postResource = $postResource;
    }

    /**
     * Apply patch
     *
     * @return void
     */
    public function apply()
    {
        // Insert sample categories
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Latest technology news and tutorials',
                'status' => 1
            ],
            [
                'name' => 'Lifestyle',
                'description' => 'Tips and tricks for better living',
                'status' => 1
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel guides and destination reviews',
                'status' => 1
            ]
        ];

        $categoryIds = [];
        foreach ($categories as $data) {
            $category = $this->categoryFactory->create();
            $category->setData($data);
            $this->categoryResource->save($category);
            $categoryIds[] = $category->getId();
        }

        // Insert sample posts
        $posts = [
            [
                'title' => 'Getting Started with Magento 2',
                'post_image' => 'https://via.placeholder.com/800x400?text=Magento+2',
                'list_image' => 'https://via.placeholder.com/400x300?text=Magento+2',
                'full_content' => '<p>Magento 2 is a powerful e-commerce platform that provides flexibility and control. In this article, we will explore the basics of Magento 2 development.</p><p>Learn how to create modules, work with layouts, and customize your store.</p>',
                'short_content' => 'An introduction to Magento 2 development and key concepts.',
                'author' => 'John Doe',
                'status' => 1,
                'published_at' => date('Y-m-d H:i:s'),
                'category_id' => $categoryIds[0]
            ],
            [
                'title' => '10 Tips for Healthy Living',
                'post_image' => 'https://via.placeholder.com/800x400?text=Healthy+Living',
                'list_image' => 'https://via.placeholder.com/400x300?text=Healthy+Living',
                'full_content' => '<p>Living a healthy lifestyle is important for your physical and mental well-being. Here are 10 practical tips to help you achieve your health goals.</p><ol><li>Drink plenty of water</li><li>Exercise regularly</li><li>Get enough sleep</li><li>Eat a balanced diet</li><li>Reduce stress</li></ol>',
                'short_content' => 'Practical tips for maintaining a healthy lifestyle.',
                'author' => 'Jane Smith',
                'status' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'category_id' => $categoryIds[1]
            ],
            [
                'title' => 'Top 5 Destinations to Visit in 2024',
                'post_image' => 'https://via.placeholder.com/800x400?text=Travel+2024',
                'list_image' => 'https://via.placeholder.com/400x300?text=Travel+2024',
                'full_content' => '<p>Planning your next vacation? Here are the top 5 destinations you should consider visiting in 2024.</p><p>From tropical beaches to historic cities, these locations offer unforgettable experiences.</p>',
                'short_content' => 'Discover the best travel destinations for 2024.',
                'author' => 'Travel Expert',
                'status' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'category_id' => $categoryIds[2]
            ]
        ];

        foreach ($posts as $data) {
            $post = $this->postFactory->create();
            $post->setData($data);
            $this->postResource->save($post);
        }
    }

    /**
     * Get dependencies
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }
}