<?php
/**
 * Sous-formulaire pour la partie Motivations.
 * @author Benjamin
 *
 */
class FP_Form_fa_MotivationSubForm extends FP_Form_common_SubForm
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

		$this->setDescription('Vos motivations');
		
		// Motivation
		$motivation = new Zend_Form_Element_Textarea('motivations');
		$motivation->setRequired(true);
		$motivation->setLabel('Quelles sont vos motivations pour devenir FA ?');
		$motivation->setAttrib('cols', '60');
		$motivation->setAttrib('rows', '10');

		$this->addElement($motivation);
	}

}