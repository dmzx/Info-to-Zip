<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\acp;

class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\dmzx\infotozip\acp\main_module',
			'title'		=> 'ACP_INFOTOZIP_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_INFOTOZIP',
					'auth'	=> 'ext_dmzx/infotozip && acl_a_board',
					'cat'	=> ['ACP_INFOTOZIP_TITLE']
				],
			],
		];
	}
}
