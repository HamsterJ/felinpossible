<?php
/**
 * Services pour les adoptants.
 * @author Benjamin
 *
 */
class FP_Service_AdoptantServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_AdoptantServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_AdoptantServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_AdoptantServices();
		}
		return self::$instance;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getMapper()
	 * @return FP_Model_Mapper_AdoptantMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->adoptantMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_Adoptant
	 */
	protected function getEmptyBean() {
		return new FP_Model_Bean_Adoptant();
	}

	/**
	 * Retourne le mapper pour les chats des adoptants.
	 * @return FP_Model_Mapper_AdoptantChatMapper
	 */
	protected function getAdoptantChatMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->adoptantChatMapper;
	}

	/**
	 * Retourne le mapper pour les chats.
	 * @return FP_Model_Mapper_ChatMapper
	 */
	protected function getChatMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->chatMapper;
	}

	/**
	 * Ajoute le chat à l'adoptant.
	 * @param string $idChat
	 * @param string $idAd
	 */
	public function ajoutChat($idChat, $idAd) {
		$mapper = $this->getAdoptantChatMapper();
		$oldBean = $mapper->find($idChat);
		$bean = new FP_Model_Bean_AdoptantChat();
		$bean->setIdChat($idChat);
		$bean->setIdAd($idAd);

		$mapper->supprimerChat($idChat);
		$mapper->insert($bean);
	}

	/**
	 * Supprime le chat de l'adoptant.
	 * @param string $idChat
	 */
	public function deleteChat($idChat) {
		$idFa = null;
		$mapper = $this->getAdoptantChatMapper();

		$mapper->supprimerChat($idChat);
	}

	/**
	 * Retourne les chats au format json pour l'adoptant.
	 * @param string $param
	 * @return string
	 */
	public function getJsonDataChatForAd($param) {
		$start = $param[FP_Util_TriUtil::START_KEY];
		$count = $param[FP_Util_TriUtil::COUNT_KEY];
		$paramSort = FP_Util_TriUtil::computeSortParam($param[FP_Util_TriUtil::SORT_KEY]);

		$sort = $paramSort[FP_Util_TriUtil::SORT_KEY];
		$order = $paramSort[FP_Util_TriUtil::ORDER_KEY];
		$ficheId = $param['id'];

		$mapper = $this->getAdoptantChatMapper();
		$chatMapper = $this->getChatMapper();

		$data = $chatMapper->fetchAllToArrayForAd($sort, $order, $start, $count, $ficheId);

		$nbElts = $mapper->getNbChats($ficheId);

		$dojoData= new Zend_Dojo_Data('id', $data, 'id');
		$dojoData->setMetadata('numRows', $nbElts);

		return $dojoData->toJson();
	}


	/**
	 * Retourne le bean à partir de l'id
	 * @param $id id du chat
	 * @return FP_Model_Bean_Chat
	 */
	public function getBeanChat($id) {
		return $this->getChatMapper()->find($id);
	}
}