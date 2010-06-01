<?php
/**
 * Services pour l'authentification.
 * @author Benjamin
 *
 */
class FP_Service_AuthServices {
	/**
	 * Instance courante.
	 * @var FP_Service_AuthServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_AuthServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_AuthServices();
		}
		return self::$instance;
	}

	/**
	 * Connexion à l'administration
	 * @param string $userName le login
	 * @return string
	 */
	public function login($userName, $password) {
		$errors = '';
		define('IN_PHPBB', true);
		include_once('../../forum/includes/functions.php');

		$mapper = FP_Model_Mapper_MapperFactory::getInstance()->userMapper;
		$userBean = $mapper->find($userName);
		if ($userBean) {
			$hash = $userBean->getHashPassword();

			if ($mapper->isUserInGroupAdmin($userName)) {
				$resCheck = phpbb_check_hash($password, $hash);
				if ($resCheck) {
					$auth = $this->getAuth();	

					$authAdapter = new FP_Model_DbTable_UserAuthTable();
					$authAdapter->setIdentity($userName);
					$authAdapter->setCredential($hash);

					$result = $auth->authenticate($authAdapter);
				} else {
					$errors = "Mot de passe incorrect.<br>";
				}
			} else {
				$errors .= "Vous n'avez pas les droits suffisants (vous n'êtes pas dans le groupe permettant l'administration du site).<br>";
			}
		} else {
			$errors .= "Login inconnu.<br>";
		}
		return $errors;
	}

	/**
	 * Retourne l'objet pour l'authentification.
	 * @return Zend_Auth
	 */
	private function getAuth() {
		$auth = Zend_Auth::getInstance();		
		return $auth;
	}
}