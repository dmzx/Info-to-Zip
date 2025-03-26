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

class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \dmzx\infotozip\controller\acp_controller $acp_controller */
		$acp_controller = $phpbb_container->get('dmzx.infotozip.controller.acp');

		/** @var \phpbb\language\language $language */
		$language = $phpbb_container->get('language');

		// Add our language file
		$language->add_lang('acp_infotozip', 'dmzx/infotozip');

		// Load a template from adm/style for our ACP page
		$this->tpl_name = 'acp_infotozip_body';

		// Set the page title for our ACP page
		$this->page_title = $language->lang('ACP_INFOTOZIP_TITLE');

		// Make the $u_action url available in our ACP controller
		$acp_controller->set_page_url($this->u_action);

		// Load the display options handle in our ACP controller
		$acp_controller->display_options();
	}
}
