<?php
/**
 * Controller commun pour le controller Adoptant/Fa Controller avec la gestion des subForms.
 * @author Benjamin
 *
 */
abstract class FP_Controller_SubFormController extends FP_Controller_CommonController
{
	/**
	 * Le nom du namspace pour stocket le fomulaire.
	 */
	protected $_namespaceForm = 'formNamespace';

	/**
	 * La session courante.
	 * @var Zend_Session_Namespace
	 */
	protected $_session;
	/**
	 * La session basée sur le namspace contenant le formulaire courant.
	 * @var Zend_Session_Namespace
	 */
	protected $_sessionForm;

	// Les méthodes à redéfinir dans les classes filles.
	abstract protected function getFormClassName();
	abstract protected function getNamespace();
	abstract protected function getViewTitle();
	abstract protected function getHeaderPath();
	abstract protected function getEmailSubject();
	abstract protected function getStyleClass();
	abstract protected function getFilterPath();
	abstract public function exportAction();

	/**
	 * Retourne le service
	 * @return FP_Service_CommonServices
	 */
	abstract protected function getService();

	public function indexAction() {}
	public function remerciementsAction() {}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#postulerAction()
	 */
	public function postulerAction() {
		$request = $this->getRequest();

		if ($request->getParam("admin")) {
			$this->checkIsLogged();
			$this->view->admin = true;
		}

		$this->getSessionNamespace()->unsetAll();
		$this->getSessionNamespaceForm()->unsetAll();

		$ficheId = $request->getParam('id', null);
		// On est en édition
		if ($ficheId) {
			if ($this->checkIsLogged()){
				$this->view->id = $ficheId;
				$data = $this->getService()->getData($ficheId);
				$this->getForm()->populate($data);
			}
		}

		if (!$form = $this->getCurrentSubForm()) {
			$form = $this->getNextSubForm();
		}
		$form->setAction($this->getFormAction($form->getId()));
		$this->view->form = $this->getForm()->prepareSubForm($form);
	}


	/**
	 * Actions lorsque le formulaire est complet.
	 */
	protected function handleFormCompleted(){
		$this->getService()->saveForm($this->getSessionNamespace());
		FP_Service_MailServices::getInstance()->envoiMailAsso($this->getEmailSubject(), $this->getForm()->toHtml());
		return $this->_helper->redirector('remerciements');
	}


	/**
	 * Actions lorsque le formulaire est complet (admin).
	 */
	protected function handleAdminFormCompleted(){
		$this->getService()->saveForm($this->getSessionNamespace());
		return $this->_helper->redirector('indexadm');
	}

	/**
	 * Retourne la liste des fiches des adoptants pour l'admin au format json.
	 */
	public function listeAction () {
		if ($this->checkIsLogged()) {
			$data = $this->getService()->getJsonData($this->getRequest()->getParams());
			echo $data;
			exit;
		}
	}

	/**
	 * Initialisation des paramètres utilisés pour la grille.
	 */
	protected function initGridParam() {
		$this->view->urlListeJson = $this->view->url(array('action' => 'liste'));
		$this->view->urlAddItem = $this->view->url(array('action' => 'postuler', 'admin' => true));
		$this->view->urlEditItem = $this->view->url(array('action' => 'postuler', 'admin' => true));
		$this->view->urlDeleteItem = $this->view->url(array('action' => 'delete'));
		$this->view->urlExportUrl = $this->view->url(array('action' => 'export'));
		$this->view->filterPath = $this->getFilterPath();
		$this->view->gridName = "commonGrid";
		
		$this->view->headerPath = $this->getHeaderPath();
		$this->view->class = $this->getStyleClass();
		$this->view->titre = $this->getViewTitle();

		$this->view->defaultSort = -3;
		$this->view->nbElements = $this->getService()->getNbElementsForGrid();
	}

	/**
	 * Index de la partie admin pour les fiches des adoptants.
	 */
	public function indexadmAction() {
		if ($this->checkIsLogged()) {
			$this->initGridParam();
			$this->render("indexgrid");
		}
	}

