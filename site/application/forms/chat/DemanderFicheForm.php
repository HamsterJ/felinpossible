<?php
/**
 * Formulaire pour la fiche de chat.
 * @author mmo
 */
class FP_Form_chat_DemanderFicheForm extends FP_Form_common_Form {
   
    /**
     * (non-PHPdoc)
     * @see site/library/Zend/Zend_Form#init()
     */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('DemandeFicheSoins');
        $this->setAttrib('class', 'formOrange');
        
        $nom = new Zend_Form_Element_Text('nom');
        $nom->setLabel('Votre nom et prénom');
        $nom->setFilters(array('StringTrim'));

        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Votre pseudo sur le forum');
        $login->setFilters(array('StringTrim'));
        $login->setRequired(true);

        $nomChat = new Zend_Form_Element_Text('nomChat');
        $nomChat->setLabel('Nom du chat concerné');
        $nomChat->setFilters(array('StringTrim'));
        $nomChat->setRequired(true);

        $dateVisite = new Zend_Form_Element_Text('dateVisite');
        $dateVisite->setLabel('Date de la visite (format JJ/MM/AAAA)');
        $dateVisite->setValidators(array(new Zend_Validate_Date(array('format'=>'DD/MM/YYYY'))));
        
        $soinIdent = new Zend_Form_Element_Checkbox('soinIdent');
        $soinIdent->setLabel('Identification (puce électronique)');

        $soinVaccins = new Zend_Form_Element_Checkbox('soinVaccins');
        $soinVaccins->setLabel('Vaccination TCL ');

        $soinTests = new Zend_Form_Element_Checkbox('soinTests');
        $soinTests->setLabel('Tests FIV/FELV');

        $soinAntiParasites = new Zend_Form_Element_Checkbox('soinAntiParasites');
        $soinAntiParasites->setLabel('Anti-parasitaire externe');

        $soinVermifuge = new Zend_Form_Element_Checkbox('soinVermifuge');
        $soinVermifuge->setLabel('Vermifuge');

        $soinAutre = new Zend_Form_Element_Textarea('soinAutre');
        $soinAutre->setLabel('Commentaires (précisions sur les soins, autre(s) soin(s) à faire etc)');
        $soinAutre->setAttrib('cols', '80');
        $soinAutre->setAttrib('rows', '5');
        $soinAutre->setFilters(array('StringTrim'));

        $soinSterilisation = new Zend_Form_Element_Checkbox('soinSterilisation');
        $soinSterilisation->setLabel('Stérilisation');

        $veto = new Zend_Form_Element_Select('idVeto');
        $veto->setLabel('Vétérinaire');
        $veto->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->vetoMapper->buildArrayForForm());

        $vetoCompl = new Zend_Form_Element_Textarea('vetoCompl');
        $vetoCompl->setLabel('Coordonnées du vétérinaire (si non présent dans la liste ci-dessus)');
        $vetoCompl->setAttrib('cols', '80');
        $vetoCompl->setAttrib('rows', '3');
        $vetoCompl->setFilters(array('StringTrim'));

        $dateDemande = new Zend_Form_Element_Hidden('dateDemande');
        $dateDemande->setValue(date('d-m-y H:i:s'));
        
        $ficheGeneree = new Zend_Form_Element_Hidden('ficheGeneree');
        $ficheGeneree->setValue(0);
                	
        $this->addElement($nom);
        $this->addElement($login);
        $this->addElement($nomChat);
        $this->addElement($dateVisite);
        $this->addElement($veto);
        $this->addElement($vetoCompl);
        $this->addElement($soinIdent);
        $this->addElement($soinTests);
        $this->addElement($soinVaccins);
        $this->addElement($soinSterilisation);
        $this->addElement($soinVermifuge);
        $this->addElement($soinAntiParasites);
        $this->addElement($soinAutre);
        $this->addElement($dateDemande);
        $this->addElement($ficheGeneree);
                
	// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Envoyer la demande',
            'class'    => 'btn btn-primary'
        ));
    }
   
}