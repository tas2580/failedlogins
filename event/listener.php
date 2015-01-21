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
	/** @var \phpbb\template\template */
	protected $template;
	
	/** @var \phpbb\user\user */
	protected $user;
	
	/** @var \phpbb\db\driver\driver */
	protected $db;
	
	/** @var \phpbb\request\request */
	protected $request;
	
	/** @var $login_try */
	private $login_try = false;

	/**
	* Constructor
	*
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	* @param \phpbb\db\driver\driver	$db
	* @param \phpbb\request\request		$request
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
			'core.user_setup'			=> 'user_setup',
			'core.login_box_redirect'	=> 'login_box_redirect',
			'core.page_footer'			=> 'page_footer',
			'core.page_header'			=> 'page_header',
		);
	}

	/**
	* Event on every login try to set $this->login_try on true
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_setup($event)
	{	
		$this->user->add_lang_ext('tas2580/failedlogins', 'common');
		if($this->request->is_set('login'))
		{
			$this->login_try = true;
		}
	}
	
	/**
	* If login success set $this->login_try on false
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function login_box_redirect($event)
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
	public function page_footer($event)
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
			$phpbb_log->add('user', ANONYMOUS, $user_ip, 'TRY_TO_LOGIN_FAIL', time(), array(
				'reportee_id'   => ANONYMOUS,
				'username'      => $username,
			 ));
		}
	}
	
	/**
	* Display message to the user if there where failed login trys
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_header($event)
	{
		if($this->user->data['failed_logins_count'] > 0)
		{
			$this->template->assign_vars(array(
				'FAILED_LOGINS'		=> ($this->user->data['failed_logins_count'] == 1) ? $this->user->lang['ONE_FAILED_LOGIN'] : sprintf($this->user->lang['FAILED_LOGINS_COUNT'], $this->user->data['failed_logins_count']),
			));
			
			$sql = 'UPDATE ' . USERS_TABLE . ' SET failed_logins_count = 0
						WHERE user_id = ' . (int) $this->user->data['user_id'];
			$this->db->sql_query($sql);
		}
	}
}
