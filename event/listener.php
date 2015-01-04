<?php
/**
*
* @package phpBB Extension - tas2580 failed logins
* @copyright (c) 2014 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\failedlogins\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;
	
	private $login_try = false;

	/**
	* Constructor
	*
	* @param \phpbb\config\config $config
	* @access public
	*/
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->request = $request;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			//'core.login_box_redirect'	=> 'log_failed',
			'core.user_setup'			=> 'login_try',
			'core.login_box_redirect'	=> 'login_success',
			'core.page_footer'			=> 'login_check',
			'core.page_header'			=> 'display_message',
		);
	}

	/**
	* Event on every login try to set $this->login_try on true
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function login_try($event)
	{	
		if($this->request->is_set('login'))
		{
			$this->login_try = true;
		}
	}
	
	/**
	* Display message to the user if there where failed login trys
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function display_message($event)
	{
		if($this->user->data['failed_logins_count'] > 0)
		{
			$this->user->add_lang_ext('tas2580/failedlogins', 'common');
			$this->template->assign_vars(array(
				'FAILED_LOGINS'		=> sprintf($this->user->lang['FAILED_LOGINS_COUNT'], $this->user->data['failed_logins_count']),
			));
			
			$sql = 'UPDATE ' . USERS_TABLE . ' SET failed_logins_count = 0
						WHERE user_id = ' . (int) $this->user->data['user_id'];
			$this->db->sql_query($sql);
		}
	}
	
	/**
	* If login success set $this->login_try on false
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function login_success($event)
	{
		$this->login_try = false;
	}

	/**
	* If $this->login_try is stil true the login failed
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function login_check($event)
	{
		if($this->login_try === true)
		{
			global $phpbb_log;
			$username = $this->request->variable('username', '', true);

			$sql = 'UPDATE ' . USERS_TABLE . ' SET failed_logins_count = failed_logins_count + 1
						WHERE username = "' . $this->db->sql_escape($username) . '"';
			$this->db->sql_query($sql);
			
			$this->user->add_lang_ext('tas2580/failedlogins', 'common');
			$user_ip = (empty($this->user->ip)) ? '' : $this->user->ip;
			$additional_data['reportee_id'] = ANONYMOUS;
			$phpbb_log->add('user', ANONYMOUS, $user_ip, sprintf($this->user->lang['TRY_TO_LOGIN_FAIL'], $username), time(), $additional_data);
		}
	}
}
