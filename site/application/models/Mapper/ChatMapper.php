<?php

/**
 * Chat data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_ChatMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Chat';

	protected $mappingDbToModel = array(
		'id' => 'id',
		'to_check' => 'aValider',
		'adopte' => 'adopte',
		'caractere' => 'caractere',
		'commentaires' => 'commentaires',
		'date_adoption' => 'dateAdoption',
		'date' => 'dateNaissance',
		'dateApproximative' => 'dateApproximative',
		'disparu' => 'disparu',
		'idCouleur' => 'idCouleur',
		'idSexe' => 'idSexe',
		'topic' => 'lienTopic',
		'miniature' => 'miniature',
		'nom' => 'nom',
		'renomme' => 'renomme',
		'parrain' => 'parrain',
		'post_id' => 'postId',
		'race' => 'race',
		'reserve' => 'reserve',
		'tatouage' => 'tatouage',
		'tests' => 'tests',
		'topic_id' => 'topicId',
		'vaccins' => 'vaccins',
		'yeux' => 'yeux',
		'libCouleur' => 'libelleCouleur',
		'libSexe' => 'libelleSexe',
		'dateRappelVaccins' => 'dateRappelVaccins',
		'notesPrivees' => 'notesPrivees',
		'datePriseEnCharge' => 'datePriseEnCharge',
		'dateAntiPuces' => 'dateAntiPuces',
		'dateVermifuge' => 'dateVermifuge',
		'statutVisite' => 'statutVisite',
		'visitePostPar' => 'visitePostPar',
		'dateTests' => 'dateTests',
		'dateSterilisation' => 'dateSterilisation',
		'declCession' => 'declarationCession',
		'sterilise' => 'sterilise',
		'dateEnvoiRappelVac' => 'dateEnvoiRappelVac',
		'dateEnvoiRappelSte' => 'dateEnvoiRappelSte',
		'dateContratAdoption' => 'dateContratAdoption',
		'papierIdRecu' => 'papierIdRecu',
        'okChats' => 'okChats',
        'okChiens' => 'okChiens',
        'okApparts' => 'okApparts',
        'okEnfants' => 'okEnfants'
		);

protected $excludeModelToDb = array('libelleCouleur' => 0,
	'libelleSexe' => 0,
	'fa' => 0);

protected $filterKeyToDbKey = array('nom' => 'cat.nom',
	'aValider' => 'cat.to_check',
	'adopte' => 'cat.adopte',
	'reserve' => 'cat.reserve',
	'aParrainer' => 'cat.parrain',
	'disparu' => 'cat.disparu',
	'dateContratAdoption' => 'cat.dateContratAdoption');

protected $clausesWhere = array(
	FP_Util_Constantes::CHAT_FICHES_A_VALIDER => 'to_check = 1 and disparu = 0',
	FP_Util_Constantes::CHAT_FICHES_A_ADOPTION => 'adopte = 0 and disparu = 0 and to_check = 0',
	FP_Util_Constantes::CHAT_FICHES_ADOPTES => 'adopte = 1 and disparu = 0 and to_check = 0',
	FP_Util_Constantes::CHAT_FICHES_DISPARUS => 'disparu = 1 and to_check = 0',
	FP_Util_Constantes::CHAT_FICHES_A_PARRAINER => 'parrain = 1 and disparu = 0 and to_check = 0',
	FP_Util_Constantes::CHAT_FICHES_RESERVES => 'reserve = 1 and to_check = 0',
	FP_Util_Constantes::CHAT_AVEC_DATE_VACCINS => 'adopte = 0 and disparu = 0',
	FP_Util_Constantes::CHAT_FICHES_A_PLACER =>  'adopte = 0 and disparu = 0',
	FP_Util_Constantes::CHAT_A_STERILISER =>  'disparu = 0 and sterilise = 0',
	FP_Util_Constantes::CHAT_FICHES_A_ADOPTION_NON_RES => 'adopte = 0 and disparu = 0 and reserve = 0 and to_check = 0',
	);


	/**
	 * Retourne les chats suivant la clause where.
	 * @param int $clauseWhereId
	 * @return array
	 */
	private function getChatsFiches($clauseWhereId) {
		$where = $this->getWhereClause($clauseWhereId);
		return $this->select(array('id', 'nom', 'miniature', 'topic', 'reserve', 'idSexe'), $where, "nom");
	}

	/**
	 * Retourne le nombre de fiches suivant l'identifiant de la clause.
	 * @param int $whereClauseId
	 * @return int
	 */
	public function getNbFiches($whereClauseId) {
		$where = $this->getWhereClause($whereClauseId);
		return $this->count($where);
	}

	/**
	 * Retourne les chats à l'adoption.
	 * @return array l'ensemble des chats à l'adoption
	 */
	public function getChatsAdoption() {
		return $this->getChatsFiches(FP_Util_Constantes::CHAT_FICHES_A_ADOPTION);
	}

	/**
	 * Retourne les chats à l'adoption, non réservés.
	 * @return array l'ensemble des chats à l'adoption, non réservés
	 */
	public function getChatsAdoptionNonReserves() {
		return $this->getChatsFiches(FP_Util_Constantes::CHAT_FICHES_A_ADOPTION_NON_RES);
	}

	/**
	 * Retourne les chats adoptés.
	 * @return array l'ensemble des chats
	 */
	public function getChatsAdoptes($currentPageNumber, $itemCountPerPage, $pageRange) {
		$where = $this->getWhereClause(FP_Util_Constantes::CHAT_FICHES_ADOPTES);
		return $this->selectWithPagination($currentPageNumber, $itemCountPerPage, $pageRange, array('id', 'nom', 'miniature', 'topic'), $where, "nom");
	}

	/**
	 * Retourne les chats à parrainer.
	 * @return array l'ensemble des chats
	 */
	public function getChatsAParrainer() {
		return $this->getChatsFiches(FP_Util_Constantes::CHAT_FICHES_A_PARRAINER);
	}

	/**
	 * Retourne les chats disparus.
	 * @return array l'ensemble des chats
	 */
	public function getChatsDisparus() {
		return $this->getChatsFiches(FP_Util_Constantes::CHAT_FICHES_DISPARUS);
	}

	/**
	 * Retourne les chats des chats à parrainer pour le formulaire.
	 * @return array (id => nomChat1, id => nomChat2, ...) l'ensemble des chats à parainer
	 */
	public function getListChatsAParrainerForForm() {
		$where = $this->getWhereClause(FP_Util_Constantes::CHAT_FICHES_A_PARRAINER);
		$listChatAParrainer = $this->select(array('id', 'nom'), $where, "nom");
		$result = array();

		foreach ($listChatAParrainer as $chat) {
			$result[$chat->getId()] = $chat->getNom();
		}
		return $result;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#find($id)
	 * @return FP_Model_Bean_Chat le chat
	 */
	public function find($idChat) {
		$select = $this->getDbTable()->select()
		->setIntegrityCheck(false)
		->from(array('cat' => 'fp_cat_fiche'))
		->join(array('couleur' => 'fp_cat_color'), 'cat.idCouleur = couleur.id', array('libCouleur' => 'couleur.name'))
		->join(array('sexe' => 'fp_cat_sex'), 'cat.idSexe = sexe.id', array('libSexe' => 'sexe.name'))
		->where('cat.id = ?', $idChat);

		$stmt = $select->query();
		$result = $this->getDataFromRowSet($stmt->fetchAll());
		if (count($result) > 0) {
			return $result[0];
		}
		return null;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArray($sort, $order, $start, $count, $where)
	 */
	public function fetchAllToArray($sort, $order, $start, $count, $where = null)
	{
		$subSelect = $this->getDbTable()->getAdapter()->select()
		->from(array('cat' => 'fp_cat_fiche'))
		->joinLeft(array('facat' => 'fp_fa_cat'), 'cat.id = facat.idChat or facat.idChat = null', array())
		->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = facat.idFa or facat.idFa = null', array('fa' => 'CONCAT(fa.prenom, \' \', fa.nom, COALESCE(CONCAT(\' (\', fa.login, \')\'), \'\'))'))
		->joinLeft(array('adcat' => 'fp_ad_cat'), 'cat.id = adcat.idChat or adcat.idChat = null', array())
		->joinLeft(array('ad' => 'fp_ad_fiche'), 'ad.id = adcat.idAd or adcat.idAd = null', array('adoptant' => 'CONCAT(ad.prenom, \' \', ad.nom, COALESCE(CONCAT(\' (\', ad.login, \')\'), \'\'))'))
		->join(array('couleur' => 'fp_cat_color'), 'cat.idCouleur = couleur.id', array('libCouleur' => 'couleur.name'))
		->join(array('sexe' => 'fp_cat_sex'), 'cat.idSexe = sexe.id', array('libSexe' => 'sexe.name'));


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
	 * Retourne les chats en accueil chez la FA.
	 * @param string $sort
	 * @param string $order
	 * @param string $start
	 * @param string $count
	 * @param string $faId
	 * @return array
	 */
	public function fetchAllToArrayForFa($sort, $order, $start, $count, $faId)
	{
		return $this->fetchAllToArray($sort, $order, $start, $count, "facat.idFa = $faId");
	}

	/**
	 * Retourne les chats adoptés par $adId.
	 * @param string $sort
	 * @param string $order
	 * @param string $start
	 * @param string $count
	 * @param string $adId
	 * @return array
	 */
	public function fetchAllToArrayForAd($sort, $order, $start, $count, $adId)
	{
		return $this->fetchAllToArray($sort, $order, $start, $count, "adcat.idAd = $adId");
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArrayForExport($where)
	 */
	public function fetchAllToArrayForExport($where = null)
	{
		$columnsToExport = array('Identifiant' => 'cat.id',
			'Nom' => 'cat.nom',
			'Renommé' => 'cat.renomme',
			'Sexe' => 'sexe.name',
			'Couleur' => 'couleur.name',
			'Identification' => 'cat.tatouage',
			'Tests' => 'cat.tests',
			'fa' => 'CONCAT(fa.prenom, \' \', fa.nom, COALESCE(CONCAT(\' (\', fa.login, \')\'), \'\'))',
			'Notes privées' => 'cat.notesPrivees',
			'Date de naissance' => 'cat.date',
			'Date de prise en charge' => 'cat.datePriseEnCharge',
			'Date d\'adoption' => 'cat.date_adoption',
			'Date contrat adoption' => 'cat.dateContratAdoption',
			'Date de rappels des vaccins' => 'cat.dateRappelVaccins',
			'Date des derniers tests' => 'cat.dateTests',
			'Date stérilisation' => 'cat.dateSterilisation',
			'Date anti-puces' => 'cat.dateAntiPuces',
			'Date vermifuge' => 'cat.dateVermifuge',
			'Déclaration de cession' => 'ELT(cat.declCession + 1, \'Non\', \'Oui\')',
			'Visite post-adoption réalisée ?' => 'ELT(cat.statutVisite + 1, \'Non\', \'Oui\')',
			'Visite réalisée par' => 'cat.visitePostPar',
			'Adopté' => 'ELT(cat.adopte + 1, \'Non\', \'Oui\')',
			'Réservé' => 'ELT(cat.reserve + 1, \'Non\', \'Oui\')',
			'Parrain' => 'ELT(cat.parrain + 1, \'Non\', \'Oui\')',
			'Disparu' => 'ELT(cat.disparu + 1, \'Non\', \'Oui\')',
			'Date mail vaccins' => 'cat.dateEnvoiRappelVac',
			'Date mail stéri' => 'cat.dateEnvoiRappelSte'
			);

		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('cat' => 'fp_cat_fiche'), $columnsToExport)
		->joinLeft(array('facat' => 'fp_fa_cat'), 'cat.id = facat.idChat or facat.idChat = null', array())
		->joinLeft(array('fa' => 'fp_fa_fiche'), 'fa.id = facat.idFa or facat.idFa = null', array())
		->join(array('couleur' => 'fp_cat_color'), 'cat.idCouleur = couleur.id', array())
		->join(array('sexe' => 'fp_cat_sex'), 'cat.idSexe = sexe.id', array())
		->order('cat.nom');


		if ($where) {
			$select->where($where);
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}

	/**
	 * Ajoute un an à la date de rappel de vaccins du chat.
	 * @param string $idChat
	 */
	public function incrDateRappelVaccins($idChat) {
		$this->getDbTable()->update(array('dateRappelVaccins' => new Zend_Db_Expr('DATE_ADD(DATE(dateRappelVaccins), INTERVAL 1 YEAR) ')), "id = $idChat");
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#count($where)
	 */
	public function count($where = null)
	{
		$db = $this->getDbTable()->getAdapter();
		$select = $db->select();
		$query = $select->from(array('cat' => 'fp_cat_fiche'), 'count(1)');

		if ($where) {
			$query = $select->where($where);
		}

		return $db->fetchOne($query);
	}

	/**
	 * Met à jour la date d'envoi du mail de rappel de vaccins.
	 * @param string $idChat
	 */
	public function updateDateRappelVaccins($idChat) {
		$this->getDbTable()->update(array('dateEnvoiRappelVac' => new Zend_Db_Expr('CURDATE()')), "id = $idChat");
	}

	/**
	 * Met à jour la date d'envoi du mail de rappel de stérilisation.
	 * @param string $idChat
	 */
	public function updateDateRappelSter($idChat) {
		$this->getDbTable()->update(array('dateEnvoiRappelSte' => new Zend_Db_Expr('CURDATE()')), "id = $idChat");
	}
        
    /**
	 * Retourne les chats à l'adoption, non réservés et filtrés.
	 * @return array : l'ensemble des chats à l'adoption, non réservés, repondant aux criteres de recherche
	 */
	public function getChatsAdoptionNonReservesFiltres($arrFiltres,$mode) {
                
            $where = $this->clausesWhere[FP_Util_Constantes::CHAT_FICHES_A_ADOPTION_NON_RES] ;
                    
            if ($mode === 'match'){
                    foreach ($arrFiltres as $crit=>$val)
                    {
                        if ($val != '0' && $crit != 'submit')
                        {
                            $where =  $where.' and '.$crit.' = '.$val;
                        }
                        
                    }
            }
            else
            {
                $test = 0;
                foreach ($arrFiltres as $crit=>$val)
                    {
                        if ($val != '0' && $crit != 'submit')
                        {
                            $where =  $where.' and '.$crit.' = 0'; //on prend les chats pour lesquels on a pas l'info
                            $test = 1; //on retient qu'au moin un critere était saisi, sinon il faut rien afficher dans la partie "Ils peuvent aussi..."
                        }
                    }
                    if ($test === 0){$where =  $where.' and 1=0';}
            }
            return $this->select(array('id', 'nom', 'miniature', 'topic', 'reserve', 'idSexe','okChats','okChiens','okApparts','okEnfants','caractere'), $where, "nom");
	}

}