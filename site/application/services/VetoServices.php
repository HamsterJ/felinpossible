<?php
/**
 * Services pour les vétos.
 * @author Benjamin
 *
 */
class FP_Service_VetoServices extends FP_Service_CommonServices {
	/**
	 * Instance courante.
	 * @var FP_Service_VetoServices
	 */
	private static $instance;

	/**
	 * Retourne l'instance courante.
	 * @return FP_Service_VetoServices
	 */
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new FP_Service_VetoServices();
		}
		return self::$instance;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getMapper()
	 * @return FP_Model_Mapper_VetoMapper
	 */
	protected function getMapper() {
		return FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper;
	}

	/**
	 * (non-PHPdoc)
	 * @see site/application/services/FP_Service_CommonServices#getEmptyBean()
	 * @return FP_Model_Bean_Veto
	 */
	protected function getEmptyBean() {
		return new FP_Model_Bean_Veto();
	}
}