<?php
/**
 * Formulaire de pour être FA.
 * @author Benjamin
 *
 */
class FP_Form_fa_Form extends FP_Form_common_Form {

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {

		// Attach sub forms to main form
		$this->addSubForms(array(
            FP_Util_Constantes::IDENTIFICATION_FORM_ID  => new FP_Form_common_IdentificationSubForm(),
            FP_Util_Constantes::LOGEMENT_FORM_ID => new FP_Form_common_LogementSubForm(),
            FP_Util_Constantes::FOYER_FORM_ID => new FP_Form_common_FoyerSubForm(),
            FP_Util_Constantes::MOTIVATION_FORM_ID => new FP_Form_fa_MotivationSubForm(),
            FP_Util_Constantes::CONDITIONS_FORM_ID => new FP_Form_fa_ConditionsSubForm()
		));
	}

}