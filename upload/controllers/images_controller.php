<?php
class ImagesController extends AppController {

	var $name = 'Images';

	function admin_index($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Image->Product->read(null, $product_id);
		
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		$this->paginate = array('contain' => 'ImageDefault', 'conditions' => array('product_id' => $product_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('order' => 'asc'));
		$this->set('images', $this->paginate());
	}

	function admin_add($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Image->Product->read(null, $product_id);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		if (!empty($this->data)) {
			for ($x=1; $x<4; $x++) {
				if (!isset($this->data['Image']['image'.$x]['tmp_name']) || !$this->data['Image']['image'.$x]['tmp_name']) 
					continue;
					
				if (!preg_match('/(jpg|jpeg|gif|png)$/i', $this->data['Image']['image'.$x]['name'])) {
					//prevent upload attacks
					$this->Session->setFlash(__('Image files must be in JPG, GIF, or PNG format.', true));
					$this->redirect($this->referer());
				}
				
				$this->data['Image']['image'.$x]['product_id'] 	= $product_id;
				$this->data['Image']['image'.$x]['order'] 	= $this->Image->getLastOrderNumber($product_id);
				
				
				//$this->Image->create();
				if ($this->Image->storeImage($this->data['Image']['image'.$x])) {
					//$this->Session->setFlash(__('The image has been added successfully.', true));
					//$this->redirect(array('action' => 'index', $product_id));
				} else {
					//$this->Session->setFlash(__('There was a problem adding the image please review the errors below and try again.', true));
				}
			}
			$this->redirect(array('action' => 'index', $product_id));
		}
	}

	function admin_saveorder($product_id = null) {
		Configure::write('debug', 0);
		$this->layout = 'js/ajax';
		if(!empty($product_id)){
			if(!empty($_POST)){
				$data = $_POST;

				foreach($data['image'] as $order => $id){
					$image['Image']['id'] = $id;
					$image['Image']['product_id'] = $product_id;
					$image['Image']['order'] = $order;

					// Turn off the validation since the upload image behavior
					// block the saving
					$this->Image->save($image, false);
				}
			}
			$this->set('message', 'The image order has been saved successfully.');
		}else{
			$this->set('message', 'Invalid product id');
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Image', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$image = $this->Image->read(null, $id);
		if(empty($image)) {
			$this->Session->setFlash(__('The image ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}

		if ($this->Image->del($id)) {
			$this->Session->setFlash(__('The image was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting the image.', true));
		}
		$this->redirect(array('action'=>'index', $image['Product']['id']));
	}
}
?>