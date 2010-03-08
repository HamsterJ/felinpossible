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
class FP_Model_Mapper_FaChatMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'FaChat';
	
	protected $mappingDbToModel = array(
	                 'idFa' => 'idFa',
                     'idChat' => 'idChat');
	
	/**
	 * Retourne le nombre de chats pour la FA.
	 * @param string $idFa
	 * @return int
	 */
	public function getNbChatsPourFa($idFa) {
		return $this->count("idFa = $idFa");
	}
	
	/**
	 * Supprimer le chat de la FA.
	 * @param string $idChat l'id du chat
	 */
	public function supprimerChatDeFa($idChat) {
		$this->delete("idChat = $idChat");
	}
}