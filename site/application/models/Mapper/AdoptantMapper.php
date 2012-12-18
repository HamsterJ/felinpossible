<?php

/**
 * Adoptant data mapper.
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_AdoptantMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Adoptant';

	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'nom' => 'nom',
	                 'prenom' => 'prenom',
	                 'adresse' => 'adresse',
	                 'cp' => 'codePostal',
	                 'ville' => 'ville',
	                 'fixe' => 'telephoneFixe',
	                 'portable' => 'telephonePortable',
	                 'email' => 'email',
	                 'idLogement' => 'idLogement',
	                 'idDependance' => 'idDependance',
	                 'securise' => 'depSecurise',
	                 'chatiere' => 'hasChatiere',
	                 'etage' => 'etage',
	                 'personnes' => 'nbPersonnes',
	                 'age' => 'age',
	                 'enfants' => 'nbEnfants',
	                 'enfantsAge' => 'enfantsAge',
	                 'heures' => 'heuresDansFoyer',
	                 'allergies' => 'personnesAllergiques',
	                 'aimePasChat' => 'personnesDesirantPasChat',
	                 'animaux' => 'animauxAutres',
	                 'habitudeChat' => 'habitudeChat',
	                 'chats' => 'chats',
	                 'motivations' => 'motivations',
	                 'criteres' => 'criteres',
	                 'repere' => 'repererChat',
	                 'idCadeaux' => 'idDestineA',
	                 'revenus' => 'revenusReguliers',
	                 'frais' => 'assumerFraisVeto',
	                 'idVacances' => 'idSolutionGardeVacances',
	                 'idDemenagement' => 'idSolutionDemenagement',
	                 'idFonderFamille' => 'idFonderFamille',
	                 'garderVie' => 'garderTouteSaVie',
	                 'securFenetre' => 'isSecurFenetres',
	                 'survFenetre' => 'isSurvFenetres',
	                 'veto' => 'contacterVeto',
	                 'parasite' => 'traiterParasites',
	                 'rappelVaccin' => 'rappelVaccins',
	                 'alimentation' => 'bonneAlimentation',
	                 'sterelise' => 'steriliser',
	                 'jouer' => 'passerTempsAvecChat',
	                 'litiere' => 'garderLitierePropre',
	                 'fichier' => 'signalerChangementAdr',
	                 'nouvelle' => 'donnerNouvelles',
	                 'visite' => 'accepterVisite',
	                 'restitue' => 'restituerChat',
	                 'autres' => 'remarques',
	                 'idConnu' =>'idConnaissanceAsso',
	                 'profession' => 'profession',
    'heureJoignable' => 'heureJoignable',
	'login' => 'login',
	'dateSubmit' => 'dateSubmit',
	'superficie' => 'superficie'
	);

	protected $filterKeyToDbKey = array('nom' => 'fp_ad_fiche.nom',
	'prenom' => 'fp_ad_fiche.prenom',
	'login' => 'fp_ad_fiche.login');

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArray($sort, $order, $start, $count, $where)
	 */
	public function fetchAllToArray($sort, $order, $start, $count, $where = null)
	{
		$subSelect = $this->getDbTable()->getAdapter()->select()
		->from('fp_ad_fiche')
		->joinLeft(array('adcat' => 'fp_ad_cat'), 'fp_ad_fiche.id = adcat.idAd or adcat.idAd = null', array())
		->joinLeft(array('cat' => 'fp_cat_fiche'), 'cat.id = adcat.idChat or adcat.idChat = null', array('chatsAdoptes' => 'GROUP_CONCAT(cat.nom SEPARATOR \', \')'))
		->group('fp_ad_fiche.id');

		if ($sort && $order) {
			$subSelect->order($sort." ".$order);
		}
		if ($where) {
			$subSelect->where($where);
		}

		if ($count != null && $start != null) {
			$select = $this->getDbTable()->getAdapter()->select()
			->from(array('subselect' => $subSelect))
			->limit($count, $start);
		} else {
			$select = $subSelect;
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArrayForExport($where)
	 */
	public function fetchAllToArrayForExport($where = null)
	{
		$columnsToExport = array('Identifiant' => 'ad.id',
		'Nom' => 'ad.nom',
		'Prénom' => 'ad.prenom',
    	'Login' => 'login',
    	'Repéré' => 'repere',
		'Code Postal' => 'ad.cp',
		'Ville' => 'ad.ville',
		'Tél. portable' => 'ad.portable',
		'Tél. fixe' => 'ad.fixe',
		'Email' => 'ad.email'
		);

		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('ad' => 'fp_ad_fiche'), $columnsToExport)
		->joinLeft(array('adcat' => 'fp_ad_cat'), 'ad.id = adcat.idAd or adcat.idAd = null', array())
		->joinLeft(array('cat' => 'fp_cat_fiche'), 'cat.id = adcat.idChat or adcat.idChat = null', array('chatsAdoptes' => 'GROUP_CONCAT(cat.nom SEPARATOR \', \')'))
		->group('ad.id')
		->order('ad.nom');

		if ($where) {
			$select->where($where);
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}

	/**
	 * Retourne l'adoptant pour le chat $idChat
	 * @param string $idChat
	 * @return FP_Model_Bean_Adoptant
	 */
	public function getAdForChat($idChat) {
		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('ad' => 'fp_ad_fiche'))
		->join(array('adcat' => 'fp_ad_cat'), 'ad.id = adcat.idAd')
		->where("adcat.idChat = $idChat");

		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if (0 == count($result)) {
			return null;
		}
		$row = $result[0];
		return $this->buildModelFromDb($row);
	}
}