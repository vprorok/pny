<?php
class PagesController extends AppController {

	var $name = 'Pages';

	var $helpers = array('Fck', 'Recaptcha');
	var $components = array('Recaptcha');

	var $uses = array('Page', 'Department');

	function beforeFilter(){
		parent::beforeFilter();

		$this->cacheAction = Configure::read('Cache.time');
		if(!empty($this->Auth)){
			$this->Auth->allow('view', 'getpages', 'contact', 'suggestion');
		}
	}

	function view($slug = null) {
		if (!$slug) {
			$this->Session->setFlash(__('Invalid Page.', true));
			$this->redirect(array('action'=>'index'));
		}

		$page = $this->Page->findBySlug($slug);
		if(!empty($page)){
			$this->set('page', $page);
		}else{
			$this->Session->setFlash(__('Invalid Page.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->pageTitle = $page['Page']['title'];
		if(!empty($page['Page']['meta_description'])) {
			$this->set('meta_description', $page['Page']['meta_description']);
		}
		if(!empty($page['Page']['meta_keywords'])) {
			$this->set('meta_keywords', $page['Page']['meta_keywords']);
		}
	}

	function contact() {
		if(!empty($this->data)) {
			if($this->Recaptcha->isValid() || Configure::read('Recaptcha.enabled') == false) {
				$this->Page->set($this->data);
				if($this->Page->validates()) {
					$data['Page'] 	 			= $this->data['Page'];

					if(!empty($data['Page']['department_id'])) {
						$department = $this->Department->read(null, $data['Page']['department_id']);
						$data['Department'] = $department['Department'];
					}

					$data['delivery'] = 'mail';
					$data['from'] 	 = $this->data['Page']['name'].' <'.$this->data['Page']['email'].'>';


					if(!empty($data['Department']['email'])) {
						$data['to'] 			= $data['Department']['email'];
					} else {
						$data['to'] 			= $this->appConfigurations['email'];
					}

					$data['subject'] 			= sprintf(__('%s - Contact Form Submitted', true), $this->appConfigurations['name']);
					$data['template'] 			= 'pages/contact';
					$this->_sendEmail($data);

					$this->Session->setFlash(__('The contact form was successfully submitted.', true), 'default', array('class' => 'success'));
					$this->redirect('/contact');
				} else {
					$this->Session->setFlash(__('There was a problem submitting the contact form please review the errors below and try again.', true));
				}
			} else {
				$this->Session->setFlash(__('The captcha form was not valid, please try again.', true), 'default', array('class' => 'message'));
				$this->set('recaptchaError', $this->Recaptcha->error);
			}
    	}
    	$this->pageTitle = __('Contact Us', true);

    	$this->set('departments', $this->Department->find('list', array('order' => array('name' => 'asc'))));
	}

	function suggestion() {
		if(!empty($this->data)) {
			$this->Page->set($this->data);
			if($this->Page->validates()) {
				$data['Page'] 	 			= $this->data['Page'];
	
				if(Configure::read('Email.delivery') == 'smtp') {
					$data['from'] 	 		= $this->appConfigurations['email'];
				} else {
					$data['from'] 	 		= $this->data['Page']['name'].' <'.$this->data['Page']['email'].'>';
				}
	
				$data['to'] 				= Configure::read('App.email');
				$data['subject'] 			= sprintf(__('%s - Suggestion Form Submitted', true), $this->appConfigurations['name']);
				$data['template'] 			= 'pages/suggestion';
				$this->_sendEmail($data);
	
				$this->Session->setFlash(__('The suggestion form was successfully submitted.', true), 'default', array('class' => 'success'));
				$this->redirect('/suggestion');
			} else {
				$this->Session->setFlash(__('There was a problem submitting the suggestion form please review the errors below and try again.', true));
			}
		}
		$this->pageTitle = __('Suggestion Box', true);
	
		$this->set('departments', $this->Department->find('list', array('order' => array('name' => 'asc'))));
	}

	function admin_index() {
		$this->set('topPages', $this->Page->find('all', array('conditions' => array('top_show' => 1), 'order' => array('top_order' => 'asc'))));
		$this->set('bottomPages', $this->Page->find('all', array('conditions' => array('bottom_show' => 1), 'order' => array('bottom_order' => 'asc'))));
		$this->set('staticPages', $this->Page->find('all', array('conditions' => array('top_show' => 0, 'bottom_show' => 0), 'order' => array('name' => 'asc'))));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Page->create();
			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('The page has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the page please review the errors below and try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Page', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('The page has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem saving the page please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Page->read(null, $id);
		}
	}

	function admin_saveorder($position = 'top') {
		$this->layout = 'js/ajax';
		$message = '';

		if(!empty($_POST)){
			$data = $_POST;

			foreach($data['page'] as $order => $id){
				$page['Page']['id'] = $id;
				$page['Page'][$position.'_show']  = 1;
				$page['Page'][$position.'_order'] = $order;

				$this->Page->save($page);
			}

			$message = __('Page order has been saved', true);
		}

		$this->set('message', $message);
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Page', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Page->del($id)) {
			$this->Session->setFlash(__('The page was successfully deleted.', true));
			$this->redirect(array('action' => 'index'));
		}
	}

}
?>
