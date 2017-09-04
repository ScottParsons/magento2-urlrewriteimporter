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
use Magento\Framework\View\Result\PageFactory;

class Upload extends \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Upload constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('URL Rewrite Importer'));
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
