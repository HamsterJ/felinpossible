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
        
        // Ajout téléphone fixe
        $telFixe = new Zend_Form_Element_Text('telFixe');
        $telFixe->setLabel('Téléphone fixe');
        $telFixe->setFilters(array('StringTrim'));
        $telFixe->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());
        
        // Ajout téléphone portable
        $telPortable = new Zend_Form_Element_Text('telPortable');
        $telPortable->setLabel('Téléphone portable');
        $telPortable->setFilters(array('StringTrim'));
        $telPortable->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());
        
        // Ajout chat à parrainer
        $listeChats = new Zend_Form_Element_Select('chatAParrainer');
		$listeChats->setLabel('Chat à parrainer');
		$listeChats->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->chatMapper->getListChatsAParrainerForForm());
        
        // Ajout dons
        $listeDons = new Zend_Form_Element_Select('don');
		$listeDons->setLabel('Don');
		$listeDons->addMultiOptions(FP_Util_Constantes::$LISTE_DONS);
		$listeDons->setAttrib('onchange', 'updateDon()');
        
        $valeurDonElt = $this->createElementDonValeur();
        $valeurDonElt->setAttrib('disabled', 'true');
        
        $this->addElements(array(
        new Zend_Form_Element_Text('nom', array(
                'required'   => true,
                'label'      => 'Nom',
                'filters'    => array('StringTrim'),
        )),

        new Zend_Form_Element_Text('prenom', array(
                'required'   => true,
                'label'      => 'Prénom',
                'filters'    => array('StringTrim')
        )),
        
        new Zend_Form_Element_Text('adresse', array(
                'required'   => true,
                'label'      => 'Adresse',
                'filters'    => array('StringTrim')
        )),

        new Zend_Form_Element_Text('codePostal', array(
                'required'   => true,
                'label'      => 'Code Postal',
                'filters'    => array('StringTrim'),
                'validators' => array(
                    'Int',
        array('StringLength', false, array(5))
        )
        )),

        new Zend_Form_Element_Text('ville', array(
                'required'   => true,
                'label'      => 'Ville',
                'filters'    => array('StringTrim')
        )),

        $telFixe,
        $telPortable,

        new Zend_Form_Element_Text('email', array(
            'label'      => 'Email',
            'required'   => true,
            'size' => 50,
            'filters'    => array('StringTrim'),
            'validators' => array('EmailAddress')
        )),
        $listeChats,
        $listeDons,
        $valeurDonElt,
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Parrainer',
            'class'    => 'btn btn-primary'
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
    	$valeurDonElt = new Zend_Form_Element_Hidden('donValeur');
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