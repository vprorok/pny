<?php
	class Referral extends AppModel {

		var $name = 'Referral';

		var $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id'
			), 
			'Referrer' => array(
				'className'  => 'User',
				'foreignKey' => 'referrer_id'
			)
		);

	}
?>