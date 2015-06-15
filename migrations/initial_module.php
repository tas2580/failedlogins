<?php

/**
 *
 * @package phpBB Extension - tas2580 Failed logins
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace tas2580\failedlogins\migrations;

class initial_module extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['failedlogins_version']) && version_compare($this->config['failedlogins_version'], '1.0.0-RC1', '>=');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
					'failed_logins_count' => array('UINT', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users' => array('failed_logins_count'),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('failedlogins_version', '1.0.0-RC1')),
			// Add ACP modules
		);
	}

}
