<?php
/**
 * Clas d'utilitaires pour les validators (pour les formulaires).
 * @author Benjamin
 *
 */
class FP_Util_ValidatorUtil {

	/**
	 * Le validator pour les numéros de téléphone.
	 */
	private static $telephoneValidator;
	
	/**
	 * Return le validator pour les numéros de téléphone
	 * @return Zend_Validate_Regex
	 */
	public static function getTelephoneValidator() {
		if (!self::$telephoneValidator) {
			self::$telephoneValidator = new Zend_Validate_Regex('/^0[1-9]{1}[0-9]{8}$/');
			self::$telephoneValidator->setDisableTranslator(true);
			self::$telephoneValidator->setMessage('Le format du numéro de téléphone n\'est pas correct (10 chiffres attendus, format 0XXXXXXXXX)'); 
		}
		return self::$telephoneValidator;
	}
	
}
