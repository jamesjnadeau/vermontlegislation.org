<?php

class Bill extends AppModel {
	public $useTable = 'bill';
	public $hasMany = array
	(
		'BillSummary' => array
		(
			'className' => 'BillSummary',
			//'conditions' => array('Recipe.approved' => '1'),
			//'order' => 'Recipe.created DESC'
		)
	);
}
