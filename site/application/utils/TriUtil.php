<?php
/**
 * Utilitaires pour le tri.
 * @author Benjamin
 *
 */
class FP_Util_TriUtil {
	/**
	 * Constante pour le tri : sort
	 * @var string
	 */
	const SORT_KEY = 'sort';

	/**
	 * Constante pour le tri : start
	 * @var string
	 */
	const START_KEY = 'start';

	/**
	 * Constante pour le tri : count
	 * @var string
	 */
	const COUNT_KEY = 'count';

	/**
	 * Constante pour le tri : order
	 * @var string
	 */
	const ORDER_KEY = 'order';

	/**
	 * Constante pour le tri : asc
	 * @var string
	 */
	const ORDER_ASC_KEY = 'asc';

	/**
	 * Constante pour le tri : desc
	 * @var string
	 */
	const ORDER_DESC_KEY = 'desc';
	
	/**
	 * Construit le tableau contenant les paramètres du tri, extraits à partir du paramètre $sort
	 * @param string $sort : au format "<nomColonne>" ou "-<nomColonne>" pour le tri desc.
	 * @return array
	 */
	public static function computeSortParam($sort) {
		$result = array();
		$result[self::SORT_KEY] = $sort;
		$result[self::ORDER_KEY] = null;

		if($sort){
			if(strchr($sort,'-')){
				$result[self::SORT_KEY] = substr($sort, 1, strlen($sort));
				$result[self::ORDER_KEY] = self::ORDER_DESC_KEY;
			} else {
				$result[self::ORDER_KEY] = self::ORDER_ASC_KEY;
			}
		}
		return $result;
	}
}