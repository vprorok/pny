<?php
class CouponsController extends AppController {

	var $name = 'Coupons';
	var $helpers = array('Html', 'Form');
	
	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('apply');
		}
	}
	
	function apply() {
		if(!empty($this->data['Coupon']['code'])){
			$coupon = $this->Coupon->findByCode(strtoupper($this->data['Coupon']['code']));
			if(!empty($coupon)) {
				$this->Session->write('Coupon', $coupon);
				$this->Session->setFlash(__('The coupon has been applied.',true), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('Invalid Coupon',true));
			}
		} else {
			$this->Session->setFlash(__('Invalid Coupon',true));
		}

		$this->redirect(array('controller' => 'users', 'action' => 'register'));
	}
	
	function admin_index() {
		$this->Coupon->recursive = 0;
		$this->set('coupons', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Coupon->create();
			if ($this->Coupon->save($this->data)) {
				$this->Session->setFlash(__('The Coupon has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Coupon could not be saved. Please, try again.', true));
			}
		}
		$couponTypes = $this->Coupon->CouponType->find('list');

		// Show the option for FREE REWARDS only if reward points is on
		if(!Configure::read('App.rewardsPoint')) {
			unset($couponTypes[5]);
		}
		$this->set(compact('couponTypes'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Coupon', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Coupon->save($this->data)) {
				$this->Session->setFlash(__('The Coupon has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Coupon could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Coupon->read(null, $id);
		}
		$couponTypes = $this->Coupon->CouponType->find('list');
		
		// Show the option for FREE REWARDS only if reward points is on
		if(!Configure::read('App.rewardsPoint')) {
			unset($couponTypes[5]);
		}

		$this->set(compact('couponTypes'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Coupon', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coupon->del($id)) {
			$this->Session->setFlash(__('Coupon deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>