<?php
/**
 * @package     Gamuza_Autocomplete
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Autocomplete_CepController extends Mage_Core_Controller_Front_Action
{
    public function indexAction ()
    {
	    $q = $this->getRequest ()->getParam ('q', false);

	    if (!empty ($q))
	    {
            $result = Mage::helper ('autocomplete')->cep ($q);

	        $this->getResponse ()
                ->clearHeaders ()
                ->setHeader('Content-Type', 'application/json')
                ->setBody ($result);
	    }

        return $this;
    }
}

