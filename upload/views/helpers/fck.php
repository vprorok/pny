<?php class FckHelper extends Helper {
    
    var $helpers = Array('Html');
    
    function input($field, $width = 400) {
        $field = explode('.', $field);
        if(empty($field[1])) {
        	// need to know how to call a model from a helper
        } else {
        	$model = $field[0];
        	$controller = $field[1];
        }
        
        require_once WWW_ROOT.DS.'js'.DS.'ckeditor'.DS.'ckeditor_php5.php';
		$oCKeditor = new CKEditor() ;
		$oCKeditor->basePath	= '/js/ckeditor/';
		$oCKeditor->height		= $width;
		$oCKeditor->editor('data['.$model.']['.$controller.']', $this->data[$model][$controller]);   
    }
}
?>