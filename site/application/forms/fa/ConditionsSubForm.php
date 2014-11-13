<?php
/**
 * Sous-formulaire pour la partie conditions.
 * @author Benjamin
 *
 */
class FP_Form_fa_ConditionsSubForm extends FP_Form_common_SubForm
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

		$this->setDescription('Etes-vous prêts ?');

		$statutId = new Zend_Form_Element_Hidden('statutId');
		$statutId->setValue(FP_Util_Constantes::FA_CANDIDATURE_STATUT);
		
		$this->addElements(array(
		new Zend_Form_Element_Radio('isSecurFenetres', array(
                'label'      => 'Sécuriser vos fenêtres/balcons avec un filet de protection ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true,
		        'value' => 1
		)),
		new Zend_Form_Element_Radio('isSurvFenetres', array(
                'label'      => 'Si non, ne pas laisser le chat sans surveillance avec une fenetre ou porte fenetre-ouverte ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('contacterVeto', array(
                'label'      => 'Vous rendre chez le vétérinaire avec le chat (à nos frais) en cas de blessure ou maladie ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('contacterAssociation', array(
                'label'      => 'Nous contacter immédiatement en cas de problème ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('patienceAvecChat', array(
                'label'      => 'Faire preuve de patience pour sociabiliser un chat craintif ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('jouerAvecChat', array(
                'label'      => 'Passer régulièrement du temps avec le chat ou les chats accueillis ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('fournirCroquettes', array(
                'label'      => 'Fournir les croquettes et la litière au chat en accueil ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('accueillirMere', array(
                'label'      => 'Accueillir une mère avec ses chatons ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('biberonnerChatons', array(
                'label'      => 'Biberonner un ou des chats (temps de présence important) ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('accueillirChatons', array(
                'label'      => 'Accueillir plusieurs chats ou chatons à la fois ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('accueillirChatFiv', array(
                'label'      => 'Accuellir un chat FIV+ ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('accueillirChatFelv', array(
                'label'      => 'Accuellir un chat FELV+ ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('donnerSoins', array(
                'label'      => 'Prodiguer des soins simples au chat (mettre une pommade, donner un comprimé) ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('mettreChatQuarantaine', array(
                'label'      => 'Mettre le chat en quarantaine pendant deux semaines à son arrivée ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		new Zend_Form_Element_Radio('isolerChat', array(
                'label'      => 'Isoler un chat plus longtemps si son état le nécessite ?',
		        'multiOptions' => FP_Util_Constantes::$LISTE_OUI_NON,
		        'required' => true
		)),
		$statutId
		));
	}

}