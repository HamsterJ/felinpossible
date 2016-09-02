<?php

/**
 * StockCategorieMateriel data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_StockCategorieMaterielMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'StockCategorieMateriel';

	protected $mappingDbToModel = array(
                                            'id'        => 'id',
                                            'libelle'   => 'libelle');

	public function buildArrayForForm($idColumnName = 'id', $valueColumnName = 'libelle', $otherColumn = NULL, $emptyValue = false) {                       
            return parent::buildArrayForForm($idColumnName, $valueColumnName);
	}
}