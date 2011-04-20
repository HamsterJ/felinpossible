<?php
/**
 * Controller pour la partie administration du site.
 * @author Benjamin
 *
 */
class AdminController extends FP_Controller_CommonController
{
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Controller/Zend_Controller_Action#init()
	 */
	public function init()
	{
		$view = $this->view;
		$view->headLink()->appendStylesheet('/site/public/js/dojo/dojox/grid/resources/Grid.css');
		$view->headLink()->appendStylesheet('/site/public/js/dojo/dojox/grid/resources/tundraGrid.css');
		$view->headScript()->appendFile('/site/public/js/adminCommon.js');

		$auth = Zend_Auth::getInstance();
		$view->login = $auth->getIdentity();
	}

	/**
	 * Action pour la page d'index de l'administration.
	 */
	public function indexAction()
	{
		if ($this->checkIsLogged(true)) {
			$this->indexajaxAction();
			$this->_helper->layout->enableLayout();
			$this->_helper->layout->setLayout('admin');
			$this->render('indexajax');
		}
	}

	/**
	 * Action pour le login sans rechargement du layout.
	 */
	public function loginwithlayoutAction() {
		$this->_helper->layout->setLayout('admin');
		$this->loginAction();
		$this->render('login');
	}

	/**
	 * Action pour se loguer à l'administration.
	 */
	public function loginAction () {
		$form = new FP_Form_admin_login_Form();
		$request = $this->getRequest();

		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				$authServices = FP_Service_AuthServices::getInstance();
				$errors = $authServices->login(htmlentities($form->login->getValue()), $form->password->getValue());

				if ($errors && $errors != '') {
					$this->view->errors = $errors;
				} else {
					return $this->_helper->redirector('index');
				}
			}
		}
		$this->view->form = $form;
	}

	/**
	 * Logout.
	 */
	public function logoutAction () {
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$auth->clearIdentity();
		}
		return $this->_helper->redirector('loginWithLayout');
	}

	/**
	 * Affichage de l'agenda GoogleAgenda.
	 */
	public function agendaAction() {
		$this->_helper->layout->disableLayout();
		if ($this->checkIsLogged()) {
			
		}
	}

	/**
	 * Affichage de la page d'accueil.
	 */
	public function indexajaxAction() {
		$this->_helper->layout->disableLayout();
		if ($this->checkIsLogged()) {
				
			$serviceChat = FP_Service_ChatServices::getInstance();
			$serviceFa = FP_Service_FaServices::getInstance();
				
			$this->view->indicNbChatsAdoption = $serviceChat->getNbFiches(FP_Util_Constantes::CHAT_FICHES_A_ADOPTION);
			$this->view->indicNbChatsRes = $serviceChat->getNbFiches(FP_Util_Constantes::CHAT_FICHES_RESERVES);
			$this->view->indicNbFichesAValider = $serviceChat->getNbFiches(FP_Util_Constantes::CHAT_FICHES_A_VALIDER);
				
			$this->view->indicNbFaActive = $serviceFa->getNbFaWithStatus(FP_Util_Constantes::FA_ACTIVE_STATUT);
			$this->view->indicNbFaDispo = $serviceFa->getNbFaWithStatus(FP_Util_Constantes::FA_DISPONIBLE_STATUT);
			
			$this->view->urlExportChatUrl = $this->view->url(array('controller' => 'chat', 'action' => 'export'));
			$this->view->urlTableauChatUrl = $this->view->url(array('controller' => 'chat', 'action' => 'tableauadoptes'));
			$this->view->urlExportFaUrl = $this->view->url(array('controller' => 'fa', 'action' => 'export'));
			$this->view->urlExportAdoptantUrl = $this->view->url(array('controller' => 'adoptant', 'action' => 'export'));
			$this->view->urlExportContactUrl = $this->view->url(array('action' => 'exportcontacts'));
		}
	}

	/**
	 * Action pour exporter la liste des contacts du forum.
	 */
	public function exportcontactsAction() {
		if ($this->checkIsLogged()) {
			FP_Service_ExportServices::getInstance()->buildCsvContactsForum();
			exit;
		}
	}
}

