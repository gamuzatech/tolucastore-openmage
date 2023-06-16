<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Webservice api session
 */
class Gamuza_Basic_Model_Api_Session extends Mage_Api_Model_Session
{
    /**
     * @param string|null $sessionName
     * @return $this
     */
    public function start($sessionName = null)
    {
//        parent::start($sessionName=null);

        $this->_currentSessId = hash('sha512', time() . uniqid('', true) . $sessionName);

        $this->sessionIds[] = $this->getSessionId();

        return $this;
    }
}

