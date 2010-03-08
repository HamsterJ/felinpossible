<?php
/**
 * Sous-formulaire pour la partie vacances/projets.
 * @author Benjamin
 *
 */
class FP_Form_adoptant_VacancesProjetsSubForm extends FP_Form_common_SubForm
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
		
		$this->setDescription('Vacances et projets');
		
		$solutionGardeVacances = new Zend_Form_Element_Select('idSolutionGardeVacances');
		$solutionGardeVacances->setLabel('Quelle(s) solution(s) de garde avez-vous pendant vos vacances pour votre futur chat ?');
		$solutionGardeVacances->setRequired(true);
		$solutionGardeVacances->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->vacancesMapper->buildArrayForForm());
		
		$solutionDemenagement = new Zend_Form_Element_Select('idSolutionDemenagement');
		$solutionDemenagement->setLabel('En cas de déménagement (France ou Etranger), que ferez-vous de votre chat ?');
		$solutionDemenagement->setRequired(true);
		$solutionDemenagement->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->demenagementMapper->buildArrayForForm());
		
		$fonderFamille = new Zend_Form_Element_Select('idFonderFamille');
		$fonderFamille->setLabel('Avez-vous le projet de fonder une famille ? Si oui, Madame, que ferez-vous du chat si vous n\'êtes pas immunisée contre la toxoplasmose ?');
		$fonderFamille->setRequired(true);
		$fonderFamille->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->fonderFamilleMapper->buildArrayForForm());
		
		$this->addElement($solutionGardeVacances);
		$this->addElement($solutionDemenagement);
		$this->addElement($fonderFamille);
	}
}