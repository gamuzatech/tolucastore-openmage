<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = $this;
$installer->startSetup ();

$connection = $this->getConnection ();

/**
 * NFC-e & NF-e
 */
$entities = array(
    'nfce' => Gamuza_Brazil_Helper_Data::NFCE_TABLE,
    'nfe'  => Gamuza_Brazil_Helper_Data::NFE_TABLE,
);

foreach ($entities as $entity => $table)
{
    /**
     * Authorized
     */
    $select = $connection->select ()
        ->join(
            array ('response' => $this->getTable ($table . '_response')),
            sprintf (
                'response.%s_id = %s.entity_id AND response.received_id = %d',
                $entity, $entity, Gamuza_Brazil_Helper_Data::NFE_RESPONSE_AUTHORIZED
            ),
            array ('authorized_at' => 'response.received_at')
        )
    ;

    $connection->query(
        $select->crossUpdateFromSelect(
            array ($entity => $this->getTable ($table))
        )
    );

    /**
     * Corrected
     */
    $select = $connection->select ()
        ->join(
            array ('event' => $this->getTable ($table . '_event')),
            sprintf (
                'event.%s_id = %s.entity_id AND event.received_id = %d AND event.type_id = %d',
                $entity, $entity,
                Gamuza_Brazil_Helper_Data::NFE_EVENT_CORRECTED,
                Gamuza_Brazil_Helper_Data::NFE_EVENT_TYPE_CORRECTED,
            ),
            array ('corrected_at' => 'event.received_at')
        )
    ;

    $connection->query(
        $select->crossUpdateFromSelect(
            array ($entity => $this->getTable ($table))
        )
    );

    /**
     * Canceled
     */
    $select = $connection->select ()
        ->join(
            array ('event' => $this->getTable ($table . '_event')),
            sprintf (
                'event.%s_id = %s.entity_id AND event.received_id = %d AND event.type_id = %d',
                $entity, $entity,
                Gamuza_Brazil_Helper_Data::NFE_EVENT_CANCELED,
                Gamuza_Brazil_Helper_Data::NFE_EVENT_TYPE_CANCELED,
            ),
            array ('canceled_at' => 'event.received_at')
        )
    ;

    $connection->query(
        $select->crossUpdateFromSelect(
            array ($entity => $this->getTable ($table))
        )
    );
}

$installer->endSetup ();