	/**
	 * Suppression d'une fiche (partie admin).
	 */
	public function deleteAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			$this->getService()->deleteElement($request->getParam("id"));
			exit;
		}
	}


	/**
	 * Action pour gérér les subForms du formulaire de FA.
	 */
	public function processAction()
	{
		if (!$this->getRequest()->isPost()) {
			$this->getSessionNamespace()->unsetAll();
		}

		if ($this->getRequest()->getParam("admin")) {
			$this->view->admin = true;
		}

		if (!$form = $this->getCurrentSubForm()) {
			return $this->_forward('postuler');
		}

		if (!$this->subFormIsValid($form,
			$this->getRequest()->getPost())
			|| ($form = $this->getNextSubForm())) {
			$form->setAction($this->getFormAction($form->getId()));
			$this->view->form = $this->getForm()->prepareSubForm($form);
			return $this->render('postuler');
		}

		if (!$this->formIsValid()) {
			$form = $this->getNextSubForm();
			$form->setAction($this->getFormAction($form->getId()));
			$this->view->form = $this->getForm()->prepareSubForm($form);
			return $this->render('postuler');
		}

		// All subForms have been processed.
		if ($this->view->admin) {
			$this->handleAdminFormCompleted();
		} else {
			$this->handleFormCompleted();
		}
	}

	/**
	 * Getter pour le formulaire courant.
	 */
	public function getForm()
	{
		$formInNamespace = $this->getSessionNamespaceForm()->form;
		if (!$formInNamespace) {
			$formClass = $this->getFormClassName();
			$formInNamespace = new $formClass();
			$this->getSessionNamespaceForm()->form = $formInNamespace;
		}
		return $formInNamespace;

	}
	
	/**
	 * Retourne l'action pour le formulaire.
	 * @param string $formId
	 * @return string
	 */
	private function getFormAction($formId) {
		return 'javascript:callAjax("'.$this->view->url(array('action' => 'process', 'admin' => $this->view->admin)).'",null, null, "'.$formId.'")';
	}
	/**
	 * Get the session namespace we're using
	 *
	 * @return Zend_Session_Namespace
	 */
	public function getSessionNamespace()
	{
		if (null === $this->_session) {
			$this->_session =
			new Zend_Session_Namespace($this->getNamespace());
		}

		return $this->_session;
	}

	/**
	 * Get the session namespace for form we're using
	 *
	 * @return Zend_Session_Namespace
	 */
	public function getSessionNamespaceForm()
	{
		if (null === $this->_sessionForm) {
			$this->_sessionForm =
			new Zend_Session_Namespace($this->_namespaceForm);
		}

		return $this->_sessionForm;
	}

	/**
	 * Get a list of forms already stored in the session
	 *
	 * @return array
	 */
	public function getStoredForms()
	{
		$stored = array();
		foreach ($this->getSessionNamespace() as $key => $value) {
			$stored[] = $key;
		}

		return $stored;
	}

	/**
	 * Get list of all subforms available
	 *
	 * @return array
	 */
	public function getPotentialForms()
	{
		return array_keys($this->getForm()->getSubForms());
	}

	/**
	 * What sub form was submitted?
	 *
	 * @return false|Zend_Form_SubForm
	 */
	public function getCurrentSubForm()
	{
		$request = $this->getRequest();
		if (!$request->isPost()) {
			return false;
		}

		foreach ($this->getPotentialForms() as $name) {
			if ($data = $request->getPost($name, false)) {
				if (is_array($data)) {
					$subForm = $this->getForm()->getSubForm($name);
					$subForm->setLegend($this->getSubFormLegend ());
					return $subForm;
				}
			}
		}
		return false;
	}

	/**
	 * Get the next sub form to display
	 *
	 * @return Zend_Form_SubForm|false
	 */
	public function getNextSubForm()
	{
		$storedForms    = $this->getStoredForms();
		$potentialForms = $this->getPotentialForms();

		foreach ($potentialForms as $name) {
			if (!in_array($name, $storedForms)) {
				$subForm = $this->getForm()->getSubForm($name);
				$subForm->setLegend($this->getSubFormLegend ());
				return $subForm;
			} else {

			}
		}

		return false;
	}

	/**
	 * Return la légende du subForm.
	 * @return string
	 */
	private function getSubFormLegend () {
		$nbSubForms = count($this->getPotentialForms());
		$currentSubForm = count($this->getStoredForms()) + 1;

		return " ($currentSubForm/$nbSubForms)";
	}

	/**
	 * Is the sub form valid?
	 *
	 * @param  Zend_Form_SubForm $subForm
	 * @param  array $data
	 * @return bool
	 */
	public function subFormIsValid(Zend_Form_SubForm $subForm, array $data)
	{
		$name = $subForm->getName();
		if ($subForm->isValid($data)) {
			$this->getSessionNamespace()->$name = $subForm->getValues();
			return true;
		}

		return false;
	}

	/**
	 * Is the full form valid?
	 *
	 * @return bool
	 */
	public function formIsValid()
	{
		$data = array();
		foreach ($this->getSessionNamespace() as $key => $info) {
			$data[$key] = $info;
		}

		return $this->getForm()->isValid($data);
	}
}