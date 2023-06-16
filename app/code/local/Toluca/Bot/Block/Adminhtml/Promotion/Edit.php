<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Block_Adminhtml_Promotion_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct ()
	{
		parent::__construct ();

		$this->_blockGroup = 'bot';
		$this->_controller = 'adminhtml_promotion';
		$this->_objectId   = 'entity_id';

		$this->_updateButton ('save',   'label', Mage::helper ('bot')->__('Save Promotion'));
		$this->_updateButton ('delete', 'label', Mage::helper ('bot')->__('Delete Promotion'));

		$this->_addButton ('saveandcontinue', array(
			'label'   => Mage::helper ('bot')->__('Save and Continue Edit'),
			'onclick' => 'saveAndContinueEdit ()',
			'class'   => 'save',
		), -100);

		$this->_formScripts [] = "
			function saveAndContinueEdit () {
				editForm.submit ($('edit_form').action + 'back/edit/');
			}
		";

        $id = $this->getRequest ()->getParam ('id');

        if ($id > 0)
        {
            $this->_addButton('send', array(
                'label'   => Mage::helper('bot')->__('Send Promotion'),
                'class'   => 'scalable delete',
                'onclick' => sprintf (
                    "confirmSetLocation('%s', '%s')",
                    $this->getSendMessage (), $this->getSendUrl ()
                ),
            ), -100);

            $collection = Mage::getModel ('bot/queue')->getCollection ()
                ->addFieldToFilter ('promotion_id', array ('eq' => $id))
            ;

            if ($collection->getSize () > 0)
            {
                $this->_removeButton ('delete');
            }
        }
	}

	public function getHeaderText ()
	{
        $promotion = Mage::registry ('promotion_data');

		if ($promotion && $promotion->getId ())
        {
		    return Mage::helper ('bot')->__("Edit Promotion '%s'", $this->htmlEscape ($promotion->getId ()));
		}
		else
        {
		     return Mage::helper ('bot')->__('Add New Promotion');
		}
	}

    public function getSendMessage ()
    {
        return Mage::helper ('bot')->__('Confirm sending the promotion to the queue?');
    }

    public function getSendUrl ()
    {
        return $this->getUrl ('*/*/queue', array ('id' => $this->getRequest()->getParam('id')));
    }
}

