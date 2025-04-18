<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Core
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Base html block
 *
 * @category   Mage
 * @package    Mage_Core
 *
 * @method $this setTagName(string $value)
 * @method $this setTagParams(array $value)
 */
class Mage_Core_Block_Text_Tag_Js extends Mage_Core_Block_Text_Tag
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTagName('script');
        $this->setTagParams(['language' => 'javascript', 'type' => 'text/javascript']);
    }

    /**
     * @param string $src
     * @param string|null $type
     * @return $this
     */
    public function setSrc($src, $type = null)
    {
        $type = (string) $type;
        if (empty($type)) {
            $type = 'js';
        }
        $url = Mage::getBaseUrl($type) . $src;

        return $this->setTagParam('src', $url);
    }
}
