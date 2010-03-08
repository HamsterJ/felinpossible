<?php
/**
 * Services pour les FA.
 * @author Benjamin
 *
 */
class FP_Service_FaServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_FaServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_FaServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_FaServices();
		}
		return self::$instance;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getMapper()
	 * @return FP_Model_Mapper_FaMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_Fa
	 */
	protected function getEmptyBean() {
		return new FP_Model_Bean_Fa();
	}

	/**
	 * Retourne le mapper pour les indisponibilités des FA.
	 * @return FP_Model_Mapper_FaIndispoMapper
	 */
	protected function getIndispoFaMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faIndispoMapper;
	}

	/**
	 * Retourne le mapper pour les chats des FA.
	 * @return FP_Model_Mapper_FaChatMapper
	 */
	protected function getFaChatMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faChatMapper;
	}

	/**
	 * Retourne le mapper pour les chats.
	 * @return FP_Model_Mapper_ChatMapper
	 */
	protected function getChatMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->chatMapper;
	}

	/**
	 * Retourne le FaStatut mapper.
	 * @return FP_Model_Mapper_FaStatutMapper
	 */
	protected function getFaStatutMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faStatutMapper;
	}

	/**
	 * Retourne le FaIndispoStatut mapper.
	 * @return FP_Model_Mapper_FaIndispoStatutMapper
	 */
	protected function getFaIndispoStatutMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->faIndispoStatutMapper;
	}

	/**
	 * Mise à jour du statut de la FA si indisponibilité
	 * @param string $idFa
	 */
	public function updateStatutIndispo($idFa) {
		if ($idFa) {
			$mapperFa = $this->getMapper();
			$beanFa = $mapperFa->find($idFa);
			if ($beanFa) {
				$currentStatut = $beanFa->getStatutId();
				$mapperIndispo = $this->getIndispoFaMapper();
				$result = $mapperIndispo->getNbIndisposEnCours($idFa);

				if ($result > 0 && $currentStatut != FP_Util_Constantes::FA_INACTIVE_STATUT
				&& $currentStatut != FP_Util_Constantes::FA_STAND_BY_STATUT) {
					// FA XX -> Indisponible
					$mapperFa->updateStatut($idFa, FP_Util_Constantes::FA_INDISPONIBLE_STATUT);
				} else {
					$this->updateStatut($idFa);
				}
			}
		} else {
			echo "IdFA non renseigné";
		}
	}

	/**
	 * Mie à jour du statut de la FA en fonction du nombre de chats placés chez elle.
	 * @param string $idFa
	 */
	public function updateStatut($idFa) {
		if ($idFa) {
			$mapperFa = $this->getMapper();
			$mapperfaChat = $this->getFaChatMapper();

			$beanFa = $mapperFa->find($idFa);
			if ($beanFa) {
				$currentStatut = $beanFa->getStatutId();
				$nbChats = $mapperfaChat->getNbChatsPourFa($idFa);
				if ($nbChats == 0 && $currentStatut == FP_Util_Constantes::FA_ACTIVE_STATUT) {
					$mapperFa->updateStatut($idFa, FP_Util_Constantes::FA_DISPONIBLE_STATUT);
				} else {
					$mapperFa->updateStatut($idFa, FP_Util_Constantes::FA_ACTIVE_STATUT);
				}
			}
		} else {
			echo "IdFA non renseigné";
		}
	}

	/**
	 * Ajoute le chat à la FA.
	 * @param string $idChat
	 * @param string $idFa
	 */
	public function ajoutChat($idChat, $idFa) {
		$mapper = $this->getFaChatMapper();
		$oldBean = $mapper->find($idChat);
		$bean = new FP_Model_Bean_FaChat();
		$bean->setIdChat($idChat);
		$bean->setIdFa($idFa);

		$mapper->supprimerChatDeFa($idChat);
		$mapper->insert($bean);
		$this->updateStatut($idFa);
		if ($oldBean && $oldBean->getIdFa() != $idFa) {
			$this->updateStatut($oldBean->getIdFa());
		}
	}

	/**
	 * Supprime le chat de la FA.
	 * @param string $idChat
	 */
	public function deleteChat($idChat) {
		$idFa = null;
		$mapper = $this->getFaChatMapper();
		$beanFaChat = $mapper->find($idChat);
		if ($beanFaChat) {
			$idFa = $beanFaChat->getIdFa();
		}

		$mapper->supprimerChatDeFa($idChat);
		$this->updateStatut($idFa);
	}

	/**
	 * Supprime une indisponibilité et met à jour le statut de la FA associée.
	 * @param sttring $idIndispo
	 */
	public function deleteIndispo($idIndispo) {
		$bean = $this->getBeanIndispo($idIndispo);
		if ($bean) {
			$mapper = $this->getIndispoFaMapper();
			$mapper->supprimeIndispo($idIndispo);
			$this->updateStatutIndispo($bean->getIdFa());
		}
	}

	/**
	 * Ajoute une nouvelle indisponibilité à la FA.
	 * @param array $data les données de l'indisponibilité
	 */
	public function ajoutIndispo($data) {
		$bean = new FP_Model_Bean_FaIndispo();
		$bean->setOptions($data);

		$mapper = $this->getIndispoFaMapper();
		$bean->setIdStatut($this->computeIndispoStatut($bean));
		$mapper->save($bean);

		$this->updateStatutIndispo($bean->getIdFa());
	}

	/**
	 * Cacule le statut de l'indisponibilité.
	 * @param FP_Model_Bean_FaIndispo $bean
	 * @return int le statut de l'indisponibilité.
	 */
	private function computeIndispoStatut($bean) {
		$dateCourante = new Zend_Date();
		$startDateIndispo = new Zend_Date($bean->getDateDebut());
		$endDateIndispo = new Zend_Date($bean->getDateFin());

		if ($startDateIndispo->isLater($dateCourante)) {
			return FP_Util_Constantes::INDISPO_A_VENIR_STATUT;
		} else if ($endDateIndispo->isLater($dateCourante)) {
			return FP_Util_Constantes::INDISPO_EN_COURS_STATUT;
		}
		return FP_Util_Constantes::INDISPO_TERMINEE_STATUT;
	}

	/**
	 * Retourne le bean à partir de l'id
	 * @param $id id fa
	 * @return FP_Model_Bean_Fa
	 */
	public function getBeanFa($id) {
		return $this->getMapper()->find($id);
	}

	/**
	 * Retourne le bean à partir de l'id
	 * @param $id id de l'indispo
	 * @return FP_Model_Bean_FaIndispo
	 */
	public function getBeanIndispo($id) {
		return $this->getIndispoFaMapper()->find($id);
	}

	/**
	 * Retourne le bean à partir de l'id
	 * @param $id id du chat
	 * @return FP_Model_Bean_Chat
	 */
	public function getBeanChat($id) {
		return $this->getChatMapper()->find($id);
	}

	/**
	 * Sauve en base les infos du formulaire de gestion
	 * @param FP_Model_Bean_Fa $fiche
	 * @param array $data
	 */
	public function saveGestionInfos($fiche, $data) {
		$mapper = $this->getMapper();
		$beanUpdated = $mapper->buildBeanFromForm($data, $fiche);
		$mapper->save($beanUpdated);
	}

	/**
	 * Retourne les chats au format json pour la FA.
	 * @param string $param
	 * @return string
	 */
	public function getJsonDataChatForFa($param) {
		$start = $param[FP_Util_TriUtil::START_KEY];
		$count = $param[FP_Util_TriUtil::COUNT_KEY];
		$paramSort = FP_Util_TriUtil::computeSortParam($param[FP_Util_TriUtil::SORT_KEY]);

		$sort = $paramSort[FP_Util_TriUtil::SORT_KEY];
		$order = $paramSort[FP_Util_TriUtil::ORDER_KEY];
		$ficheId = $param['id'];

		$mapper = $this->getFaChatMapper();
		$chatMapper = $this->getChatMapper();

		$data = $chatMapper->fetchAllToArrayForFa($sort, $order, $start, $count, $ficheId);

		$nbElts = $mapper->getNbChatsPourFa($ficheId);

		$dojoData= new Zend_Dojo_Data('id', $data, 'id');
		$dojoData->setMetadata('numRows', $nbElts);

		return $dojoData->toJson();
	}

	/**
	 * Retourne le nombre d'indisponibilités futures.
	 * @return int
	 */
	public function getNbIndispoFutures() {
		return $this->getIndispoFaMapper()->getNbIndispoFutures();
	}

	/**
	 * Retourne le nombre de chats pour la FA.
	 * @param string $idFa
	 * @return int
	 */
	public function getNbChatsForFa($idFa) {
		return $this->getFaChatMapper()->getNbChatsPourFa($idFa);
	}
	
	/**
	 * Retourne les indisponibilités au format json pour la FA selon une condition
	 * @param string $param
	 * @param string $where
	 * @return string
	 */
	public function getJsonDataIndispo($param, $where = null, $nbElts = null) {
		return $this->getJsonData($param, $this->getIndispoFaMapper());
	}

	/**
	 * Retourne le nombre de FA avec le statut demandé.
	 * @param int $idStatut
	 * @return unknown_type
	 */
	public function getNbFaWithStatus($idStatut) {
		return $this->getMapper()->getNbFaWithStatus($idStatut);
	}


	/**
	 * Retourne les statuts des FA au format json.
	 * @return string
	 */
	public function getJsonDataStatutFa() {
		$mapper = $this->getFaStatutMapper();

		$data = $mapper->fetchAllToArray('nom');
		$dojoData= new Zend_Dojo_Data('id', $data, 'name');
		$dojoData->setMetadata('numRows', count($data));

		return $dojoData->toJson();
	}

	/**
	 * Retourne les statuts des indisponibilités au format json.
	 * @return string
	 */
	public function getJsonDataStatutIndispo() {
		$mapper = $this->getFaIndispoStatutMapper();

		$data = $mapper->fetchAllToArray('nom');
		$dojoData= new Zend_Dojo_Data('id', $data, 'name');
		$dojoData->setMetadata('numRows', count($data));

		return $dojoData->toJson();
	}

}