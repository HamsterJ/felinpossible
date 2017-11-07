<?php
/**
 * Formulaire pour l'inscription sur la liste rouge de non-sollicitation par mail.
 * @author MMO
 *
 */
class FP_Form_common_UnsubscribeForm extends FP_Form_common_Form {
	 
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setName('unsubscribe');
		$this->setAttrib('class', 'formOrange');

		$dest = new Zend_Form_Element_Text('mail');
		$dest->setLabel('Votre adresse mail');
		$dest->setRequired(true);
		$dest->setAttrib('size', 100);
		$dest->setValidators(array('EmailAddress'));
		$dest->setFilters(array('StringTrim'));
		
		$this->addElement($dest);

		 
		// Add the submit button
		$this->addElement('submit', 'submit', array(
                    'ignore'   => true,
                    'label'    => 'Me désinscrire',
		));
	}
	 
}