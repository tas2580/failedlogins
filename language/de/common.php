<?php
/**
*
* @package phpBB Extension - tas2580 failed logins
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
	'ONE_FAILED_LOGIN'		=> 'Seit deinem letzten Login gab es einen fehlgeschlagenen Loginversuch!',
	'FAILED_LOGINS_COUNT'	=> 'Seit deinem letzten Login gab es %d fehlgeschlagene Loginversuche!',
	'TRY_TO_LOGIN_FAIL'		=> '<strong>Anmeldung fehlgeschlagen</strong><br />» Benutzername: %s',
));