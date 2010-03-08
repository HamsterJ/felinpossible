<?php
/**
 * Sous-formulaire pour la partie conditions d'adoptions.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_ConditionsSubForm extends FP_Form_common_SubForm
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
		$this->setDescription('Vos engagements envers votre futur chat, êtes vous prêt ?');
		
		$this->addElements(array(
		new Zend_Form_Element_Radio('garderTouteSaVie', array(
                'label'      => 'Le garder toute sa vie (15 ans voire plus) et ne jamais l\'abandonner ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true,
		        'value' => 1
		)),
		new Zend_Form_Element_Radio('isSecurFenetres', array(
                'label'      => 'Sécuriser vos fenêtres/balcons avec un filet de protection ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('isSurvFenetres', array(
                'label'      => 'Si non, ne pas laisser le chat sans surveillance avec une fenêtre ou porte fenêtre-ouverte ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('contacterVeto', array(
                'label'      => 'Vous rendre chez le vétérinaire avec le chat en cas de blessure ou maladie ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('traiterParasites', array(
                'label'      => 'Le traiter contre les parasites (puces, vers etc...) en prévention régulièrement ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('rappelVaccins', array(
                'label'      => 'Lui faire ses rappels de vaccins chaque année ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('bonneAlimentation', array(
                'label'      => 'Donner une alimentation de qualité au chat (pas de croquettes bas de gamme) ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('steriliser', array(
                'label'      => 'Le stériliser dès qu\'il en aura l\'âge si c\'est un chaton ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('passerTempsAvecChat', array(
                'label'      => 'Passer chaque jour du temps avec le chat (jeux, câlins, brossage etc...) ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('garderLitierePropre', array(
                'label'      => 'Garder sa litière propre en la nettoyant chaque jour ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('signalerChangementAdr', array(
                'label'      => 'Signaler au fichier félin tout changement de vos coordonnées (adresse, téléphone etc) ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('donnerNouvelles', array(
                'label'      => 'Donner régulièrement des nouvelles à l\'association \'Félin Possible\' ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('accepterVisite', array(
                'label'      => 'Accepter une ou plusieurs visites de l\'association dans le cadre du suivi post-adoption \'Félin Possible\' ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('restituerChat', array(
                'label'      => 'Restituer le chat à l\'association \'Félin Possible\' en cas de séparation inévitable ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		))
		));
	}
}