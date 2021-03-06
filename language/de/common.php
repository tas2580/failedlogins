<?php
/**
*
* @package phpBB Extension - tas2580 Failed logins
* @copyright (c) 2015 tas2580 (https://tas2580.net)
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
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//
$lang = array_merge($lang, array(
	'FAILED_LOGINS_COUNT'		=> 'Seit deinem letzten Besuch gab es %d fehlgeschlagene Loginversuche!',
	'ONE_FAILED_LOGIN'			=> 'Seit deinem letzten Besuch gab es einen fehlgeschlagenen Loginversuch!',
	'TRY_TO_LOGIN_FAIL'		=> '<strong>Anmeldung fehlgeschlagen</strong><br />» Benutzername: %s',
	'REMOVE_MESSAGE'			=> 'Meldung entfernen',
	'REMOVED_FAILED_LOGINS'	=> 'Die fehlgeschlagenen Logins seit deinem letzten besuch werden jetzt nicht mehr angezeigt.'
));
