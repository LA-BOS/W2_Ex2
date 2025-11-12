<?php
namespace Tigren\SimpleBlog\Block\Adminhtml\Category;

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
        $this->_objectId = 'category_id';
        $this->_blockGroup = 'Tigren_SimpleBlog';
        $this->_controller = 'adminhtml_category';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Category'));
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
        $this->buttonList->update('delete', 'label', __('Delete Category'));
    }

    public function getHeaderText()
    {
        $category = $this->_coreRegistry->registry('simpleblog_category');
        if ($category->getId()) {
            return __("Edit Category '%1'", $this->escapeHtml($category->getName()));
        } else {
            return __('New Category');
        }
    }

    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
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