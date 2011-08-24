<?php
	class Gender extends AppModel {

		var $name = 'Gender';
		var $useTable=false;
		
		var $hasMany = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'gender_id',
				'dependent'  => false
			)
		);


		function find($type=null) {
			return array(	'1'=>'Male',
										'2'=>'Female');
		}
	}
?>
