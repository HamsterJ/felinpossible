<?php
/**
 * Sous-formulaire pour la partie foyer.
 * @author Benjamin
 *
 */
class FP_Form_common_FoyerSubForm extends FP_Form_common_SubForm
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
		
		$this->setDescription('Votre foyer');
		
		// Ajout nb personnes
		$nbPersonne = new Zend_Form_Element_Text('nbPersonnes', array(
                'required'   => true,
                'label'      => 'Combien de personnes vivent dans votre foyer ?',
                'validators' => array('Int')));
		$nbPersonne->setOrder(10);
		
		// Ajout nb enfants
		$nbEnfants = new Zend_Form_Element_Text('nbEnfants', array(
                'label'      => 'Nombre d\'enfants dans votre foyer ?',
                'validators' => array('Int')));
		$nbEnfants->setOrder(20);
		
		// Ajout age enfants
		$ageEnfants = new Zend_Form_Element_Text('enfantsAge', array(
                'label'      => 'Age des enfants (séparés par une virgule)',
                'filters'    => array('StringTrim')));
		$ageEnfants->setOrder(30);
		
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		
		// Autres animaux foyers
		$animauxFoyer = new Zend_Form_Element_Textarea('animauxAutres');
		$animauxFoyer->setLabel('Les autres animaux de votre foyer (cliquez dans la zone blanche ci-dessous)');
		//$animauxFoyer->setAttrib('readonly', '');
		$animauxFoyer->setAttrib('cols', '70');
		$animauxFoyer->setAttrib('rows', '4');
		$animauxFoyer->setAttrib('onClick', 'javascript:popup(\''.$baseUrl.'/index/saisiranimal\',\'Saisir un animal\',\'width=450,height=440\')');
		$animauxFoyer->setOrder(40);
		
		// Parmi ces animaux s'il y a des chats
		$animauxChats = new Zend_Form_Element_Textarea('chats');
		$animauxChats->setLabel('Parmi ces animaux s\'il y a des chats (cliquez dans la zone blanche ci-dessous)');
		//$animauxFoyer->setAttrib('readonly', '');
		$animauxChats->setAttrib('cols', '70');
		$animauxChats->setAttrib('rows', '4');
		$animauxChats->setAttrib('onClick', 'javascript:popup(\''.$baseUrl.'/index/saisirchat\',\'Saisir un animal\',\'width=450,height=500\')');
		$animauxChats->setOrder(50);
		
		$this->addElement($nbPersonne);
		$this->addElement($nbEnfants);
		$this->addElement($ageEnfants);
		$this->addElement($animauxFoyer);
		$this->addElement($animauxChats);
	}

}