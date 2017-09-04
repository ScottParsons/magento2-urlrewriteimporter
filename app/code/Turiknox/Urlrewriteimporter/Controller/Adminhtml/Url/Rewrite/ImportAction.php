<?php
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Turiknox\Urlrewriteimporter\Controller\Adminhtml\Url\Rewrite;

use Magento\Backend\App\Action;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class ImportAction extends Action
{
    /**
     * File name
     * @var
     */
    protected $filename;

    /**
     * Total number of records
     * @var
     */
    protected $total = 0;

    /**
     * Total numer of records imported
     * @var int
     */
    protected $totalImported = 0;

    /**
     * Skip headers in import
     * @var
     */
    protected $skipHeaders = false;

    /**
     * @var UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        Action\Context $context,
        UrlRewriteFactory $urlRewriteFactory
    ) {
        parent::__construct($context);
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * @param $type
     * @return bool
     */
    private function allowedType($type)
    {
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
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->getRequest()->getPost()) {
            $this->filename = $_FILES['file']['tmp_name'];

            if (!file_exists($this->filename)) {
                $this->messageManager->addErrorMessage('Unable to upload the file!');
                return $resultRedirect->setPath('*/url_rewrite/');
            }

            if ($this->allowedType($_FILES['file']['type']) == false) {
                $this->messageManager->addErrorMessage('Sorry, this file type is not allowed.');
                return $resultRedirect->setPath('*/url_rewrite/');
            }

            $this->skipHeaders = $this->getRequest()->getParam('skip_headers', false);
            try {
                $this->processUrlRewrites();
                $this->messageManager->addSuccessMessage(
                    sprintf('%s URL rewrites have been imported.', $this->totalImported)
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(sprintf('Import Error: %s', $e->getMessage()));
            }
        }
        return $resultRedirect->setPath('*/url_rewrite/');
    }

    /**
     * Process URL Rewrites
     */
    public function processUrlRewrites()
    {
        if (($fp = fopen($this->filename, 'r'))) {
            while (($line = fgetcsv($fp))) {
                $this->total++;

                if ($this->skipHeaders && ($this->total === 1)) {
                    continue;
                }

                $storeId = isset($line[0]) ? $line[0] : 0;
                $requestPath = isset($line[1]) ? $line[1] : '';
                $targetPath = isset($line[2]) ? $line[2] : '';
                $redirect = isset($line[3]) ? $line[3] : '';
                $description = isset($line[4]) ? $line[4] : '';

                $model = $this->urlRewriteFactory->create();
                $model->setEntityType('custom')
                    ->setRequestPath($requestPath)
                    ->setTargetPath($targetPath)
                    ->setRedirectType($redirect)
                    ->setStoreId($storeId)
                    ->setDescription($description);

                $model->save();
                $this->totalImported++;
            }
            fclose($fp);
            unlink($this->filename);
        }
    }
}
