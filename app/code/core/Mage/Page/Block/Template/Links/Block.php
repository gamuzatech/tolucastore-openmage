<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Page
 */

/**
 * Simple links list block
 *
 * @package    Mage_Page
 */
class Mage_Page_Block_Template_Links_Block extends Mage_Core_Block_Template
{
    /**
     * First link flag
     *
     * @var bool
     */
    protected $_isFirst = false;

    /**
     * Last link flag
     *
     * @var bool
     */
    protected $_isLast = false;

    /**
     * Link label
     *
     * @var string
     */
    protected $_label = null;

    /**
     * Link url
     *
     * @var string
     */
    protected $_url = null;

    /**
     * Link title
     *
     * @var string
     */
    protected $_title = null;

    /**
     * Li element params
     *
     * @var string
     */
    protected $_liParams = null;

    /**
     * A element params
     *
     * @var string
     */
    protected $_aParams = null;

    /**
     * Message before link text
     *
     * @var string
     */
    protected $_beforeText = null;

    /**
     * Message after link text
     *
     * @var string
     */
    protected $_afterText = null;

    /**
     * Position in link list
     * @var int
     */
    protected $_position = 0;

    /**
     * Set default template
     *
     */
    protected function _construct()
    {
        $this->setTemplate('page/template/linksblock.phtml');
    }

    /**
     * Return link position in link list
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->_position;
    }

    /**
     * Return first position flag
     *
     * @return bool
     */
    public function getIsFirst()
    {
        return $this->_isFirst;
    }

    /**
     * Set first list flag
     *
     * @param bool $value
     * return Mage_Page_Block_Template_Links_Block
     * @return Mage_Page_Block_Template_Links_Block
     */
    public function setIsFirst($value)
    {
        $this->_isFirst = (bool) $value;
        return $this;
    }

    /**
     * Return last position flag
     *
     * @return bool
     */
    public function getIsLast()
    {
        return $this->_isLast;
    }

    /**
     * Set last list flag
     *
     * @param bool $value
     * return Mage_Page_Block_Template_Links_Block
     * @return Mage_Page_Block_Template_Links_Block
     */
    public function setIsLast($value)
    {
        $this->_isLast = (bool) $value;
        return $this;
    }

    /**
     * Return link label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Return link title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Return link url
     *
     * @return string
     */
    public function getLinkUrl()
    {
        return $this->_url;
    }

    /**
     * Prepare tag attributes
     *
     * @param string|array $params
     * @return string
     */
    protected function _prepareParams($params)
    {
        if (is_string($params)) {
            return $params;
        } elseif (is_array($params)) {
            $result = '';
            foreach ($params as $key => $value) {
                $result .= ' ' . $key . '="' . addslashes($value) . '"';
            }
            return $result;
        }
        return '';
    }

    /**
    * Return Li Params
    *
    * @return string
    */
    public function getLiParams()
    {
        return $this->_prepareParams($this->_liParams);
    }

    /**
    * Return Link Tag Params
    *
    * @return string
    */
    public function getAParams()
    {
        return $this->_prepareParams($this->_aParams);
    }
}
