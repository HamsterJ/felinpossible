<?php

/**
 * Post table data gateway
 *
 * @uses       Zend_Db_Table_Abstract
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_DbTable_PostTable extends Zend_Db_Table_Abstract
{
    /**
     * @var string Name of the database table
     */
    protected $_name = 'phpbb_posts';
    
    /**
     * Primary key.
     * @var string
     */
    protected $_primary ='post_id';
}