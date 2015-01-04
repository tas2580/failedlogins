<?php
/**
*
* @package phpBB Extension - tas2580 failed logins
* @copyright (c) 2014 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\failed_logins\event;

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
	public function __construct(\phpbb\config\config $config)
	{
		$this->config = $config;
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
		global $request;
		if($request->is_set('login'))
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
			global $phpbb_log, $user, $request;
			$username = $request->variable('username', '', true);

			$user->add_lang_ext('tas2580/failed_logins', 'common');
			$user_ip = (empty($user->ip)) ? '' : $user->ip;
			$additional_data['reportee_id'] = ANONYMOUS;
			$phpbb_log->add('user', ANONYMOUS, $user_ip, sprintf($user->lang['TRY_TO_LOGIN_FAIL'], $username), time(), $additional_data);
		}
	}
}
