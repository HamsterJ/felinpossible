<?php
/**
 * 
 * @author Benjamin
 * @package FelinPossible
 */
class FP_Model_Mapper_MapperFactory {
	private static $instance;
	
	/**
	 * Retourne l'instance courante.
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Model_Mapper_MapperFactory();
		}
		return self::$instance;
	}
	
	/**
	 * Retourne le mapper $name
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	public function __get($name) {
		if (Zend_Registry::isRegistered($name)) {
			return Zend_Registry::get($name);
		}
		
		$classname = 'FP_Model_Mapper_'.ucfirst($name);
		$mapper = new $classname();
		Zend_Registry::set($name, $mapper);
		return $mapper;
	}
}