<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Captcha
 */

/**
 * Captcha controller
 *
 * @package    Mage_Captcha
 */
class Mage_Captcha_Adminhtml_RefreshController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Refreshes captcha and returns JSON encoded URL to image (AJAX action)
     * Example: {'imgSrc': 'http://example.com/media/captcha/67842gh187612ngf8s.png'}
     */
    public function refreshAction()
    {
        $formId = $this->getRequest()->getPost('formId');
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        $this->getLayout()->createBlock($captchaModel->getBlockName())->setFormId($formId)->setIsAjax(true)->toHtml();
        $this->getResponse()->setBody(json_encode(['imgSrc' => $captchaModel->getImgSrc()]));
        $this->setFlag('', self::FLAG_NO_POST_DISPATCH, true);
    }

    /**
     * Check is allowed access to action
     *
     * @return true
     */
    protected function _isAllowed()
    {
        return true;
    }
}
