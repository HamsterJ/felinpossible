<?php

/**
 * News data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_NewsMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'News';

	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'contenu' => 'contenu',
                     'date' => 'dateEvenement',
	                 'titre' => 'titre',
	                 'timestamp' => 'timestampCreation');

	/**
	 * Retournes les news triées par date décroissante.
	 * @return array
	 */
	public function fetchAllByDate(){
		return $this->fetchAll(null, "date desc");
	}
	
	/**
	 * Retourne les news paginées et triées par date d'événement.
	 * @param int $currentPageNumber page courante
	 * @param int $itemCountPerPage nombre d'éléments par page
	 * @param int $pageRange nombre de page visibles
	 * @return array le tableau contenant le paginator et les news à afficher
	 */
	public function getNewsOrderedWithPagination($currentPageNumber, $itemCountPerPage, $pageRange) {
		return $this->selectWithPagination($currentPageNumber, $itemCountPerPage, $pageRange, null, null, "date desc");
	}

}