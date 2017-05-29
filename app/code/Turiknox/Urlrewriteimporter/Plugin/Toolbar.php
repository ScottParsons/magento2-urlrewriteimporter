<?php
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Turiknox\Urlrewriteimporter\Plugin;

use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Framework\View\Element\AbstractBlock;

class Toolbar
{
    /**
     * Add Import button
     * @param \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject
     * @param AbstractBlock $block
     * @param ButtonList $buttonList
     * @return array
     */
    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject,
        AbstractBlock $block,
        ButtonList $buttonList
    ) {
        if ($block instanceof \Magento\UrlRewrite\Block\GridContainer) {
            $buttonList->add('import', [
                'label' => __('Import'),
                'onclick' => 'setLocation(\'' .$block->getUrl('urlrewriteimporter/urlrewrite/upload'). '\')',
                'class' => 'import'
            ]);
        }

        return [$block, $buttonList];
    }
}