<?php
/**
 * Formulaire pour la fiche vétérinaire.
 * @author Benjamin
 *
 */
class FP_Form_veto_Form extends FP_Form_common_Form {

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Attach sub forms to main form
		$this->addSubForms(array(
            FP_Util_Constantes::IDENTIFICATION_FORM_ID  => new FP_Form_common_IdentificationEntrepriseSubForm()
		));
	}

}