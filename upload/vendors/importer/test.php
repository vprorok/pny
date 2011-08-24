<?php

set_time_limit(0);
include("../../../include/init.inc.php");

$importer_class = 'hotmail';
$classFile = $_CONFIG['BASE_DIR'].'/include/classes/importer/'.$importer_class.'.php';
if (file_exists($classFile)) {
		require_once($classFile);
		$oImporter = new $importer_class();

		$result_array = $oImporter->run('','');
		
		var_dump($result_array);
		
}
?>