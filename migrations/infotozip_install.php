<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\migrations;

class infotozip_install extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v320\v320'
		];
	}

	public function update_data()
	{
		return [
			['config.add', ['infotozip_version', '1.0.0']],
			['config.add', ['infotozip_enable', 0]],
			['config.add', ['infotozip_count', 15]],

			// Members
			['permission.add', ['u_infotozip_use_member', true]],
			['permission.permission_set', ['REGISTERED', 'u_infotozip_use_member', 'group']],

			// Admins
			['permission.add', ['u_infotozip_use', true]],
			['permission.add', ['u_infotozip_see_ucp', true]],
			['permission.permission_set', ['ADMINISTRATORS', 'u_infotozip_use', 'group']],
			['permission.permission_set', ['ADMINISTRATORS', 'u_infotozip_see_ucp', 'group']],
			['permission.permission_set', ['ADMINISTRATORS', 'u_infotozip_use_member', 'group']],

			['custom', [[$this, 'install_infotozip_bbcode']]],

			['module.add', [
				'ucp',
				false,
				'UCP_INFOTOZIP_TITLE'
			]],
			['module.add', [
				'ucp',
				'UCP_INFOTOZIP_TITLE',
				[
					'module_basename'	=> '\dmzx\infotozip\ucp\ucp_infotozip_module',
					'auth'				=> 'ext_dmzx/infotozip',
					'modes'			 => [
						'allmyzip',
						'totalzip'
					],
				],
			]],
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_INFOTOZIP_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_INFOTOZIP_TITLE',
				[
					'module_basename'	=> '\dmzx\infotozip\acp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}

	public function revert_data()
	{
		return [
			['custom', [[$this, 'remove_infotozip_bbcode']]],
		];
	}

	public function install_infotozip_bbcode()
	{
		if (!class_exists('acp_bbcodes'))
		{
			include($this->phpbb_root_path . 'includes/acp/acp_bbcodes.' . $this->php_ext);
			}

		$bbcode_tool = new \acp_bbcodes();

		$bbcode_name = 'infotozip';
		$bbcode_array = [
			'bbcode_helpline'		=> '[infotozip]Download link coded URL[/infotozip]',
			'display_on_posting'	=> 0,
			'bbcode_match'			=> '[infotozip]{SIMPLETEXT}[/infotozip]',
			'bbcode_tpl'			=> '<iframe src="./app.php/infotozip?mode=preview&urlcodec={SIMPLETEXT}" width="100%" height="90" frameborder=0 scrolling="no" style="margin-left: 0px; margin-right: 0px; margin-top: 0px; margin-bottom: 0px;">WARNING: [EXT] Info to Zip - To work correctly, it is necessary to enable the iframes in your browser !!!.</iframe>',
		];

		$data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl'], $bbcode_array['bbcode_helpline']);

		$bbcode_array += [
			'bbcode_tag'			=> $data['bbcode_tag'],
			'first_pass_match'		=> $data['first_pass_match'],
			'first_pass_replace'	=> $data['first_pass_replace'],
			'second_pass_match'		=> $data['second_pass_match'],
			'second_pass_replace' 	=> $data['second_pass_replace']
		];

		$sql = 'SELECT bbcode_id
			FROM ' . $this->table_prefix . "bbcodes
			WHERE LOWER(bbcode_tag) = '" . strtolower($bbcode_name) . "'
			OR LOWER(bbcode_tag) = '" . strtolower($bbcode_array['bbcode_tag']) . "'";
		$result = $this->db->sql_query($sql);
		$row_exists = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row_exists)
		{
			$bbcode_id = $row_exists['bbcode_id'];

			$sql = 'UPDATE ' . $this->table_prefix . 'bbcodes
				SET ' . $this->db->sql_build_array('UPDATE', $bbcode_array) . '
				WHERE bbcode_id = ' . (int) $bbcode_id;
			$this->db->sql_query($sql);
		}
		else
		{
			$sql = 'SELECT MAX(bbcode_id) AS max_bbcode_id
				FROM ' . $this->table_prefix . 'bbcodes';
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row)
			{
				$bbcode_id = $row['max_bbcode_id'] + 1;

				if ($bbcode_id <= NUM_CORE_BBCODES)
				{
					$bbcode_id = NUM_CORE_BBCODES + 1;
				}
			}
			else
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}

			if ($bbcode_id <= BBCODE_LIMIT)
			{
				$bbcode_array['bbcode_id'] = (int) $bbcode_id;

				$this->db->sql_query('INSERT INTO ' . $this->table_prefix . 'bbcodes ' . $this->db->sql_build_array('INSERT', $bbcode_array));
			}
		}
	}

	public function remove_infotozip_bbcode()
	{
		$sql = 'DELETE FROM ' . BBCODES_TABLE . ' WHERE bbcode_tag = \'infotozip\'';
		$this->db->sql_query($sql);
	}
}
