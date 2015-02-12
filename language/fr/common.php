<?php
/**
*
* @package phpBB Extension - tas2580 tas2580 failed logins
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @translated into French by Galixte (http://www.galixte.com)
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
	'ONE_FAILED_LOGIN'		=> 'Depuis votre dernière visite il y a eu une tentative de connexion qui a échoué !',
	'FAILED_LOGINS_COUNT'	=> 'Depuis votre dernière visite il y a eu %d tentatives de connexion qui ont échoué !',
	'TRY_TO_LOGIN_FAIL'		=> '<strong>Échec de la connexion</strong><br />» Nom d’utilisateur : %s',
));
