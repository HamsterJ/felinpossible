<?php
/**
 * Formulaire de parrainage.
 * @author Benjamin
 *
 */
class FP_Form_parrainage_Form extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('parrainage');
        $this->setAttrib('class', 'formOrange');
        
        // Ajout lastname
        $this->addElement('text', 'nom', array(
            'label'      => 'Nom',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        // Ajout firstname
        $this->addElement('text', 'prenom', array(
            'label'      => 'Prénom',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        // Ajout adresse
        $this->addElement('text', 'adresse', array(
            'label'      => 'Adresse',
            'filters'    => array('StringTrim')
        ));
        
        // Ajout code postal
        $this->addElement('text', 'codePostal', array(
            'label'      => 'Code Postal',
            'filters'    => array('StringTrim'),
            'validators' => array('Int')
        ));
        
        // Ajout ville
        $this->addElement('text', 'ville', array(
            'label'      => 'Ville',
            'filters'    => array('StringTrim')
        ));
        
        // Ajout téléphone fixe
        $telFixe = new Zend_Form_Element_Text('telFixe');
        $telFixe->setLabel('Téléphone fixe');
        $telFixe->setFilters(array('StringTrim'));
        $telFixe->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());
        $this->addElement($telFixe);
        
        // Ajout téléphone portable
        $telPortable = new Zend_Form_Element_Text('telPortable');
        $telPortable->setLabel('Téléphone portable');
        $telPortable->setFilters(array('StringTrim'));
        $telPortable->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());
        $this->addElement($telPortable);
        
        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email',
            'required'   => true,
            'size' => 50,
            'filters'    => array('StringTrim'),
            'validators' => array('EmailAddress')
        ));
        
        // Ajout chat à parrainer
        $listeChats = new Zend_Form_Element_Select('chatAParrainer');
		$listeChats->setLabel('Chat à parrainer');
		$listeChats->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->chatMapper->getListChatsAParrainerForForm());
        $this->addElement($listeChats);
        
        // Ajout dons
        $listeDons = new Zend_Form_Element_Select('don');
		$listeDons->setLabel('Don');
		$listeDons->addMultiOptions(FP_Util_Constantes::$LISTE_DONS);
		$listeDons->setAttrib('onchange', 'updateDon()');
        $this->addElement($listeDons);
        
        $valeurDonElt = $this->createElementDonValeur();
        $valeurDonElt->setAttrib('disabled', 'true');
        $this->addElement($valeurDonElt);
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Parrainer',
        ));
    }
    /**
     * (non-PHPdoc)
     * @see site/library/Zend/Zend_Form#isValid($data)
     */
    public function isValid($data) {
    	$result = parent::isValid($data);

    	// "Autres" sélectionné pour les dons
    	if ($data['don'] == 0) {
    		$valeurDonElt = $this->getElement('donValeur');
    		$valeurDonElt->setAttrib('disabled', null);
    	} else {
    		$this->getElement('donValeur')->setValue($this->getElement('don')->getValue());
    	}
    	
    	if (!$data['telPortable'] && !$data['telFixe']) {
    		$this->getElement('telPortable')->addError('Un des numéros de téléphone doit être rempli.');
    		$result = false;
    	}
    	
        if (array_key_exists('donValeur', $data) && !$data['donValeur']) {
    		$this->getElement('donValeur')->addError('La valeur du don doit être remplie.');
    		$result = false;
    	}
    	return $result;
    }
    
    /**
     * Créé l'élément pour la valeur du don.
     * @return Zend_Form_Element_Text
     */
    private function createElementDonValeur() {
    	$valeurDonElt = new Zend_Form_Element_Text('donValeur');
        $valeurDonElt->setValue(15);
        $valeurDonElt->setValidators(array('Int'));
        return $valeurDonElt;
    }
    
    /**
     * Affiche le formulaire.
     * @return string
     */
    public function toHtml() {
    	$result = '';
    	foreach ($this->getElements() as $element) {
    		if ($element->getId() == 'don' && $element->getValue() == 0) {
    			$result = $result."<b>Don</b> : ".$this->getElement('donValeur')->getValue()."<br>";
    			continue;
    		}
    		
    		if ($element->getId() == 'donValeur' || $element->getId() == 'submit') {
    			continue;
    		} else if ($element->getId() == 'chatAParrainer') {
    			$result = $result.'<b>'.$element->getLabel().'</b> : '.$element->getMultiOption($element->getValue()).'<br>';
    			continue;
    		}
    		
    		$result = $result.'<b>'.$element->getLabel().'</b> : '.$element->getValue().'<br>';
    	}
    	return $result;
    }
}