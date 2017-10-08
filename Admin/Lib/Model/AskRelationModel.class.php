<?php
/**
 * 问题关联模型
 */
class AskRelationModel extends RelationModel{

	protected $tableName = 'ask';

	protected $_link = array(
		'answer' => array(
			'mapping_type' => HAS_MANY,
			'foreign_key' => 'aid'
			)
		);
}
?>