<?php
class ProductsController extends AppController {

	var $name = 'Products';

	var $helpers = array('Fck');
	var $uses = array('Product', 'Package');

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Product.title' => 'asc'));
		$this->set('products', $this->paginate('Product'));

		$this->set('languages', $this->Language->find('count'));
	}

	function admin_auctions($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		$product = $this->Product->read(null, $id);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('product', $product);

		$this->paginate = array('conditions' => array('Auction.product_id' => $id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time' => 'desc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_add($id = null) {
		if (!empty($this->data)) {
			if(empty($this->data['Product']['rrp'])) {
				$this->data['Product']['rrp'] = 0;
			}
			if(empty($this->data['Product']['fixed_price'])) {
				$this->data['Product']['fixed_price'] = 0;
			}
			if(empty($this->data['Product']['delivery_cost'])) {
				$this->data['Product']['delivery_cost'] = 0;
			}
			if(empty($this->data['Product']['autobid_limit'])) {
				$this->data['Product']['autobid_limit'] = 0;
			}
			if(empty($this->data['Product']['minimum_price'])) {
				$this->data['Product']['minimum_price'] = 0;
			}
			if(empty($this->data['Product']['limit_id'])) {
				$this->data['Product']['limit_id'] = 0;
			}
			if(empty($this->data['Product']['stock_number'])) {
				$this->data['Product']['stock_number'] = 0;
			}
			if(empty($this->data['Product']['buy_now'])) {
				$this->data['Product']['buy_now'] = 0;
			}
			
			$this->Product->create();
			if ($this->Product->save($this->data)) {
				$product_id=$this->Product->getInsertID();
				
				//*** Processed attached images
				if (is_uploaded_file($this->data['Product']['image1']['tmp_name'])) {
					$this->data['Product']['image1']['product_id'] = $product_id;
					$this->data['Product']['image1']['order'] 	   = $this->Product->Image->getLastOrderNumber($product_id);
					$this->Product->Image->storeImage($this->data['Product']['image1']);
				}
				if (is_uploaded_file($this->data['Product']['image2']['tmp_name'])) {
					$this->data['Product']['image2']['product_id'] = $product_id;
					$this->data['Product']['image2']['order'] 	   = $this->Product->Image->getLastOrderNumber($product_id);
					$this->Product->Image->storeImage($this->data['Product']['image2']);
				}
				if (is_uploaded_file($this->data['Product']['image3']['tmp_name'])) {
					$this->data['Product']['image3']['product_id'] = $product_id;
					$this->data['Product']['image3']['order'] 	   = $this->Product->Image->getLastOrderNumber($product_id);
					$this->Product->Image->storeImage($this->data['Product']['image3']);
				}
				
				
				
				if(!empty($this->data['SettingIncrement'])) {
					$this->data['SettingIncrement']['product_id'] = $this->Product->getInsertID();
					
					//check/set default settings
					if (!$this->data['SettingIncrement']['bid_debit']) {
						$this->data['SettingIncrement']['bid_debit'] =1;
					}
					if (!$this->data['SettingIncrement']['price_increment']) {
						$this->data['SettingIncrement']['price_increment'] = 0.01;
					}
					if (!$this->data['SettingIncrement']['time_increment']) {
						$this->data['SettingIncrement']['time_increment'] = 30;
					}
					
					$this->Product->SettingIncrement->create();
					if(!$this->Product->SettingIncrement->save($this->data, false)) {
						$this->Session->setFlash(__('The product has been added HOWEVER there was a problem adding in the bidding increments.  Please edit this product now and correct the setting information.', true));
						$this->redirect(array('action'=>'edit', $this->Product->getInsertID()));
					}
				}
				$this->Session->setFlash(sprintf(__('The product has been added successfully.  <a href="/admin/images/index/%d">Click here to add more images to the product</a>.', true), $this->Product->getInsertID()));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the product please review the errors below and try again.', true));
			}
		}
		$this->set('categories', $this->Product->Category->generatetreelist(null, null, null, '-'));

		$this->set('priceIncrement', $this->requestAction('/settings/get/price_increment'));
		$this->set('markUp', $this->Setting->get('mark_up'));
		$this->set('packagePrice', $this->Package->most_expensive_package());
		
		if(!empty($this->appConfigurations['limits']['active'])) {
			$this->set('limits', $this->Product->Limit->find('list', array('order' => array('Limit.limit' => 'desc'))));
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Product', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if(!empty($this->data['SettingIncrement'])) {
				
				//check/set default settings
				if (!$this->data['SettingIncrement']['bid_debit']) {
					$this->data['SettingIncrement']['bid_debit'] =1;
				}
				if (!$this->data['SettingIncrement']['price_increment']) {
					$this->data['SettingIncrement']['price_increment'] = 0.01;
				}
				if (!$this->data['SettingIncrement']['time_increment']) {
					$this->data['SettingIncrement']['time_increment'] = 30;
				}
				$this->data['SettingIncrement']['product_id'] = $this->data['Product']['id'];
				if(empty($this->data['SettingIncrement']['id'])) {
					$this->Product->SettingIncrement->create();
				}
				if(!$this->Product->SettingIncrement->save($this->data, false)) {
					$this->Session->setFlash(__('The product has been added HOWEVER there was a problem adding in the bidding increments.  Please edit this product now and correct the setting information.', true));
					$this->redirect(array('action'=>'edit', $this->data['Product']['id']));
				}
			}

			if(empty($this->data['Product']['rrp'])) {
				$this->data['Product']['rrp'] = 0;
			}
			if(empty($this->data['Product']['fixed_price'])) {
				$this->data['Product']['fixed_price'] = 0;
			}
			if(empty($this->data['Product']['delivery_cost'])) {
				$this->data['Product']['delivery_cost'] = 0;
			}
			if(empty($this->data['Product']['autobid_limit'])) {
				$this->data['Product']['autobid_limit'] = 0;
			}
			if(empty($this->data['Product']['minimum_price'])) {
				$this->data['Product']['minimum_price'] = 0;
			}
			if(empty($this->data['Product']['limit_id'])) {
				$this->data['Product']['limit_id'] = 0;
			}
			if(empty($this->data['Product']['stock_number'])) {
				$this->data['Product']['stock_number'] = 0;
			}

			if ($this->Product->save($this->data)) {
				$this->Product->Auction->clearCache();
				
				$this->Session->setFlash(__('The product has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the product please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Product->read(null, $id);
			if(empty($this->data)) {
				$this->Session->setFlash(__('Invalid Product', true));
				$this->redirect(array('action'=>'index'));
			}
			if($this->appConfigurations['bidIncrements'] == 'product') {
				$settings = $this->Product->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.product_id' => $id), 'recursive' => -1));
				$this->data['SettingIncrement'] = $settings['SettingIncrement'];
			}
		}
		$this->set('categories', $this->Product->Category->generatetreelist(null, null, null, '-'));
		
		if(!empty($this->appConfigurations['limits']['active'])) {
			$this->set('limits', $this->Product->Limit->find('list', array('order' => array('Limit.limit' => 'desc'))));
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Product.', true));

		}
		if ($this->Product->del($id)) {
			$this->Session->setFlash(__('The product was deleted successfully.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this product.', true));
		}
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_help() {
		$this->layout = false;
		
		if(!empty($this->data)) {
			$this->Product->set($this->data);
    		if($this->Product->validates()) {
    			$total = $this->data['Product']['cost_price'] + $this->data['Product']['profit'];
    			$numberOfBids = $total / $this->data['Product']['bid_cost'];

    			$min_price =  $this->data['Product']['start_price'] + ($this->data['Product']['bid_increment'] * $numberOfBids);
    			$this->set('min_price', $min_price);
    		} else {
    			$this->Session->setFlash(__('There was a problem calulating the minimum price, please review the errors below.', true));
    		}
		}
	}
}
?>