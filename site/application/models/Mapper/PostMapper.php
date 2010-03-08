<?php

/**
 * Post data mapper
 *
 * Implements the Data Mapper design pattern:
 * http://www.martinfowler.com/eaaCatalog/dataMapper.html
 *
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Mapper_PostMapper extends FP_Model_Mapper_CommonMapper {
	protected $idClassName = 'Post';

	protected $mappingDbToModel = array(
	                 'post_id' => 'id',
                     'post_text' => 'content',
	                 'post_time' => 'date');

}