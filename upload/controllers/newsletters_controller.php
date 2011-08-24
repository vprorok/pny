<?php
class NewslettersController extends AppController {

	var $name = 'Newsletters';
	var $helpers = array('Fck');
	var $uses = array('Newsletter', 'User');

	function beforeFilter(){
		parent::beforeFilter();

		if(isset($this->Auth)){
			$this->Auth->allow('unsubscribe');
		}
	}

	function _replaceImageSource($source){
		// prepend any src with app.url here
		preg_match_all('/<img.*src="(.*?)"/', $source, $matches);
		if(!empty($matches[1])){
			foreach($matches[1] as $pattern){
				// Only prepend the src if it doesn't have http which means
				// already has url or it's another site url
				if(!substr_count($pattern, 'http://')){
					$source = str_replace($pattern, $this->appConfigurations['url'].$pattern, $source);
				}
			}
		}

		return $source;
	}

	function unsubscribe($email){
		$this->User->recursive = -1;
		$user = $this->User->findByEmail($email);
		if(!empty($user)){
			$user['User']['newsletter'] = 0;
			if($this->User->save($user)){
				$this->Session->setFlash(__('You have been unsubscribed from our newsletter.', true));
			}else{
				$this->Session->setFlash(__('There was a problem while saving your preferences. Please try again.', true));
			}
		}else{
			$this->Session->setFlash(__('Invalid email.', true));
		}

		$this->redirect('/');
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Newsletter.created' => 'desc'));
		$this->set('users', $this->paginate());

		$this->set('newsletters', $this->paginate());
	}

	function admin_send($id = null) {
		if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
			$this->demoDisabled();
		}
		
		$data = array();

		$newsletter = $this->Newsletter->read(null, $id);
		if(empty($newsletter)) {
			$this->Session->setFlash(__('Invalid Newsletter.', true));
			$this->redirect(array('action'=>'index'));
		}

		$newsletterUsers = $this->User->find('all', array('conditions' => array('User.newsletter' => 1), 'contain' => ''));
		
		if(!empty($newsletterUsers)) {
			$newsletter['Newsletter']['sent'] = 1;
			$newsletter['Newsletter']['modified'] = date('Y-m-d H:i:s');

			$this->Newsletter->save($newsletter);

			$data['template'] = 'newsletters/send';
			$data['layout'] = 'newsletter';

			// prepend any src with app.url here
			$newsletter['Newsletter']['body'] = $this->_replaceImageSource($newsletter['Newsletter']['body']);

			foreach($newsletterUsers as $user) {
				$body 		= $newsletter['Newsletter']['body'];
				$subject 	= $newsletter['Newsletter']['subject'];

				// lets add in our pre defined variables
				$body = str_replace('{first_name}', $user['User']['first_name'], $body);
				$subject = str_replace('{first_name}', $user['User']['first_name'], $subject);

				$body = str_replace('{last_name}', $user['User']['last_name'], $body);
				$subject = str_replace('{last_name}', $user['User']['last_name'], $subject);

				$body = str_replace('{email}', $user['User']['email'], $body);
				$subject = str_replace('{email}', $user['User']['email'], $subject);

				$body = str_replace('{username}', $user['User']['username'], $body);
				$subject = str_replace('{username}', $user['User']['username'], $subject);

				$this->set('body', $body);

				$data['subject'] = $subject;
				$data['to']=$user['User']['email'];
				$this->_sendEmail($data);
			}
			$this->Session->setFlash(__('The newsletter has been sent successfully.', true));
			$this->redirect(array('action'=>'index'));
		} else {
			$this->Session->setFlash(__('No users are subscribed to the newsletter, so the newsletter was NOT sent.', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_test($id = null) {
		if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
			$this->demoDisabled();
		}
		
		$newsletter = $this->Newsletter->read(null, $id);
		if(empty($newsletter)) {
			$this->Session->setFlash(__('Invalid Newsletter.', true));
			$this->redirect(array('action'=>'index'));
		}

		$data['template'] = 'newsletters/send';
		$data['layout'] = 'newsletter';

		// prepend any src with app.url here
		$newsletter['Newsletter']['body'] = $this->_replaceImageSource($newsletter['Newsletter']['body']);

		$data['to'] = $this->appConfigurations['email'];

		$body 		= $newsletter['Newsletter']['body'];
		$subject 	= $newsletter['Newsletter']['subject'];

		$this->set('body', $body);

		$data['subject'] = $subject;

		$this->_sendEmail($data);

		$this->Session->setFlash(__('The newsletter has been sent as a test.', true));
		$this->redirect(array('action'=>'index'));
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Newsletter.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('newsletter', $this->Newsletter->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Newsletter->create();
			//$this->data['Newsletter']['created']=date('Y-m-d H:i:s');
			if ($this->Newsletter->save($this->data)) {
				$this->Session->setFlash(__('The Newsletter has been added and can be sent by pressing the \'send\' button next to the newsletter below.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Newsletter could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Newsletter', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			//$this->data['Newsletter']['modified']=date('Y-m-d H:i:s');
			if ($this->Newsletter->save($this->data)) {
				$this->Session->setFlash(__('The Newsletter has been saved.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Newsletter could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Newsletter->read(null, $id);
		}
	}

	function admin_exportsubscribers(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$users = $this->User->find('all', array('conditions' => array('User.newsletter' => 1), 'order' => 'User.id ASC'));
		if(!empty($users)){
			$this->set('users', $users);
		}else{
			$this->Session->setFlash(__('There is no user which subscribe to newsletter', true));
			$this->redirect(array('action' => 'index'));
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Newsletter', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Newsletter->del($id)) {
			$this->Session->setFlash(__('Newsletter deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
