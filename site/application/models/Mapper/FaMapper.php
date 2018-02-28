<?php

/**
 * Fa data mapper.
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_FaMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Fa';

	protected $mappingDbToModel = array(
		'id' => 'id',
		'nom' => 'nom',
		'prenom' => 'prenom',
                'dateNaissance' => 'dateNaissance',
		'adresse' => 'adresse',
		'cp' => 'codePostal',
		'ville' => 'ville',
		'fixe' => 'telephoneFixe',
		'portable' => 'telephonePortable',
		'email' => 'email',
		'idLogement' => 'idLogement',
		'idDependance' => 'idDependance',
		'chatiere' => 'hasChatiere',
		'etage' => 'etage',
		'personnes' => 'nbPersonnes',
		'enfants' => 'nbEnfants',
		'enfantsAge' => 'enfantsAge',
		'animaux' => 'animauxAutres',
		'chats' => 'chats',
		'motivations' => 'motivations',
		'securFenetre' => 'isSecurFenetres',
		'survFenetre' => 'isSurvFenetres',
		'veto' => 'contacterVeto',
		'contact' => 'contacterAssociation',
		'patience' => 'patienceAvecChat',
		'jouer' => 'jouerAvecChat',
		'croquette' => 'fournirCroquettes',
		'mere' => 'accueillirMere',
		'biberon' => 'biberonnerChatons',
		'chatons' => 'accueillirChatons',
		'fiv' => 'accueillirChatFiv',
		'felv' => 'accueillirChatFelv',
		'soins' => 'donnerSoins',
		'quarantaine' => 'mettreChatQuarantaine',
		'isoler' => 'isolerChat',
		'idStatut' => 'statutId',
		'notes' => 'notes',
		'login' => 'login',
		'dateSubmit' => 'dateSubmit',
		'superficie' => 'superficie',
		'dateContratFa' => 'dateContratFa'
		);

protected $filterKeyToDbKey = array('nom' => 'fa.nom',
	'login' => 'fa.login',
	'prenom' => 'fa.prenom',
	'statut' => 'fa.idStatut',
	'dateContratFa' => 'fa.dateContratFa');

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArray($sort, $order, $start, $count, $where)
	 */
	public function fetchAllToArray($sort = 'nom', $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
	{
		$subSelect = $this->getDbTable()->getAdapter()->select()
		->from(array('fa' => 'fp_fa_fiche'))
		->joinLeft(array('facat' => 'fp_fa_cat'), 'fa.id = facat.idFa or facat.idFa = null', array())
		->joinLeft(array('cat' => 'fp_cat_fiche'), 'cat.id = facat.idChat or facat.idChat = null', array('chatsAccueil' => 'GROUP_CONCAT(cat.nom SEPARATOR \', \')'))
		->join(array('st' => 'fp_fa_statut'), 'fa.idStatut = st.id', array('statutLib' => 'st.nom'))
		->group('fa.id');

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
                if ($sort && $order) {
                    $select->order(array($sort." ".$order));       
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
		$columnsToExport = array('Identifiant' => 'fa.id',
			'Nom' => 'fa.nom',
			'Prénom' => 'fa.prenom',
			'Statut' => 'st.nom',
			'Date réception contrat FA' => 'fa.dateContratFa',
			'Login' => 'login',
			'Chats en accueil' => 'GROUP_CONCAT(cat.nom SEPARATOR \', \')',
			'FIV' => 'ELT(fa.fiv + 1, \'Non\', \'Oui\')',
			'FELV' => 'ELT(fa.felv + 1, \'Non\', \'Oui\')',
			'Quarantaine' => 'ELT(fa.quarantaine + 1, \'Non\', \'Oui\')',
			'Notes' => 'notes',
			'Animmaux' => 'animaux',
			'Chats' => 'chats',
			'Code Postal' => 'fa.cp',
			'Ville' => 'fa.ville',
			'Tél. portable' => 'fa.portable',
			'Tél. fixe' => 'fa.fixe',
			'Email' => 'email'
			);
		
		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('fa' => 'fp_fa_fiche'), $columnsToExport)
		->joinLeft(array('facat' => 'fp_fa_cat'), 'fa.id = facat.idFa or facat.idFa = null', array())
		->joinLeft(array('cat' => 'fp_cat_fiche'), 'cat.id = facat.idChat or facat.idChat = null', array())
		->join(array('st' => 'fp_fa_statut'), 'fa.idStatut = st.id', array())
		->group('fa.id')
		->order('fa.idStatut');

		if ($where) {
			$select->where($where);
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}
	
	/**
	 * Mise à jour du statut de la FA en fonction du nombre de chats placés chez elle.
	 * @param string $idFa
	 */
	public function updateStatut($idFa, $statut) {
		$this->getDbTable()->update(array('idStatut' => $statut), "id = ".$idFa);
	}

	/**
	 * Retourne la FA pour le chat $idChat
	 * @param string $idChat
	 * @return FP_Model_Bean_Fa
	 */
	public function getFaForChat($idChat) {
		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('fa' => 'fp_fa_fiche'))
		->join(array('facat' => 'fp_fa_cat'), 'fa.id = facat.idFa')
		->where("facat.idChat = $idChat");

		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if (0 == count($result)) {
			return null;
		}
		$row = $result[0];
		return $this->buildModelFromDb($row);
	}

	/**
	 * Retourne le nombre de FA avec le statut désiré.
	 * @param int $idStatut
	 * @return number
	 */
	public function getNbFaWithStatus($idStatut) {
		return $this->count("idStatut = ".$idStatut);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#count($where)
	 */
	public function count($where = null)
	{
		$db = $this->getDbTable()->getAdapter();
		$select = $db->select();
		$query = $select->from(array('fa' => 'fp_fa_fiche'), 'count(1)');

		if ($where) {
			$query = $select->where($where);
		}

		return $db->fetchOne($query);
	}

}