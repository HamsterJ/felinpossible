<?php

/**
 * Veto data mapper.
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_VetoMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Veto';

	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'raison' => 'raison',
	                 'adresse' => 'adresse',
	                 'cp' => 'codePostal',
	                 'ville' => 'ville',
	                 'fixe' => 'telephoneFixe',
	                 'portable' => 'telephonePortable',
	                 'email' => 'email'
	);

	protected $filterKeyToDbKey = array('raison' => 'fp_veto_fiche.raison',
	'cp' => 'fp_veto_fiche.cp',
	'ville' => 'fp_veto_fiche.ville');

	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#fetchAllToArrayForExport($where)
	 */
	public function fetchAllToArrayForExport($where = null)
	{
		$columnsToExport = array('Identifiant' => 'veto.id',
		'Raison sociale' => 'raison',
		'Code Postal' => 'veto.cp',
		'Ville' => 'veto.ville',
		'Tél. portable' => 'veto.portable',
		'Tél. fixe' => 'veto.fixe'
		);

		$select = $this->getDbTable()->getAdapter()->select()
		->from(array('veto' => 'fp_veto_fiche'), $columnsToExport);

		if ($where) {
			$select->where($where);
		}

		$stmt = $select->query();
		return $stmt->fetchAll();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see site/application/models/Mapper/FP_Model_Mapper_CommonMapper#buildArrayForForm($idColumnName, $valueColumnName)
	 */
	public function buildArrayForForm($idColumnName = 'id', $valueColumnName = 'name') {
		return parent::buildArrayForForm('id', 'raison', 'ville', true);
	}
}