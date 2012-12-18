<?php
/**
 * Controller commun.
 * @author Benjamin
 *
 */
abstract class FP_Controller_CommonController extends Zend_Controller_Action {


	/**
	 * Initialisation des actions
	 * @see site/library/Zend/Controller/Zend_Controller_Action#init()
	 */
	public function init() {
		if($this->_request->isXmlHttpRequest())
		{
			$this->_helper->layout()->disableLayout();
		}
	}
	
	/**
	 * Test si l'utilisateur est loggé, si non, redirection vers la page de login.
	 */
	protected function checkIsLogged() {
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_forward('login', 'admin');
			return false;
		}
		return true;
	}
	
}