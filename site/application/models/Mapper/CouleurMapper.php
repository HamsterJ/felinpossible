<?php

/**
 * Couleur data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_CouleurMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Couleur';
	
	protected $mappingDbToModel = array(
	                 'id' => 'id',
                     'name' => 'libelleCouleur',
	                 'key_words' => 'motsClefs');

}