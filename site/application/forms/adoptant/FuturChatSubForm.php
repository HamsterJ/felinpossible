<?php
/**
 * Sous-formulaire pour la partie "Votre futur chat"
 * @author Benjamin
 *
 */
class FP_Form_adoptant_FuturChatSubForm extends FP_Form_common_SubForm
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

		$this->setDescription('Votre futur chat');
		
		$motivation = new Zend_Form_Element_Textarea('motivations');
		$motivation->setRequired(true);
		$motivation->setLabel('Pourquoi vouloir adopter un chat ? Quel caractère aimeriez-vous pour votre futur chat ?');
		$motivation->setAttrib('cols', '60');
		$motivation->setAttrib('rows', '10');

		$criteres = new Zend_Form_Element_Textarea('criteres');
		$criteres->setLabel('Avez-vous des critères de recherche particuliers au niveau du physique (couleur de la robe, longueur des poils, race etc) ?');
		$criteres->setAttrib('cols', '60');
		$criteres->setAttrib('rows', '4');
		
		$repererChats = new Zend_Form_Element_Textarea('repererChat');
		$repererChats->setLabel('Avez-vous déjà repéré un ou plusieurs chats sur le site de l’association qui pourraient vous convenir ? Si oui, quel sont leurs noms ?');
		$repererChats->setAttrib('cols', '60');
		$repererChats->setAttrib('rows', '4');
		
		$destineA = new Zend_Form_Element_Select('idDestineA');
		$destineA->setLabel('Votre futur chat est-il destiné à vous-même ou à être offert en cadeau à une autre personne ?');
		$destineA->setRequired(true);
		$destineA->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->cadeauMapper->buildArrayForForm());
		
		$this->addElement($motivation);
		$this->addElement($criteres);
		$this->addElement($repererChats);
		$this->addElement($destineA);
	}

}