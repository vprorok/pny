<?php
class Reward extends AppModel{
    var $name = 'Reward';
    		var $actsAs = array(
	   	    'Containable',
			'ImageUpload');
    
    function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'title' => array(
				'rule' => array('minLength', 1),
				'message' => __('Title is a required field.', true)
			),
			'description' => array(
				'rule' => array('minLength', 1),
				'message' => __('Description is a required field.', true)
			),
			'points' => array(
					'comparison' => array(
						'rule'=> array('comparison', 'not equal', 0),
						'message' => __('The points cannot be zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('The points can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Points is required.', true)
					)
				),
			'image' => array()
		);
	}

    function afterFind($results, $primary = false){
		// Parent method redefined
		$results = parent::afterFind($results, $primary);

		if(!empty($results)){
			// Getting rate for current currency
			$rate = $this->_getRate();

			// This for find('all')
			if(!empty($results[0]['Reward'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['Reward']['rrp'])){
						$results[$key]['Reward']['rrp'] = $result['Reward']['rrp'] * $rate;
					}
				}

			// This for find('first')
			}elseif(!empty($results['Reward'])){
				if(!empty($results['Reward']['rrp'])){
					$results['Reward']['rrp'] = $results['Reward']['rrp'] * $rate;
				}
			}
		}

		// Return back the results
		return $results;
	}

	function beforeSave(){
		// Price currency rate revert back to application default (USD)
		// Get the rate
		$rate = 1 / $this->_getRate();

		// Convert it back to USD
		if(!empty($this->data['Reward']['rrp'])){
			$this->data['Reward']['rrp'] = $this->data['Reward']['rrp'] * $rate;
		}

		return true;
	}
	
	function beforeValidate() {
		return true;
	}
	
	function storeImage($params) {
		
		$image_array=$params['image'];
		
		$sizes=array(
			'thumb' => array(
				'directory' => 'img/rewards/thumbs/',
				'width'     => IMAGE_THUMB_WIDTH,
				'height'    => IMAGE_THUMB_HEIGHT,
				'phpThumb' => array(
					'far' => 1,
					'bg'  => 'FFFFFF'
				)
			),
			'max' => array(
				'directory'   => 'img/rewards/max/',
				'width'       => IMAGE_MAX_WIDTH,
				'height'      => IMAGE_MAX_HEIGHT,
				'phpThumb' => array(
					'zc' => 0
				)
			));
		
		// Save path
		//$saveAs = realpath('img/product_images/') . DS . $image_array['name'];
		$uniqueFileName = sha1(uniqid(rand(), true));
		$extension = explode('.', $image_array['name']);
		if (!is_dir(WWW_ROOT . 'img/rewards/')) {
			// self-repairing installation
			mkdir(WWW_ROOT . 'img/rewards/');
			chmod(WWW_ROOT . 'img/rewards/', 0755);
			mkdir(WWW_ROOT . 'img/rewards/thumbs/');
			chmod(WWW_ROOT . 'img/rewards/thumbs/', 0755);
			mkdir(WWW_ROOT . 'img/rewards/max/');
			chmod(WWW_ROOT . 'img/rewards/max/', 0755);
		}
		$saveAs    = realpath(WWW_ROOT . 'img/rewards/') .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
		
		// Attempt to move uploaded file
		if(!move_uploaded_file($image_array['tmp_name'], $saveAs)) {
			return false;
		}
		
		foreach($sizes as $name => $size){
			$this->generateThumbnailWrap($saveAs, $size);
		}
		
		////No idea why Cake won't accept this
		//$this->create();
		//$this->save(array('Reward'=>array(	'id'=>$params['id'],
		//					'image'=>$saveAs)));
		
		$this->query("UPDATE `rewards` SET `image`='".basename($saveAs)."' WHERE `id`='".$params['id']."'");
		
		return true;
		
	}

}
?>
