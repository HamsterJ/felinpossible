<?php

/**
 * FP_Model_DbTable_StockMaterielsDemandeTable table data gateway
 *
 * @uses       Zend_Db_Table_Abstract
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_DbTable_StockMaterielsDemandeTable extends Zend_Db_Table_Abstract
{
    /**
     * @var string Name of the database table
     */
    protected $_name = 'fp_stock_materiels_demande';
    
    /**
     * Clef primaire.
     */
    protected $_primary = 'id'; 
}