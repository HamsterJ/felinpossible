<?php
/**
 * Formulaire pour la fiche de chat.
 * @author Benjamin
 *
 */
class FP_Form_chat_FicheSoinsForm extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('ficheSoins');
        $this->setAttrib('class', 'formOrange');
        
        $nom = new Zend_Form_Element_Text('nom');
        $nom->setLabel('Nom');
        $nom->setFilters(array('StringTrim'));

        $qualite = new Zend_Form_Element_Text('qualite');
        $qualite->setLabel('Qualité');
        $qualite->setFilters(array('StringTrim'));

        $adresse = new Zend_Form_Element_Text('adresse');
        $adresse->setLabel('Adresse');
        $adresse->setFilters(array('StringTrim'));

        $codePostal = new Zend_Form_Element_Text('codePostal');
        $codePostal->setLabel('Code Postal');
        $codePostal->setFilters(array('StringTrim'));

        $ville = new Zend_Form_Element_Text('ville');
        $ville->setLabel('Ville');
        $ville->setFilters(array('StringTrim'));

        $telFixe = new Zend_Form_Element_Text('telephoneFixe');
        $telFixe->setLabel('Téléphone fixe');
        $telFixe->setFilters(array('StringTrim'));

        $telMobile = new Zend_Form_Element_Text('telephonePortable');
        $telMobile->setLabel('Téléphone portable');
        $telMobile->setFilters(array('StringTrim'));

        $nomChat = new Zend_Form_Element_Text('nomChat');
        $nomChat->setLabel('Nom du chat');
        $nomChat->setFilters(array('StringTrim'));

        $couleurChat = new Zend_Form_Element_Text('couleur');
        $couleurChat->setLabel('Couleur du chat');
        $couleurChat->setFilters(array('StringTrim'));

        $identification = new Zend_Form_Element_Text('identification');
        $identification->setLabel('Identification');
        $identification->setFilters(array('StringTrim'));

        $dateNaissance = new Zend_Form_Element_Text('dateNaissance');
        $dateNaissance->setLabel('Date de naissance');
        $dateNaissance->setFilters(array('StringTrim'));

        $dateApprox = new Zend_Form_Element_Checkbox('dateNaissanceApprox');
        $dateApprox->setLabel('Date de naissance approximative');

        $sexe = new Zend_Form_Element_Text('sexe');
        $sexe->setLabel('Sexe');
        $sexe->setFilters(array('StringTrim'));

        $soinPuce = new Zend_Form_Element_Checkbox('soinPuce');
        $soinPuce->setLabel('Identification (puce)');

        $soinTatouage = new Zend_Form_Element_Checkbox('soinTatouage');
        $soinTatouage->setLabel('Identification (tatouage)');

        $soinVaccins = new Zend_Form_Element_Checkbox('soinVaccins');
        $soinVaccins->setLabel('Vaccins TCL');

        $soinTests = new Zend_Form_Element_Checkbox('soinTests');
        $soinTests->setLabel('Tests FIV/FELV');

        $soinAntiParasites = new Zend_Form_Element_Checkbox('soinAntiParasites');
        $soinAntiParasites->setLabel('Anti-parasitaire externe');

        $soinVermifuge = new Zend_Form_Element_Checkbox('soinVermifuge');
        $soinVermifuge->setLabel('Vermifuge');

        $soinAutre = new Zend_Form_Element_Textarea('soinAutre');
        $soinAutre->setLabel('Commentaires (précisions sur les soins, autre(s) soin(s) à faire etc)');
        $soinAutre->setAttrib('cols', '60');
        $soinAutre->setAttrib('rows', '8');
        $soinAutre->setAttrib('maxlength','1000');
        $soinAutre->setFilters(array('StringTrim'));

        $soinSterilisation = new Zend_Form_Element_Select('soinSterilisation');
        $soinSterilisation->setLabel('Stérilisation');
        $soinSterilisation->addMultiOptions(FP_Util_Constantes::$LISTE_STERILISATION);

        $veto = new Zend_Form_Element_Select('idVeto');
        $veto->setLabel('Vétérinaire');
        $veto->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper->buildArrayForForm());
       
        $idChat = new Zend_Form_Element_Hidden('id');
        
        $tokenDemande = new Zend_Form_Element_Hidden('token'); //Identifiant codé d'une demande de fiche de soins d'origine
        $envoiVeto = new Zend_Form_Element_Hidden('envoiVeto'); 
        
        $this->addElement($tokenDemande); 
        $this->addElement($nom);
        $this->addElement($qualite);
        $this->addElement($adresse);
        $this->addElement($ville);
        $this->addElement($codePostal);
        $this->addElement($telFixe);
        $this->addElement($telMobile);
        
        $this->addElement($idChat);
        $this->addElement($nomChat);
        $this->addElement($couleurChat);
        $this->addElement($identification);
        $this->addElement($dateNaissance);
        $this->addElement($dateApprox);
        $this->addElement($sexe);
        $this->addElement($veto);
        $this->addElement($soinPuce);
        $this->addElement($soinTatouage);
        $this->addElement($soinTests);
        $this->addElement($soinVaccins);
        $this->addElement($soinSterilisation);
        $this->addElement($soinVermifuge);
        $this->addElement($soinAntiParasites);
        $this->addElement($soinAutre);
        $this->addElement($envoiVeto);   
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Générer la fiche',
            'class'    => 'btn btn-primary'
        )); 
    }
   
}