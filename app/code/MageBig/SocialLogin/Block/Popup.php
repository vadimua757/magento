<?php
/**
 * Copyright © magebig.com - All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageBig\SocialLogin\Block;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use MageBig\SocialLogin\Helper\Data as HelperData;

/**
 * Class Popup
 *
 * @package MageBig\SocialLogin\Block
 */
class Popup extends Template
{
    /**
     * @type \MageBig\SocialLogin\Helper\Data
     */
    protected $helperData;

    /**
     * @type \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \MageBig\SocialLogin\Helper\Data $helperData
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        CustomerSession $customerSession,
        array $data = []
    )
    {
        $this->helperData      = $helperData;
        $this->customerSession = $customerSession;

        parent::__construct($context, $data);
    }

    /**
     * Is enable popup
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled() && !$this->customerSession->isLoggedIn() && $this->helperData->getConfigGeneral('popup_login');
    }

    /**
     * Js params
     *
     * @return string
     */
    public function getFormParams()
    {
        $params = [
            'headerLink'    => $this->getHeaderLink(),
            'popupEffect'   => $this->getPopupEffect(),
            'formLoginUrl'  => $this->getFormLoginUrl(),
            'forgotFormUrl' => $this->getForgotFormUrl(),
            'createFormUrl' => $this->getCreateFormUrl(),
            'fakeEmailUrl'  => $this->getFakeEmailUrl(),
            'popupCreate'   => $this->getPopupCreate(),
            'popupForgot'   => $this->getPopupForgot()
        ];

        return json_encode($params);
    }

    /**
     * @return mixed
     */
    public function getPopupCreate()
    {
        return (int) $this->helperData->getConfigGeneral('popup_create');
    }

    /**
     * @return mixed
     */
    public function getPopupForgot()
    {
        return (int) $this->helperData->getConfigGeneral('popup_forgot');
    }

    /**
     * @return string
     */
    public function getHeaderLink()
    {
        $links = $this->helperData->getConfigGeneral('link_trigger');

        return $links ?: '.header .links, .section-item-content .header.links';
    }

    /**
     * @return mixed
     */
    public function getPopupEffect()
    {
        return $this->helperData->getPopupEffect();
    }

    /**
     * get Social Login Form Url
     *
     * @return string
     */
    public function getFormLoginUrl()
    {
        return $this->getUrl('customer/ajax/login', ['_secure' => $this->isSecure()]);
    }

    /**
     * @return string
     */
    public function getFakeEmailUrl()
    {
        return $this->getUrl('sociallogin/social/email', ['_secure' => $this->isSecure()]);
    }

    /**
     * @return string
     */
    public function getForgotFormUrl()
    {
        return $this->getUrl('sociallogin/popup/forgot', ['_secure' => $this->isSecure()]);
    }

    /**
     *  get Social Login Form Create Url
     *
     * @return string
     */
    public function getCreateFormUrl()
    {
        return $this->getUrl('sociallogin/popup/create', ['_secure' => $this->isSecure()]);
    }

    /**
     * get is secure url
     *
     * @return mixed
     */
    public function isSecure()
    {
        return (bool) $this->helperData->isSecure();
    }
}
