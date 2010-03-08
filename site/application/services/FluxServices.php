<?php
/**
 * Services pour les flux.
 * @author Benjamin
 *
 */
class FP_Service_FluxServices {
	/**
	 * Instance courante.
	 * @var FP_Service_FluxServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_FluxServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_FluxServices();
		}
		return self::$instance;
	}

	/**
	 * Retourne le mapper pour les news.
	 * @return FP_Model_Mapper_NewsMapper
	 */
	private function getNewsMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->newsMapper;
	}

	/**
	 * Retourne le flux rss de news.
	 * @return Zend_Feed
	 */
	public function getFluxAtom() {
		$newsMapper = $this->getNewsMapper();
		$entries = array();

		foreach ($newsMapper->fetchAllByDate() as $news) {
			$entries[] = array(
                'title' => $news->getTitre(), 
                'link' => '',
                'description' => $news->getContenu(),
			    'lastUpdate'   => $news->getTimestampCreation()
			);
		}

		$feedArray = array(
            'title' => "News de Félin Possible", // titre de mon flux
            'link' => '', // Lien vers mon flux
            'charset' => FP_Util_Constantes::ENCODING, // Encodage
            'description' => "Les news de Félin Possible", // description
            'email' => 'asso@felinpossible.fr', // Adresse email
            'generator' => 'Zend Framework Zend_Feed',
            'language' => 'fr', // La langue
            'entries' => $entries // Un tableau qui va contenir les articles
		);

		return Zend_Feed::importArray($feedArray,'rss');
	}
}