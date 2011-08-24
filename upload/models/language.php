<?php
class Language extends AppModel {

	var $name = 'Language';
	
	var $actsAs = array('Containable');
	
	var $hasMany = 'Translation';
}
?>