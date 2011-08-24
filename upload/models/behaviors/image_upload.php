<?php

App::import('Core', array('Folder', 'File'));
App::import('Vendor', 'phpthumb', array('file' => 'phpthumb'.DS.'phpthumb.php'));

class ImageUploadBehavior extends ModelBehavior{
    var $options = array(
        'required'		    => false,
		'directory'         => 'img/upload',
		'allowed_mime' 	    => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
		'allowed_extension' => array('.jpg', '.jpeg', '.png', '.gif'),
		'allowed_size'	    => 1048576,
		'random_filename'   => true,
        'resize' => array(
            'max' => array(
                'directory' => 'img/upload/max',
                'width' => 640,
                'height' => 480,
                'phpThumb' => array(
                    'zc' => 0
                )
            ),

            'thumb' => array(
                'directory' => 'img/upload/thumbs',
                'width' => 320,
                'height' => 240,
                'phpThumb' => array(
                    'zc' => 0
                )
            )
        )
    );

    /**
	 * Array of errors
	 */
	var $errors = array();

	var $__fields;
	
	function setup(&$model, $config = array()){
		$config_temp = array();
		
		foreach($config as $field => $options){
		    // Check if given field exists
		    if(!$model->hasField($field)){
					unset($config[$field]);
					unset($model->data[$model->name][$field]);
					
					continue;
		    }
		
				if(substr($options['directory'], -1) != '/'){
					$options['directory'] = $options['directory'] . DS;
		    }
		
		    foreach($options['resize'] as $name => $resize){
					if(isset($options['resize'][$name]['directory']) && substr($options['resize'][$name]['directory'], -1) != '/'){
					   $options['resize'][$name]['directory'] = $options['resize'][$name]['directory'] . DS;
					}
		    }
		
		    $config_temp[$field] = $options;
		}
		
		$this->__fields = $config_temp;
	}
	
	function beforeSave(&$model) {		
		return true;
	}

	function beforeDelete(&$model) {
		if(count($this->__fields) > 0){
			$model->read(null, $model->id);
			if (isset($model->data)) {
				foreach($this->__fields as $field => $options){
					if(!empty($model->data[$model->name][$field])) {
						$this->removeImages($model->data[$model->name][$field], $options);
					}
				}
			}
		}
		return true;
	}
	
	function removeImages($file, $options) {
		$file_with_ext = WWW_ROOT . $options['directory'] . $file;
		if(file_exists($file_with_ext)) {
			unlink($file_with_ext);
		}
		
		foreach($options['resize'] as $name => $resize){
			$resizePath = WWW_ROOT . $resize['directory'] . $file;
			if(file_exists($resizePath)){
				unlink($resizePath);
			}
		}
	}

	function generateThumbnailWrap($model, $saveAs, $options){
		return $this->generateThumbnail($saveAs, $options);    
	}

    
	function generateThumbnail($saveAs, $options){
		$destination = WWW_ROOT . $options['directory'] . DS . basename($saveAs);
		$ext = substr(basename($saveAs), strrpos(basename($saveAs), '.') + 1);
		if($ext == '.jpg' || $ext == '.jpeg'){
			$format = 'jpeg';
		}elseif ($ext == 'png'){
			$format = 'png';
		}elseif ($ext == 'gif'){
			$format = 'gif';
		}else{
			$format = 'jpeg';
		}
		
		
		$phpThumb = new phpthumb();
		$phpThumb->setSourceFilename($saveAs);
		$phpThumb->setCacheDirectory(CACHE);
		$phpThumb->setParameter('w', $options['width']);
		$phpThumb->setParameter('h', $options['height']);
		$phpThumb->setParameter('f', $format);
		
		if(!empty($options['phpThumb'])){
			foreach($options['phpThumb'] as $name => $value){
				if(!empty($value)){
					$phpThumb->setParameter($name, $value);
				}
			}
		}
		//pr($phpThumb->debugmessages);
		if ($phpThumb->generateThumbnail()){
			if($phpThumb->RenderToFile($destination)){
				chmod($destination, 0644);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

?>