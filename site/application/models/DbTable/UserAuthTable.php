<?php

/**
 * UserAuth table data gateway
 *
 * @uses       Zend_Db_Table_Abstract
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_DbTable_UserAuthTable extends Zend_Auth_Adapter_DbTable {

	public function __construct() {
		parent::__construct(Zend_Db_Table::getDefaultAdapter(), 'phpbb_users', 'username', 'user_password');
	}
}