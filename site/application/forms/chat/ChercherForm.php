<?php
/**
 * Formulaire pour la recherche de chat.
 * @author FlickFlack
 *
 */
class FP_Form_chat_ChercherForm extends FP_Form_common_Form {
	 
	/**
	 * Surcharge de l'init de form Zend
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Init du formulaire (methode POST) : SEXE, OK_CHAT, OK_CHIEN, OK_ENFANT, OK_APPART
		$this->setMethod('post');
		$this->setName('chercher');

		$idSexe = new Zend_Form_Element_Select('idSexe');
		$idSexe->setLabel('De sexe');
		$idSexe->addMultiOptions(array( '0'                                 => 'Indifférent',
                                                FP_Util_Constantes::ID_SEXE_MALE    => 'Mâle',
                                                FP_Util_Constantes::ID_SEXE_FEMELLE => 'Femelle'));

                $okChats = new Zend_Form_Element_Select('okChats');
		$okChats->setLabel('Habitué aux chats');
		$okChats->addMultiOptions(array('0' => 'Indifférent',
                                                '1' => 'Oui'));
                
                $okChiens = new Zend_Form_Element_Select('okChiens');
		$okChiens->setLabel('Habitué aux chiens');
		$okChiens->addMultiOptions(array('0' => 'Indifférent',
                                                 '1' => 'Oui'));
                
                $okEnfants = new Zend_Form_Element_Select('okEnfants');
		$okEnfants->setLabel('Habitué aux enfants');
		$okEnfants->addMultiOptions(array(  '0' => 'Indifférent',
                                                    '1' => 'Oui'));
                
                $okApparts = new Zend_Form_Element_Select('okApparts');
		$okApparts->setLabel('Compatible appartements');
		$okApparts->addMultiOptions(array(   '0' => 'Indifférent',
                                                    '1' => 'Oui'));

		$this->addElement($idSexe);
                $this->addElement($okChats);
                $this->addElement($okChiens);
                $this->addElement($okEnfants);
		$this->addElement($okApparts);

		$this->addElement('submit', 'submit', array(
                'ignore'   => true,
                'label'    => 'Valider',
                'class'    => 'btn btn-primary'
		));
	} 
}