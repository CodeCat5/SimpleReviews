<?php

class Users extends CI_Model
{
	public $fields = array(
		'email' => 1,
		'password' => 0,
		'first_name' => 1,
		'last_name' => 1,
		'access_level' => 1
	);

	/**
	 * Log the user in
	 * @param $email
	 * @param $password
	 * @return bool
	 */
	public function login($email, $password)
	{
		$query = $this->db->get_where('users', array('email' => $email));
		$user = $query->row_array();

		if (password_verify($password, $user['password'])) {
			session_name('loginSession');
			session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], false, true);
			session_start();

			$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['user'] = $user;
			return true;
		}

		return false;
	}

	/**
	 * Make sure the session matches
	 * @param $area
	 * @return bool
	 */
	public function validate_session($area)
	{
		if (!isset($_SESSION['IPaddress'])
			OR !isset($_SESSION['userAgent'])
			OR $_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']
			OR $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']
		){
			return false;
		}

		$timestamp = time();

		// Regenerate sessionids after 30 min
		if ($_SESSION['user']['userid']) {
			if (!isset($_SESSION['CREATED'])) {
				$_SESSION['CREATED'] = $timestamp;
			} else if ($_SESSION['CREATED'] - $timestamp > 1800) {
				session_regenerate_id(true);
				$_SESSION['CREATED'] = $timestamp;
			}
		}

		if ($_SESSION['user']['access_level'] == 'admin' OR $_SESSION['user']['access_level'] == $area)
		{
			return true;
		}

		return false;
	}


	/**
	 * Verify the user has access
	 * @param $area
	 * @return bool
	 */
	public function verify_access($area)
	{
		if ($this->validateSession($area)) {
			return true;
		}

		$this->redirect('/managercp');
	}

	/**
	 * Get all users
	 * @return array
	 */
	public function get_all()
	{
		$query = $this->db->get('users');
		return $query->results_array();
	}
}