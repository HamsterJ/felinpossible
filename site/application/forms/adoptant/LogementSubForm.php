<?php
/**
 * Sous-formulaire pour la partie logement.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_LogementSubForm extends FP_Form_common_LogementSubForm
{
	/**
	 * Construction du subForm.
	 *
	 * @param  mixed $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);
		$depSecurisee = new Zend_Form_Element_Radio('depSecurise', array(
                'label'      => 'Dépendance sécurisée ?',
		        'required' => true,
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON
		));
		$depSecurisee->setOrder(35);
		
		$this->addElement($depSecurisee) ;
	}
}