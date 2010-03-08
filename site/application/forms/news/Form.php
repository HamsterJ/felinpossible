<?php
/**
 * Formulaire de création pour les news.
 * @author Benjamin
 *
 */
class FP_Form_news_Form extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('news');
        $this->setAttrib('class', 'formOrange');
        
        $titre = new Zend_Form_Element_Text('titre');
		$titre->setLabel('Titre');
		$titre->setRequired(true);
		$titre->setFilters(array('StringTrim'));

        $contenu = new Zend_Form_Element_Textarea('contenu');
		$contenu->setLabel('Contenu');
		$contenu->setRequired(true);
		$contenu->setAttrib('cols', '60');
		$contenu->setAttrib('rows', '20');
        
		$date = new Zend_Form_Element_Text('dateEvenement');
		$date->setLabel('Date de l\'événement');
		$date->setRequired(true);
		$date->setAttrib('dojoType', "dijit.form.DateTextBox");

        $idNews = new Zend_Form_Element_Hidden('id');
        
        $this->addElement($titre);
		$this->addElement($contenu);
		$this->addElement($date);
		$this->addElement($idNews);
                
		// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Valider',
        ));
    }
   
}