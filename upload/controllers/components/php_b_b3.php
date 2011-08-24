<?php
class PhpBB3Component extends Object {

	var $controller;
	var $model;

	function startup(&$controller) {
		// only fire up this component if it is required
		if(!Configure::read('App.forum')){
			return false;
		}
		
		$this->controller = &$controller;

		define('IN_PHPBB', true);

		global $phpbb_root_path, $phpEx, $db, $config, $user, $auth, $cache, $template;

		$phpbb_root_path = WWW_ROOT . 'forum/';
		$phpEx = substr(strrchr(__FILE__, '.'), 1);
		require_once($phpbb_root_path . 'common.' . $phpEx);

		$this->table_prefix = $table_prefix;
		$this->auth = $auth;
		$this->user = $user;

		// Start session management
		$this->user->session_begin();
		$this->auth->acl($user->data);
		$this->user->setup();

		require_once($phpbb_root_path .'includes/functions_user.php');
	}

	private function checkUserExists($username, $isFalse = false) {

		if (user_get_id_name($isFalse, $username) == 'NO_USERS') {
			return false;
		} else {
			return true;
		}

	}

	// Helper Methods

	/**
	 * Generate salt for hash generation
	 */
	private function _hash_gensalt_private($input,&$itoa64,$iteration_count_log2 = 6) {
		if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31) {
			$iteration_count_log2 = 8;
		}

		$output = '$H$';
		$output .= $itoa64 [min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
		$output .= $this->_hash_encode64($input, 6, $itoa64);

		return $output;
	}

	/**
	 * Encode hash
	 */
	private function _hash_encode64($input,$count,&$itoa64) {

		$output = '';
		$i = 0;

		do {
			$value = ord ( $input [$i ++] );
			$output .= $itoa64 [$value & 0x3f];

			if ($i < $count) {
				$value |= ord ( $input [$i] ) << 8;
			}

			$output .= $itoa64 [($value >> 6) & 0x3f];

			if ($i ++ >= $count) {
				break;
			}

			if ($i < $count) {
				$value |= ord ( $input [$i] ) << 16;
			}

			$output .= $itoa64 [($value >> 12) & 0x3f];

			if ($i ++ >= $count) {
				break;
			}

			$output .= $itoa64 [($value >> 18) & 0x3f];
		} while ( $i < $count );

		return $output;
	}

	/**
	 * The crypt function/replacement
	 */
	private function _hash_crypt_private($password,$setting,&$itoa64) {
		$output = '*';

		// Check for correct hash
		if (substr ( $setting, 0, 3 ) != '$H$') {
			return $output;
		}

		$count_log2 = strpos ( $itoa64, $setting [3] );

		if ($count_log2 < 7 || $count_log2 > 30) {
			return $output;
		}

		$count = 1 << $count_log2;
		$salt = substr ( $setting, 4, 8 );

		if (strlen ( $salt ) != 8) {
			return $output;
		}

		/**
		 * We're kind of forced to use MD5 here since it's the only
		 * cryptographic primitive available in all versions of PHP
		 * currently in use.  To implement our own low-level crypto
		 * in PHP would result in much worse performance and
		 * consequently in lower iteration counts and hashes that are
		 * quicker to crack (by non-PHP code).
		 */
		if (PHP_VERSION >= 5) {
			$hash = md5 ( $salt . $password, true );
			do {
				$hash = md5 ( $hash . $password, true );
			} while ( -- $count );
		} else {
			$hash = pack ( 'H*', md5 ( $salt . $password ) );
			do {
				$hash = pack ( 'H*', md5 ( $hash . $password ) );
			} while ( -- $count );
		}

		$output = substr ( $setting, 0, 12 );
		$output .= $this->_hash_encode64 ( $hash, 16, $itoa64 );

		return $output;
	}

	private function unique_id($extra = 'c') {
		static $dss_seeded = false;
		global $config;

		$val = $config ['rand_seed'] . microtime ();
		$val = md5 ( $val );
		$config ['rand_seed'] = md5 ( $config ['rand_seed'] . $val . $extra );

		$dss_seeded = true;
		return substr ( $val, 4, 16 );
	}


	private function phpbb_hash($password) {

		$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$random_state = $this->unique_id();
		$random = '';
		$count = 6;

		if (($fh = @fopen ( '/dev/urandom', 'rb' ))) {
			$random = fread ($fh, $count);
			fclose ($fh);
		}

		if (strlen($random) < $count) {
			$random = '';
			for($i=0;$i<$count;$i+=16) {
				$random_state = md5($this->unique_id () . $random_state);
				$random .= pack('H*', md5($random_state));
			}
			$random = substr($random, 0, $count);
		}

		$hash = $this->_hash_crypt_private($password, $this->_hash_gensalt_private($random, $itoa64 ), $itoa64);

		if (strlen($hash) == 34) {
			return $hash;
		}

		return md5($password);
	}

	public function login($username, $password, $email) {

		if ($this->checkUserExists($username) == false) {

			$user_row = array(
				'username' => $username,
				'user_password' => md5($password),
				'user_email' => $email,
				'group_id' => 2, //Registered users group
				'user_timezone' => '1.00',
				'user_dst' => 0,
				'user_lang' => 'en',
				'user_type' => '0',
				'user_actkey' => '',
				'user_dateformat' => 'd M Y H:i',
				'user_style' => 1,
				'user_regdate' => time(),
			);

			user_add($user_row);

		}

		$this->auth->login($username, $password);

	}

	public function changePassword($username, $password) {
		if ($this->checkUserExists($username) == true) {
			global $db;
			$sql = "UPDATE `" . $this->table_prefix . "users` SET user_password = '" . $this->phpbb_hash($password) . "' WHERE username = '" . $username . "'";
			$db->sql_query($sql);
			$this->logout();
		}
	}

	public function logout() {
		$this->user->session_kill();
		$this->user->session_begin();
	}

}
?>