<?php

/**
 * FaIndispo data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_FaIndispoMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'FaIndispo';

	protected $mappingDbToModel = array(
	                 'id' => 'id',
	                 'idFa' => 'idFa',
                     'dateDeb' => 'dateDebut',
	                 'dateFin' => 'dateFin',
	               'comment' => 'commentaires',
	                 'idStatut' => 'idStatut'
                         );
	
	protected $filterKeyToDbKey = array('statutIndispo' => 'indispo.idStatut',
	'id' => 'indispo.idFa');
	
	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArray($sort, $order, $start, $count, $where)
	 */
	public function fetchAllToArray($sort = NULL, $order = 'asc', $start = NULL, $count = NULL, $where = NULL)
	{
		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('indispo' => 'fp_fa_indispo'))
		->join(array('fa' => 'fp_fa_fiche'), 'fa.id = indispo.idFa', array('faLib' => 'CONCAT(fa.prenom, \' \', fa.nom, \' (\', fa.login, \')\')'))
		->join(array('statut' => 'fp_fa_indispo_statut'), 'indispo.idStatut = statut.id', array('libStatut' => 'statut.nom'))
		->limit($count, $start)
		->order($sort." ".$order);
		
		if ($where) {
			$select->where($where);
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#count($where)
	 */
	public function count($where = null)
	{
		$db = $this->getDbTable()->getAdapter();
		$select = $db->select();
		$query = $select->from(array('indispo' => 'fp_fa_indispo'), 'count(1)');

		if ($where) {
			$query = $select->where($where);
		}

		return $db->fetchOne($query);
	}
	
	/**
	 * Retourne la clause pour récupérer les indispo. futures.
	 * @return string
	 */
	public function getWhereIndispoFutures() {
		return "indispo.idStatut = ".FP_Util_Constantes::INDISPO_A_VENIR_STATUT;
	}
	
	/**
	 * Retourne la clause pour la jointure avec la FA.
	 * @param string $idFa
	 */
	public function getWhereIdFa($idFa) {
		return "indispo.idFa = $idFa";
	}
	
	/**
	 * Retourne le nombre d'indisponibilité totales pour une fa donnée
	 * @param string $idFa
	 * @return int
	 */
	public function getNbIndispos($idFa) {
		return $this->count("idFa = $idFa");
	}
	
	/**
	 * Retourne le nombre d'indisponibilité en cours pour une fa donnée
	 * @param string $idFa
	 * @return int
	 */
	public function getNbIndisposEnCours($idFa) {
		return $this->count("idFa = $idFa and idStatut = ".FP_Util_Constantes::INDISPO_EN_COURS_STATUT);
	}
	
	/**
	 * Retourne le nombre d'indisponilités à venir.
	 * @return int
	 */
	public function getNbIndispoFutures() {
		return $this->count("idStatut = ".FP_Util_Constantes::INDISPO_A_VENIR_STATUT);
	}
	
	/**
	 * Supprime l'indisponibilité.
	 * @param string $idIndispo l'id de l'indisponibilité
	 */
	public function supprimeIndispo($idIndispo) {
		$this->delete("id = $idIndispo");
	}
}