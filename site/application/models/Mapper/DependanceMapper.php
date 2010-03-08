<?php

/**
 * Dependance data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_DependanceMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Dependance';
	
	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'name' => 'libelle');

}