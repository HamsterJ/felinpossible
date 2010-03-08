<?php
/**
 * Controller pour les FA.
 * @author Benjamin
 *
 */
class FaController extends FP_Controller_SubFormController
{
	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFormClassName()
	 */
	protected function getFormClassName() {
		return 'FP_Form_fa_Form';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getNamespace()
	 */
	protected function getNamespace() {
		return 'FaController';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getHeaderPath()
	 */
	protected function getHeaderPath() {
		return "fa/headeradm.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFilterPath()
	 */
	protected function getFilterPath() {
		return "fa/filterfa.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getViewTitle()
	 */
	protected function getViewTitle() {
		return "Fiches des FA";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getEmailSubject()
	 */
	protected function getEmailSubject() {
		return FP_Util_Constantes::MAIL_BE_FA_SUJET;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getStyleClass()
	 */
	protected function getStyleClass() {
		return "ficheFa";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getService()
	 * @return FP_Service_FaServices
	 */
	protected function getService() {
		return FP_Service_FaServices::getInstance();
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#handleFormCompleted()
	 */
	protected function handleFormCompleted(){
		$this->getService()->saveForm($this->getSessionNamespace());
		FP_Service_MailServices::getInstance()->envoiMailAssoFa($this->getEmailSubject(), $this->getForm()->toHtml());
		return $this->_helper->redirector('remerciements');
	}
	
	/**
	 * Action pour l'affichage de la page de gestion des FA.
	 */
	public function indexgestionAction() {
		if ($this->checkIsLogged()) {

			$this->view->urlListeJson = $this->view->url(array('action' => 'liste'));
			$this->view->urlAddItem = $this->view->url(array('action' => 'postuler', 'admin' => true));
			$this->view->urlEditItem = $this->view->url(array('action' => 'gestion', 'admin' => true));
			$this->view->urlDeleteItem = $this->view->url(array('action' => 'delete'));
			$this->view->urlExportUrl = $this->view->url(array('action' => 'export'));
			$this->view->filterPath = $this->getFilterPath();
			$this->view->gridName = "commonGrid";

			$this->view->headerPath = $this->getHeaderPath();
			$this->view->class = $this->getStyleClass();
			$this->view->titre = "Gestion des FA";

			$this->view->defaultSort = -3;
			$this->view->nbElements = $this->getService()->getNbElementsForGrid();

			$this->render("indexgrid");
		}
	}

	/**
	 * Gestion la partie "Infos" (init/update).
	 * @param string $ficheId id. de la fiche de la FA.
	 */
	private function handleInfosFa($ficheId) {
		$form = new FP_Form_fa_GestionForm();

		$request = $this->getRequest();

		$fiche = $this->getService()->getBeanFa($ficheId);

		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				if ($fiche) {
					$this->getService()->saveGestionInfos($fiche, $form->getValues());
				}
			}
		} else {
			if ($fiche) {
				$form->populate($fiche->toArray());
			}
		}

		$this->view->fiche = $fiche;
		$form->setAction('javascript:showPage("'.$this->view->url(array('action' => 'validerinfos')).'","'.$form->getId().'","infos", "chargementInfos")');
		$this->view->form = $form;
	}

	/**
	 * Gestion de la partie "Chats en accueil".
	 * @param string $ficheId id. de la fiche de la FA.
	 */
	private function handleGridChats($ficheId) {
		$this->view->urlChatListeJson = $this->view->url(array('action' => 'listechat', 'id' => $ficheId, 'idChat' => null));
		$this->view->urlChatAddItem = $this->view->url(array('action' => 'ajoutchat', 'id' => $ficheId, 'idChat' => null));
		$this->view->urlChangerFa = $this->view->url(array('action' => 'changerfa', 'id' => $ficheId, 'idChat' => null));
		$this->view->urlChatDeleteItem = $this->view->url(array('action' => 'deletechat', 'id' => $ficheId, 'idChat' => null));

		$this->view->headerChatPath = "chat/headeradm.phtml";
		$this->view->titreChat = "Liste des chats en accueil dans cette FA";

		if ($ficheId) {
			$this->view->nbChats = $this->getService()->getNbChatsForFa($ficheId);
		}
	}

	/**
	 * Gestion de la partie "Indisponibilités".
	 * @param string $ficheId id. de la fiche de la FA.
	 */
	private function handleGridIndispo($ficheId) {
		$this->view->urlIndispoListeJson = $this->view->url(array('action' => 'listeindispo', 'id' => $ficheId));
		$this->view->urlIndispoAdd = $this->view->url(array('action' => 'ajoutindispo', 'id' => $ficheId));
		$this->view->urlIndispoDelete = $this->view->url(array('action' => 'deleteindispo', 'id' => $ficheId));
		$this->view->urlIndispoEdit =  $this->view->url(array('action' => 'editindispo', 'id' => $ficheId));

		$this->view->headerIndispoPath = "fa/headerindispo.phtml";
		$this->view->titreIndispo = "Indisponibilité(s) de la FA";
	}

	/**
	 * Liste les chats en accueil dans la FA.
	 */
	public function listechatAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo $this->getService()->getJsonDataChatForFa($request->getParams());
			exit;
		}
	}

	/**
	 * Liste les indisponibilités de la FA.
	 */
	public function listeindispoAction () {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo $this->getService()->getJsonDataIndispo($request->getParams());
			exit;
		}
	}

	/**
	 * Action pour gérer une FA.
	 */
	public function gestionAction() {
		if ($this->checkIsLogged()) {

			$ficheId = $this->getRequest()->getParam('id', null);

			$this->handleInfosFa($ficheId);
			$this->handleGridChats($ficheId);
			$this->handleGridIndispo($ficheId);
		}
	}

	/**
	 * Validation du formualaire de modification de infos de la FA.
	 */
	public function validerinfosAction() {
		if ($this->checkIsLogged()) {

			$ficheId = $this->getRequest()->getParam('id', null);

			$this->handleInfosFa($ficheId);
			$this->render('fa/infosfa', 'fa', 'fa');
		}
	}

	/**
	 * Action pour accéder à l'écran d'ajout d'un chat.
	 */
	public function ajoutchatAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id', null);
			$idChatSelected = $this->getRequest()->getParam('idChat', null);

			if ($idChatSelected) {
				$this->getService()->ajoutChat($idChatSelected, $ficheId);
				$this->_forward('gridchats', null, null, array('id' => $ficheId));
				return;
			} else {
				$this->view->urlChatListeJson = $this->view->url(array('controller' => 'chat', 'action' => 'liste', FP_Util_Constantes::WHERE_KEY => FP_Util_Constantes::CHAT_FICHES_A_PLACER));
				$this->view->urlSelectChat = $this->view->url(array('action' => 'ajoutchat', 'id' => $ficheId));
				$this->view->urlRetourListeChat = $this->view->url(array('action' => 'gridchats', 'id' => $ficheId));
				$this->view->headerChatPath = "chat/headeradm.phtml";
				$this->view->filterPath = "chat/filterchat.phtml";
				$this->view->gridName = "gridChatSelection";
				
				$this->view->titre = "Sélectionner un chat à l'adoption";
			}
		}
	}

	/**
	 * Action pour l'ajout d'une indisponibilité.
	 */
	public function ajoutindispoAction() {
		if ($this->checkIsLogged()) {
			$form = new FP_Form_fa_IndispoForm;
			$request = $this->getRequest();
			$ficheId = $request->getParam('id');
			if ($request->isPost()) {
				if ($form->isValid($request->getPost())) {
					FP_Service_FaServices::getInstance()->ajoutIndispo($form->getValues());
					$this->_forward('gridindispo', null, null, array('id' => $ficheId));
					return;
				}
			}

			$this->view->urlRetourListeIndispo = $this->view->url(array('action' => 'gridindispo', 'id' => $ficheId));
			$form->idFa->setValue($ficheId);
			$form->setAction('javascript:showPage("'.$this->view->url(array('action' => 'ajoutindispo')).'","'.$form->getId().'","indispo", "chargementIndispo")');
			$this->view->formIndispo = $form;
		}
	}

	/**
	 * Affiche la grille des indisponibilités pour une FA.
	 */
	public function gridindispoAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id');
			if ($ficheId) {
				$this->handleGridIndispo($ficheId);
			}
		}
	}

	/**
	 * Affiche la grille des chats pour une FA.
	 */
	public function gridchatsAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id');
			if ($ficheId) {
				$this->handleGridChats($ficheId);
			}
		}
	}

	/**
	 * Action pour éditer une indisponibilité.
	 */
	public function editindispoAction() {
		if ($this->checkIsLogged()) {
			$form = new FP_Form_fa_IndispoForm;
			$request = $this->getRequest();
			$indispoId = $request->getParam('idIndispo');
			$ficheId = $request->getParam('id');
			$fromIndex = $request->getParam('fromIndex', null);

			if ($request->isPost()) {
				if ($form->isValid($request->getPost())) {
					$this->getService()->ajoutIndispo($form->getValues(), $form->idFa->getValue());

					if ($fromIndex) {
						$this->_forward('indexindispo');
					}else {
						$this->_forward('gridindispo', null, null, array('id' => $form->idFa->getValue()));
					}
					return;
				}
			} else if ($indispoId) {
				$bean = $this->getService()->getBeanIndispo($indispoId);
				if ($bean) {
					$form->populate($bean->toArray());
				} else {
					echo "Id : $indispoId inconnu";
					$this->_forward('indexindispo');
				}
			}

			if ($fromIndex) {
				$form->setAction('javascript:showPage("'.$this->view->url(array('action' => 'editindispo')).'","'.$form->getId().'","corps", "chargementCorps")');
			} else {
				$form->setAction('javascript:showPage("'.$this->view->url(array('action' => 'editindispo')).'","'.$form->getId().'","indispo", "chargementIndispo")');
			}

			$this->view->formIndispo = $form;
			$this->view->fromIndex = $fromIndex;
		}
	}

	/**
	 * Action pour changer un chat de FA.
	 */
	public function changerfaAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id', null);
			$idChat = $this->getRequest()->getParam('idChat', null);
			$idNewFa = $this->getRequest()->getParam('idNewFa', null);
			$callbackChat = $this->getRequest()->getParam('callback', null);

			if ($idNewFa && $idChat) {
				$this->getService()->ajoutChat($idChat, $idNewFa);
				if ($callbackChat) {
					$this->_helper->redirector($callbackChat, 'chat');
				}else {
					$this->_forward('gridchats', null, null, array('id' => $ficheId));
				}
				return;
			} else {
				$this->view->urlFaListeJson = $this->view->url(array('action' => 'liste'));
				$this->view->urlSelectFa = $this->view->url(array('action' => 'changerfa', 'id' => $ficheId, 'idChat' => $idChat));
				$this->view->filterPath = $this->getFilterPath();
				$this->view->gridName = "gridFaSelection";
				$this->view->headerFaPath = "fa/headeradm.phtml";

				$chat = $this->getService()->getBeanChat($idChat);
				$this->view->titre = "Sélectionner une nouvelle FA pour ".$chat->getNom();

				if ($callbackChat) {
					$this->view->urlRetourListeChat = $this->view->url(array('controller' => 'chat', 'action' => $callbackChat));
					$this->view->displayDiv = true;
				} else {
					$this->view->urlRetourListeChat = $this->view->url(array('action' => 'gridchats', 'id' => $ficheId));
				}
			}
		}
	}

	/**
	 * Action pour supprimer un chat d'une FA.
	 */
	public function deletechatAction() {
		if ($this->checkIsLogged()) {
			$chatId = $this->getRequest()->getParam('idChat', null);
			if ($chatId) {
				$this->getService()->deleteChat($chatId);
			}
			exit;
		}
	}

	/**
	 * Action pour la suppression d'une indisponibilité.
	 */
	public function deleteindispoAction () {
		if ($this->checkIsLogged()) {
			$indispoId = $this->getRequest()->getParam('idIndispo', null);

			if ($indispoId) {
				$this->getService()->deleteIndispo($indispoId);
			}
			exit;
		}
	}

	/**
	 * Action pour lister les indisponibilités futures.
	 */
	public function indexindispoAction() {
		if ($this->checkIsLogged()) {
			$this->view->urlListeJson = $this->view->url(array('action' => 'listeindispo'));
			$this->view->urlEditItem = $this->view->url(array('action' => 'editindispo', 'fromIndex' => true));
			$this->view->urlDeleteItem = $this->view->url(array('action' => 'deleteindispo'));

			$this->view->headerPath = "fa/headerindispo.phtml";
			$this->view->filterPath = "fa/filterindispo.phtml";
			$this->view->class = $this->getStyleClass();
			$this->view->titre = "Indisponibilités à venir";
			$this->view->initFilter = "{statutIndispo : ".FP_Util_Constantes::INDISPO_A_VENIR_STATUT."}";

			$this->view->defaultSort = 3;
			$this->view->nbElements = $this->getService()->getNbIndispoFutures();
			$this->view->redefineButtons = "fa/gridindispoactions.phtml";;

			$this->render("indexindispo");
		}
	}

	/**
	 * Export excel pour les toutes les FA.
	 */
	public function exportAction() {
		if ($this->checkIsLogged()) {
			$workbook = FP_Service_ExportServices::getInstance()->buildExcelAllFa();
			exit;
		}
	}

	/**
	 * Retourne la liste des statuts des FAs au format json.
	 * @return string au format json
	 */
	public function listestatutsAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo $this->getService()->getJsonDataStatutFa();
			exit;
		}
	}

	/**
	 * Retourne la liste des statuts des indispo. au format json.
	 * @return string au format json
	 */
	public function listestatutsindispoAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo $this->getService()->getJsonDataStatutIndispo();
			exit;
		}
	}

}