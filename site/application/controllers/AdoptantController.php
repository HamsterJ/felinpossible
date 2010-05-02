<?php
/**
 * Controller pour les actions liés aux adoptants.
 * @author Benjamin
 *
 */
class AdoptantController extends FP_Controller_SubFormController
{
	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFormClassName()
	 */
	protected function getFormClassName() {
		return 'FP_Form_adoptant_Form';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getNamespace()
	 */
	protected function getNamespace() {
		return 'AdoptantController';
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getHeaderPath()
	 */
	protected function getHeaderPath() {
		return "adoptant/headeradm.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getFilterPath()
	 */
	protected function getFilterPath() {
		return "adoptant/filterad.phtml";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getViewTitle()
	 */
	protected function getViewTitle() {
		return "Fiches des adoptants";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getEmailSubject()
	 */
	protected function getEmailSubject() {
		return FP_Util_Constantes::MAIL_ADOPTER_SUJET;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getStyleClass()
	 */
	protected function getStyleClass() {
		return "ficheAdoptant";
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#getService()
	 * @return FP_Service_AdoptantServices
	 */
	protected function getService() {
		return FP_Service_AdoptantServices::getInstance();
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/controllers/FP_Controller_SubFormController#exportAction()
	 */
	public function exportAction() {
		if ($this->checkIsLogged()) {
			$workbook = FP_Service_ExportServices::getInstance()->buildExcelAllAdoptants();
			exit;
		}
	}

	/**
	 * Index de la partie admin pour les fiches des adoptants.
	 */
	public function indexadmAction() {
		if ($this->checkIsLogged()) {

			$this->initGridParam();
			$this->view->redefineButtons = "adoptant/gridactions.phtml";
			$this->view->urlChatAdItem = $this->view->url(array('action' => 'chat'));

			$this->render("indexgrid");
		}
	}

	/**
	 * Action pour afficher le tableau d'ajout/suppression d'un chat à l'adoptant.
	 */
	public function chatAction() {
		$ficheId = $this->getRequest()->getParam('id', null);

		$this->view->urlChatListeJson = $this->view->url(array('action' => 'listechat', 'id' => $ficheId, 'idChat' => null));
		$this->view->urlChatAddItem = $this->view->url(array('action' => 'ajoutchat', 'id' => $ficheId, 'idChat' => null));
		$this->view->urlChatDeleteItem = $this->view->url(array('action' => 'deletechat', 'id' => $ficheId, 'idChat' => null));

		$this->view->headerChatPath = "chat/headeradm.phtml";

		if ($ficheId) {
			$adoptant = $this->getService()->getElement($ficheId);
			if ($adoptant) {
				$this->view->titreChat = "Liste des chats adoptés par ".$adoptant->getPrenom()." ".$adoptant->getNom();
			}
		}
	}

	/**
	 * Liste les chats en accueil dans la FA.
	 */
	public function listechatAction() {
		if ($this->checkIsLogged()) {
			$request = $this->getRequest();
			echo $this->getService()->getJsonDataChatForAd($request->getParams());
			exit;
		}
	}

	/**
	 * Action pour supprimer un chat d'un adoptant.
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
	 * Action pour accéder à l'écran d'ajout d'un chat.
	 */
	public function ajoutchatAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id', null);
			$idChatSelected = $this->getRequest()->getParam('idChat', null);

			if ($idChatSelected) {
				$this->getService()->ajoutChat($idChatSelected, $ficheId);
				$this->_forward('chat', null, null, array('id' => $ficheId));
				return;
			} else {
				$this->view->urlChatListeJson = $this->view->url(array('controller' => 'chat', 'action' => 'liste'));
				$this->view->urlSelectChat = $this->view->url(array('action' => 'ajoutchat', 'id' => $ficheId));
				$this->view->urlRetourListeChat = $this->view->url(array('action' => 'chat', 'id' => $ficheId));
				$this->view->headerChatPath = "adoptant/headerchat.phtml";
				$this->view->filterPath = "chat/filterchat.phtml";
				$this->view->gridName = "gridChatSelection";

				$this->view->titre = "Sélectionner un chat";
			}
		}
	}

	/**
	 * Action pour choisir un adoptant.
	 */
	public function choisiradAction() {
		if ($this->checkIsLogged()) {
			$ficheId = $this->getRequest()->getParam('id', null);
			$idChat = $this->getRequest()->getParam('idChat', null);
			$idNewAd = $this->getRequest()->getParam('idNewAd', null);
			$callbackChat = $this->getRequest()->getParam('callback', null);

			if ($idNewAd && $idChat) {
				$this->getService()->ajoutChat($idChat, $idNewAd);
				$this->_helper->redirector($callbackChat, 'chat');
				return;
			} else {
				$this->view->urlAdListeJson = $this->view->url(array('action' => 'liste'));
				$this->view->urlSelectAd = $this->view->url(array('action' => 'choisirad', 'id' => $ficheId, 'idChat' => $idChat));
				$this->view->filterPath = $this->getFilterPath();
				$this->view->gridName = "gridAdSelection";
				$this->view->headerAdPath = "adoptant/headeradm.phtml";

				$chat = $this->getService()->getBeanChat($idChat);
				$this->view->titre = "Sélectionner un adoptant pour ".$chat->getNom();
				$this->view->urlRetourListeChat = $this->view->url(array('controller' => 'chat', 'action' => $callbackChat));

			}
		}
	}
}

