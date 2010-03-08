<?php
/**
 * Formulaire pour l'envoi de mail.
 * @author Benjamin
 *
 */
class FP_Form_common_MailForm extends FP_Form_common_Form {
	 
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setName('mail');
		$this->setAttrib('class', 'formOrange');

		$dest = new Zend_Form_Element_Text('destinataire');
		$dest->setLabel('Destinataire');
		$dest->setRequired(true);
		$dest->setAttrib('size', 70);
		$dest->setValidators(array('EmailAddress'));
		$dest->setFilters(array('StringTrim'));

		$copy = new Zend_Form_Element_Text('copy');
		$copy->setLabel('Cc');
		$copy->setAttrib('size', 70);
		$copy->setValidators(array('EmailAddress'));
		$copy->setFilters(array('StringTrim'));
		
		$sujet = new Zend_Form_Element_Text('sujet');
		$sujet->setLabel('Sujet');
		$sujet->setAttrib('size', 70);
		$sujet->setFilters(array('StringTrim'));

		$contenu = new Zend_Form_Element_Textarea('contenu');
		$contenu->setLabel('Contenu');
		$contenu->setAttrib('cols', '60');
		$contenu->setAttrib('rows', '10');

		$idChat = new Zend_Form_Element_Hidden('id');
		
		$this->addElement($dest);
		$this->addElement($copy);
		$this->addElement($sujet);
		$this->addElement($contenu);
		$this->addElement($idChat);
		 
		// Add the submit button
		$this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Envoyer le mail',
		));
	}
	 
}