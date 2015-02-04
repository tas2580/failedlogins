<?php
/**
*
* @package phpBB Extension - tas2580 tas2580 failed logins
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\failedlogins\migrations;
class initial_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['failedlogins_version']) && version_compare($this->config['pss_version'], '0.1.3', '>=');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
					'failed_logins_count'	=> array('INT:', 0),
				),
			),
		);
	}
	
	public function revert_schema()
	{
			return array(
				'drop_columns'	=> array(
					$this->table_prefix . 'users' => array('failed_logins_count'),
				),
			);
	}
	
	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('failedlogins_version', '0.1.3')),
			// Add ACP modules
		);
	}
}