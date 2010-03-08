<?php
/**
 * Classe d'utilitaires pour les dates.
 * @author Benjamin
 *
 */
class FP_Util_DateUtil {

	/**
	 * Formatte la date pour l'affichage.
	 * @param $date
	 * @return string la date formattée.
	 */
	public static function getDateFormatted($date){
		$date = new Zend_Date($date);
		return $date->toString(FP_Util_Constantes::DATE_FORMAT);
	}

	/**
	 * Formatte la date au format YYYYMMDD.
	 * @param $date
	 * @return string la date formattée.
	 */
	public static function getDateFormattedForExport($date = null){
		$date = new Zend_Date($date);
		return $date->toString(FP_Util_Constantes::DATE_FORMAT_YYYYMMJJ);
	}
}