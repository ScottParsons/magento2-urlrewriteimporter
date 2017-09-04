<?php
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Turiknox\Urlrewriteimporter\Block\Adminhtml\Urlrewrite;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends Container
{
    /**
     * Core registry
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_urlrewrite';
        $this->_blockGroup = 'Turiknox_Urlrewriteimporter';

        parent::_construct();

        $this->buttonList->remove('save');
        $this->buttonList->remove('reset');

        $url = $this->_urlBuilder->getUrl('*/*/importAction');
        $this->buttonList->add(
            'upload',
            [
                'label' => __('Import'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'save',
                            'target' => '#edit_form'
                        ]
                    ]
                ],
                'onclick' => "setLocation('$url')"
            ],
            -100
        );
    }
}
