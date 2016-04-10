<?php
/**
 * Formulaire pour la fiche de chat.
 * @author Benjamin
 *
 */
class FP_Form_chat_lightForm extends FP_Form_common_Form {
	 
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

		$sexe = new Zend_Form_Element_Hidden('idSexe');
		       
		$dateNaissance = new Zend_Form_Element_Hidden('dateNaissance');
		
		$dateNaissanceApprox = new Zend_Form_Element_Hidden('dateApproximative');
		
		$datePriseEnCharge = new Zend_Form_Element_Hidden('datePriseEnCharge');
		
		$dateAdoption = new Zend_Form_Element_Hidden('dateAdoption');
		
		$dateContratAdoption = new Zend_Form_Element_Text('dateContratAdoption');
		$dateContratAdoption->setLabel('Date réception contrat adoption');
		$dateContratAdoption->setAttrib('data-dojo-type', "dijit/form/DateTextBox");
		
		$dateVaccins = new Zend_Form_Element_Hidden('dateRappelVaccins');
		
		$dateTests = new Zend_Form_Element_Hidden('dateTests');
		
		$dateSterilisation = new Zend_Form_Element_Text('dateSterilisation');
		$dateSterilisation->setLabel('Date de la stérilisation (prévue ou effectuée)');
		$dateSterilisation->setAttrib('data-dojo-type', "dijit/form/DateTextBox");

		$sterilise = new Zend_Form_Element_Checkbox('sterilise');
		$sterilise->setLabel('Stérilisé(e) ?');
		
		$dateAntiPuces = new Zend_Form_Element_Hidden('dateAntiPuces');
		
		$dateVermifuge = new Zend_Form_Element_Hidden('dateVermifuge');
		
		$race = new Zend_Form_Element_Hidden('race');
		
		$couleur = new Zend_Form_Element_Hidden('idCouleur');
		
		$yeux = new Zend_Form_Element_Hidden('yeux');
		
		$tests = new Zend_Form_Element_Hidden('tests');
		
		$vaccins = new Zend_Form_Element_Hidden('vaccins');
		
		$tatouage = new Zend_Form_Element_Text('tatouage');
		$tatouage->setLabel('Identification');
		$tatouage->setFilters(array('StringTrim'));

		$caractere = new Zend_Form_Element_Hidden('caractere');
		
		$commentaires = new Zend_Form_Element_Hidden('commentaires');
		
		$lienTopic = new Zend_Form_Element_Hidden('lienTopic');

		$miniature = new Zend_Form_Element_Hidden('miniature');
		
		$adopte = new Zend_Form_Element_Checkbox('adopte');
		$adopte->setLabel('Adopté(e)');

		$reserve = new Zend_Form_Element_Hidden('reserve');
		
		$parrain = new Zend_Form_Element_Hidden('parrain');
		
		$disparu = new Zend_Form_Element_Checkbox('disparu');
		$disparu->setLabel('Disparu');

		$avalider = new Zend_Form_Element_Hidden('aValider');
		
		$declarationCession = new Zend_Form_Element_Hidden('declarationCession');
		
		$papierIdRecu = new Zend_Form_Element_Hidden('papierIdRecu');
		
		/*$statutPostAdoption = new Zend_Form_Element_Checkbox('statutVisite');
		$statutPostAdoption->setLabel('Visite post-adoption');*/
                
                $statutPostAdoption = new Zend_Form_Element_Select('statutVisite');
		$statutPostAdoption->setLabel('Visite post-adoption');
		$statutPostAdoption->addMultiOptions(FP_Util_Constantes::$STATUT_VISITES);

                
                

		$realiseePar = new Zend_Form_Element_Text('visitePostPar');
		$realiseePar->setLabel('Visite post-adoption réalisée par');
		$realiseePar->setFilters(array('StringTrim'));

		$notesPrivees = new Zend_Form_Element_Textarea('notesPrivees');
		$notesPrivees->setLabel('Notes privées');
		$notesPrivees->setAttrib('cols', '60');
		$notesPrivees->setAttrib('rows', '5');

                $okChats = new Zend_Form_Element_Hidden('okChats');

                $okChiens = new Zend_Form_Element_Hidden('okChiens');

                $okApparts = new Zend_Form_Element_Hidden('okApparts');

                $okEnfants = new Zend_Form_Element_Hidden('okEnfants');

                $chgtProprio = new Zend_Form_Element_Checkbox('chgtProprio');
		$chgtProprio->setLabel('Changement de propriétaire fait ?');
		
		$postId = new Zend_Form_Element_Hidden('postId');
		
		$topicId = new Zend_Form_Element_Hidden('topicId');
		
		$idChat = new Zend_Form_Element_Hidden('id');

		
                $this->addElement($nom);
		$this->addElement($renomme);
                $this->addElement($dateContratAdoption);
                $this->addElement($dateSterilisation);
		$this->addElement($sterilise);
                $this->addElement($tatouage);
               
                $this->addElement($adopte);
                $this->addElement($disparu);
                $this->addElement($statutPostAdoption);
		$this->addElement($realiseePar);

		$this->addElement($chgtProprio);
                $this->addElement($notesPrivees);
                
             		// Add the submit button
		$this->addElement('submit', 'submit', array(
                                'ignore'   => true,
                                'label'    => 'Valider',
                                'class'    => 'btn btn-primary'
		));
                
      		$this->addElement($lienTopic);           
		$this->addElement($sexe);
		$this->addElement($dateNaissance);
		$this->addElement($dateNaissanceApprox);
		$this->addElement($datePriseEnCharge);
		$this->addElement($dateAdoption);
		$this->addElement($dateVaccins);
		$this->addElement($dateTests);
		$this->addElement($dateAntiPuces);
		$this->addElement($dateVermifuge);
		$this->addElement($race);
		$this->addElement($couleur);
		$this->addElement($yeux);
		$this->addElement($tests);
		$this->addElement($vaccins);
		$this->addElement($caractere);
		$this->addElement($commentaires);
                $this->addElement($okChats);
                $this->addElement($okChiens);
                $this->addElement($okApparts);
                $this->addElement($okEnfants);
		$this->addElement($miniature);
		$this->addElement($reserve);
		$this->addElement($parrain);
		
		$this->addElement($avalider);
		$this->addElement($declarationCession);		
		$this->addElement($papierIdRecu);
		
		$this->addElement($postId);
		$this->addElement($topicId);
		$this->addElement($idChat);


	}
	 
}