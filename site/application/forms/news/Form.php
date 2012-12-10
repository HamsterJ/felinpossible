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

		$titre = new Zend_Form_Element_Text('titre');
		$titre->setLabel('Titre');
		$titre->setRequired(true);
		$titre->setOptions(array('class' => 'input-xxlarge'));
		$titre->setFilters(array('StringTrim'));

		$contenu = new Zend_Form_Element_Textarea('contenu');
		$contenu->setLabel('Contenu');
		$contenu->setRequired(true);
		$contenu->setAttrib('cols', '80');
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

		$submit = new Zend_Form_Element_Submit(
			'submit',
			array(
				'label'    => 'Valider',
				'required' => false,
				'ignore'   => true,
				'order' => 100,
				'class' => 'btn btn-primary'
				)
			);
		// Add the submit button
		$this->addElement($submit);
	}

}