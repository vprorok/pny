<?php
	class Category extends AppModel {

		var $name = 'Category';

		var $actsAs = array(
			'Containable',
			'Tree',
			'ImageUpload' => array(
				'image' => array(
					'required' 			  => false,
					'directory'           => 'img/category_images/',
					'allowed_mime'        => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
					'allowed_extension'   => array('.jpg', '.jpeg', '.png', '.gif'),
					'allowed_size'        => 2097152,
					'random_filename'     => true,
					'resize' => array(
						'thumb' => array(
							'directory' => 'img/category_images/thumbs/',
							'width'     => IMAGE_THUMB_WIDTH,
							'height'    => IMAGE_THUMB_HEIGHT,
							'phpThumb' => array(
								'far' => 1,
								'bg'  => 'FFFFFF'
							)
						),

						'max' => array(
							'directory' => 'img/category_images/max/',
							'width'     => IMAGE_MAX_WIDTH,
							'height'    => IMAGE_MAX_HEIGHT,
							'phpThumb' => array(
								'far' => 1,
								'bg'  => 'FFFFFF'
							)
						)
					)
				)
			)
		);

		var $belongsTo = array(
			'ParentCategory' => array(
				'className'  => 'Category',
				'foreignKey' => 'parent_id'
			)
		);

		var $hasMany = array(
			'Product' => array(
				'className'  => 'Product',
				'foreignKey' => 'category_id',
				'dependent'  => false
			),
			'ChildCategory' => array(
				'className'  => 'Category',
				'foreignKey' => 'parent_id',
				'dependent'  => false
			)
		);

		var $validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
	        	'message' => 'Name is a required field.'
	        ),
	        'parent_id' => array(
				'rule' => 'parentCheck',
				'message' => 'The Parent Category cannot be the current category.'
	        ),
		);

		// this makes sure the parent category isn't the current ID
		function parentCheck() {
			if(!empty($this->data['Category']['id']) && (!empty($this->data['Category']['parent_id']))) {
				if($this->data['Category']['id'] == $this->data['Category']['parent_id']) {
					return false;
				}
			}
			return true;
		}
		
		function storeImage($image_array) {
			//pr($image_array);exit;
			$sizes=array(
				'thumb' => array(
					'directory' => 'img/category_images/thumbs/',
					'width'     => IMAGE_THUMB_WIDTH,
					'height'    => IMAGE_THUMB_HEIGHT,
					'phpThumb' => array(
						'far' => 1,
						'bg'  => 'FFFFFF'
					)
				),
				'max' => array(
					'directory'   => 'img/category_images/max/',
					'width'       => IMAGE_MAX_WIDTH,
					'height'      => IMAGE_MAX_HEIGHT,
					'phpThumb' => array(
						'zc' => 0
					)
				)
			);
			
			// Save path
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
			
			return basename($saveAs);
			
		}

		function getlist($parent = null, $find = 'list', $count = null){
			if($parent == 'parent') {
				if($find !== 'all') {
					$this->recursive = -1;
				}
				$this->contain();
				$categories = $this->find($find, array('conditions' => array('Category.parent_id' => 0), 'order' => array('Category.name' => 'asc')));

			} else {
				$categories = $this->generateTreeList(null, null, null, '-');
			}
			if($count == 'count') {
				foreach($categories as $key => $category) {
					$category_id = $category['Category']['id'];
					$categories[$key]['Category']['count'] = $this->Product->Auction->find('count', array('conditions' => "Product.category_id = $category_id AND Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . date('Y-m-d H:i:s') . "'"));
					$children = $this->children($category_id, false);
					if(!empty($children)) {
						foreach ($children as $child) {
							$category_id = $child['Category']['id'];
							$categories[$key]['Category']['count'] += $this->Product->Auction->find('count', array('conditions' => "Product.category_id = $category_id AND Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . date('Y-m-d H:i:s') . "'"));
						}
					}
				}
			}

			return $categories;
		}

		function afterSave($created){
			parent::afterSave($created);
	
			$this->clearCache();
			return true;
		}

		function afterDelete(){
			parent::afterDelete();

			$this->clearCache();
			return true;
		}

		function clearCache() {
			Cache::delete('menuCategories');
			Cache::delete('menuCategoriesSelect');
			Cache::delete('menuCategoriesCount');
		}
	}
?>