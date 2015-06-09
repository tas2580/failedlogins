<?php

/**
 *
 * @package phpBB Extension - tas2580 Failed logins
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
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

	/** @var \phpbb\log\log */
	protected $log;

	/**
	 * Constructor
	 *
	 * @param \phpbb\template\template	$template
	 * @param \phpbb\user				$user
	 * @param \phpbb\db\driver\driver		$db
	 * @param \phpbb\request\request		$request
	 * @access public
	 */
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\log\log $log)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->request = $request;
		$this->log = $log;
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
			'core.user_setup'		=> 'user_setup',
			'core.login_box_failed'	=> 'login_box_failed',
			'core.page_header'		=> 'page_header',
		);
	}

	/**
	 * Add language on user setup
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function user_setup($event)
	{
		$this->user->add_lang_ext('tas2580/failedlogins', 'common');
	}

	/**
	 * If login failed set the conter +1
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function login_box_failed($event)
	{
		$sql = 'UPDATE ' . USERS_TABLE . ' SET failed_logins_count = failed_logins_count + 1
					WHERE username_clean = "' . $this->db->sql_escape(strtolower($event['username'])) . '"';
		$this->db->sql_query($sql);

		$this->log->add('user', ANONYMOUS, $this->user->ip, 'TRY_TO_LOGIN_FAIL', time(), array(
			'reportee_id'	=> ANONYMOUS,
			'username'	=> $event['username'],
		));
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
		if ($this->user->data['failed_logins_count'] > 0)
		{
			$this->template->assign_vars(array(
				'FAILED_LOGINS'	=> ($this->user->data['failed_logins_count'] == 1) ? $this->user->lang['ONE_FAILED_LOGIN'] : sprintf($this->user->lang['FAILED_LOGINS_COUNT'], $this->user->data['failed_logins_count']),
			));

			$sql = 'UPDATE ' . USERS_TABLE . ' SET failed_logins_count = 0
						WHERE user_id = ' . (int) $this->user->data['user_id'];
			$this->db->sql_query($sql);
		}
	}

}