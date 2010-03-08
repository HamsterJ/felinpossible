<?php
/**
 * Sous-formulaire pour la partie Identité.
 * @author Benjamin
 *
 */
class FP_Form_common_IdentificationSubForm extends FP_Form_common_SubForm
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
		$this->setDescription('Identité');

		// Elément téléphone fixe
		$telFixe = new FP_Form_common_ElementText('telephoneFixe');
		$telFixe->setLabel('Téléphone fixe');
		$telFixe->setFilters(array('StringTrim'));
		$telFixe->setRequired(true);
		$telFixe->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());

		// Elément téléphone portable
		$telPortable = new FP_Form_common_ElementText('telephonePortable');
		$telPortable->setLabel('Téléphone portable');
		$telPortable->setFilters(array('StringTrim'));
		$telPortable->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());


		$dateSubmit = new Zend_Form_Element_Hidden('dateSubmit');
		$dateCourante = new Zend_Date();
		$dateSubmit->setValue($dateCourante->toString(FP_Util_Constantes::DATE_FORMAT_MYSQL));
		
		$this->addElements(array(
		new Zend_Form_Element_Text('nom', array(
                'required'   => true,
                'label'      => 'Nom',
                'filters'    => array('StringTrim'),
		)),

		new Zend_Form_Element_Text('prenom', array(
                'required'   => true,
                'label'      => 'Prénom',
                'filters'    => array('StringTrim')
		)),

		new Zend_Form_Element_Text('login', array(
                'label'      => 'Login sur le forum (si vous êtes déjà inscrit)',
                'filters'    => array('StringTrim')
		)),
		
		new Zend_Form_Element_Text('adresse', array(
                'required'   => true,
                'label'      => 'Adresse',
                'filters'    => array('StringTrim')
		)),

		new Zend_Form_Element_Text('codePostal', array(
                'required'   => true,
                'label'      => 'Code Postal',
                'filters'    => array('StringTrim'),
                'validators' => array(
                    'Int',
		array('StringLength', false, array(5))
		)
		)),

		new Zend_Form_Element_Text('ville', array(
                'required'   => true,
                'label'      => 'Ville',
                'filters'    => array('StringTrim')
		)),

		$telFixe,
		$telPortable,

		new Zend_Form_Element_Text('email', array(
            'label'      => 'Email',
            'required'   => true,
		    'size' => 50,
            'filters'    => array('StringTrim'),
            'validators' => array('EmailAddress')
		)),

		new Zend_Form_Element_Hidden('id'),
		$dateSubmit,
		));
	}

}
