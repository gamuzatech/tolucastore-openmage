<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Contact API
 */
class Toluca_Bot_Model_Contact_Api extends Mage_Api_Model_Resource_Abstract
{
    public function items ()
    {
        $result = array ();

        $collection = Mage::getModel ('bot/contact')->getCollection ();

        foreach ($collection as $contact)
        {
            $result [] = array(
                'entity_id' => intval ($contact->getId ()),
                'type_id'   => $contact->getTypeId (),
                'name'      => $contact->getName (),
                'number'    => $contact->getNumber (),
                'image'     => $contact->getImage (),
                'is_business'   => boolval ($contact->getIsBusiness ()),
                'is_enterprise' => boolval ($contact->getIsEnterprise ()),
                'is_me'         => boolval ($contact->getIsMe ()),
                'is_psa'        => boolval ($contact->getIsPsa ()),
                'is_user'       => boolval ($contact->getIsUser ()),
                'is_my_contact' => boolval ($contact->getIsMyContact ()),
                'is_wa_contact' => boolval ($contact->getIsWaContact ()),
                'is_active'     => boolval ($contact->getIsActive ()),
                'created_at' => $contact->getCreatedAt (),
                'updated_at' => $contact->getUpdatedAt (),
            );
        }

        return $result;
    }

    public function add ($contactsData)
    {
        if (!is_array ($contactsData) || !count ($contactsData))
        {
            $this->_fault ('invalid_contact_data');
        }

        foreach ($contactsData as $data)
        {
            if (empty ($data ['type_id']) || empty ($data ['number']))
            {
                $this->_fault ('data_invalid', var_export ($data));
            }

            $contact = Mage::getModel ('bot/contact')->getCollection ()
                ->addFieldToFilter ('type_id', $data ['type_id'])
                ->addFieldToFilter ('number',  $data ['number'])
                ->getFirstItem ();

            $exists = $contact && $contact->getId ();

            $contact->setUpdatedAt ($exists ? 'updated_at' : 'created_at', date ('c'));
            $contact->addData ($data)->save ();

            if (!$contact || !$contact->getId ())
            {
                $this->_fault ('add_contact_fault');
            }
        }

        return true;
    }
}

