<?php
/**
 * Sous-formulaire pour la partie Foyer.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_FoyerSubForm extends FP_Form_common_FoyerSubForm
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
		
		$age = new Zend_Form_Element_Text('age', array(
                'label'      => 'Votre âge',
		        'required' => true,
		        'validators' => array('Int'),
                'filters'    => array('StringTrim')));
		$age->setOrder(15);
		
		$profession = new Zend_Form_Element_Text('profession', array(
                'label'      => 'Votre profession',
		        'required' => false,
                'filters'    => array('StringTrim')));
		$profession->setOrder(16);
		
		$habitudeChat = new Zend_Form_Element_Radio('habitudeChat', array(
                'label'      => 'Vos animaux ont-ils l\'habitude des chats ?',
		        'required' => true,
                'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON
		));
		$habitudeChat->setOrder(55);
		
		$heuresDansFoyer = new Zend_Form_Element_Text('heuresDansFoyer', array(
                'label'      => 'Combien d\'heures par jour êtes-vous présent à votre domicile ?',
		        'required' => true,
		        'validators' => array('Int'),
                'filters'    => array('StringTrim')));
		$heuresDansFoyer->setOrder(60);
		
		$allergies = new Zend_Form_Element_Radio('personnesAllergiques', array(
                'label'      => 'Y a-t-il des personnes allergiques dans votre foyer ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		));
		$allergies->setOrder(70);
		
		$personnesDesirantPasChat = new Zend_Form_Element_Radio('personnesDesirantPasChat', array(
                'label'      => 'Y a-t-il des personnes qui ne désirent pas de chat ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		));
		$personnesDesirantPasChat->setOrder(80);
		
		$this->addElement($age);
		$this->addElement($profession);
		$this->addElement($habitudeChat);
		$this->addElement($heuresDansFoyer);
		$this->addElement($allergies);
		$this->addElement($personnesDesirantPasChat);
	}

}