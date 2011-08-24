<?php
class Translation extends AppModel {

	var $name = 'Translation';

	var $actsAs = array('Containable');

	var $belongsTo = array('Language', 'Product');

	/**
	* Constructor, redefine to use __() in validate message
	*/
	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'language_id' => array(
				'rule' => array('minLength', 1),
				'message' => __('Language is required.', true)
			)
		);
	}

	/**
	* Function to conduct translations
	*
	* @param array $auctions
	* @return array Auctions array
	*/
	function translate($auctions) {
		foreach($auctions as $key => $auction) {
	 		if(!empty($auction['Product'])) {
	 			$lang = $this->find('first', array('conditions' => array('Translation.language_id' => Configure::read('Lang.id'), 'Translation.product_id' => $auction['Product']['id']), 'contain' => ''));
	 			if(!empty($lang['Translation']['title'])) {
	 				$auctions[$key]['Product']['title'] = $lang['Translation']['title'];
	 			}
	 			if(!empty($lang['Translation']['brief'])) {
	 				$auctions[$key]['Product']['brief'] = $lang['Translation']['brief'];
	 			}
	 			if(!empty($lang['Translation']['description'])) {
	 				$auctions[$key]['Product']['description'] = $lang['Translation']['description'];
	 			}
	 			if(!empty($lang['Translation']['meta_description'])) {
	 				$auctions[$key]['Product']['meta_description'] = $lang['Translation']['meta_description'];
	 			}
	 			if(!empty($lang['Translation']['meta_keywords'])) {
	 				$auctions[$key]['Product']['meta_keywords'] = $lang['Translation']['meta_keywords'];
	 			}
	 			if(!empty($lang['Translation']['delivery_information'])) {
	 				$auctions[$key]['Product']['delivery_information'] = $lang['Translation']['delivery_information'];
	 			}
	 		} elseif(!empty($auction['Auction']['Product'])) {
	 			$lang = $this->find('first', array('conditions' => array('Translation.language_id' => Configure::read('Lang.id'), 'Translation.product_id' => $auction['Auction']['Product']['id']), 'contain' => ''));
	 			if(!empty($lang['Translation']['title'])) {
	 				$auctions[$key]['Auction']['Product']['title'] = $lang['Translation']['title'];
	 			}
	 			if(!empty($lang['Translation']['brief'])) {
	 				$auctions[$key]['Auction']['Product']['brief'] = $lang['Translation']['brief'];
	 			}
	 			if(!empty($lang['Translation']['description'])) {
	 				$auctions[$key]['Auction']['Product']['description'] = $lang['Translation']['description'];
	 			}
	 			if(!empty($lang['Translation']['meta_description'])) {
	 				$auctions[$key]['Auction']['Product']['meta_description'] = $lang['Translation']['meta_description'];
	 			}
	 			if(!empty($lang['Translation']['meta_keywords'])) {
	 				$auctions[$key]['Auction']['Product']['meta_keywords'] = $lang['Translation']['meta_keywords'];
	 			}
	 			if(!empty($lang['Translation']['delivery_information'])) {
	 				$auctions[$key]['Auction']['Product']['delivery_information'] = $lang['Translation']['delivery_information'];
	 			}
	 		}
	 	}
	 	return $auctions;
	 }
}
?>
