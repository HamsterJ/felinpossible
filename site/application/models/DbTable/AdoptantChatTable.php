<?php

/**
 * AdoptantChat table data gateway
 *
 * @uses       Zend_Db_Table_Abstract
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_DbTable_AdoptantChatTable extends Zend_Db_Table_Abstract
{
    /**
     * @var string Name of the database table
     */
    protected $_name = 'fp_ad_cat';
    
    /**
     * Clef primaire.
     * @var string
     */
    protected $_primary = 'idChat'; 
}
