<?php

/**
 * Data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_AdoptantChatMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'AdoptantChat';
	
	protected $mappingDbToModel = array(
	                 'idAd' => 'idAd',
                     'idChat' => 'idChat');
	
	/**
	 * Supprimer le chat de l'adoptant.
	 * @param string $idChat l'id du chat
	 */
	public function supprimerChat($idChat) {
		$this->delete("idChat = $idChat");
	}
	
	/**
	 * Retourne le nombre de chats pour l'adoptant.
	 * @param string $idAd
	 * @return int
	 */
	public function getNbChats($idAd) {
		return $this->count("idAd = $idAd");
	}
}