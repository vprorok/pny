<?php
class InvitesController extends AppController{
	var $name = 'Invites';
	var $uses = array();
	//var $components = array('Importer');
	
	function import($service = null) {
		//App::import('Vendor', array('file' => APP.'vendors/openinviter/openinviter.php'));
		require(APP.'vendors/openinviter/openinviter.php');
    	
    		Configure::write('debug', 0);
    	
		$inviter = new OpenInviter();
		$oi_services=$inviter->getPlugins();
		
		$this->layout = 'ajax';
		
		$result  = '';
		$data = array();
		$data['login']    = !empty($_POST['login']) ? trim($_POST['login']) : null;
		$data['password'] = !empty($_POST['password']) ? trim($_POST['password']) : null;
		
		if(!empty($data['login']) && !empty($data['password'])){
		
			switch($service){
				case 'aol':
					$data['login'].='@aol.com';
					break;
				
				case 'gmail':
					$data['login'].='@gmail.com';
					break;
				
				case 'hotmail':
					$data['login'].='@hotmail.com';
					break;
				
				case 'msn_mail':
					$service='msn';
					$data['login'].='@msn.com';
					break;
				
				case 'yahoo':
					$data['login'].='@yahoo.com';
					break;
			}
		}
		
		$errors=''; $addresses=array();
		$inviter->startPlugin($service);
		$internal=$inviter->getInternalError();
		
		if (!$inviter->login($data['login'],$data['password'])) {
			$internal=$inviter->getInternalError();
			$errors=($internal?$internal: __("Login failed. Please check the username and password and try again.", true));
		} elseif (false===$contacts=$inviter->getMyContacts()) {
			$errors=__("A general error occurred loading the contacts, please try again.", true);
		}
		
		if (!empty($contacts)) {
			foreach ($contacts as $email=>$name) {
				$addresses[]=$email;
			}
		} else {
			if (!$errors) {
				$errors=__("Sorry, no contacts were found in this account.", true);
			}
		}
	
		$this->set('errors', $errors);
		$this->set('contacts', $addresses);
	
	}

	function index() {
		if (!empty($this->data)) {
			$emails = $this->data['Invite']['friends_email'];
			
			$data['to']       = explode(',', $emails);
			$data['template'] = 'invites/invite';
			$data['subject']  = __('Check out this website', true);
			$data['message']  = $this->data['Invite']['message'];
			$data['from'] 	  = $this->Auth->user('first_name').' '.$this->Auth->user('last_name').' <'.$this->Auth->user('email').'>';
			
			// lets make this send through mail to prevent spammers
			$data['delivery'] = 'mail';
			
			$this->_sendBulkEmail($data);
			
			$this->Session->setFlash(__('We have sent the email invitations to your friends.', true), 'default', array('class'=>'success'));
			$this->redirect(array('controller'=>'invites', 'action'=>'index'));
		} else {
			$this->data['Invite']['message'] = Configure::read('Settings.user_invite_message');
			$this->data['Invite']['message'] = str_replace('SITENAME', $this->appConfigurations['name'], $this->data['Invite']['message']);
			$this->data['Invite']['message'] = str_replace('URL', $this->appConfigurations['url'].'/users/register/'.$this->Auth->user('username'), $this->data['Invite']['message']);
			$this->data['Invite']['message'] = str_replace('SENDER', $this->Auth->user('first_name'), $this->data['Invite']['message']);
			$this->data['Invite']['message'] = str_replace('\n', "\n", $this->data['Invite']['message']);
		}
	}
}








?>