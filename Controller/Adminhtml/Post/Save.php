<?php
namespace Tigren\SimpleBlog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Tigren\SimpleBlog\Model\PostFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action
{
    protected $postFactory;
    protected $uploaderFactory;
    protected $filesystem;
    protected $dateTime;

    public function __construct(
        Context $context,
        PostFactory $postFactory,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        DateTime $dateTime
    ) {
        parent::__construct($context);
        $this->postFactory = $postFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->dateTime = $dateTime;
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
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            // Handle post_image upload
            if (isset($_FILES['post_image']) && $_FILES['post_image']['name']) {
                try {
                    $uploader = $this->uploaderFactory->create(['fileId' => 'post_image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    
                    $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('simpleblog/post');
                    
                    // Create directory if not exists
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    
                    $result = $uploader->save($destinationPath);
                    $data['post_image'] = 'simpleblog/post/' . $result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                        $this->messageManager->addErrorMessage($e->getMessage());
                    }
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

            // Set default status if not set
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }

            // Set published_at to current time if not set and status is published
            if (empty($data['published_at']) && $data['status'] == 1) {
                $data['published_at'] = $this->dateTime->gmtDate();
            }

            // Handle list_image as JSON (for future enhancement)
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
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_SimpleBlog::post_save');
    }
}