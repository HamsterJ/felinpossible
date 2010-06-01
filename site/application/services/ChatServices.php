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
	 * @return FP_Model_Mapper_AdoptantMapper
	 */
	protected function getAdoptantMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->adoptantMapper;
	}

	/**
	 * Return le mapper des Vetos
	 * @return FP_Model_Mapper_VetoMapper
	 */
	protected function getVetoMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper;
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
	 * Retourne les données pour la fiche de soins pour le chat sélectionné.
	 * @param string $idChat
	 */
	public function getDataFicheSoins($idChat) {
		$beanFa = $this->getFaMapper()->getFaForChat($idChat);
		$beanChat = $this->getMapper()->find($idChat);
		$beanFicheSoins = new FP_Model_Bean_FicheSoins();

		$beanFicheSoins->setId($idChat);

		if ($beanFa) {
			$beanFicheSoins->setNom($beanFa->getPrenom()." ".$beanFa->getNom());
			$beanFicheSoins->setQualite("Famille d'accueil");
			$beanFicheSoins->setAdresse($beanFa->getAdresse());
			$beanFicheSoins->setVille($beanFa->getVille());
			$beanFicheSoins->setCodePostal($beanFa->getCodePostal());
			$beanFicheSoins->setTelephoneFixe($beanFa->getTelephoneFixe());
			$beanFicheSoins->setTelephonePortable($beanFa->getTelephonePortable());
		} else  {
			$beanAdoptant = $this->getAdoptantMapper()->getAdForChat($idChat);
			if ($beanAdoptant) {
				$beanFicheSoins->setNom($beanAdoptant->getPrenom()." ".$beanAdoptant->getNom());
				$beanFicheSoins->setQualite("Adoptant");
				$beanFicheSoins->setAdresse($beanAdoptant->getAdresse());
				$beanFicheSoins->setVille($beanAdoptant->getVille());
				$beanFicheSoins->setCodePostal($beanAdoptant->getCodePostal());
				$beanFicheSoins->setTelephoneFixe($beanAdoptant->getTelephoneFixe());
				$beanFicheSoins->setTelephonePortable($beanAdoptant->getTelephonePortable());
			}
		}

		if ($beanChat) {
			$beanFicheSoins->setNomChat($beanChat->getNom());
			$beanFicheSoins->setCouleur($beanChat->getLibelleCouleur());
			$beanFicheSoins->setIdentification($beanChat->getTatouage());
			$beanFicheSoins->setDateNaissance(FP_Util_DateUtil::getDateFormatted($beanChat->getDateNaissance()));
			$beanFicheSoins->setSexe($beanChat->getLibelleSexe());
			$beanFicheSoins->setDateNaissanceApprox($beanChat->getDateApproximative());
		}
		return $beanFicheSoins->toArray();
	}

	/**
	 * Génère la fiche de soins pour le chat sélectionné.
	 * @param FP_Form_chat_FicheSoinsForm $ficheSoinForm
	 */
	public function generateFicheSoins($ficheSoinForm) {
		$nomDocument = "ficheSoins";

		$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);

		$phpLiveDocx = new Zend_Service_LiveDocx_MailMerge(array ('username' => $config->livedocx->login, 'password' => $config->livedocx->password));

		if ($phpLiveDocx) {
			$phpLiveDocx->setLocalTemplate(FP_Util_Constantes::DOCUMENT_FICHE_SOINS_PATH);

			if ($ficheSoinForm) {
				$nomDocument .= "_".$ficheSoinForm->nomChat->getValue();
				$beanVeto = $this->getVetoMapper()->find($ficheSoinForm->idVeto->getValue());

				if ($beanVeto) {
					$phpLiveDocx->assign('raison_veto', $beanVeto->getRaison());
					$phpLiveDocx->assign('adresse_veto', $beanVeto->getAdresse());
					$phpLiveDocx->assign('code_postal_veto', $beanVeto->getCodePostal());
					$phpLiveDocx->assign('ville_veto', $beanVeto->getVille());
					$phpLiveDocx->assign('telephone_veto', $beanVeto->getTelephoneFixe());
				}

				$phpLiveDocx->assign('nom', $ficheSoinForm->nom->getValue());
				$phpLiveDocx->assign('qualite', $ficheSoinForm->qualite->getValue());
				$phpLiveDocx->assign('adresse', $ficheSoinForm->adresse->getValue());
				$phpLiveDocx->assign('ville', $ficheSoinForm->ville->getValue());
				$phpLiveDocx->assign('code_postal', $ficheSoinForm->codePostal->getValue());
				$phpLiveDocx->assign('tel_fixe', $ficheSoinForm->telephoneFixe->getValue());
				$phpLiveDocx->assign('tel_mobile', $ficheSoinForm->telephonePortable->getValue());
				$phpLiveDocx->assign('nom_chat', $ficheSoinForm->nomChat->getValue());
				$phpLiveDocx->assign('couleur_chat', $ficheSoinForm->couleur->getValue());
				$phpLiveDocx->assign('identification_chat', $ficheSoinForm->identification->getValue());
				$phpLiveDocx->assign('naissance_chat', $ficheSoinForm->dateNaissance->getValue());
				$phpLiveDocx->assign('sexe_chat', $ficheSoinForm->sexe->getValue());

				if ($ficheSoinForm->dateNaissanceApprox->checked) {
					$phpLiveDocx->assign('date_approx', 'date approximative');
				}

				if ($ficheSoinForm->soinPuce->checked) {
					$phpLiveDocx->assign('soin_puce', 'Identification (puce)');
				}
				if ($ficheSoinForm->soinTatouage->checked) {
					$phpLiveDocx->assign('soin_tatouage', 'Identification (tatouage)');
				}
				if ($ficheSoinForm->soinVaccins->checked) {
					$phpLiveDocx->assign('soin_vaccins', 'Vaccins TCL');
				}
				if ($ficheSoinForm->soinTests->checked) {
					$phpLiveDocx->assign('soin_tests', 'Tests FIV/FELV');
				}
				if ($ficheSoinForm->soinSterilisation->getValue()) {
					$phpLiveDocx->assign('soin_sterilisation', $ficheSoinForm->soinSterilisation->getLabel());
				}
				if ($ficheSoinForm->soinAutre->getValue()) {
					$phpLiveDocx->assign('soin_autre', $ficheSoinForm->soinAutre->getValue());
				}
				
				$phpLiveDocx->assign('date_fiche', date('d/m/Y à H\hi', time()));
			}

			header("Content-type: pdf");
			header("Content-Disposition: attachment; filename=\"".$nomDocument.".pdf\"");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");

			$phpLiveDocx->createDocument();
			$document = $phpLiveDocx->retrieveDocument('pdf');
			echo $document;
		}
			
		unset($phpLiveDocx);
	}
}