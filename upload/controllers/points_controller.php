<?php
class PointsController extends AppController {

	var $name = 'Points';
	
	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Point->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);
		
		if (!empty($this->data)) {
			$this->data['Point']['user_id'] = $user_id;
			
			if(empty($this->data['Point']['id'])) {
				$this->Point->create();
			}

			if($this->Point->save($this->data)) {
				$this->Session->setFlash(__('The points has been updated successfully.', true));
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the points please review the errors below and try again.', true));
			}
		} else {
			$this->data = $this->Point->find('first', array('conditions' => array('Point.user_id' => $user_id)));
			if(empty($this->data['Point']['points'])) {
				$this->data['Point']['points'] = 0;
			}
		}
	}
}
?>