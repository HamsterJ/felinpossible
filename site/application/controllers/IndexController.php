<?php
/**
 * Controller pour les actions de la page principale (partie publique).
 * @author Benjamin
 *
 */
class IndexController extends FP_Controller_CommonController
{

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Controller/Zend_Controller_Action#init()
	 */
	public function init() {
		$this->_helper->layout->disableLayout();
	}

	/**
	 * Page d'index.
	 * @return unknown_type
	 */
	public function indexAction()
	{
		$this->_helper->layout->enableLayout();
		$this->render('home');
	}

	public function homeAction() {}
	public function arriveeAction() {}
	public function adherentAction() {}
	public function benevoleAction() {}
	public function faAction() {}
	public function dangersAction() {}
	public function liensAction() {}
	public function adoptionAction() {}
	public function boutiqueAction() {}
	public function contactAction() {}
	public function remerciementsAction() {}
	public function perdusAction() {}
	public function presentationAction() {}
	public function santeAction() {}
	public function statutsAction() {}
	public function sterilisationAction() {}
	public function tarifsAction() {}

	/**
	 * Action pour le formulaire de parrainage.
	 */
	public function parrainerAction() {
		$request = $this->getRequest();
		$form = new FP_Form_parrainage_Form();

		// Check to see if this action has been POST'ed to.
		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				$mailServices = FP_Service_MailServices::getInstance();
				$mailServices->envoiMailAsso(FP_Util_Constantes::MAIL_PARRAINER_SUJET, $form->toHtml());
				return $this->_helper->redirector('remerciements');
			}
		}
		$form->setAction('javascript:showPage("'.$this->view->url(array('action' => 'parrainer')).'","'.$form->getId().'")');
		// Assign the form to the view
		$this->view->form = $form;
	}



	/**
	 * Aide à la saisie d'un animal.
	 */
	public function saisiranimalAction() {
		$request = $this->getRequest();
		$form = new FP_Form_common_SaisirAnimalForm();

		// Assign the form to the view
		$this->view->form = $form;
	}

	/**
	 * Aide à la saisie d'un chat.
	 */
	public function saisirchatAction() {
		$request = $this->getRequest();
		$form = new FP_Form_common_SaisirAnimalChatForm();

		// Assign the form to the view
		$this->view->form = $form;
		$this->render('saisiranimal');
	}

}

