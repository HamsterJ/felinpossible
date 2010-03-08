<?php

/**
 * Common data mapper.
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
abstract class FP_Model_Mapper_CommonMapper
{
	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;

	/**
	 * id class name.
	 * @var string
	 */
	protected $idClassName;

	/**
	 * Mapping db => model.
	 * @var string
	 */
	protected $mappingDbToModel;

	/**
	 * Keys excluded for mapping model => db.
	 * @var string
	 */
	protected $excludeModelToDb = array();

	/**
	 * Tableau des clauses where
	 * @var array
	 */
	protected $clausesWhere = array();

	/**
	 * Mapping filters key => db key
	 * @var array
	 */
	protected $filterKeyToDbKey = array();
	
	/**
	 * Return the DbTable class name.
	 * @return string
	 */
	protected function getDbTableClassName() {
		return 'FP_Model_DbTable_'.$this->idClassName.'Table';
	}

	/**
	 * Return the Model class name.
	 * @return string
	 */
	protected function getModelClassName() {
		return 'FP_Model_Bean_'.$this->idClassName;
	}

	/**
	 * Return the mapping : db fields => model fields.
	 * @return array
	 */
	public function getMappingDbToModel() {
		return $this->mappingDbToModel;
	}

	/**
	 * Return le mapping filter key => db key
	 * @return array
	 */
	public function getFilterKeyToDbKey() {
		return $this->filterKeyToDbKey;
	}
	
	/**
	 * Return the key to exclued for mapping : model fields => db fields
	 * @return array
	 */
	protected function getExcludeModelToDb() {
		return $this->excludeModelToDb;
	}
	/**
	 * Specify Zend_Db_Table instance to use for data operations
	 *
	 * @param  Zend_Db_Table_Abstract $dbTable
	 * @return FP_Model_Mapper_CommonMapper
	 */
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * Get registered Zend_Db_Table instance
	 *
	 * Lazy loads FP_Model_DbTable_News if no instance registered
	 *
	 * @return Zend_Db_Table_Abstract
	 */
	public function getDbTable(){
		if (null === $this->_dbTable) {
			$this->setDbTable($this->getDbTableClassName());
		}
		return $this->_dbTable;
	}

	/**
	 * Save a entry
	 *
	 * @param FP_Model_Bean_Common $common
	 */
	public function save($common)
	{
		$data = $this->buildDbArrayFromModel($common);
		if (null === ($id = $common->getId()) || $id == "") {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}

	/**
	 * Insert a entry
	 *
	 * @param FP_Model_Bean_Common $common
	 */
	public function insert($common)
	{
		$data = $this->buildDbArrayFromModel($common);
		$this->getDbTable()->insert($data);
	}


	/**
	 * Find a entry by id
	 *
	 * @param  int $id
	 * @return FP_Model_Bean_Common
	 */
	public function find($id)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		return $this->buildModelFromDb($row);
	}

	/**
	 * Retourne true si l'objet existe.
	 * @param $id
	 * @return boolean
	 */
	public function exists($id)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return false;
		}
		return true;
	}

	/**
	 * Fetch all entries.
	 * @param string $where
	 * @param string $order
	 * @return array
	 */
	public function fetchAll($where = null, $order = null)
	{
		$resultSet = $this->getDbTable()->fetchAll($where, $order);
		return $this->getDataFromRowSet($resultSet);
	}

	/**
	 * Fetch all entries.
	 *
	 * @return array
	 */
	public function fetchAllToArray($sort = null, $order = FP_Util_TriUtil::ORDER_ASC_KEY, $start = null, $count = null, $where = null)
	{
		$sortKey = null;
		if ($sort) {
			$sortKey =  $sort;
			if ($order) {
			  $sortKey .= " $order";
			} 
		}

		return $this->getDbTable()->fetchAll($where, $sortKey, $count, $start);
	}

	
	/**
	 * Retourne les données à export pour l'export Excel.
	 * @param string $where
	 * @return array
	 */
	public function fetchAllToArrayForExport ($where) {
		return $this->fetchAllToArray(null, null, null, null, $where);
	}
	
	/**
	 * Retourne la clause where à partir de son id.
	 * @param int $clauseId
	 * @return string
	 */
	public function getWhereClause($clauseId) {
		if (array_key_exists($clauseId, $this->clausesWhere)) {
			return $this->clausesWhere[$clauseId];
		}
		return null;
	}

	/**
	 * Delete row with $id.
	 * @param $id
	 */
	public function delete($where)
	{
		$this->getDbTable()->delete($where);
	}

	/**
	 * Count number of rows in the table.
	 * @param $where where clause.
	 * @return int
	 */
	public function count($where = null)
	{
		$db = $this->getDbTable()->getAdapter();
		$select = $this->getDbTable()->select();
		$query = $select->from($this->getDbTable(), 'count(1)');

		if ($where) {
			$query = $select->where($where);
		}

		return $db->fetchOne($query);
	}

	/**
	 * Retourne les résultat paginés.
	 * @param int $currentPageNumber page courante
	 * @param int $itemCountPerPage nombre d'éléments par page
	 * @param int $pageRange nombre de page visibles
	 * @param string $columns les colonnes à sélectionner
	 * @param string $where where condition
	 * @param string $order order by
	 *
	 * @return array le tableau contenant le paginator et les résultats
	 */
	public function selectWithPagination($currentPageNumber, $itemCountPerPage, $pageRange, $columns = null, $where = null, $order = null) {
		$select = $this->getDbTable()->select();
			
		if ($columns) {
			$select->from($this->getDbTable(), $columns);
		}
			
		if ($where) {
			$select->where($where);
		}
			
		if ($order) {
			$select->order($order);
		}
			
		$paginator = Zend_Paginator::factory($select);
		$paginator->setPageRange($pageRange);
		$paginator->setCurrentPageNumber($currentPageNumber);
		$paginator->setItemCountPerPage($itemCountPerPage);

		$data   = $this->getDataFromRowSet($paginator);
		$result = array(
		FP_Util_PaginationConstantes::PAGINATOR_KEY => $paginator,
		FP_Util_PaginationConstantes::DATA_KEY => $data
		);

		return $result;
	}

	/**
	 * Retourne les résultats.
	 * @param string $where where condition
	 * @param string $order order by
	 *
	 * @return array le tableau contenant les résultats
	 */
	public function select($columns = null, $where = null, $order = null) {
		$select = $this->getDbTable()->select();
			
		if ($columns) {
			$select->from($this->getDbTable(), $columns);
		}
			
		if ($where) {
			$select->where($where);
		}
			
		if ($order) {
			$select->order($order);
		}
			
		$stmt = $select->query();
		return $this->getDataFromRowSet($stmt->fetchAll());
	}

	/**
	 * Return les éléments résultats.
	 * @param array $rowSet le résultat brut
	 * @param boolean $toArray true si le résultat doit être un tableau de tableau et non un tableau d'objet (utile pour dojo)
	 *
	 * @return array le tableau contenant les résultats
	 */
	protected function getDataFromRowSet($rowSet, $toArray = false) {
		$result = array();
		foreach ($rowSet as $row) {
			$bean = $this->buildModelFromDb($row);
			if ($toArray) {
				$bean = $bean->toArray();
			}
			$result[] = $bean;
		}
		return $result;
	}

	/**
	 * Construit le model à partir des infos de la base
	 * @param array $dbRow la ligne brut correspondant à l'objet à créer
	 * @return FP_Model_Bean_Common le model créé
	 */
	protected function buildModelFromDb($dbRow) {
		$modelClass = $this->getModelClassName();
		$model = new $modelClass;
		$mapping = $this->getMappingDbToModel();
		$data = $dbRow;

		if ($dbRow && $dbRow instanceof Zend_Db_Table_Row_Abstract) {
			$data = $dbRow->toArray();
		}

		foreach ($data as $key => $value) {
			if (array_key_exists($key, $mapping)) {
				$method = 'set' . ucfirst($mapping[$key]);
				$model->$method($value);
			}
		}

		return $model;
	}

	/**
	 * Construit un représentation du model pour l'enregistrement en base.
	 * @param FP_Model_Bean_Common $model
	 * @return array l'objet db
	 */
	protected function buildDbArrayFromModel($model) {
		$dbArray = array();

		foreach ($this->getMappingDbToModel() as $key => $value) {
			if (!array_key_exists($value, $this->getExcludeModelToDb())) {
				$function = "get".ucfirst($value);
				$dbArray[$key] = $model->$function();
			}
		}

		return $dbArray;
	}

	/**
	 * Contruit le tableau (id => valeur) pour l'affichage dans le formulaire.
	 * @param string $idColumnName
	 * @param string $valueColumnName
	 * @return array
	 */
	public function buildArrayForForm($idColumnName = 'id', $valueColumnName = 'name') {
		$select = $this->getDbTable()->select();
		$select->from($this->getDbTable(), array($idColumnName, $valueColumnName));
		$select->order($valueColumnName);
		$stmt = $select->query();

		$result = array();
		foreach ($stmt->fetchAll() as $row) {
			$result[$row[$idColumnName]] = $row[$valueColumnName];
		}

		return $result;
	}

	/**
	 * Construit le bean à partir des données passées en paramètre.
	 * @param array $data
	 * @return FP_Model_Bean_Common
	 */
	public function buildBeanFromForm($data, $beanToUpdate = null) {
		$modelClass = $this->getModelClassName();
		if ($beanToUpdate) {
			$model = $beanToUpdate;
		} else {
			$model = new $modelClass;
		}

		foreach ($data as $key => $value) {
			$function = "set".ucfirst($key);
			$model->$function($value);
		}
		return $model;
	}
}
