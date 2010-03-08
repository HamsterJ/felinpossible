<?php

/**
 * FonderFamille data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_FonderFamilleMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'FonderFamille';
	
	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'name' => 'libelle');

}