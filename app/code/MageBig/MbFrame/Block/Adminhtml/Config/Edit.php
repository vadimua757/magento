<?php
/**
 * Copyright © magebig.com - All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * Config edit page
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace MageBig\MbFrame\Block\Adminhtml\Config;

use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\Serialize\Serializer\Json;

/**
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Edit extends \Magento\Backend\Block\Widget
{
    const DEFAULT_SECTION_BLOCK = 'MageBig\MbFrame\Block\Adminhtml\Config\Form';

    /**
     * Form block class name
     *
     * @var string
     */
    protected $_formBlockName;

    /**
     * Block template File
     *
     * @var string
     */
    protected $_template = 'MageBig_MbFrame::system/config/edit.phtml';

    /**
     * Configuration structure
     *
     * @var \Magento\Config\Model\Config\Structure
     */
    protected $_configStructure;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $jsonSerializer;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Config\Model\Config\Structure  $configStructure
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \MageBig\MbFrame\Model\Config\Structure $configStructure,
        array $data = [],
        Json $jsonSerializer = null
    ) {
        $this->_configStructure = $configStructure;
        $this->jsonSerializer = $jsonSerializer ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout object
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        /** @var $section \Magento\Config\Model\Config\Structure\Element\Section */
        $section = $this->_configStructure->getElement($this->getRequest()->getParam('section'));
        $this->_formBlockName = $section->getFrontendModel();
        if (empty($this->_formBlockName)) {
            $this->_formBlockName = self::DEFAULT_SECTION_BLOCK;
        }
        $this->setTitle($section->getLabel());
        $this->setHeaderCss($section->getHeaderCss());

        $this->getToolbar()->addChild(
            'reset_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Reset'), 'onclick' => 'window.location.reload()', 'class' => 'reset']
        );

        $this->getToolbar()->addChild(
            'save_button',
            \Magento\Backend\Block\Widget\Button::class,
            [
                'id' => 'save',
                'label' => __('Save Config'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#config-edit-form']],
                ]
            ]
        );
        $block = $this->getLayout()->createBlock($this->_formBlockName);
        $this->setChild('form', $block);
        return parent::_prepareLayout();
    }

    /**
     * Retrieve rendered save buttons
     *
     * @return string
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * Retrieve config save url
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/config/save', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getConfigSearchParamsJson()
    {
        $params = [];
        if ($this->getRequest()->getParam('section')) {
            $params['section'] = $this->getRequest()->getParam('section');
        }
        if ($this->getRequest()->getParam('group')) {
            $params['group'] = $this->getRequest()->getParam('group');
        }
        if ($this->getRequest()->getParam('field')) {
            $params['field'] = $this->getRequest()->getParam('field');
        }
        return $this->jsonSerializer->serialize($params);
    }
}
