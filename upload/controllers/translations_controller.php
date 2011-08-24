<?php
class TranslationsController extends AppController {

	var $name = 'Translations';
	
	var $helpers = array('Fck');

	function admin_index($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Translation->Product->read(null, $product_id);
		
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);
		
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'conditions' => array('Translation.product_id' => $product_id), 'contain' => 'Language');
		$this->set('translations', $this->paginate());
	}

	function admin_add($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Translation->Product->read(null, $product_id);
		
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);
		
		if (!empty($this->data)) {
			$this->data['Translation']['product_id'] = $product_id;
			$this->Translation->create();
			if ($this->Translation->save($this->data)) {
				$this->Session->setFlash(__('The translation has been added successfully.', true));
				$this->redirect(array('action'=>'index', $product['Product']['id']));
			} else {
				$this->Session->setFlash(__('There was a problem adding the translation please review the errors below and try again.', true));
			}
		}
		
		$this->set('languages', $this->Language->find('list', array('conditions' => array('Language.default' => 0), 'fields' => 'language')));
	}

	function admin_edit($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('The translation ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$translation = $this->Translation->read(null, $id);
		
		if(empty($translation)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $translation);
		
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Transction', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Translation->save($this->data)) {
				$this->Session->setFlash(__('The translation has been updated successfully.', true));
				$this->redirect(array('action'=>'index', $translation['Product']['id']));
			} else {
				$this->Session->setFlash(__('There was a problem updating the translation please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $translation;
		}

		$this->set('languages', $this->Language->find('list', array('conditions' => array('Language.default' => 0), 'fields' => 'language')));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Translation', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$translation = $this->Translation->read(null, $id);
		if(empty($translation)) {
			$this->Session->setFlash(__('The translation ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}

		if ($this->Translation->del($id)) {
			$this->Session->setFlash(__('The translation was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting the translation.', true));
		}
		$this->redirect(array('action'=>'index', $translation['Product']['id']));
	}
}
?>