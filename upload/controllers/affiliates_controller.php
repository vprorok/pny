<?php
class AffiliatesController extends AppController {

	var $name = 'Affiliates';

	function index() {
		$this->paginate = array('conditions' => array('Affiliate.affiliate_id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Affiliate.created' => 'desc'));
		$this->set('affiliates', $this->paginate());

		$this->pageTitle = __('Affiliate Earnings', true);
		
		$affiliateBalance = $this->Affiliate->balance($this->Auth->user('id'));
		$this->set('affiliateBalance', $affiliateBalance);
		
		if(!empty($this->data)) {
			$this->data['Affiliate']['affiliateBalance'] = $affiliateBalance;
			$this->Affiliate->set($this->data);
    		if($this->Affiliate->validates()) {
    			// lets update the affiliate account balance
    			$affiliate = array();
    			$affiliate['Affiliate']['affiliate_id'] = $this->Auth->user('id');
    			$affiliate['Affiliate']['description'] 	= __('Withdrawal Made', true);
    			$affiliate['Affiliate']['debit']		= $this->data['Affiliate']['balance'];
    			
    			$this->Affiliate->create();
    			$this->Affiliate->save($affiliate);
    			
    			$user = $this->Affiliate->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'contain' => ''));
    			
    			// lets sent an email to the website owner to tell them to make the payment
    			$data['User'] 			   	   = $user['User'];
    			$data['Affiliate'] 			   = $affiliate['Affiliate'];
				$data['Affiliate']['email']	   = $this->data['Affiliate']['email'];
				$data['to'] 				   = $this->appConfigurations['email'];
				$data['subject'] 			   = sprintf(__('%s - An Affiliate has made a Cash Withdrawal Request', true), $this->appConfigurations['name']);
				$data['template'] 			   = 'affiliates/withdraw';
				$this->_sendEmail($data);
    			
    			// finally lets redirect them back
    			$this->Session->setFlash(__('Your withdrawal request was made and will be actioned within 3 days.', true));
    			$this->redirect(array('action'=>'index'));
    		}
		} else {
			$this->data['Affiliate']['balance'] = $affiliateBalance;
			$this->data['Affiliate']['email'] 	= $this->Auth->user('email');
		}
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Affiliate->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Affiliate.affiliate_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Affiliate.created' => 'desc'));
		
		$this->set('affiliates', $this->paginate());
		
		$this->set('affiliateBalance', $this->Affiliate->balance($user_id));
	}
}
?>