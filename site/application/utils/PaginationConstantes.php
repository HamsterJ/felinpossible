<?php
/**
 * Constantes pour la pagination.
 * @author Benjamin
 *
 */
class FP_Util_PaginationConstantes {
	/**
	 * Page par défaut pour les news.
	 * @var int
	 */
	const NEWS_CURRENT_DEFAULT_PAGE = 1;
	
	/**
	 * Nombre d'éléments par page pour les news.
	 * @var int
	 */
	const NEWS_ITEM_COUNT = 3;
	
	/**
	 * Nombre de pages visibles pour les news.
	 * @var int
	 */
	const NEWS_PAGE_RANGE = 5;

	/**
	 * Page par défaut pour les chats.
	 * @var int
	 */
	const CHATS_CURRENT_DEFAULT_PAGE = 1;
	
	/**
	 * Nombre d'éléments par page pour les chats.
	 * @var int
	 */
	const CHATS_ITEM_COUNT = 30;
	
	/**
	 * Nombre de pages visibles pour les chats.
	 * @var int
	 */
	const CHATS_PAGE_RANGE = 5;
	
	/**
	 * Clef pour accéder au paginateur.
	 * @var string
	 */
	const PAGINATOR_KEY = 'paginator';
	
	/**
	 * Clef pour accéder aux données de pagination.
	 * @var string
	 */
	const DATA_KEY = 'data';
	
	/**
	 * Clef pour accéder au numéro de la page.
	 * @var string
	 */
	const PAGE_KEY = 'page';
	
	/**
	 * Celf pour accéder aux nombre d'éléments par page.
	 * @var string
	 */
	const NB_ITEMS_KEY = 'par';
}