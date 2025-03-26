<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\core;

use phpbb\auth\auth;
use phpbb\template\template;
use phpbb\user;

class infotozip_input_link
{
	/** @var auth */
	protected $auth;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var string */
	protected $php_ext;

	/** @var string phpBB root path */
	protected $root_path;

	/**
	* Constructor
	*
	* @param auth		$auth
	* @param template	$template
	* @param user		$user
	* @param string		$php_ext
	* @param string		$root_path
	*
	*/
	public function __construct(
		auth $auth,
		template $template,
		user $user,
		$php_ext,
		$root_path
	)
	{
		$this->auth			= $auth;
		$this->template		= $template;
		$this->user			= $user;
		$this->php_ext		= $php_ext;
		$this->root_path	= $root_path;
	}

	function main()
	{
		$this->template->assign_vars([
			'U_MAKE'	=> "{$this->root_path}app.php/infotozip?mode=make",
		]);

		// Generate the page
		page_header($this->user->lang['ADD_LINK']);

		// Generate the page template
		$this->template->set_filenames([
			'body'	=> 'infotozip_input_link.html'
		]);

		page_footer();
	}
}
