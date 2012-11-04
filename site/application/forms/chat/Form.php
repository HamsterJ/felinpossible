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
		$dateNaissance->setRequired(true);
		$dateNaissance->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateNaissanceApprox = new Zend_Form_Element_Checkbox('dateApproximative');
		$dateNaissanceApprox->setLabel('Date de naissance approximative ?');

		$datePriseEnCharge = new Zend_Form_Element_Text('datePriseEnCharge');
		$datePriseEnCharge->setLabel('Date de prise en charge');
		$datePriseEnCharge->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateAdoption = new Zend_Form_Element_Text('dateAdoption');
		$dateAdoption->setLabel('Date d\'adoption (calcul auto)');
		$dateAdoption->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateContratAdoption = new Zend_Form_Element_Text('dateContratAdoption');
		$dateContratAdoption->setLabel('Date réception contrat adoption');
		$dateContratAdoption->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateVaccins = new Zend_Form_Element_Text('dateRappelVaccins');
		$dateVaccins->setLabel('Date du prochain rappel de vaccins');
		$dateVaccins->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateTests = new Zend_Form_Element_Text('dateTests');
		$dateTests->setLabel('Date des derniers tests');
		$dateTests->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateSterilisation = new Zend_Form_Element_Text('dateSterilisation');
		$dateSterilisation->setLabel('Date de la stérilisation (prévue ou effectuée)');
		$dateSterilisation->setAttrib('dojoType', "dijit.form.DateTextBox");

		$sterilise = new Zend_Form_Element_Checkbox('sterilise');
		$sterilise->setLabel('Stérilisé(e) ?');

		$dateAntiPuces = new Zend_Form_Element_Text('dateAntiPuces');
		$dateAntiPuces->setLabel('Date anti-puces');
		$dateAntiPuces->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateVermifuge = new Zend_Form_Element_Text('dateVermifuge');
		$dateVermifuge->setLabel('Date vermifuge');
		$dateVermifuge->setAttrib('dojoType', "dijit.form.DateTextBox");

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
		$lienTopic->setAttrib('size', 60);
		$lienTopic->setFilters(array('StringTrim'));

		$miniature = new Zend_Form_Element_Text('miniature');
		$miniature->setLabel('Miniature');
		$miniature->setAttrib('size', 60);
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

		$postId = new Zend_Form_Element_Text('postId');
		$postId->setLabel('Post id (calcul auto)');
		$postId->setFilters(array('Int'));

		$topicId = new Zend_Form_Element_Text('topicId');
		$topicId->setLabel('Topic id (calcul auto)');
		$topicId->setFilters(array('Int'));

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