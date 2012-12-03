<?php
/**
 * Controller commun.
 * @author Benjamin
 *
 */
abstract class FP_Controller_CommonController extends Zend_Controller_Action {
	/**
	 * Test si l'utilisateur est loggé, si non, redirection vers la page de login.
	 */
	protected function checkIsLogged($enableLayout = false) {
		if ($enableLayout == true) {
			$action = "loginWithLayout";
		} else {
			$action = "login";
		}
		
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_forward($action, 'admin');
			return false;
		}
		return true;
	}

	/**
	 * Ajout header pour cache.
	 */
	public function postDispatch() {
		$this->getResponse()->setHeader('Expires', '', true);
		$this->getResponse()->setHeader('Cache-Control', 'public', true);
		$this->getResponse()->setHeader('Cache-Control', 'max-age=3800');
		$this->getResponse()->setHeader('Pragma', '', true);
	}
	
}