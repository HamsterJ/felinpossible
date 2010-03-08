<?php
/**
 * Sous-formulaire pour la partie budget.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_BudgetSubForm extends FP_Form_common_SubForm
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
		
		$this->setDescription('Votre budget');
		
		$revenusReguliers = new Zend_Form_Element_Radio('revenusReguliers', array(
                'label'      => 'Avez-vous une source de revenus régulière ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true,
		        'value' => 1
		));
		
		$assumerFraisVeto = new Zend_Form_Element_Radio('assumerFraisVeto', array(
                'label'      => 'Pourrez-vous assumer les frais vétérinaires courants et les imprévus (maladie, blessure…) qui montent parfois très vite ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		));
		
		$this->addElement($revenusReguliers);
		$this->addElement($assumerFraisVeto);
	}
}