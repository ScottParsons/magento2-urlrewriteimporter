<?php
namespace Turiknox\Urlrewriteimporter\Block\Adminhtml\Urlrewrite\Edit;
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('urlrewrite_import_general');
        $this->setDestElementId('edit_form');
        $this->setTitle('Url Rewrite Importer');
    }

    /**
     * Add General tab
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'general',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Turiknox\Urlrewriteimporter\Block\Adminhtml\Urlrewrite\Edit\Tab\General'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}