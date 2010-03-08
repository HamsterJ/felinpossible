<?php
/**
 * Formulaire pour l'adoption.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_Form extends FP_Form_common_Form {

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Attach sub forms to main form
		$this->addSubForms(array(
            FP_Util_Constantes::IDENTIFICATION_FORM_ID  => new FP_Form_common_IdentificationSubForm(),
            FP_Util_Constantes::LOGEMENT_FORM_ID => new FP_Form_adoptant_LogementSubForm(),
		    FP_Util_Constantes::FOYER_FORM_ID => new FP_Form_adoptant_FoyerSubForm(),
		    FP_Util_Constantes::FUTUR_CHAT_FORM_ID => new FP_Form_adoptant_FuturChatSubForm(),
		    FP_Util_Constantes::BUDGET_FORM_ID => new FP_Form_adoptant_BudgetSubForm(),
		    FP_Util_Constantes::VACANCES_FORM_ID => new FP_Form_adoptant_VacancesProjetsSubForm(),
		    FP_Util_Constantes::CONDITIONS_FORM_ID => new FP_Form_adoptant_ConditionsSubForm(),
		    FP_Util_Constantes::REMARQUES_FORM_ID => new FP_Form_adoptant_RemarquesSubForm()
		));
	}

}