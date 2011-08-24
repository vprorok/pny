<?php
class Point extends AppModel{
    var $name = 'Point';
    
    var $belongsTo = 'User';

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'points' => array(
				'comparison' => array(
					'rule'=> array('comparison', 'isgreater', 0),
					'message' => __('The minimum points must be a positive number.', true)
				),
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('The number of points can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Number of points is required.', true)
				)
			),
		);

	}

    function balance($user_id = null){
        $balance = 0;

        if(!empty($user_id)){
            $point = $this->findByUserId($user_id);
            if(!empty($point)){
                $balance = $point['Point']['points'];
            }
        }

        return $balance;
    }
}
?>
