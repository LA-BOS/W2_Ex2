<?php
namespace Tigren\SimpleBlog\Block\Adminhtml\Post;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'post_id';
        $this->_blockGroup = 'Tigren_SimpleBlog';
        $this->_controller = 'adminhtml_post';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Post'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Post'));
    }

    public function getHeaderText()
    {
        $post = $this->_coreRegistry->registry('simpleblog_post');
        if ($post->getId()) {
            return __("Edit Post '%1'", $this->escapeHtml($post->getTitle()));
        } else {
            return __('New Post');
        }
    }

    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            require([
                'jquery'
            ], function($){
                $('#edit_form').submit(function() {
                    if ($('#edit_form').valid()) {
                        return true;
                    }
                    return false;
                });
            });
        ";
        return parent::_prepareLayout();
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }
}