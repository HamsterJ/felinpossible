	<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Initialisation de la vue.
	 * @return Zend_View la vue
	 */
	protected function _initView()
	{
		// Initialize view
		$view = new Zend_View();
		$view->doctype('XHTML1_STRICT');

		$view->headTitle('Association Felin Possible - Bienvenue - Adoption de chats sur Rennes et l\'Ille et Vilaine');

		$view->headLink()->appendStylesheet('/site/public/css/bootstrap.min.css');
		$view->headLink()->appendStylesheet('/site/public/css/bootstrap-responsive.min.css');
		$view->headLink()->appendStylesheet('/site/public/css/felinpossible.css');
        $view->headLink()->appendStylesheet('/site/public/css/design.css');

		Zend_Dojo::enableView($view);
		$view->dojo()->setLocalPath('/site/public/js/dojo/dojo/dojo.js')
		->addStyleSheetModule('dijit.themes.tundra')
		-> setDjConfigOption('parseOnLoad', true)
		-> enable();
        
		$view->headScript()->appendFile('/site/public/js/jquery-1.8.2.min.js');	
        $view->headScript()->appendFile('/site/public/js/common.js');
        $view->headScript()->appendFile('/site/public/js/adminCommon.js');    
        $view->headScript()->appendFile('/site/public/js/fp_form.js');  
		$view->headScript()->appendFile('/site/public/js/dojo/dojo/fpDojo.js');
		$view->headScript()->appendFile('/site/public/js/bootstrap.min.js');

		// Add it to the ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);

		// Return it, so that it can be stored by the bootstrap
		return $view;
	}

	/**
	 * Chargement des classes.
	 * @return l'autoloader.
	 */
	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
                'namespace' => 'FP_',
                'basePath'  => APPLICATION_PATH,
		));

		$autoloader->addResourceTypes(array(
                'util' => array(
                'namespace' => 'Util_',
                'path'      => '/utils',
		)));

		$autoloader->addResourceTypes(array(
                'controller' => array(
                'namespace' => 'Controller_',
                'path'      => '/controllers',
		)));

                $autoloader->addResourceTypes(array(
                'model' => array(
                'namespace' => 'Model_',
                'path'      => '/models',
                )));

                $autoloader->addResourceTypes(array(
                'mapper' => array(
                'namespace' => 'Model_Mapper',
                'path'      => '/models/Mapper',
                )));

		return $autoloader;
	}

	/**
	 * Initialisation du BaseUrl (pour la construction des autres urls).
	 */
	protected function _initBaseUrl() {
		$frontController = Zend_Controller_Front::getInstance();
		$config = $this->getConfig();
		$frontController->setBaseUrl($config->site->prefix->url);
	}

	/**
	 * Initialisation de la connexion à la base.
	 * @return la connexion à la base.
	 */
	protected function _initDb()
	{
		//on charge notre fichier de configuration
		$config = $this->getConfig();

		//On essaye de faire une connection a la base de donnee.
		try {
			$db = Zend_Db::factory($config->resources->db);
			//on test si la connection se fait
			$db->getConnection();
			$db->query("SET NAMES '".FP_Util_Constantes::ENCODING."'");
		} catch ( Exception $e ) {
			exit( $e -> getMessage() );
		}
			
		Zend_Db_Table::setDefaultAdapter($db);
		return $db;
	}

	/**
	 * Initialisation du layout pour le MVC.
	 */
	protected function _initMvc() {
		Zend_Layout::startMvc();
	}

	/**
	 * Retourne la configuration.
	 * @return Zend_Config
	 */
	private function getConfig() {
		if (!Zend_Registry::isRegistered(FP_Util_Constantes::CONFIG_ID)) {
			$config = new Zend_Config($this->getOptions());
			Zend_Registry::set(FP_Util_Constantes::CONFIG_ID, $config);
		} else {
			$config = Zend_Registry::get(FP_Util_Constantes::CONFIG_ID);
		}
		return $config;
	}
}

