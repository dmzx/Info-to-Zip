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

class ucp_infotozip_module
{
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container, $user;

		// Get an instance of the UCP controller
		$controller = $phpbb_container->get('dmzx.infotozip.ucp.controller');

		/** @var \phpbb\language\language $language */
		$language = $phpbb_container->get('language');

		// Add our language file
		$language->add_lang('ucp_infotozip', 'dmzx/infotozip');

		// Make the $u_action url available in the UCP controller
		$controller->set_page_url($this->u_action);

		switch ($mode)
		{
			case 'allmyzip':
				// Load a template for our UCP page
				$this->tpl_name = 'ucp_infotozip';
				// Set the page title for our UCP page
				$this->page_title = $user->lang['UCP_INFOTOZIP_TITLE'];
				// Load the display all handle in the ucp controller
				$controller->infotozip_all();
			break;

			case 'totalzip':
				// Load a template for our UCP page
				$this->tpl_name = 'ucp_infotozip';
				// Set the page title for our UCP page
				$this->page_title = $user->lang['UCP_INFOTOZIP_TITLE'];
				// Load the display total handle in the ucp controller
				$controller->infotozip_total();
			break;
		}
	}
}
