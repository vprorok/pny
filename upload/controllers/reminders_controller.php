<?php
class RemindersController extends AppController{
    var $name = 'Reminders';

    function add($auction_id = null){
        if(!empty($auction_id)){
            $reminder = $this->Reminder->find('first', array('conditions' => array('Reminder.auction_id' => $auction_id,
                                                                                   'Reminder.user_id' => $this->Auth->user('id'))));

            if(empty($reminder)){
                $auction = $this->Reminder->Auction->findById($auction_id);
                if(!empty($auction)){
                    if(strtotime($auction['Auction']['start_time']) > time()){
                        $reminder['Reminder']['auction_id'] = $auction_id;
                        $reminder['Reminder']['user_id']    = $this->Auth->user('id');
                        if($this->Reminder->save($reminder)){
                            $this->Session->setFlash(__('Reminder has been added.', true), 'default', array('class' => 'success'));
                            $this->redirect($this->referer('/'));
                        }else{
                            $this->Session->setFlash(__('Reminder cannot be added. Please try again.', true));
                            $this->redirect($this->referer('/'));
                        }
                    }else{
                        $this->Session->setFlash(__('Reminder cannot be added. This auction has been started.', true));
                        $this->redirect($this->referer('/'));
                    }
                }
            }else{
                $this->Session->setFlash(__('You already add a reminder for this auction.', true));
                $this->redirect($this->referer('/'));
            }
        }

        $this->Session->setFlash(__('Reminder cannot be added. Invalid auction.', true));
        $this->redirect($this->referer('/'));
    }
}
?>