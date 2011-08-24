<?php
App::import('Vendor', 'phpthumb', array('file' => 'phpthumb'.DS.'phpthumb.php'));

	class Image extends AppModel {

		var $name = 'Image';

		var $belongsTo = array(
			'Product' => array(
				'className'  => 'Product',
				'foreignKey' => 'product_id'
			), 'ImageDefault'
		);

		var $actsAs = array(
	   	    'Containable',
			'ImageUpload' => array(
				'image' => array(
					'required' 		=> true,
					'directory'           => 'img/product_images/',
					'allowed_mime'        => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
					'allowed_extension'   => array('.jpg', '.jpeg', '.png', '.gif'),
					'allowed_size'        => 2097152,
					'random_filename'     => true,
					'resize' => array(
						'thumb' => array(
							'directory' => 'img/product_images/thumbs/',
							'width'     => IMAGE_THUMB_WIDTH,
							'height'    => IMAGE_THUMB_HEIGHT,
							'phpThumb' => array(
								'far' => 1,
								'bg'  => 'FFFFFF'
							)
						),

						'max' => array(
							'directory'   => 'img/product_images/max/',
							'width'       => IMAGE_MAX_WIDTH,
							'height'      => IMAGE_MAX_HEIGHT,
							'phpThumb' => array(
								'zc' => 0
							)
						)
					)
				)
			)
		);

		/**
		 * Function to get last order number
		 *
		 * @return int Return last order number
		 */
		function getLastOrderNumber($product_id = null) {
			$this->recursive = -1;
			$lastItem = $this->find('first', array('conditions' => array('product_id' => $product_id), 'order' => array('order' => 'desc')));
			if(!empty($lastItem)) {
				return $lastItem['Image']['order'] + 1;
			} else {
				return 0;
			}
		}
		
		function storeImage($image_array) {
			$sizes=array(
				'thumb' => array(
					'directory' => 'img/product_images/thumbs/',
					'width'     => IMAGE_THUMB_WIDTH,
					'height'    => IMAGE_THUMB_HEIGHT,
					'phpThumb' => array(
						'far' => 1,
						'bg'  => 'FFFFFF'
					)
				),
				'max' => array(
					'directory'   => 'img/product_images/max/',
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
	                $saveAs    = realpath(WWW_ROOT . 'img/product_images/') .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
	                
			// Attempt to move uploaded file
			if(!move_uploaded_file($image_array['tmp_name'], $saveAs)) {
				return false;
			}

			foreach($sizes as $name => $size){
				$this->generateThumbnailWrap($saveAs, $size);
			}
			
			/* $this->create();
			$success=$this->save(array('Image'=>array(	'Image.product_id'=>$image_array['product_id'],
									'Image.image'=>basename($saveAs),
									'Image.order'=>$image_array['order'],
									'Image.created'=>date('Y-m-d H:i:s'),
									'Image.modified'=>date('Y-m-d H:i:s'))));
			
			*/
			
			$success=$this->query("INSERT INTO `images` SET 
				`product_id`='{$image_array['product_id']}', 
				`image`='".basename($saveAs)."', 
				`order`='{$image_array['order']}', 
				`created`=NOW(), 
				`modified`=NOW()");
			
			
			return $success;
			
		}
	}
?>
