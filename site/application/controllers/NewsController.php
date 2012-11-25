<?php
/**
 * Controller pour les News.
 * @author Benjamin
 *
 */
class NewsController extends FP_Controller_CommonController {

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Controller/Zend_Controller_Action#init()
	 */
	public function init()
	{
		$this->_helper->layout->disableLayout();
	}

	/**
	 * Retourne le service associé au controller.
	 * @return FP_Service_NewsServices
	 */
	private function getService() {
		return FP_Service_NewsServices::getInstance();
	}
	
	/**
	 * Affiche les news paginées.
	 */
	public function indexAction() {
		$result = $this->getService()->getPage($this->getRequest()->getParams());
		
		$this->view->entries = $result[FP_Util_PaginationConstantes::DATA_KEY];
		$this->view->paginator = $result[FP_Util_PaginationConstantes::PAGINATOR_KEY];
	}

	/**
	 * Retourne la liste des news pour l'admin au format json.
	 */
	public function listeAction () {
		if ($this->checkIsLogged()) {
			echo $this->getService()->getJsonData($this->getRequest()->getParams());
			exit;
		}
	}

	/**
	 * Index de la partie admin pour les news.
	 */
	public function indexadmAction() {
		if ($this->checkIsLogged()) {

			$this->view->urlListeJson = $this->view->url(array('action' => 'liste'));
			$this->view->urlAddItem = $this->view->url(array('action' => 'add'));
			$this->view->urlEditItem = $this->view->url(array('action' => 'edit'));
			$this->view->urlDeleteItem = $this->view->url(array('action' => 'delete'));
			$this->view->headerPath = "news/headeradm.phtml";
			$this->view->class = "ficheNews";
			$this->view->titre = "Liste des news";
			
			$this->view->defaultSort = -4;
			
			$this->view->nbElements = $this->getService()->getNbElementsForGrid();

			$this->render("indexgrid");
		}
	}

	/**
	 * Suppression d'une news (partie admin).
	 */
	public function deleteAction() {
		if ($this->checkIsLogged()) {
			$id = $this->getRequest()->getParam('id');
			$this->getService()->deleteElement($id);
			exit;
		}
	}

	/**
	 * Action pour ajouter une nouvelle news.
	 */
	public function addAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			$form = new FP_Form_news_Form();

			// Check to see if this action has been POST'ed to.
			if ($request->isPost()) {
				if ($form->isValid($request->getPost())) {
					$this->getService()->save($form->getValues());
					
					return $this->_helper->redirector('indexadm');
				}
			}
			$form->setAction('javascript:CallAjax("'.$this->view->url(array('action' => 'add')).'", null, null, "'.$form->getId().'")');
			// Assign the form to the view
			$this->view->form = $form;
		}
	}

	/**
	 * Action pour éditer une news.
	 */
	public function editAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			$form = new FP_Form_news_Form();
			$newsId = $request->getParam('id', null);

			if ($newsId) {
				$data = $this->getService()->getData($newsId);
				if ($data) {
					$form->populate($data);
					$form->setAction('javascript:callAjax("'.$this->view->url(array('action' => 'add')).'", null, null, "'.$form->getId().'")');
					$this->view->form = $form;
					$this->render("add");
				}
			}
		}
	}
	
	/**
	 * Affiche le flux rss pour les news.
	 */
	public function fluxAction() {
		echo FP_Service_FluxServices::getInstance()->getFluxAtom()->send();
	}
}

