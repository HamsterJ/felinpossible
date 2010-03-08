<?php
/**
 * Sous-formulaire pour la partie Logement.
 * @author Benjamin
 *
 */
class FP_Form_common_LogementSubForm extends FP_Form_common_SubForm
{
	/**
	 * Construction du subForm.
	 *
	 * @param  mixed $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);

		$this->setDescription('Votre logement');
		
		// Ajout logement
		$listeLogements = new Zend_Form_Element_Select('idLogement');
		$listeLogements->setLabel('Logement');
		$listeLogements->setRequired(true);
		$listeLogements->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->logementMapper->buildArrayForForm());
		$listeLogements->setOrder(10);

		// Ajout étage
		$etage = new Zend_Form_Element_Text('etage', array(
                'label'      => 'Si c\'est un appartement, quel étage ?',
                'filters'    => array('StringTrim')));
		$etage->setOrder(20);
		
		$superficie = new Zend_Form_Element_Text('superficie', array(
                'label'      => 'Superficie (m²) ?',
                'filters'    => array('StringTrim')));
		$superficie->setOrder(25);
		
		// Ajout dépendances
		$listeDependances = new Zend_Form_Element_Select('idDependance');
		$listeDependances->setLabel('Dépendance');
		$listeDependances->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->dependanceMapper->buildArrayForForm());
		$listeDependances->setOrder(30);
		
		// Chatière
		$chatiere = new Zend_Form_Element_Radio('hasChatiere', array(
                'label'      => 'Avez-vous une chatière ?',
		        'required' => true,
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON));
		$chatiere->setOrder(40);
		
		
		$this->addElement($listeLogements);
		$this->addElement($etage);
		$this->addElement($superficie);
		$this->addElement($listeDependances);
		$this->addElement($chatiere);
		
	}

}