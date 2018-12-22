<?php
/**
 * Name:    Toolcals Model
 * Author:  Buu Tran
 *           buubecas@gmail.com
 * @benedmunds
 *
 * Added Awesomeness: Buu Tran
 *
 * Created:  03.10.2018
 *
 * Description:  
 *
 * Requirements: PHP5 or above
 *
 * @package    CodeIgniter-Toolcals
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Toolcals Model
 * @property Bcrypt $bcrypt The Bcrypt library
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Toolcals_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 */
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 */
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 */
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 */
	public $identity;

	/**
	 * Where
	 *
	 * @var array
	 */
	public $_ion_where = array();

	/**
	 * Select
	 *
	 * @var array
	 */
	public $_ion_select = array();

	/**
	 * Like
	 *
	 * @var array
	 */
	public $_ion_like = array();

	/**
	 * Limit
	 *
	 * @var string
	 */
	public $_ion_limit = NULL;

	/**
	 * Offset
	 *
	 * @var string
	 */
	public $_ion_offset = NULL;

	/**
	 * Order By
	 *
	 * @var string
	 */
	public $_ion_order_by = NULL;

	/**
	 * Order
	 *
	 * @var string
	 */
	public $_ion_order = NULL;

	/**
	 * Hooks
	 *
	 * @var object
	 */
	protected $_ion_hooks;

	/**
	 * Response
	 *
	 * @var string
	 */
	protected $response = NULL;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 */
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 */
	protected $errors;

	/**
	 * error start delimiter
	 *
	 * @var string
	 */
	protected $error_start_delimiter;

	/**
	 * error end delimiter
	 *
	 * @var string
	 */
	protected $error_end_delimiter;

	/**
	 * caching of users and their toolcals
	 *
	 * @var array
	 */
	public $_cache_user_in_toolcal = array();

	/**
	 * caching of toolcals
	 *
	 * @var array
	 */
	protected $_cache_toolcals = array();

	public function __construct()
	{
		$this->load->database();
		$this->config->load('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');

		// initialize db tables data
		$this->tables = $this->config->item('tables', 'ion_auth');

		// initialize data
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length = $this->config->item('salt_length', 'ion_auth');
		$this->join = $this->config->item('join', 'ion_auth');

		// initialize hash method options (Bcrypt)
		$this->hash_method = $this->config->item('hash_method', 'ion_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
		$this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
		$this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
		$this->max_rounds = $this->config->item('max_rounds', 'ion_auth');

		// initialize messages and error
		$this->messages    = array();
		$this->errors      = array();
		$delimiters_source = $this->config->item('delimiters_source', 'ion_auth');

		// load the error delimeters either from the config file or use what's been supplied to form validation
		if ($delimiters_source === 'form_validation')
		{
			// load in delimiters from form_validation
			// to keep this simple we'll load the value using reflection since these properties are protected
			$this->load->library('form_validation');
			$form_validation_class = new ReflectionClass("CI_Form_validation");

			$error_prefix = $form_validation_class->getProperty("_error_prefix");
			$error_prefix->setAccessible(TRUE);
			$this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
			$this->message_start_delimiter = $this->error_start_delimiter;

			$error_suffix = $form_validation_class->getProperty("_error_suffix");
			$error_suffix->setAccessible(TRUE);
			$this->error_end_delimiter = $error_suffix->getValue($this->form_validation);
			$this->message_end_delimiter = $this->error_end_delimiter;
		}
		else
		{
			// use delimiters from config
			$this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
			$this->message_end_delimiter = $this->config->item('message_end_delimiter', 'ion_auth');
			$this->error_start_delimiter = $this->config->item('error_start_delimiter', 'ion_auth');
			$this->error_end_delimiter = $this->config->item('error_end_delimiter', 'ion_auth');
		}

		// initialize our hooks object
		$this->_ion_hooks = new stdClass;

		// load the bcrypt class if needed
		if ($this->hash_method == 'bcrypt')
		{
			if ($this->random_rounds)
			{
				$rand = rand($this->min_rounds,$this->max_rounds);
				$params = array('rounds' => $rand);
			}
			else
			{
				$params = array('rounds' => $this->default_rounds);
			}

			$params['salt_prefix'] = $this->config->item('salt_prefix', 'ion_auth');
			$this->load->library('bcrypt',$params);
		}

		$this->trigger_events('model_constructor');
	}

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @param string $password
	 * @param bool   $salt
	 * @param bool   $use_sha1_override
	 *
	 * @return false|string
	 * @author Mathew
	 */
	public function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			return $this->bcrypt->hash($password);
		}


		if ($this->store_salt && $salt)
		{
			return sha1($password . $salt);
		}
		else
		{
			$salt = $this->salt();
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @param string|int $id
	 * @param string     $password
	 * @param bool       $use_sha1_override
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('password, salt')
		                  ->where('id', $id)
		                  ->limit(1)
		                  ->order_by('id', 'desc')
		                  ->get($this->tables['users']);

		$hash_password_db = $query->row();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password,$hash_password_db->password))
			{
				return TRUE;
			}

			return FALSE;
		}

		// sha1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);

			$db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}

		if($db_password == $hash_password_db->password)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @param string $password
	 *
	 * @return false|string
	 * @author Mathew
	 */
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 *
	 * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
	 *
	 * @return bool|string
	 * @author Anthony Ferrera
	 */
	public function salt()
	{
		$raw_salt_len = 16;

		$buffer = '';
		$buffer_valid = FALSE;

		if (function_exists('random_bytes'))
		{
			$buffer = random_bytes($raw_salt_len);
			if ($buffer)
			{
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER'))
		{
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
			if ($buffer)
			{
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes'))
		{
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
			if ($buffer)
			{
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && @is_readable('/dev/urandom'))
		{
			$f = fopen('/dev/urandom', 'r');
			$read = strlen($buffer);
			while ($read < $raw_salt_len)
			{
				$buffer .= fread($f, $raw_salt_len - $read);
				$read = strlen($buffer);
			}
			fclose($f);
			if ($read >= $raw_salt_len)
			{
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid || strlen($buffer) < $raw_salt_len)
		{
			$bl = strlen($buffer);
			for ($i = 0; $i < $raw_salt_len; $i++)
			{
				if ($i < $bl)
				{
					$buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
				}
				else
				{
					$buffer .= chr(mt_rand(0, 255));
				}
			}
		}

		$salt = $buffer;

		// encode string with the Base64 variant used by crypt
		$base64_digits = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string = base64_encode($salt);
		$salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

		$salt = substr($salt, 0, $this->salt_length);

		return $salt;
	}

	/**
	 * Validates and removes activation code.
	 *
	 * @param int|string $id
	 * @param bool       $code
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function activate($id, $code = FALSE)
	{
		$this->trigger_events('pre_activate');

		if ($code !== FALSE)
		{
			$query = $this->db->select($this->identity_column)
			                  ->where('activation_code', $code)
			                  ->where('id', $id)
			                  ->limit(1)
			                  ->order_by('id', 'desc')
			                  ->get($this->tables['users']);

			$query->row();

			if ($query->num_rows() !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('activate_unsuccessful');
				return FALSE;
			}

			$data = array(
			    'activation_code' => NULL,
			    'active'          => 1
			);

			$this->trigger_events('extra_where');
			$this->db->update($this->tables['users'], $data, array('id' => $id));
		}
		else
		{
			$data = array(
			    'activation_code' => NULL,
			    'active'          => 1
			);

			$this->trigger_events('extra_where');
			$this->db->update($this->tables['users'], $data, array('id' => $id));
		}

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}

		return $return;
	}


	/**
	 * Updates a users row with an activation code.
	 *
	 * @param int|string|null $id
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}
		else if ($this->logged_in() && $this->user()->row()->id == $id)
		{
			$this->set_error('deactivate_current_user_unsuccessful');
			return FALSE;
		}

		$activation_code = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data = array(
		    'activation_code' => $activation_code,
		    'active'          => 0
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array('id' => $id));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->set_message('deactivate_successful');
		}
		else
		{
			$this->set_error('deactivate_unsuccessful');
		}

		return $return;
	}

	/**
	 * Finds the user with the given forgotten password code and clears the forgotten password fields
	 *
	 * @param string $code
	 *
	 * @return bool Success
	 */
	public function clear_forgotten_password_code($code) {

		if (empty($code))
		{
			return FALSE;
		}

		$this->db->where('forgotten_password_code', $code);

		if ($this->db->count_all_results($this->tables['users']) > 0)
		{
			$data = array(
			    'forgotten_password_code' => NULL,
			    'forgotten_password_time' => NULL
			);

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Reset password
	 *
	 * @param    string $identity
	 * @param    string $new
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function reset_password($identity, $new) {
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		                  ->order_by('id', 'desc')
		                  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result = $query->row();

		$new = $this->hash_password($new, $result->salt);

		// store the new password and reset the remember code so all remembered instances have to re-login
		// also clear the forgotten password code
		$data = array(
		    'password' => $new,
		    'remember_code' => NULL,
		    'forgotten_password_code' => NULL,
		    'forgotten_password_time' => NULL,
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $return;
	}

	/**
	 * Change password
	 *
	 * @param    string $identity
	 * @param    string $old
	 * @param    string $new
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');

		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		                  ->order_by('id', 'desc')
		                  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$user = $query->row();

		$old_password_matches = $this->hash_password_db($user->id, $old);

		if ($old_password_matches === TRUE)
		{
			// store the new password and reset the remember code so all remembered instances have to re-login
			$hashed_new_password  = $this->hash_password($new, $user->salt);
			$data = array(
			    'password' => $hashed_new_password,
			    'remember_code' => NULL,
			);

			$this->trigger_events('extra_where');

			$successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
			if ($successfully_changed_password_in_db)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}

			return $successfully_changed_password_in_db;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

	/**
	 * Checks username
	 *
	 * @param string $username
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function username_check($username = '')
	{
		$this->trigger_events('username_check');

		if (empty($username))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->where('username', $username)
						->limit(1)
						->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Checks email
	 *
	 * @param string $email
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function email_check($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->where('email', $email)
						->limit(1)
						->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function identity_check($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		return $this->db->where($this->identity_column, $identity)
						->limit(1)
						->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @param    string $identity
	 *
	 * @return    bool
	 * @author  Mathew
	 * @updated Ryan
	 */
	public function forgotten_password($identity)
	{
		if (empty($identity))
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			return FALSE;
		}

		// All some more randomness
		$activation_code_part = "";
		if (function_exists("openssl_random_pseudo_bytes"))
		{
			$activation_code_part = openssl_random_pseudo_bytes(128);
		}

		for ($i = 0; $i < 1024; $i++)
		{
			$activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
		}

		$key = $this->hash_code($activation_code_part . $identity);

		// If enable query strings is set, then we need to replace any unsafe characters so that the code can still work
		if ($key != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
		{
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if (!preg_match("|^[" . str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-')) . "]+$|i", $key))
			{
				$key = preg_replace("/[^" . $this->config->item('permitted_uri_chars') . "]+/i", "-", $key);
			}
		}

		// Limit to 40 characters since that's how our DB field is setup
		$this->forgotten_password_code = substr($key, 0, 40);

		$this->trigger_events('extra_where');

		$update = array(
			'forgotten_password_code' => $key,
			'forgotten_password_time' => time()
		);

		$this->db->update($this->tables['users'], $update, array($this->identity_column => $identity));

		$return = $this->db->affected_rows() == 1;

		if ($return)
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
		}
		else
		{
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
		}

		return $return;
	}

	/**
	 * Forgotten Password Complete
	 *
	 * @param    string $code
	 * @param    bool   $salt
	 *
	 * @return    string
	 * @author    Mathew
	 */
	public function forgotten_password_complete($code, $salt = FALSE)
	{
		$this->trigger_events('pre_forgotten_password_complete');

		if (empty($code))
		{
			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
			return FALSE;
		}

		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if ($profile)
		{

			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
			{
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				if (time() - $profile->forgotten_password_time > $expiration)
				{
					//it has expired
					$this->set_error('forgot_password_expired');
					$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
					return FALSE;
				}
			}

			$password = $this->salt();

			$data = array(
				'password' => $this->hash_password($password, $salt),
				'forgotten_password_code' => NULL,
				'active' => 1,
			);

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_successful'));
			return $password;
		}

		$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
		return FALSE;
	}

	/**
	 * Register
	 *
	 * @param    string $identity
	 * @param    string $password
	 * @param    string $email
	 * @param    array  $additional_data
	 * @param    array  $toolcals
	 *
	 * @return    bool
	 * @author    Mathew
	 */
	public function register($identity, $password, $email, $additional_data = array(), $toolcals = array())
	{
		$this->trigger_events('pre_register');

		$manual_activation = $this->config->item('manual_activation', 'ion_auth');

		if ($this->identity_check($identity))
		{
			$this->set_error('account_creation_duplicate_identity');
			return FALSE;
		}
		else if (!$this->config->item('default_toolcal', 'ion_auth') && empty($toolcals))
		{
			$this->set_error('account_creation_missing_default_toolcal');
			return FALSE;
		}

		// check if the default set in config exists in database
		$query = $this->db->get_where($this->tables['toolcals'], array('name' => $this->config->item('default_toolcal', 'ion_auth')), 1)->row();
		if (!isset($query->id) && empty($toolcals))
		{
			$this->set_error('account_creation_invalid_default_toolcal');
			return FALSE;
		}

		// capture default toolcal details
		$default_toolcal = $query;

		// IP Address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt = $this->store_salt ? $this->salt() : FALSE;
		$password = $this->hash_password($password, $salt);

		// Users table.
		$data = array(
			$this->identity_column => $identity,
			'username' => $identity,
			'password' => $password,
			'email' => $email,
			'ip_address' => $ip_address,
			'created_on' => time(),
			'active' => ($manual_activation === FALSE ? 1 : 0)
		);

		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data
		$user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

		$this->trigger_events('extra_set');

		$this->db->insert($this->tables['users'], $user_data);

		$id = $this->db->insert_id($this->tables['users'] . '_id_seq');

		// add in toolcals array if it doesn't exists and stop adding into default toolcal if default toolcal ids are set
		if (isset($default_toolcal->id) && empty($toolcals))
		{
			$toolcals[] = $default_toolcal->id;
		}

		if (!empty($toolcals))
		{
			// add to toolcals
			foreach ($toolcals as $toolcal)
			{
				$this->add_to_toolcal($toolcal, $id);
			}
		}

		$this->trigger_events('post_register');

		return (isset($id)) ? $id : FALSE;
	}

	/**
	 * login
	 *
	 * @param    string $identity
	 * @param    string $password
	 * @param    bool   $remember
	 *
	 * @return    bool
	 * @author    Mathew
	 */
	public function login($identity, $password, $remember=FALSE)
	{
		$this->trigger_events('pre_login');

		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select($this->identity_column . ', email, id, password, active, last_login')
						  ->where($this->identity_column, $identity)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get($this->tables['users']);

		if ($this->is_max_login_attempts_exceeded($identity))
		{
			// Hash something anyway, just to take up time
			$this->hash_password($password);

			$this->trigger_events('post_login_unsuccessful');
			$this->set_error('login_timeout');

			return FALSE;
		}

		if ($query->num_rows() === 1)
		{
			$user = $query->row();

			$password = $this->hash_password_db($user->id, $password);

			if ($password === TRUE)
			{
				if ($user->active == 0)
				{
					$this->trigger_events('post_login_unsuccessful');
					$this->set_error('login_unsuccessful_not_active');

					return FALSE;
				}

				$this->set_session($user);

				$this->update_last_login($user->id);

				$this->clear_login_attempts($identity);

				if ($remember && $this->config->item('remember_users', 'ion_auth'))
				{
					$this->remember_user($user->id);
				}

				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');

				return TRUE;
			}
		}

		// Hash something anyway, just to take up time
		$this->hash_password($password);

		$this->increase_login_attempts($identity);

		$this->trigger_events('post_login_unsuccessful');
		$this->set_error('login_unsuccessful');

		return FALSE;
	}

	/**
	 * Verifies if the session should be rechecked according to the configuration item recheck_timer. If it does, then
	 * it will check if the user is still active
	 * @return bool
	 */
	public function recheck_session()
	{
		$recheck = (NULL !== $this->config->item('recheck_timer', 'ion_auth')) ? $this->config->item('recheck_timer', 'ion_auth') : 0;

		if ($recheck !== 0)
		{
			$last_login = $this->session->userdata('last_check');
			if ($last_login + $recheck < time())
			{
				$query = $this->db->select('id')
								  ->where(array($this->identity_column => $this->session->userdata('identity'), 'active' => '1'))
								  ->limit(1)
								  ->order_by('id', 'desc')
								  ->get($this->tables['users']);
				if ($query->num_rows() === 1)
				{
					$this->session->set_userdata('last_check', time());
				}
				else
				{
					$this->trigger_events('logout');

					$identity = $this->config->item('identity', 'ion_auth');

					if (substr(CI_VERSION, 0, 1) == '2')
					{
						$this->session->unset_userdata(array($identity => '', 'id' => '', 'user_id' => ''));
					}
					else
					{
						$this->session->unset_userdata(array($identity, 'id', 'user_id'));
					}
					return FALSE;
				}
			}
		}

		return (bool)$this->session->userdata('identity');
	}

	/**
	 * is_max_login_attempts_exceeded
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string      $identity   user's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return boolean
	 */
	public function is_max_login_attempts_exceeded($identity, $ip_address = NULL)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$max_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
			if ($max_attempts > 0)
			{
				$attempts = $this->get_attempts_num($identity, $ip_address);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	/**
	 * Get number of login attempts for the given IP-address or identity
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string      $identity   User's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return int
	 */
	public function get_attempts_num($identity, $ip_address = NULL)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$this->db->select('1', FALSE);
			$this->db->where('login', $identity);
			if ($this->config->item('track_login_ip_address', 'ion_auth'))
			{
				if (!isset($ip_address))
				{
					$ip_address = $this->_prepare_ip($this->input->ip_address());
				}
				$this->db->where('ip_address', $ip_address);
			}
			$this->db->where('time >', time() - $this->config->item('lockout_time', 'ion_auth'), FALSE);
			$qres = $this->db->get($this->tables['login_attempts']);
			return $qres->num_rows();
		}
		return 0;
	}

	/**
	 * @deprecated This function is now only a wrapper for is_max_login_attempts_exceeded() since it only retrieve
	 *             attempts within the given period.
	 *
	 * @param string      $identity   User's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return boolean Whether an account is locked due to excessive login attempts within a given period
	 */
	public function is_time_locked_out($identity, $ip_address = NULL)
	{
		return $this->is_max_login_attempts_exceeded($identity, $ip_address);
	}

	/**
	 * @deprecated This function is now only a wrapper for is_max_login_attempts_exceeded() since it only retrieve
	 *             attempts within the given period.
	 *
	 * @param string      $identity   User's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return int The time of the last login attempt for a given IP-address or identity
	 */
	public function get_last_attempt_time($identity, $ip_address = NULL)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$this->db->select('time');
			$this->db->where('login', $identity);
			if ($this->config->item('track_login_ip_address', 'ion_auth'))
			{
				if (!isset($ip_address))
				{
					$ip_address = $this->_prepare_ip($this->input->ip_address());
				}
				$this->db->where('ip_address', $ip_address);
			}
			$this->db->order_by('id', 'desc');
			$qres = $this->db->get($this->tables['login_attempts'], 1);

			if ($qres->num_rows() > 0)
			{
				return $qres->row()->time;
			}
		}

		return 0;
	}

	/**
	 * Get the IP address of the last time a login attempt occured from given identity
	 *
	 * @param string $identity User's identity
	 *
	 * @return string
	 */
	public function get_last_attempt_ip($identity)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth') && $this->config->item('track_login_ip_address', 'ion_auth'))
		{
			$this->db->select('ip_address');
			$this->db->where('login', $identity);
			$this->db->order_by('id', 'desc');
			$qres = $this->db->get($this->tables['login_attempts'], 1);

			if ($qres->num_rows() > 0)
			{
				return $qres->row()->ip_address;
			}
		}

		return '';
	}

	/**
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * Note: the current IP address will be used if track_login_ip_address config value is TRUE
	 *
	 * @param string $identity User's identity
	 *
	 * @return bool
	 */
	public function increase_login_attempts($identity)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			$data = array('ip_address' => '', 'login' => $identity, 'time' => time());
			if ($this->config->item('track_login_ip_address', 'ion_auth'))
			{
				$data['ip_address'] = $this->_prepare_ip($this->input->ip_address());
			}
			return $this->db->insert($this->tables['login_attempts'], $data);
		}
		return FALSE;
	}

	/**
	 * clear_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string      $identity                   User's identity
	 * @param int         $old_attempts_expire_period In seconds, any attempts older than this value will be removed.
	 *                                                It is used for regularly purging the attempts table.
	 *                                                (for security reason, minimum value is lockout_time config value)
	 * @param string|null $ip_address                 IP address
	 *                                                Only used if track_login_ip_address is set to TRUE.
	 *                                                If NULL (default value), the current IP address is used.
	 *                                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return bool
	 */
	public function clear_login_attempts($identity, $old_attempts_expire_period = 86400, $ip_address = NULL)
	{
		if ($this->config->item('track_login_attempts', 'ion_auth'))
		{
			// Make sure $old_attempts_expire_period is at least equals to lockout_time
			$old_attempts_expire_period = max($old_attempts_expire_period, $this->config->item('lockout_time', 'ion_auth'));

			$this->db->where('login', $identity);
			if ($this->config->item('track_login_ip_address', 'ion_auth'))
			{
				if (!isset($ip_address))
				{
					$ip_address = $this->_prepare_ip($this->input->ip_address());
				}
				$this->db->where('ip_address', $ip_address);
			}
			// Purge obsolete login attempts
			$this->db->or_where('time <', time() - $old_attempts_expire_period, FALSE);

			return $this->db->delete($this->tables['login_attempts']);
		}
		return FALSE;
	}

	/**
	 * @param int $limit
	 *
	 * @return static
	 */
	public function limit($limit)
	{
		$this->trigger_events('limit');
		$this->_ion_limit = $limit;

		return $this;
	}

	/**
	 * @param int $offset
	 *
	 * @return static
	 */
	public function offset($offset)
	{
		$this->trigger_events('offset');
		$this->_ion_offset = $offset;

		return $this;
	}

	/**
	 * @param array|string $where
	 * @param null|string  $value
	 *
	 * @return static
	 */
	public function where($where, $value = NULL)
	{
		$this->trigger_events('where');

		if (!is_array($where))
		{
			$where = array($where => $value);
		}

		array_push($this->_ion_where, $where);

		return $this;
	}

	/**
	 * @param string      $like
	 * @param string|null $value
	 * @param string      $position
	 *
	 * @return static
	 */
	public function like($like, $value = NULL, $position = 'both')
	{
		$this->trigger_events('like');

		array_push($this->_ion_like, array(
			'like'     => $like,
			'value'    => $value,
			'position' => $position
		));

		return $this;
	}

	/**
	 * @param array|string $select
	 *
	 * @return static
	 */
	public function select($select)
	{
		$this->trigger_events('select');

		$this->_ion_select[] = $select;

		return $this;
	}

	/**
	 * @param string $by
	 * @param string $order
	 *
	 * @return static
	 */
	public function order_by($by, $order='desc')
	{
		$this->trigger_events('order_by');

		$this->_ion_order_by = $by;
		$this->_ion_order    = $order;

		return $this;
	}

	/**
	 * @return object|mixed
	 */
	public function row()
	{
		$this->trigger_events('row');

		$row = $this->response->row();

		return $row;
	}

	/**
	 * @return array|mixed
	 */
	public function row_array()
	{
		$this->trigger_events(array('row', 'row_array'));

		$row = $this->response->row_array();

		return $row;
	}

	/**
	 * @return mixed
	 */
	public function result()
	{
		$this->trigger_events('result');

		$result = $this->response->result();

		return $result;
	}

	/**
	 * @return array|mixed
	 */
	public function result_array()
	{
		$this->trigger_events(array('result', 'result_array'));

		$result = $this->response->result_array();

		return $result;
	}

	/**
	 * @return int
	 */
	public function num_rows()
	{
		$this->trigger_events(array('num_rows'));

		$result = $this->response->num_rows();

		return $result;
	}

	/**
	 * users
	 *
	 * @param array|null $toolcals
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function users($toolcals = NULL)
	{
		$this->trigger_events('users');

		if (isset($this->_ion_select) && !empty($this->_ion_select))
		{
			foreach ($this->_ion_select as $select)
			{
				$this->db->select($select);
			}

			$this->_ion_select = array();
		}
		else
		{
			// default selects
			$this->db->select(array(
			    $this->tables['users'].'.*',
			    $this->tables['users'].'.id as id',
			    $this->tables['users'].'.id as user_id'
			));
		}

		// filter by toolcal id(s) if passed
		if (isset($toolcals))
		{
			// build an array if only one toolcal was passed
			if (!is_array($toolcals))
			{
				$toolcals = Array($toolcals);
			}

			// join and then run a where_in against the toolcal ids
			if (isset($toolcals) && !empty($toolcals))
			{
				$this->db->distinct();
				$this->db->join(
				    $this->tables['users_toolcals'],
				    $this->tables['users_toolcals'].'.'.$this->join['users'].'='.$this->tables['users'].'.id',
				    'inner'
				);
			}

			// verify if toolcal name or toolcal id was used and create and put elements in different arrays
			$toolcal_ids = array();
			$sTenThucAns = array();
			foreach($toolcals as $toolcal)
			{
				if(is_numeric($toolcal)) $toolcal_ids[] = $toolcal;
				else $sTenThucAns[] = $toolcal;
			}
			$or_where_in = (!empty($toolcal_ids) && !empty($sTenThucAns)) ? 'or_where_in' : 'where_in';
			// if toolcal name was used we do one more join with toolcals
			if(!empty($sTenThucAns))
			{
				$this->db->join($this->tables['toolcals'], $this->tables['users_toolcals'] . '.' . $this->join['toolcals'] . ' = ' . $this->tables['toolcals'] . '.id', 'inner');
				$this->db->where_in($this->tables['toolcals'] . '.name', $sTenThucAns);
			}
			if(!empty($toolcal_ids))
			{
				$this->db->{$or_where_in}($this->tables['users_toolcals'].'.'.$this->join['toolcals'], $toolcal_ids);
			}
		}

		$this->trigger_events('extra_where');

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}

			$this->_ion_where = array();
		}

		if (isset($this->_ion_like) && !empty($this->_ion_like))
		{
			foreach ($this->_ion_like as $like)
			{
				$this->db->or_like($like['like'], $like['value'], $like['position']);
			}

			$this->_ion_like = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);

			$this->_ion_order    = NULL;
			$this->_ion_order_by = NULL;
		}

		$this->response = $this->db->get($this->tables['users']);

		return $this;
	}

	/**
	 * user
	 *
	 * @param int|string|null $id
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function user($id = NULL)
	{
		$this->trigger_events('user');

		// if no id was passed use the current users id
		$id = isset($id) ? $id : $this->session->userdata('user_id');

		$this->limit(1);
		$this->order_by($this->tables['users'].'.id', 'desc');
		$this->where($this->tables['users'].'.id', $id);

		$this->users();

		return $this;
	}

	/**
	 * get_users_toolcals
	 *
	 * @param int|string|bool $id
	 *
	 * @return CI_DB_result
	 * @author Ben Edmunds
	 */
	public function get_users_toolcals($id = FALSE)
	{
		$this->trigger_events('get_users_toolcal');

		// if no id was passed use the current users id
		$id || $id = $this->session->userdata('user_id');

		return $this->db->select($this->tables['users_toolcals'].'.'.$this->join['toolcals'].' as id, '.$this->tables['toolcals'].'.name, '.$this->tables['toolcals'].'.description')
		                ->where($this->tables['users_toolcals'].'.'.$this->join['users'], $id)
		                ->join($this->tables['toolcals'], $this->tables['users_toolcals'].'.'.$this->join['toolcals'].'='.$this->tables['toolcals'].'.id')
		                ->get($this->tables['users_toolcals']);
	}

	/**
	 * add_to_toolcal
	 *
	 * @param array|int|float|string $toolcal_ids
	 * @param bool|int|float|string  $user_id
	 *
	 * @return int
	 * @author Ben Edmunds
	 */
	public function add_to_toolcal($toolcal_ids, $user_id = FALSE)
	{
		$this->trigger_events('add_to_toolcal');

		// if no id was passed use the current users id
		$user_id || $user_id = $this->session->userdata('user_id');

		if(!is_array($toolcal_ids))
		{
			$toolcal_ids = array($toolcal_ids);
		}

		$return = 0;

		// Then insert each into the database
		foreach ($toolcal_ids as $toolcal_id)
		{
			// Cast to float to support bigint data type
			if ($this->db->insert(
								  $this->tables['users_toolcals'],
								  array(
								  	$this->join['toolcals'] => (float)$toolcal_id,
									$this->join['users']  => (float)$user_id
								  )
								)
			)
			{
				if (isset($this->_cache_toolcals[$toolcal_id]))
				{
					$sTenThucAn = $this->_cache_toolcals[$toolcal_id];
				}
				else
				{
					$toolcal = $this->toolcal($toolcal_id)->result();
					$sTenThucAn = $toolcal[0]->name;
					$this->_cache_toolcals[$toolcal_id] = $sTenThucAn;
				}
				$this->_cache_user_in_toolcal[$user_id][$toolcal_id] = $sTenThucAn;

				// Return the number of toolcals added
				$return++;
			}
		}

		return $return;
	}

	/**
	 * remove_from_toolcal
	 *
	 * @param array|int|float|string|bool $toolcal_ids
	 * @param int|float|string|bool $user_id
	 *
	 * @return bool
	 * @author Ben Edmunds
	 */
	public function remove_from_toolcal($toolcal_ids = FALSE, $user_id = FALSE)
	{
		$this->trigger_events('remove_from_toolcal');

		// user id is required
		if (empty($user_id))
		{
			return FALSE;
		}

		// if toolcal id(s) are passed remove user from the toolcal(s)
		if (!empty($toolcal_ids))
		{
			if (!is_array($toolcal_ids))
			{
				$toolcal_ids = array($toolcal_ids);
			}

			foreach ($toolcal_ids as $toolcal_id)
			{
				// Cast to float to support bigint data type
				$this->db->delete(
					$this->tables['users_toolcals'],
					array($this->join['toolcals'] => (float)$toolcal_id, $this->join['users'] => (float)$user_id)
				);
				if (isset($this->_cache_user_in_toolcal[$user_id]) && isset($this->_cache_user_in_toolcal[$user_id][$toolcal_id]))
				{
					unset($this->_cache_user_in_toolcal[$user_id][$toolcal_id]);
				}
			}

			$return = TRUE;
		}
		// otherwise remove user from all toolcals
		else
		{
			// Cast to float to support bigint data type
			if ($return = $this->db->delete($this->tables['users_toolcals'], array($this->join['users'] => (float)$user_id)))
			{
				$this->_cache_user_in_toolcal[$user_id] = array();
			}
		}
		return $return;
	}

	/**
	 * toolcals
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function toolcals()
	{
		$this->trigger_events('toolcals');

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}
			$this->_ion_where = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);
		}

		$this->response = $this->db->get($this->tables['cal_chedoan']);

		return $this;
	}

	/**
	 * geToolcalByEmail
	 *
	 * @param int|string|null $id
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function geToolcalByEmail($email = NULL)
	{
		$this->db->where('sCreateUser',$email);
		$query  =   $this->db->get('cal_chedoan');
		return $query;
	}

	/**
	 * toolcal
	 *
	 * @param int|string|null $id
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function toolcal($id = NULL)
	{
		$this->db->where('iIDCheDoAn',$id);
		$query  =   $this->db->get('cal_chedoan');
		return $query;

		// $this->trigger_events('toolcal');

		// if (isset($id))
		// {
		// 	$this->where($this->tables['cal_chedoan'].'.iIDCheDoAn', $id);
		// }

		// $this->limit(1);
		// $this->order_by('iIDCheDoAn', 'desc');

		// return $this->toolcals();
	}

	/**
	 * search_toolcals
	 *
	 * @param int|string|null $keyword
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function search_toolcals($keyword = NULL)
	{
		$this->db->like('sTenKhachHang',$keyword);
		$query  =   $this->db->get('cal_chedoan');
		return $query->result_array();
	}

	/**
	 * toolcals
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function toolcaldetails()
	{
		$this->trigger_events('toolcaldetails');

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}
			$this->_ion_where = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);
		}

		$this->response = $this->db->get($this->tables['cal_chedoanchitiet']);

		return $this;
	}

	/**
	 * toolcaldetail
	 *
	 * @param int|string|null $id
	 *
	 * @return static
	 * @author Ben Edmunds
	 */
	public function toolcaldetail($iIDCheDoAn = NULL)
	{
		$this->db->where('iIDCheDoAn',$iIDCheDoAn);
		$query  =   $this->db->get('cal_chedoanchitiet');
		return $query;

		// $this->trigger_events('toolcaldetail');

		// if (isset($id))
		// {
		// 	$this->where($this->tables['cal_chedoanchitiet'].'.iIDCheDoAn', $iIDCheDoAn);
		// }

		// // $this->limit(1);
		// $this->order_by('iIDGioAn', 'asc');

		// return $this->toolcaldetails();
	}

	/**
	 * update
	 *
	 * @param int|string $id
	 * @param array      $data
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function update($id, array $data)
	{
		$this->trigger_events('pre_update_user');

		$user = $this->user($id)->row();

		$this->db->trans_begin();

		if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->db->trans_rollback();
			$this->set_error('account_creation_duplicate_identity');

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Filter the data passed
		$data = $this->_filter_data($this->tables['users'], $data);

		if (array_key_exists($this->identity_column, $data) || array_key_exists('password', $data) || array_key_exists('email', $data))
		{
			if (array_key_exists('password', $data))
			{
				if( ! empty($data['password']))
				{
					$data['password'] = $this->hash_password($data['password'], $user->salt);
				}
				else
				{
					// unset password so it doesn't effect database entry if no password passed
					unset($data['password']);
				}
			}
		}

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array('id' => $user->id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}

	/**
	 * delete_user
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		$this->db->trans_begin();

		// remove user from toolcals
		$this->remove_from_toolcal(NULL, $id);

		// delete user from users table should be placed after remove from toolcal
		$this->db->delete($this->tables['users'], array('id' => $id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		return TRUE;
	}

	/**
	 * update_last_login
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 * @author Ben Edmunds
	 */
	public function update_last_login($id)
	{
		$this->trigger_events('update_last_login');

		$this->load->helper('date');

		$this->trigger_events('extra_where');

		$this->db->update($this->tables['users'], array('last_login' => time()), array('id' => $id));

		return $this->db->affected_rows() == 1;
	}

	/**
	 * set_lang
	 *
	 * @param string $lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 */
	public function set_lang($lang = 'en')
	{
		$this->trigger_events('set_lang');

		// if the user_expire is set to zero we'll set the expiration two years from now.
		if($this->config->item('user_expire', 'ion_auth') === 0)
		{
			$expire = (60*60*24*365*2);
		}
		// otherwise use what is set
		else
		{
			$expire = $this->config->item('user_expire', 'ion_auth');
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	/**
	 * set_session
	 *
	 * @param object $user
	 *
	 * @return bool
	 * @author jrmadsen67
	 */
	public function set_session($user)
	{
		$this->trigger_events('pre_set_session');

		$session_data = array(
		    'identity'             => $user->{$this->identity_column},
		    $this->identity_column => $user->{$this->identity_column},
		    'email'                => $user->email,
		    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
		    'old_last_login'       => $user->last_login,
		    'last_check'           => time(),
		);

		$this->session->set_userdata($session_data);

		$this->trigger_events('post_set_session');

		return TRUE;
	}

	/**
	 * remember_user
	 *
	 * @param int|string $id
	 *
	 * @return bool
	 * @author Ben Edmunds
	 */
	public function remember_user($id)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id)
		{
			return FALSE;
		}

		$user = $this->user($id)->row();

		$salt = $this->salt();

		$this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

		if ($this->db->affected_rows() > -1)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'ion_auth') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'ion_auth');
			}

			set_cookie(array(
			    'name'   => $this->config->item('identity_cookie_name', 'ion_auth'),
			    'value'  => $user->{$this->identity_column},
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => $this->config->item('remember_cookie_name', 'ion_auth'),
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 */
	public function login_remembered_user()
	{
		$this->trigger_events('pre_login_remembered_user');

		// check for valid data
		if (!get_cookie($this->config->item('identity_cookie_name', 'ion_auth'))
			|| !get_cookie($this->config->item('remember_cookie_name', 'ion_auth'))
			|| !$this->identity_check(get_cookie($this->config->item('identity_cookie_name', 'ion_auth'))))
		{
			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
			return FALSE;
		}

		// get the user
		$this->trigger_events('extra_where');
		$query = $this->db->select($this->identity_column . ', id, email, last_login')
						  ->where($this->identity_column, urldecode(get_cookie($this->config->item('identity_cookie_name', 'ion_auth'))))
						  ->where('remember_code', get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
						  ->where('active', 1)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get($this->tables['users']);

		// if the user was found, sign them in
		if ($query->num_rows() == 1)
		{
			$user = $query->row();

			$this->update_last_login($user->id);

			$this->set_session($user);

			// extend the users cookies if the option is enabled
			if ($this->config->item('user_extend_on_login', 'ion_auth'))
			{
				$this->remember_user($user->id);
			}

			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
		return FALSE;
	}


	/**
	 * create_toolcal
	 *
	 * @param string|bool $sTenThucAn
	 * @param string      $toolcal_description
	 * @param array       $additional_data
	 *
	 * @return int|bool The ID of the inserted toolcal, or FALSE on failure
	 * @author aditya menon
	 */
	public function create_toolcal($additional_data = array(),$additional_data_foods= array())
	{
		// bail if the toolcal name was not passed
		if(!isset($additional_data) || sizeof($additional_data)<=0)
		{
			$this->set_error('sTenKhachHang_required');
			return FALSE;
		}
		
		// bail if the toolcal name already exists
		// $existing_toolcal = $this->db->get_where($this->tables['cal_thucan'], array('sTenKhachHang' => $sTenThucAn))->num_rows();
		// if($existing_toolcal !== 0)
		// {
		// 	$this->set_error('toolcal_already_exists');
		// 	return FALSE;
		// }

		// $data = array('sTenThucAn'=>$sTenThucAn,
		//               'sSLplusDVT'=>$sSLplusDVT,
		// 			  'fCalori'=>$fCalori,
		// 			  'fDam'=>$fDam,
		// 			  'fBeo'=>$fBeo,
		// 			  'fBotOrDuong'=>$fBotOrDuong,
		// 			  'fXo'=>$fXo,
		// 			  'dCreateDate'=>date('Y-m-d H:i:s')
		// 			);

		// filter out any data passed that doesnt have a matching column in the toolcals table
		// and merge the set toolcal data and the additional data
		// if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['cal_thucan'], $additional_data), $data);

		$this->trigger_events('extra_toolcal_set');

		// insert the new cal_chedoan
		$this->db->insert($this->tables['cal_chedoan'], $additional_data);
		$iIDCheDoAn = $this->db->insert_id($this->tables['cal_chedoan'] . '_id_seq');
		// insert the new cal_chedoanchitiet
		for ($i = 1; $i <= $additional_data["iSoBuaAnNgay"]; $i++) {
			$lstBua=json_decode($additional_data_foods["hideBua".$i],true);
			$this->create_toolcaldetail($lstBua,$iIDCheDoAn,$i);
		} 
		// //Bua 1
		// if($additional_data["iSoBuaAnNgay"]>=1){
		// 	$lstBua1=json_decode($additional_data_foods["hideBua1"],true);
		// 	$this->create_toolcaldetail($lstBua1,$iIDCheDoAn,1);
		// }
		// //Bua 2
		// if($additional_data["iSoBuaAnNgay"]>=2){
		// 	$lstBua2=json_decode($additional_data_foods["hideBua2"],true);
		// 	$this->create_toolcaldetail($lstBua2,$iIDCheDoAn,2);
		// }
		// //Bua 3
		// if($additional_data["iSoBuaAnNgay"]>=3){
		// 	$lstBua3=json_decode($additional_data_foods["hideBua3"],true);
		// 	$this->create_toolcaldetail($lstBua3,$iIDCheDoAn,3);
		// }
		// //Bua 4
		// if($additional_data["iSoBuaAnNgay"]>=4){
		// 	$lstBua4=json_decode($additional_data_foods["hideBua4"],true);
		// 	$this->create_toolcaldetail($lstBua4,$iIDCheDoAn,4);
		// }
		// //Bua 5
		// if($additional_data["iSoBuaAnNgay"]>=5){
		// 	$lstBua5=json_decode($additional_data_foods["hideBua5"],true);
		// 	$this->create_toolcaldetail($lstBua5,$iIDCheDoAn,5);
		// }
		// //Bua 6
		// if($additional_data["iSoBuaAnNgay"]>=6){
		// 	$lstBua6=json_decode($additional_data_foods["hideBua6"],true);
		// 	$this->create_toolcaldetail($lstBua6,$iIDCheDoAn,5);
		// }
		
		// report success
		$this->set_message('toolcal_creation_successful');
		// return the brand new toolcal id
		return $iIDCheDoAn;
	}

	/**
	 * create_toolcaldetail
	 *
	 * @param string|bool $lstBua1
	 *
	 * @return int|bool The ID of the inserted toolcal, or FALSE on failure
	 * @author aditya menon
	 */
	public function create_toolcaldetail($lstThucAn = array(),$iIDCheDoAn=0,$iIDGioAn=0)
	{
		if(isset($lstThucAn) && sizeof($lstThucAn)>0){
			foreach ($lstThucAn as $value) {
			$data_detail = array('iIDCheDoAn'=>$iIDCheDoAn,
		              'iIDGioAn'=>$iIDGioAn,
					  'iIDThucAn'=>$value[0],
					  'sThoiGian'=>$value[1],
					  'sTenThucAn'=>$value[2],
					  'sSLplusDVT'=>$value[3],
					  'fCalori'=>$value[4],
					  'fDam'=>$value[5],
					  'fBeo'=>$value[6],
					  'fBotOrDuong'=>$value[7],
					  'fXo'=>$value[8],
					  'dCreateDate'=>date('Y-m-d H:i:s')
					);
			$this->db->insert($this->tables['cal_chedoanchitiet'], $data_detail);
			$iIDCheDoAnChiTiet = $this->db->insert_id($this->tables['cal_chedoanchitiet'] . '_id_seq');
		}
		}
	}

	/**
	 * update_toolcal
	 *
	 * @param int|string|bool $iIDThucAn
	 * @param string|bool     $sTenThucAn
	 * @param string|array    $additional_data IMPORTANT! This was string type $description; strings are still allowed
	 *                                         to maintain backward compatibility. New projects should pass an array of
	 *                                         data instead.
	 *
	 * @return bool
	 * @author aditya menon
	 */
	public function update_toolcal($iIDCheDoAn = FALSE,$additional_data = array(),$additional_data_foods= array())
	{
		if (empty($iIDCheDoAn))
		{
			return FALSE;
		}

		$data = array();

		if($additional_data!=null || sizeof($additional_data)>0)
		{
			// we are changing the name, so do some checks

			// bail if the toolcal name already exists
			// $existing_toolcal = $this->db->get_where($this->tables['cal_thucan'], array('sTenThucAn' => $sTenThucAn))->row();
			// if (isset($existing_toolcal->iIDThucAn) && $existing_toolcal->iIDThucAn != $iIDThucAn)
			// {
			// 	$this->set_error('toolcal_already_exists');
			// 	return FALSE;
			// }

			// $data['sTenThucAn'] = $sTenThucAn;
			// $data['sSLplusDVT'] = $sSLplusDVT;
			// $data['fCalori'] = $fCalori;
			// $data['fDam'] = $fDam;
			// $data['fBeo'] = $fBeo;
			// $data['fBotOrDuong'] = $fBotOrDuong;
			// $data['fXo'] = $fXo;
			// $data['dUpdateDate'] = date('Y-m-d H:i:s');
		}

		// // restrict change of name of the admin toolcal
		// $toolcal = $this->db->get_where($this->tables['cal_thucan'], array('iIDThucAn' => $iIDThucAn))->row();
		// if ($this->config->item('admin_toolcal', 'ion_auth') === $toolcal->name && $sTenThucAn !== $toolcal->sTenThucAn)
		// {
		// 	$this->set_error('sTenThucAn_admin_not_alter');
		// 	return FALSE;
		// }

		// TODO Third parameter was string type $description; this following code is to maintain backward compatibility
		// if (is_string($additional_data))
		// {
		// 	$additional_data = array('sTenThucAn' => $additional_data);
		// }

		// // filter out any data passed that doesnt have a matching column in the toolcals table
		// // and merge the set toolcal data and the additional data
		// if (!empty($additional_data))
		// {
		// 	$data = array_merge($this->_filter_data($this->tables['cal_thucan'], $additional_data), $data);
		// }

		$this->db->update($this->tables['cal_chedoan'], $additional_data, array('iIDCheDoAn' => $iIDCheDoAn));

		// delete cal_chedoanchitiet
		$this->delete_toolcaldetail($iIDCheDoAn);
		// insert the cal_chedoanchitiet
		for ($i = 1; $i <= $additional_data["iSoBuaAnNgay"]; $i++) {
			$lstBua=json_decode($additional_data_foods["hideBua".$i],true);
			$this->create_toolcaldetail($lstBua,$iIDCheDoAn,$i);
		} 
		// //Bua 1
		// if($additional_data["iSoBuaAnNgay"]>=1){
		// 	$lstBua1=json_decode($additional_data_foods["hideBua1"],true);
		// 	$this->create_toolcaldetail($lstBua1,$iIDCheDoAn,1);
		// }
		// //Bua 2
		// if($additional_data["iSoBuaAnNgay"]>=2){
		// 	$lstBua2=json_decode($additional_data_foods["hideBua2"],true);
		// 	$this->create_toolcaldetail($lstBua2,$iIDCheDoAn,2);
		// }
		// //Bua 3
		// if($additional_data["iSoBuaAnNgay"]>=3){
		// 	$lstBua3=json_decode($additional_data_foods["hideBua3"],true);
		// 	$this->create_toolcaldetail($lstBua3,$iIDCheDoAn,3);
		// }
		// //Bua 4
		// if($additional_data["iSoBuaAnNgay"]>=4){
		// 	$lstBua4=json_decode($additional_data_foods["hideBua4"],true);
		// 	$this->create_toolcaldetail($lstBua4,$iIDCheDoAn,4);
		// }
		// //Bua 5
		// if($additional_data["iSoBuaAnNgay"]>=5){
		// 	$lstBua5=json_decode($additional_data_foods["hideBua5"],true);
		// 	$this->create_toolcaldetail($lstBua5,$iIDCheDoAn,5);
		// }
		// //Bua 6
		// if($additional_data["iSoBuaAnNgay"]>=6){
		// 	$lstBua6=json_decode($additional_data_foods["hideBua6"],true);
		// 	$this->create_toolcaldetail($lstBua6,$iIDCheDoAn,5);
		// }
		
		$this->set_message('toolcal_update_successful');

		return TRUE;
	}

	/**
	 * delete_toolcaldetail
	 *
	 * @param iIDCheDoAn
	 *
	 * @return int|bool The ID of the inserted toolcal, or FALSE on failure
	 * @author aditya menon
	 */
	public function delete_toolcaldetail($iIDCheDoAn=0)
	{
		try {
			$this->db->where('iIDCheDoAn', $iIDCheDoAn);
			$this->db->delete('cal_chedoanchitiet');
			return true;
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			return false;
		}
	}

	/**
	 * delete_toolcal
	 *
	 * @param int|string|bool $toolcal_id
	 *
	 * @return bool
	 * @author aditya menon
	 */
	public function delete_toolcal($iIDCheDoAn = FALSE)
	{
		// bail if mandatory param not set
		if(!$iIDCheDoAn || empty($iIDCheDoAn))
		{
			return FALSE;
		}
		//delete cal_chedoan
		$this->db->where('iIDCheDoAn', $iIDCheDoAn);
		$this->db->delete('cal_chedoan');
		//delete cal_chedoanchitiet
		$this->delete_toolcaldetail($iIDCheDoAn);

		return TRUE;


		// $toolcal = $this->toolcal($toolcal_id)->row();
		// if($toolcal->name == $this->config->item('admin_toolcal', 'ion_auth'))
		// {
		// 	$this->trigger_events(array('post_delete_toolcal', 'post_delete_toolcal_notallowed'));
		// 	$this->set_error('toolcal_delete_notallowed');
		// 	return FALSE;
		// }

		// $this->trigger_events('pre_delete_toolcal');

		// $this->db->trans_begin();

		// // remove all users from this toolcal
		// $this->db->delete($this->tables['users_toolcals'], array($this->join['toolcals'] => $toolcal_id));
		// // remove the toolcal itself
		// $this->db->delete($this->tables['toolcals'], array('id' => $toolcal_id));

		// if ($this->db->trans_status() === FALSE)
		// {
		// 	$this->db->trans_rollback();
		// 	$this->trigger_events(array('post_delete_toolcal', 'post_delete_toolcal_unsuccessful'));
		// 	$this->set_error('toolcal_delete_unsuccessful');
		// 	return FALSE;
		// }

		// $this->db->trans_commit();

		// $this->trigger_events(array('post_delete_toolcal', 'post_delete_toolcal_successful'));
		// $this->set_message('toolcal_delete_successful');
		// return TRUE;
	}

	/**
	 * @param string $event
	 * @param string $name
	 * @param string $class
	 * @param string $method
	 * @param array $arguments
	 */
	public function set_hook($event, $name, $class, $method, $arguments)
	{
		$this->_ion_hooks->{$event}[$name] = new stdClass;
		$this->_ion_hooks->{$event}[$name]->class     = $class;
		$this->_ion_hooks->{$event}[$name]->method    = $method;
		$this->_ion_hooks->{$event}[$name]->arguments = $arguments;
	}

	/**
	 * @param string $event
	 * @param string $name
	 */
	public function remove_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]))
		{
			unset($this->_ion_hooks->{$event}[$name]);
		}
	}

	/**
	 * @param string $event
	 */
	public function remove_hooks($event)
	{
		if (isset($this->_ion_hooks->$event))
		{
			unset($this->_ion_hooks->$event);
		}
	}

	/**
	 * @param string $event
	 * @param string $name
	 *
	 * @return bool|mixed
	 */
	protected function _call_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method))
		{
			$hook = $this->_ion_hooks->{$event}[$name];

			return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
		}

		return FALSE;
	}

	/**
	 * @param string|array $events
	 */
	public function trigger_events($events)
	{
		if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				$this->trigger_events($event);
			}
		}
		else
		{
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
			{
				foreach ($this->_ion_hooks->$events as $name => $hook)
				{
					$this->_call_hook($events, $name);
				}
			}
		}
	}

	/**
	 * set_message_delimiters
	 *
	 * Set the message delimiters
	 *
	 * @param string $start_delimiter
	 * @param string $end_delimiter
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_error_delimiters
	 *
	 * Set the error delimiters
	 *
	 * @param string $start_delimiter
	 * @param string $end_delimiter
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @param string $message The message
	 *
	 * @return string The given message
	 * @author Ben Edmunds
	 */
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}

	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return string
	 * @author Ben Edmunds
	 */
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @param bool $langify
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 */
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}

	/**
	 * clear_messages
	 *
	 * Clear messages
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function clear_messages()
	{
		$this->messages = array();

		return TRUE;
	}

	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @param string $error The error to set
	 *
	 * @return string The given error
	 * @author Ben Edmunds
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return string
	 * @author Ben Edmunds
	 */
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @param bool $langify
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 */
	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->errors as $error)
			{
				$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}

	/**
	 * clear_errors
	 *
	 * Clear Errors
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function clear_errors()
	{
		$this->errors = array();

		return TRUE;
	}

	/**
	 * @param string $table
	 * @param array  $data
	 *
	 * @return array
	 */
	protected function _filter_data($table, $data)
	{
		$filtered_data = array();
		$columns = $this->db->list_fields($table);

		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				if (array_key_exists($column, $data))
					$filtered_data[$column] = $data[$column];
			}
		}

		return $filtered_data;
	}

	/**
	 * @deprecated Now just returns the given string for backwards compatibility reasons
	 * @param string $ip_address The IP address
	 *
	 * @return string The given IP address
	 */
	protected function _prepare_ip($ip_address) {
		return $ip_address;
	}

	function getAllCuongDo()
	{
		// $result = $this - > db - > select('iIDCuongDo, sTenCuongDo') - > get('cal_cuongdo') - > result_array(); 
		$result = $this->db->select('*')
		                //   ->where('id', $id)
		                //   ->limit(1)
		                  ->order_by('iIDCuongDo', 'asc')
						  ->get($this->tables['cal_cuongdo'])->result_array();
		// return $result->result_array();
 
        $cal_cuongdo = array(); 
        foreach($result as $r) { 
            $cal_cuongdo[$r['iIDCuongDo']] = $r['sTenCuongDo']; 
        } 
        // $cal_cuongdo[''] = 'Chọn cường độ...'; 
        return $cal_cuongdo; 
	}

}
