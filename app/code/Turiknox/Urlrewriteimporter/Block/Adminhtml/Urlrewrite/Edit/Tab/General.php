<?php
namespace Turiknox\Urlrewriteimporter\Block\Adminhtml\Urlrewrite\Edit\Tab;
/*
 * Turiknox_Urlrewriteimporter

 * @category   Turiknox
 * @package    Turiknox_Urlrewriteimporter
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/Turiknox/magento2-urlrewriteimporter/blob/master/LICENSE.md
 * @version    1.0.0
 */
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * General constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );

        $fieldset->addField('skip_headers', 'checkbox', array(
            'name' => 'skip_headers',
            'label' => 'Skip Headers?',
            'value' => '1',
            'after_element_html' => '<small>Skip the first line of CSV file (headers)</small>',
        ));

        $fieldset->addField('file', 'file', array(
            'name' => 'file',
            'label' => 'Upload CSV',
            'class' => 'required-entry',
            'required' => true
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     * @return string
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     * @return string
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}