<?php
namespace Turiknox\Urlrewriteimporter\Controller\Adminhtml\Urlrewrite;
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
use Magento\Backend\App\Action;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class Save extends Action
{
    /**
     * File name
     * @var
     */
    protected $_filename;

    /**
     * Total number of records
     * @var
     */
    protected $_total = 0;

    /**
     * Total numer of records imported
     * @var int
     */
    protected $_totalImported = 0;

    /**
     * Skip headers in import
     * @var
     */
    protected $_skipHeaders = false;

    /**
     * @var UrlRewriteFactory
     */
    protected $_urlRewriteFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param UrlRewriteFactory $urlrewriteFactory
     */
    public function __construct(
        Action\Context $context,
        UrlRewriteFactory $urlrewriteFactory
    )
    {
        parent::__construct($context);
        $this->_urlRewriteFactory = $urlrewriteFactory;
    }

    /**
     * @param $type
     * @return bool
     */
    private function _allowedType($type) {
        $mimes = array(
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        );

        if (in_array($type, $mimes)) {
            return true;
        }
        return false;
    }

    /**
     * Execute action
     * @return $this
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->getRequest()->getPost()) {

            $this->_filename = $_FILES['file']['tmp_name'];

            if (!file_exists($this->_filename)) {
                $this->messageManager->addErrorMessage('Unable to upload the file!');
                return $resultRedirect->setPath('*/*/upload');
            }

            if ($this->_allowedType($_FILES['file']['type']) == false) {
                $this->messageManager->addErrorMessage('Sorry, this file type is not allowed.');
                return $resultRedirect->setPath('*/*/upload');
            }

            $this->_skipHeaders = $this->getRequest()->getParam('skip_headers', false);
            try {
                $this->processUrlRewrites();
                $this->messageManager->addSuccessMessage(sprintf('%s URL rewrites have been imported.', $this->_totalImported));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(sprintf('Import Error: %s', $e->getMessage()));
            }
        }
        return $resultRedirect->setPath('*/*/upload');
    }

    /**
     * Process URL Rewrites
     */
    public function processUrlRewrites()
    {
        if (($fp = fopen($this->_filename, 'r'))) {
            while (($line = fgetcsv($fp))) {
                $this->_total++;
                if ($this->_skipHeaders && ($this->_total === 1)) {
                    continue;
                }

                $storeId = isset($line[0]) ? $line[0] : 0;
                $requestPath = isset($line[1]) ? $line[1] : '';
                $targetPath = isset($line[2]) ? $line[2] : '';
                $redirect = isset($line[3]) ? $line[3] : '';
                $description = isset($line[4]) ? $line[4] : '';

                $model = $this->_urlRewriteFactory->create();
                $model->setEntityType('custom')
                    ->setRequestPath($requestPath)
                    ->setTargetPath($targetPath)
                    ->setRedirectType($redirect)
                    ->setStoreId($storeId)
                    ->setDescription($description);

                $model->save();
                $this->_totalImported++;
            }

            fclose($fp);
            unlink($this->_filename);
        }
    }
}