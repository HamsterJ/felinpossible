<?php
/**
 * Service commun.
 * @author Benjamin
 *
 */
abstract class FP_Service_CommonServices {
	/**
	 * Retourne le mapper principal du service.
	 * @return FP_Model_Mapper_CommonMapper
	 */
	abstract protected function getMapper();

	/**
	 * Retourne une instance de model
	 * @return FP_Model_Bean_Common
	 */
	abstract protected function getEmptyBean();

	/**
	 * Retourne le nombre d'éléments pour la grille.
	 * @return int
	 */
	public function getNbElementsForGrid() {
		return $this->getMapper()->count();
	}

	/**
	 * Retourne l'élement $id
	 * @param $id
	 * @return FP_Model_Bean_Common
	 */
	public function getElement($id) {
		return $this->getMapper()->find($id);
	}

	/**
	 * Retourne le contenu de l'élement $id sous forme d'un tableau
	 * @param $id
	 * @return array
	 */
	public function getData($id) {
		$element = $this->getMapper()->find($id);
		if ($element) {
			return $element->toArray();
		}
		return array();
	}

	/**
	 * Sauvegarde en base les données du formulaire (stockés dans la session)
	 * @param Zend_Session_Namespace $session
	 */
	public function saveForm($session) {
		$bean = $this->buildBeanFromSession($session);
		$this->getMapper()->save($bean);
	}

	/**
	 * Sauve en base les données.
	 * @param array $data les données à sauver.
	 */
	public function save($data) {
		$bean = $this->getEmptyBean();

		$mapper = $this->getMapper();
		$beanUpdated = $mapper->buildBeanFromForm($data, $bean);
		$mapper->save($beanUpdated);
	}

	/**
	 * Supprime l'élément identifié par $id
	 * @param string $id
	 */
	public function deleteElement($id) {
		if ($id && $id != '') {
			$this->getMapper()->delete("id = ".$id);
		} else {
			echo "Id non renseigné.";
		}
	}

	/*
	 * Contruit l'objet à partir du formulaire stocké en session.
	 * @param Zend_Session_Namespace $session la session
	 * @return FP_Model_Bean_Common le bean créé à partir des données du formulaire
	 */
	private function buildBeanFromSession($session) {
		$bean = $this->getEmptyBean();

		foreach ($session as $info) {
			foreach ($info as $form => $data) {
				foreach ($data as $key => $value) {
					$function = "set".ucfirst($key);
					$bean->$function($value);
				}
			}
		}

		return $bean;
	}

	/**
	 * Retourne les données au format json pour l'affichage dans la grille.
	 * @param array les paramètres (start, count etc) pour récupérer les objets.
	 * @param mapper
	 * @return string JSON
	 */
	public function getJsonData($param, $mapper = null) {
		$start = null;
		$count = null;
		$where = null;
		$paramSort = null;
		$sort = null;
		$order = null;

		if ($mapper == null) {
			$mapper = $this->getMapper();
		}

		foreach ($param as $key => $value ) {
			switch ($key) {
				case FP_Util_TriUtil::SORT_KEY :
					$paramSort = FP_Util_TriUtil::computeSortParam($param[FP_Util_TriUtil::SORT_KEY]);
					$sort = $paramSort[FP_Util_TriUtil::SORT_KEY];
					$order = $paramSort[FP_Util_TriUtil::ORDER_KEY];
					break;
				case FP_Util_Constantes::WHERE_KEY :
					$where = $mapper->getWhereClause($param[FP_Util_Constantes::WHERE_KEY]);
					break;
				case FP_Util_TriUtil::COUNT_KEY :
					$count = $param[FP_Util_TriUtil::COUNT_KEY];
					break;
				case FP_Util_TriUtil::START_KEY :
					$start = $param[FP_Util_TriUtil::START_KEY];
					break;
				default :
					$filterKeyToDbKey = $mapper->getFilterKeyToDbKey();
					if (array_key_exists($key, $filterKeyToDbKey) && $value != '') {
						$dbKey = $filterKeyToDbKey[$key];
						if ($where) {
							$where .= " and ";
						} else {
							$where = '';
						}
						
						if ($value != 'is null') {
							$pattern = addslashes($value);
							$where .= " $dbKey like '$pattern%' ";
						} else {
							$where .= " $dbKey is null ";	
						}
					}
					break;
			}
		}

		$data = $mapper->fetchAllToArray($sort, $order, $start, $count, $where);
		$nbElts = $mapper->count($where);

		$dojoData= new Zend_Dojo_Data('id', $data, 'id');
		$dojoData->setMetadata('numRows', $nbElts);

		return $dojoData->toJson();
	}

	/**
	 * Retourne les données pour l'export
	 * @param array $param
	 * @return array
	 */
	public function getDataForExport($param = null) {
		$where = null;
		if ($param && array_key_exists(FP_Util_Constantes::WHERE_KEY, $param)) {
			$where = $this->getMapper()->getWhereClause($param[FP_Util_Constantes::WHERE_KEY]);
		}

		return $this->getMapper()->fetchAllToArrayForExport($where);
	}
}