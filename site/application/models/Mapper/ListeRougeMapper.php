<?php

/**
 * ListeRouge data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_ListeRougeMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'ListeRouge';

	protected $mappingDbToModel = array(
	                'id'            => 'id',
                        'date_demande'  => 'date_demande',
	                'email'         => 'email',
                        'commentaire'   => 'commentaire');

}