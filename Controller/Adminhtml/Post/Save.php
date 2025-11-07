<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Tigren\SimpleBlog\Model\PostFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;

class Save extends Action
{
    protected $postFactory;
    protected $uploaderFactory;
    protected $filesystem;

    public function __construct(
        Context $context,
        PostFactory $postFactory,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->postFactory = $postFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            $model = $this->postFactory->create();

            if ($id) {
                $model->load($id);
            }

            // Handle post_image upload
            if (isset($_FILES['post_image']) && $_FILES['post_image']['name']) {
                try {
                    $uploader = $this->uploaderFactory->create(['fileId' => 'post_image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    
                    $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save($mediaDirectory->getAbsolutePath('simpleblog/post'));
                    $data['post_image'] = 'simpleblog/post/' . $result['file'];
                } catch (\Exception $e) {
                    unset($data['post_image']);
                }
            } else {
                if (isset($data['post_image']) && is_array($data['post_image'])) {
                    if (!empty($data['post_image']['delete'])) {
                        $data['post_image'] = null;
                    } else {
                        unset($data['post_image']);
                    }
                }
            }

            // Handle list_image as JSON
            if (isset($data['list_image'])) {
                if (is_array($data['list_image'])) {
                    $data['list_image'] = json_encode($data['list_image']);
                }
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the post.'));
                
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['post_id' => $id]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_SimpleBlog::post_save');
    }
}