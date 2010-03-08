<?php

/**
 * FaChat table data gateway
 *
 * @uses       Zend_Db_Table_Abstract
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_DbTable_FaChatTable extends Zend_Db_Table_Abstract
{
    /**
     * @var string Name of the database table
     */
    protected $_name = 'fp_fa_cat';
    
    /**
     * Clef primaire.
     * @var string
     */
    protected $_primary = 'idChat'; 
}
