<?php
/**
 * Sous-formulaire pour la partie remarques.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_RemarquesSubForm extends FP_Form_common_SubForm
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

		$this->setDescription('Vos remarques');
		
		$remarques = new Zend_Form_Element_Textarea('remarques');
		$remarques->setLabel('Auriez vous d\'autres choses à nous indiquer auxquelles nous n\'aurions pas pensé ?');
		$remarques->setAttrib('cols', '60');
		$remarques->setAttrib('rows', '10');

		$connaissanceAsso = new Zend_Form_Element_Select('idConnaissanceAsso');
		$connaissanceAsso->setLabel('Question subsidiaire : comment avez vous connu notre association ?');
		$connaissanceAsso->setRequired(true);
		$connaissanceAsso->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->connaissanceAssoMapper->buildArrayForForm());
		
		$heureJoignable = new Zend_Form_Element_Text('heureJoignable');
		$heureJoignable->setLabel('Quelle est l\'heure maximum à laquelle nous pouvons vous rappeler en soirée sans vous déranger ?');
		$heureJoignable->setFilters(array('StringTrim'));
		
		$this->addElement($remarques);
		$this->addElement($connaissanceAsso);
		$this->addElement($heureJoignable);
	}

}