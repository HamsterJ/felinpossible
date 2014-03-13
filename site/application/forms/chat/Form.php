<?php
/**
 * Formulaire pour la fiche de chat.
 * @author Benjamin
 *
 */
class FP_Form_chat_Form extends FP_Form_common_Form {
	 
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setName('chat');
		$this->setAttrib('class', 'formOrange');

		$nom = new Zend_Form_Element_Text('nom');
		$nom->setLabel('Nom');
		$nom->setRequired(true);
		$nom->setFilters(array('StringTrim'));

		$renomme = new Zend_Form_Element_Text('renomme');
		$renomme->setLabel('Renommé');
		$renomme->setFilters(array('StringTrim'));

		$sexe = new Zend_Form_Element_Select('idSexe');
		$sexe->setLabel('Sexe');
		$sexe->setRequired(true);
		$sexe->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->sexeMapper->buildArrayForForm());

		$dateNaissance = new Zend_Form_Element_Text('dateNaissance');
		$dateNaissance->setLabel('Date de naissance');
		$dateNaissance->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateNaissanceApprox = new Zend_Form_Element_Checkbox('dateApproximative');
		$dateNaissanceApprox->setLabel('Date de naissance approximative ?');

		$datePriseEnCharge = new Zend_Form_Element_Text('datePriseEnCharge');
		$datePriseEnCharge->setLabel('Date de prise en charge');
		$datePriseEnCharge->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateAdoption = new Zend_Form_Element_Text('dateAdoption');
		$dateAdoption->setLabel('Date d\'adoption (calcul auto)');
		$dateAdoption->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateContratAdoption = new Zend_Form_Element_Text('dateContratAdoption');
		$dateContratAdoption->setLabel('Date réception contrat adoption');
		$dateContratAdoption->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateVaccins = new Zend_Form_Element_Text('dateRappelVaccins');
		$dateVaccins->setLabel('Date du prochain rappel de vaccins');
		$dateVaccins->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateTests = new Zend_Form_Element_Text('dateTests');
		$dateTests->setLabel('Date des derniers tests');
		$dateTests->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateSterilisation = new Zend_Form_Element_Text('dateSterilisation');
		$dateSterilisation->setLabel('Date de la stérilisation (prévue ou effectuée)');
		$dateSterilisation->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$sterilise = new Zend_Form_Element_Checkbox('sterilise');
		$sterilise->setLabel('Stérilisé(e) ?');

		$dateAntiPuces = new Zend_Form_Element_Text('dateAntiPuces');
		$dateAntiPuces->setLabel('Date anti-puces');
		$dateAntiPuces->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$dateVermifuge = new Zend_Form_Element_Text('dateVermifuge');
		$dateVermifuge->setLabel('Date vermifuge');
		$dateVermifuge->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$race = new Zend_Form_Element_Text('race');
		$race->setLabel('Race');
		$race->setFilters(array('StringTrim'));

		$couleur = new Zend_Form_Element_Select('idCouleur');
		$couleur->setLabel('Couleur');
		$couleur->setRequired(true);
		$couleur->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->couleurMapper->buildArrayForForm());

		$yeux = new Zend_Form_Element_Text('yeux');
		$yeux->setLabel('Yeux');
		$yeux->setFilters(array('StringTrim'));

		$tests = new Zend_Form_Element_Text('tests');
		$tests->setLabel('Tests');
		$tests->setFilters(array('StringTrim'));

		$vaccins = new Zend_Form_Element_Text('vaccins');
		$vaccins->setLabel('Vaccins');
		$vaccins->setFilters(array('StringTrim'));

		$tatouage = new Zend_Form_Element_Text('tatouage');
		$tatouage->setLabel('Identification');
		$tatouage->setFilters(array('StringTrim'));

		$caractere = new Zend_Form_Element_Textarea('caractere');
		$caractere->setLabel('Caractère');
		$caractere->setAttrib('cols', '60');
		$caractere->setAttrib('rows', '5');
		$caractere->setFilters(array('StringTrim'));

		$commentaires = new Zend_Form_Element_Textarea('commentaires');
		$commentaires->setLabel('Commentaires');
		$commentaires->setAttrib('cols', '60');
		$commentaires->setAttrib('rows', '5');

		$lienTopic = new Zend_Form_Element_Text('lienTopic');
		$lienTopic->setLabel('Topic');
		$lienTopic->setOptions(array('class' => 'input-xxlarge'));
		$lienTopic->setFilters(array('StringTrim'));

		$miniature = new Zend_Form_Element_Text('miniature');
		$miniature->setLabel('Miniature');
		$miniature->setOptions(array('class' => 'input-xxlarge'));
		$miniature->setFilters(array('StringTrim'));

		$adopte = new Zend_Form_Element_Checkbox('adopte');
		$adopte->setLabel('Adopté(e)');

		$reserve = new Zend_Form_Element_Checkbox('reserve');
		$reserve->setLabel('Réservé(e)');

		$parrain = new Zend_Form_Element_Checkbox('parrain');
		$parrain->setLabel('Parrainage');

		$disparu = new Zend_Form_Element_Checkbox('disparu');
		$disparu->setLabel('Disparu');

		$avalider = new Zend_Form_Element_Checkbox('aValider');
		$avalider->setLabel('Non visible sur le site');

		$declarationCession = new Zend_Form_Element_Checkbox('declarationCession');
		$declarationCession->setLabel('Déclaration de cession');

		$papierIdRecu = new Zend_Form_Element_Checkbox('papierIdRecu');
		$papierIdRecu->setLabel('Papier d\'identification reçu ?');

		$statutPostAdoption = new Zend_Form_Element_Checkbox('statutVisite');
		$statutPostAdoption->setLabel('Visite post-adoption');

		$realiseePar = new Zend_Form_Element_Text('visitePostPar');
		$realiseePar->setLabel('Visite post-adoption réalisée par');
		$realiseePar->setFilters(array('StringTrim'));

		$notesPrivees = new Zend_Form_Element_Textarea('notesPrivees');
		$notesPrivees->setLabel('Notes privées');
		$notesPrivees->setAttrib('cols', '60');
		$notesPrivees->setAttrib('rows', '5');

        $okChats = new Zend_Form_Element_Select('okChats');
		$okChats->setLabel('OK chats');
		$okChats->addMultiOptions(array(   '0' => 'Indéterminé',
                                                    '1' => 'Oui',
                                                    '2' => 'Non'));
                
        $okChiens = new Zend_Form_Element_Select('okChiens');
		$okChiens->setLabel('OK chiens');
		$okChiens->addMultiOptions(array(   '0' => 'Indéterminé',
                                                    '1' => 'Oui',
                                                    '2' => 'Non'));
               
        $okApparts = new Zend_Form_Element_Select('okApparts');
		$okApparts->setLabel('OK appart');
		$okApparts->addMultiOptions(array(   '0' => 'Indéterminé',
                                                    '1' => 'Oui',
                                                    '2' => 'Non'));
                
        $okEnfants = new Zend_Form_Element_Select('okEnfants');
		$okEnfants->setLabel('OK enfants');
		$okEnfants->addMultiOptions(array(   '0' => 'Indéterminé',
                                                    '1' => 'Oui',
                                                    '2' => 'Non'));
        
        $chgtProprio = new Zend_Form_Element_Checkbox('chgtProprio');
		$chgtProprio->setLabel('Changement de propriétaire fait ?');
		
		$postId = new Zend_Form_Element_Hidden('postId');
		
		$topicId = new Zend_Form_Element_Hidden('topicId');
		
		$idChat = new Zend_Form_Element_Hidden('id');

		$this->addElement($nom);
		$this->addElement($renomme);
		$this->addElement($sexe);
		$this->addElement($dateNaissance);
		$this->addElement($dateNaissanceApprox);
		$this->addElement($datePriseEnCharge);
		$this->addElement($dateAdoption);
		$this->addElement($dateContratAdoption);
		$this->addElement($dateVaccins);
		$this->addElement($dateTests);
		$this->addElement($dateSterilisation);
		$this->addElement($sterilise);
		$this->addElement($dateAntiPuces);
		$this->addElement($dateVermifuge);
		$this->addElement($race);
		$this->addElement($couleur);
		$this->addElement($yeux);
		$this->addElement($tests);
		$this->addElement($vaccins);
		$this->addElement($tatouage);
		$this->addElement($caractere);
		$this->addElement($commentaires);
        $this->addElement($okChats);
        $this->addElement($okChiens);
        $this->addElement($okApparts);
        $this->addElement($okEnfants);
		$this->addElement($lienTopic);
		$this->addElement($miniature);
		$this->addElement($adopte);
		$this->addElement($reserve);
		$this->addElement($parrain);
		$this->addElement($disparu);
		$this->addElement($avalider);
		$this->addElement($statutPostAdoption);
		$this->addElement($realiseePar);
		$this->addElement($declarationCession);
		$this->addElement($chgtProprio);
		$this->addElement($papierIdRecu);
		$this->addElement($notesPrivees);
		$this->addElement($postId);
		$this->addElement($topicId);
		$this->addElement($idChat);

		// Add the submit button
		$this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Valider',
            'class'    => 'btn btn-primary'
		));
	}
	 
}