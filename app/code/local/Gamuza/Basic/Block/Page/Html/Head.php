<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Html page block
 */
class Gamuza_Basic_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{
    const CATALOG_PRODUCT_VIEW_PATH_INFO = 'catalog/product/view/id/';

    protected $_pathInfo = null;

    protected function _construct ()
    {
        parent::_construct ();

        $this->_pathInfo = Mage::app ()->getRequest ()->getPathInfo ();
    }

    /**
     * Getter for path to Favicon
     *
     * @return string
     */
    public function getFaviconFile()
    {
        return $this->getSkinUrl('favicon.png');
    }

    /**
     * Retrieve url of skins file
     *
     * @param   string $file path to file in skin
     * @param   array $params
     * @return  string
     */
    public function getSkinUrl($file = null, array $params = array())
    {
        $file = str_replace('favicon.ico', 'favicon.png', $file);

        return parent::getSkinUrl($file, $params);
    }

    /**
     * Add HEAD Item 'BEFORE'
     *
     * Allowed types:
     *  - js
     *  - js_css
     *  - skin_js
     *  - skin_css
     *  - rss
     *
     * @param string $type
     * @param string $name
     * @param string $params
     * @param string $if
     * @param string $cond
     * @return Mage_Page_Block_Html_Head
     */
    public function prependItem ($type, $name, $params = null, $if = null, $cond = null)
    {
        if ($type === 'skin_css' && empty ($params)) $params = 'media="all"';
        
        $_item ["$type/$name"] = array
        (
            'type'   => $type,
            'name'   => $name,
            'params' => $params,
            'if'     => $if,
            'cond'   => $cond,
        );
        
        $this->_data ['items'] = array_merge ($_item, $this->_data ['items']);
        
        return $this;
    }

    /**
     * Add HEAD Item 'AFTER'
     *
     * Allowed types:
     *  - js
     *  - js_css
     *  - skin_js
     *  - skin_css
     *  - rss
     *
     * @param string $type
     * @param string $name
     * @param string $params
     * @param string $if
     * @param string $cond
     * @return Mage_Page_Block_Html_Head
     */
    public function appendItem ($type, $name, $params = null, $if = null, $cond = null)
    {
        if ($type === 'skin_css' && empty ($params)) $params = 'media="all"';
        
        $_item ["$type/$name"] = array
        (
            'type'   => $type,
            'name'   => $name,
            'params' => $params,
            'if'     => $if,
            'cond'   => $cond,
        );
        
        $this->_data ['items'] = array_merge ($this->_data ['items'], $_item);
        
        return $this;
    }

    public function getHomeUrl ()
    {
        return Mage::helper ('core/url')->getHomeUrl ();
    }

    public function getImageUrl ()
    {
        if (strpos ($this->_pathInfo, self::CATALOG_PRODUCT_VIEW_PATH_INFO) !== false)
        {
            $product = Mage::registry ('product');

            if ($product && $product->getId () && $product->getImage ())
            {
                $result = sprintf (
                    '%s/catalog/product/%s',
                    Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_MEDIA),
                    $product->getImage ()
                );

                return $result;
            }
        }
        else
        {
            return Mage::helper ('basic')->getLogoUrl ();
        }
    }

    public function getLocaleCode ()
    {
        return Mage::helper ('basic')->getLocaleCode ();
    }

    public function getPageTitle ()
    {
        if (strpos ($this->_pathInfo, self::CATALOG_PRODUCT_VIEW_PATH_INFO) !== false)
        {
            $product = Mage::registry ('product');

            if ($product && $product->getId () && $product->getFinalPrice ())
            {
                $result = sprintf (
                    '%s # %s',
                    $product->getName (),
                    Mage::helper ('core')->formatPrice ($product->getFinalPrice (), false),
                );

                return $result;
            }
        }

        return $this->getLayout()->getBlock('head')->getTitle ();
    }

    public function getShortDescription ()
    {
        $description = Mage::getStoreConfig ('general/store_information/short_description');

        if (empty ($description))
        {
            $description = $this->getDescription ();
        }

        if (strpos ($this->_pathInfo, self::CATALOG_PRODUCT_VIEW_PATH_INFO) !== false)
        {
            $product = Mage::registry ('product');

            if ($product && $product->getId () && $product->getShortDescription ())
            {
                $description = $product->getShortDescription ();
            }

            if ($product && $product->getId () && $product->getFinalPrice ())
            {
                $description = sprintf (
                    '%s # %s',
                    $description,
                    Mage::helper ('core')->formatPrice ($product->getFinalPrice (), false),
                );
            }
        }

        return $description;
    }
}

