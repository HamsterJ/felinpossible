<?php
/**
 * Services pour les chats.
 * @author Benjamin
 *
 */
class FP_Service_ChatServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_ChatServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_ChatServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_ChatServices();
		}
		return self::$instance;
	}

	/**
	 * Return le mapper des chats
	 * @return FP_Model_Mapper_ChatMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->chatMapper;
	}

	/**
	 * Return le mapper des FA
	 * @return FP_Model_Mapper_FaMapper
	 */
	protected function getFaMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faMapper;
	}

	/**
	 * Return le mapper des Adoptants
	 * @return FP_Model_Mapper_FaMapper
	 */
	protected function getAdoptantMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->adoptantMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_Chat
	 */
	protected function getEmptyBean() {
		return new FP_Model_Bean_Chat();
	}

	/**
	 * Retourne le mapper pour les posts.
	 * @return FP_Model_Mapper_PostMapper
	 */
	private function getPostMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->postMapper;
	}
	/**
	 * Retourne la page de news demandée suivant la pagination.
	 * @param array $param
	 * @return array le tableau contenant le paginator et les news à afficher
	 */
	public function getPage($param) {
		if (array_key_exists(FP_Util_PaginationConstantes::PAGE_KEY, $param)) {
			$currentPageNumber = $param[FP_Util_PaginationConstantes::PAGE_KEY];
		} else {
			$currentPageNumber = FP_Util_PaginationConstantes::NEWS_CURRENT_DEFAULT_PAGE;
		}

		if (array_key_exists(FP_Util_PaginationConstantes::NB_ITEMS_KEY, $param)) {
			$itemCountPerPage = $param[FP_Util_PaginationConstantes::NB_ITEMS_KEY];
		} else {
			$itemCountPerPage = FP_Util_PaginationConstantes::NEWS_ITEM_COUNT;
		}

		$pageRange = FP_Util_PaginationConstantes::NEWS_PAGE_RANGE;

		return $this->getMapper()->getNewsOrderedWithPagination($currentPageNumber, $itemCountPerPage, $pageRange);
	}

	/**
	 * Retourne le tableau contenant les chats à l'adoption.
	 * @return array
	 */
	public function getChatsAdoption() {
		return $this->getMapper()->getChatsAdoption();
	}

	/**
	 * Retourne le tableau des chats adoptés avec pagination.
	 * @param $param les paramètres de la pagination.
	 * @return array les chats adoptés.
	 */
	public function getChatsAdoptes($param) {
		if (array_key_exists(FP_Util_PaginationConstantes::PAGE_KEY, $param)) {
			$currentPageNumber = $param[FP_Util_PaginationConstantes::PAGE_KEY];
		} else {
			$currentPageNumber = FP_Util_PaginationConstantes::CHATS_CURRENT_DEFAULT_PAGE;
		}

		if (array_key_exists(FP_Util_PaginationConstantes::NB_ITEMS_KEY, $param)) {
			$itemCountPerPage = $param[FP_Util_PaginationConstantes::NB_ITEMS_KEY];
		} else {
			$itemCountPerPage = FP_Util_PaginationConstantes::CHATS_ITEM_COUNT;
		}

		$pageRange = FP_Util_PaginationConstantes::CHATS_PAGE_RANGE;

		return $this->getMapper()->getChatsAdoptes($currentPageNumber, $itemCountPerPage, $pageRange);
	}

	/**
	 * Retourne la tableau des chats à parrainer.
	 * @return array
	 */
	public function getChatsAParrainer() {
		return $this->getMapper()->getChatsAParrainer();
	}

	/**
	 * Retourne le tableau des chats disparus.
	 * @return array
	 */
	public function getChatsDisparus() {
		return $this->getMapper()->getChatsDisparus();
	}

	/**
	 * Retourne le tableau des chats à l'adoption, non réservé.
	 * @return array
	 */
	public function getChatsAdoptionNonReserves() {
		return $this->getMapper()->getChatsAdoptionNonReserves();
	}

	/**
	 * Retourne le nombre de fiches suivant la demande.
	 * @param int $demandeType l'id de la demande
	 * @return int le nombre de fiches
	 */
	public function getNbFiches($demandeId) {
		return $this->getMapper()->getNbFiches($demandeId);
	}

	/**
	 * Retourne les informations sous forme de tableau extraites à partir du postId du chat.
	 * @param string $idChat
	 * @return array
	 */
	public function getInfosFromPostId($idChat) {
		$chat = $this->getMapper()->find($idChat);
		if ($chat) {
			$chat = $this->updateChatFromPostId($chat);
			return $chat->toArray();
		}
		return array();
	}

	/**
	 * Met à jour la fiche du chat à partir des information de son post.
	 * @param FP_Model_Bean_Chat $ficheChat
	 * @return FP_Model_Bean_Chat
	 */
	private function updateChatFromPostId($ficheChat){
		$postId = $ficheChat->getPostId();
		if ($postId){
			$post = $this->getPostMapper()->find($postId);
			if ($post) {
				return FP_Util_ChatUtil::updateChatFromPostId($ficheChat, $post);
			}
		}
		return $ficheChat;
	}

	/**
	 * Retourne les donnes d'initialisation du formalaire de rappel des vaccins.
	 * @param string $idChat
	 */
	public function getDataForMailVaccins($idChat) {
		$data = array();
		$beanFa = $this->getFaMapper()->getFaForChat($idChat);
		$beanChat = $this->getMapper()->find($idChat);

		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
		$data['copy'] = $config->email->address;
		$data['id'] = $idChat;

		if ($beanFa) {
			$data['destinataire'] = $beanFa->getEmail();
		}
		if ($beanChat) {
			$mailService = FP_Service_MailServices::getInstance();
			$data['sujet'] = FP_Util_Constantes::MAIL_RAPPEL_VACCINS_SUJET.$beanChat->getNom();
			$data['contenu'] = $mailService->getMailBody(FP_Util_Constantes::MAIL_TEMPLATE_VACCINS_FA, $beanChat);
		}

		return $data;
	}

	/**
	 * Retourne les donnes d'initialisation du formalaire de rappel des stérilisation
	 * @param string $idChat
	 */
	public function getDataForMailSterilisation($idChat) {
		$data = array();
		$beanFa = $this->getFaMapper()->getFaForChat($idChat);
		$beanChat = $this->getMapper()->find($idChat);

		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
		$data['copy'] = $config->email->address;
		$data['id'] = $idChat;

		if ($beanFa) {
			$data['destinataire'] = $beanFa->getEmail();
		} else {
			$beanAd = $this->getAdoptantMapper()->getAdForChat($idChat);
			if ($beanAd) {
				$data['destinataire'] = $beanAd->getEmail();
			}
		}

		if ($beanChat) {
			$mailService = FP_Service_MailServices::getInstance();
			$data['sujet'] = FP_Util_Constantes::MAIL_RAPPEL_STERILISATION_SUJET.$beanChat->getNom();
			$data['contenu'] = $mailService->getMailBody(FP_Util_Constantes::MAIL_TEMPLATE_STERILISATION_FA, $beanChat);
		}

		return $data;
	}

	/**
	 * Incrémente la date du rappel de vaccins pour le chat sélectionné.
	 * @param string $idChat
	 */
	public function incrDateRappelVaccins($idChat) {
		$this->getMapper()->incrDateRappelVaccins($idChat);
	}

	/**
	 * Met à jour la date d'envoi du mail de rappel de stérilisation.
	 * @param string $idChat
	 */
	public function updateDateRappelSter($idChat) {
		$this->getMapper()->updateDateRappelSter($idChat);
	}

	/**
	 * Met à jour la date d'envoi du mail de rappel de vaccins.
	 * @param string $idChat
	 */
	public function updateDateRappelVaccins($idChat) {
		$this->getMapper()->updateDateRappelVaccins($idChat);
	}

	/**
	 * Génère la fiche de soins pour le chat sélectionné.
	 * @param string $idChat
	 */
	public function generateFicheSoins($idChat) {
		$beanFa = $this->getFaMapper()->getFaForChat($idChat);
		$beanChat = $this->getMapper()->find($idChat);

		$nomDocument = "ficheSoins";

		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);

		$phpLiveDocx = new Zend_Service_LiveDocx_MailMerge(array ('username' => $config->livedocx->login, 'password' => $config->livedocx->password));

		if ($phpLiveDocx) {
			$phpLiveDocx->setLocalTemplate(FP_Util_Constantes::DOCUMENT_FICHE_SOINS_PATH);

			if ($beanFa) {
				$phpLiveDocx->assign('nom', $beanFa->getPrenom()." ".$beanFa->getNom());
				$phpLiveDocx->assign('qualite', "Famille d'accueil");
				$phpLiveDocx->assign('adresse', $beanFa->getAdresse());
				$phpLiveDocx->assign('ville', $beanFa->getVille());
				$phpLiveDocx->assign('code_postal', $beanFa->getCodePostal());
				$phpLiveDocx->assign('tel_fixe', $beanFa->getTelephoneFixe());
				$phpLiveDocx->assign('tel_mobile', $beanFa->getTelephonePortable());
			}
			if ($beanChat) {
				$phpLiveDocx->assign('nom_chat', $beanChat->getNom());
				$phpLiveDocx->assign('couleur_chat', $beanChat->getLibelleCouleur());
				$phpLiveDocx->assign('identification_chat', $beanChat->getTatouage());
				$phpLiveDocx->assign('naissance_chat', FP_Util_DateUtil::getDateFormatted($beanChat->getDateNaissance()));
				$phpLiveDocx->assign('sexe_chat', $beanChat->getLibelleSexe());

				$nomDocument .= "_".$beanChat->getNom();
			}

			$phpLiveDocx->createDocument();
			$document = $phpLiveDocx->retrieveDocument('pdf');

			header("Content-type: pdf");
			header("Content-Disposition: attachment; filename=\"".$nomDocument.".pdf\"");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");

			echo $document;
		}
			
		unset($phpLiveDocx);
	}
}