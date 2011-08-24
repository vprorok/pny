<?php
class AppModel extends Model{

	var $appConfigurations;

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->appConfigurations = Configure::read('App');
	}

	/**
	 * Function to get price rate used for beforeSave and afterFind
	 *
	 * @return float The rate which user choose
	 */
	function _getRate(){
		$currency = strtolower($this->appConfigurations['currency']);
		$rate 	  = Cache::read('currency_'.$currency.'_rate');

		if(!empty($rate)){
			return $rate;
		}else{
			return 1;
		}
	}

    /**
	* This function generates a unique slug
	*
	* @param $title
	* @param $id
	* @return $slug
	*/
	function generateNiceName($title, $id = null) {
		$title = strtolower($title);
		$nice_name = Inflector::slug($title, '-');

		if(!empty($id)) {
			 $conditions = array('conditions' => array($this->name.'.slug' => $nice_name, $this->name.'.id' => '<> '.$id));
		} else {
			 $conditions = array('conditions' => array($this->name.'.slug' => $nice_name));
		}

		$total = $this->find('count', $conditions);
		if($total > 0) {
			for($number = 2; $number > 0; $number ++) {
				if(!empty($id)) {
					 $conditions = array('conditions' => array($this->name.'.slug' => $nice_name.'-'.$number, $this->name.'.id' => '<> '.$id));
				} else {
					 $conditions = array('conditions' => array($this->name.'.slug' => $nice_name.'-'.$number));
				}

				$total = $this->find('count', $conditions);
				if($total == 0) {
					$nice_name = $nice_name.'-'.$number;
					$number = -1;
				}
			}
		}

		return $nice_name;
	}

	/**
	* This function checks that the field is unique taking into account the $id
	*
	* @param $data
	* @param $fieldName
	* @return true if valid, false otherwise
	*/
	function checkUnique($data, $fieldName) {
        $valid = false;
        if(!empty($fieldName) && $this->hasField($fieldName)) {
            if(!empty($this->data[$this->name]['id'])) {
            	$conditions = array($this->name.'.'.$fieldName => $data[$fieldName], $this->name.'.id <>' => $this->data[$this->name]['id']);
            	$valid = $this->isUnique($conditions, false);
            } else {
            	$conditions = array($this->name.'.'.$fieldName => $data[$fieldName]);
            }
            $valid = $this->isUnique($conditions, false);
        }
        return $valid;
	}

	/**
	* This function matches two fields
	*
	* @param $data
	* @param $fieldName1 (no longer required)
	* @param $fieldName2
	* @return true if they match, false otherwise
	*/
	function matchFields($data = array(), $compare_field) {
        foreach($data as $key => $value ){
            $v1 = $value;
            
            if(!empty($this->data[$this->name][$compare_field])) {
	            $v2 = $this->data[$this->name][$compare_field];
	            if($v1 !== $v2) {
	                return false;
	            } else {
	                continue;
	            }
            } else {
            	continue;
            }
        }
        return true; 
	}

	/**
	* This function is used for
	*
	* @param $data
	* @return true if they match, false otherwise
	*/
	function custom($data) {
        if(Configure::read('Validation')) {
        	foreach ($data as $field => $value) {
        		if(!empty($value)) {
        			if($field == 'postcode') {
						if($regex = Configure::read('Validation.custom_rule_postcode')){
							if(preg_match($regex, $value)) {
        						return true;
        					} else {
        						return false;
        					}
						}elseif(Configure::read('Validation.postcode') == 'XXXX-XXX') {
        					if(preg_match('/^[0-9]{4}-[0-9]{3}$/', $value)) {
        						return true;
        					} else {
        						return false;
        					}
        				} else {
        					return true;
        				}
        			} else {
        				// can add future add ons here
        				return true;
        			}
        		} else {
        			return true;
        		}
        	}
       	} else {
       		return true;
       	}
	}

	/**
	 * validate alphanumeric for php compatibility sanity
	 *
	 */
	function alphaNumeric($data) {
		$val = array_shift(array_values($data));
		if (ereg('[^A-Za-z0-9]', $val)) {
			return false;
		}else{
			return true;
		}
	}
	
	function getEndTime() {
		return date('Y-m-d H:i:s', strtotime('-1 minute'));
	}
}
?>