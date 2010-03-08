<?php
/**
 * Services pour les News.
 * @author Benjamin
 *
 */
class FP_Service_NewsServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_NewsServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_NewsServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_NewsServices();
		}
		return self::$instance;
	}

	/**
	 * Return le mapper de news
	 * @return FP_Model_Mapper_NewsMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->newsMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_News
	 */
	protected function getEmptyBean() {
		$news = new FP_Model_Bean_News();
		$news->setTimestampCreation(time());
		return $news;
	}

	/**
	 * Retourne la page de news demandée suivant la pagination.
	 * @param array $param
	 * @return array le tableau contenant le paginator et les news à afficher
	 */
	public function getPage($param) {
		if (array_key_exists(FP_Util_PaginationConstantes::PAGE_KEY, $param)) {
			$currentPageNumber = $param[FP_Util_PaginationConstantes::PAGE_KEY];
		} else {
			$currentPageNumber = FP_Util_PaginationConstantes::NEWS_CURRENT_DEFAULT_PAGE;
		}

		if (array_key_exists(FP_Util_PaginationConstantes::NB_ITEMS_KEY, $param)) {
			$itemCountPerPage = $param[FP_Util_PaginationConstantes::NB_ITEMS_KEY];
		} else {
			$itemCountPerPage = FP_Util_PaginationConstantes::NEWS_ITEM_COUNT;
		}
		
		$pageRange = FP_Util_PaginationConstantes::NEWS_PAGE_RANGE;

		return $this->getMapper()->getNewsOrderedWithPagination($currentPageNumber, $itemCountPerPage, $pageRange);
	}
	
}