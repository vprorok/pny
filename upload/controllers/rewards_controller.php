<?php
class RewardsController extends AppController {

	var $name = 'Rewards';
	var $helpers = array('Html', 'Form');
	var $uses = array('Reward', 'User');

	function beforeFilter(){
		parent::beforeFilter();

		if($this->Auth){
			$this->Auth->allow('index', 'view');
		}
	}
	function index() {
		$this->paginate = array('limit' => $this->appConfigurations['pageLimit'], 'order' => array('points' => 'asc'));
		$this->set('rewards', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Reward.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('reward', $this->Reward->read(null, $id));
	}

	function purchase($id = null){
		if(!$id){
			$this->Session->setFlash(__('Invalid Reward', true));
			$this->redirect(array('action'=>'index'));
		}

		$reward = $this->Reward->findById($id);
		$this->User->recursive=0;
		$user=$this->User->findById($this->Auth->user('id'));
		
		if(!empty($reward)){
			$balance = $this->requestAction('/users/points');
			if($balance >= $reward['Reward']['points']){

				$addresses       = $this->User->Address->addressTypes();
				$userAddress 	 = array();
				$addressRequired = 0;

				if(!empty($addresses)) {
					foreach($addresses as $address_type_id=>$address) {
						$userAddress[$address] = $this->User->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $address_type_id)));
						if(empty($userAddress[$address])) {
							$addressRequired = 1;
						}
					}
				}

				$this->set('reward', $reward);
				$this->set('address', $userAddress);
				$this->set('addressRequired', $addressRequired);

				if(!empty($this->data)){
					$point = $this->User->Point->findByUserId($this->Auth->user('id'));
					$point['Point']['points'] -= $reward['Reward']['points'];

					if($this->User->Point->save($point)) {
						$data['User']   = $user['User'];
						$data['Reward']   = $reward['Reward'];
						$data['Address']  = $userAddress['Shipping']['Address'];
						$data['Country']  = $userAddress['Shipping']['Country'];
						$data['template'] = 'rewards/purchase';
						$data['to']       = $this->appConfigurations['email'];
						$data['subject']  = __('Rewards were redeemed by a user', true);
						
						if($this->_sendEmail($data)){
							$this->Session->setFlash(__('Thank you for redeeming your reward.  We will contact you once we have shipped your reward.', true), 'default', array('class' => 'success'));
							$this->redirect(array('action'=>'index'));
						}else{
							$point['Point']['points'] += $reward['Reward']['points'];
							$this->User->Point->save($point);

							$this->Session->setFlash(__('Email sending failed. Your points has been refunded to your account.  Please try again', true));
							$this->redirect(array('action'=>'index'));
						}
					}else{
						$this->Session->setFlash(__('Reward redeeming failed. Please try again later.', true));
						$this->redirect(array('action'=>'index'));
					}
				}

			}else{
				$this->Session->setFlash(__('Your balance is not enough to redeem this reward.', true));
				$this->redirect(array('action'=>'index'));
			}
		}else{
			$this->Session->setFlash(__('Invalid Reward'));
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('points' => 'asc'));
		$this->set('rewards', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Reward->create();
			
			//prevent null error
			if (!$this->data['Reward']['rrp']) $this->data['Reward']['rrp']=0;
			
			$image=$this->data['Reward']['image']; unset($this->data['Reward']['image']);
			if ($this->Reward->save($this->data)) {
				//*** Processed attached images
				
				if (is_uploaded_file($image['tmp_name'])) {  
					if (!preg_match('/(jpg|jpeg|gif|png)$/i', $image['name'])) {
						//prevent upload attacks
						$this->Session->setFlash(__('Image files must be in JPG, GIF, or PNG format.', true));
						$this->redirect($this->referer());
					}
					$this->data['Reward']['image']=$image;
					$this->data['Reward']['id']=$this->Reward->getLastInsertId();
					$this->Reward->storeImage($this->data['Reward']);
				}
				
				$this->Session->setFlash(__('The Reward has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Reward could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Reward', true));
			$this->redirect(array('action'=>'index'));
		}
		
		
		if (!empty($this->data)) {
			
			//prevent null error
			if (!$this->data['Reward']['rrp']) $this->data['Reward']['rrp']=0;
			
			if ($this->Reward->save($this->data)) {
				$this->Session->setFlash(__('The Reward has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Reward could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Reward->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Reward', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Reward->del($id)) {
			$this->Session->setFlash(__('Reward deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
	function admin_image($id = null, $delete = false) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Reward', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			
			//if ($this->Reward->save($this->data)) {
				
				//*** Processed attached images
				if (is_uploaded_file($this->data['Reward']['image']['tmp_name'])) {
					$this->Reward->storeImage($this->data['Reward']);
				}
				
				$this->Session->setFlash(__('The new image has been uploaded', true));
				$this->redirect(array('action'=>'index'));
			// } else {
				// $this->Session->setFlash(__('The new image could not be saved. Please, try again.', true));
			// }
		}
		if (empty($this->data)) {
			$this->data = $this->Reward->read(null, $id);
		}
	}
}
?>