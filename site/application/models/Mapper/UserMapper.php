<?php

/**
 * User data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_UserMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'User';

	protected $mappingDbToModel = array(
	                 'user_id' => 'id',
                     'username' => 'login',
	                 'user_password' => 'hashPassword',
	                 'user_email' => 'email',
	                 'group_name' => 'groupName');

	/**
	 * Retourne true si le $username est dans le group $groupId
	 * @param unknown_type $username
	 * @return boolean
	 */
	public function isUserInGroupAdmin($username) {
		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
		$groupId = $config->group->id->admin->site;
			
		$select = $this->getDbTable()->select()
		->setIntegrityCheck(false)
		->from(array('users' => 'phpbb_users'))
		->join(array('groups' => 'phpbb_user_group'), 'users.user_id = groups.user_id')
		->where('users.username = ?', $username)
		->where('groups.group_id = ?', $groupId);

		$stmt = $select->query();
		if (count($stmt->fetchAll()) > 0) {
			return true;
		}
		return false;
	}
	
	/**
	 * Retourne les contacts.
	 * @return array
	 */
	public function getListeContacts() {
		$select = $this->getDbTable()->select()
		->setIntegrityCheck(false)
		->from(array('users' => 'phpbb_users'))
		->joinLeft(array('groups' => 'phpbb_groups'), 'users.group_id = groups.group_id')
		->joinRight(array('fa' => 'fp_fa_fiche'), 'users.user_email = fa.email', array('username' => 'concat(fa.nom, " ", fa.prenom)', 'user_email' => 'fa.email'))
		->where('users.user_email is not null and fa.email is null and (groups.group_id != ?  and groups.group_id != ?)', 6, 1)
		->orWhere('fa.email is not null and users.user_email is null')
		->order('users.username');

		$stmt = $select->query();
		return $this->getDataFromRowSet($stmt->fetchAll());
	}
}