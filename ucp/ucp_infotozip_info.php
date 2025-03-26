<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\ucp;

class ucp_infotozip_info
{
	function module()
	{
		return [
			'filename'			=> 'dmzx\infotozip\ucp\ucp_infotozip_module',
			'title'			 => 'UCP_INFOTOZIP_TITLE',
			'modes'			 => [
				'allmyzip'	=> [
					'title'	 => 'INFOTOZIP_ZIP_ME',
					'auth'		=> 'ext_dmzx/infotozip && acl_u_infotozip_see_ucp',
					'cat'		=> ['UCP_MAIN']
				],
				'totalzip'	=> [
					'title'	 => 'INFOTOZIP_ZIP_ALL',
					'auth'		=> 'ext_dmzx/infotozip && acl_u_infotozip_see_ucp',
					'cat'		=> ['UCP_MAIN']
				],
			],
		];
	}
}
