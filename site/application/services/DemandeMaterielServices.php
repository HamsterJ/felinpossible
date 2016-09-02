<?php

class FP_Service_DemandeMaterielServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_DemandeMaterielServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_DemandeMaterielServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_DemandeMaterielServices();
		}
		return self::$instance;
	}

	/**
	 * Return le mapper 
	 * @return StockMaterielsDemandeMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->StockMaterielsDemandeMapper;
	}

	protected function getEmptyBean() {
		return new FP_Model_Bean_StockMaterielsDemande();
	}


	
}