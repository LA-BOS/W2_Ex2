<?php
namespace Tigren\SimpleBlog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Tigren\SimpleBlog\Model\ResourceModel\Category\CollectionFactory;

class Form extends Generic
{
    protected $categoryCollectionFactory;
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('simpleblog_post');
        
        // Khởi tạo model nếu null
        if (!$model) {
            $model = $this->_objectManager->create(\Tigren\SimpleBlog\Model\Post::class);
        }

        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        ]);

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        // Category dropdown
        $categories = $this->categoryCollectionFactory->create();
        $categories->addFieldToFilter('status', 1);
        $categoryOptions = [['value' => '', 'label' => __('-- Please Select --')]];
        foreach ($categories as $category) {
            $categoryOptions[] = ['value' => $category->getId(), 'label' => $category->getName()];
        }

        $fieldset->addField(
            'category_id',
            'select',
            [
                'name' => 'category_id',
                'label' => __('Category'),
                'title' => __('Category'),
                'required' => true,
                'values' => $categoryOptions
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'author',
            'text',
            [
                'name' => 'author',
                'label' => __('Author'),
                'title' => __('Author'),
                'required' => false
            ]
        );

        $fieldset->addField(
            'post_image',
            'image',
            [
                'name' => 'post_image',
                'label' => __('Post Image'),
                'title' => __('Post Image'),
                'required' => false,
                'note' => __('Allowed file types: jpg, jpeg, gif, png')
            ]
        );

        $fieldset->addField(
            'short_content',
            'textarea',
            [
                'name' => 'short_content',
                'label' => __('Short Content'),
                'title' => __('Short Content'),
                'required' => false,
                'style' => 'height: 100px;'
            ]
        );

        $fieldset->addField(
            'full_content',
            'editor',
            [
                'name' => 'full_content',
                'label' => __('Full Content'),
                'title' => __('Full Content'),
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig(),
                'wysiwyg' => true
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'values' => [
                    ['value' => 1, 'label' => __('Published')],
                    ['value' => 0, 'label' => __('Draft')]
                ]
            ]
        );

        $fieldset->addField(
            'published_at',
            'date',
            [
                'name' => 'published_at',
                'label' => __('Published At'),
                'title' => __('Published At'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'HH:mm:ss',
                'required' => false,
                'note' => __('Leave empty to use current date/time')
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}