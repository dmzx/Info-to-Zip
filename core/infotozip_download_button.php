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
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\db\tools\tools_interface;
use phpbb\config\config;
use dmzx\infotozip\core\functions_infotozip;

class infotozip_download_button
{
	/** @var auth */
	protected $auth;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var db_interface */
	protected $db;

	/** @var tools_interface */
	protected $db_tools;

	/** @var config */
	protected $config;

	/** @var functions_infotozip */
	protected $functions_infotozip;

	/**
	* The database table
	*
	* @var string
	*/
	protected $infotozip_table;

	/**
	* Constructor
	*
	* @param auth					$auth
	* @param template				$template
	* @param user					$user
	* @param db_interface			$db
	* @param tools_interface		$db_tools
	* @param config					$config
	* @param functions_infotozip	$functions_infotozip
	* @param string					$infotozip_table
	*
	*/
	public function __construct(
		auth $auth,
		template $template,
		user $user,
		db_interface $db,
		tools_interface $db_tools,
		config $config,
		functions_infotozip $functions_infotozip,
		$infotozip_table
	)
	{
		$this->auth						 	= $auth;
		$this->template					 	= $template;
		$this->user						 	= $user;
		$this->db							= $db;
		$this->db_tools					 	= $db_tools;
		$this->config						= $config;
		$this->functions_infotozip 			= $functions_infotozip;
		$this->infotozip_table		 		= $infotozip_table;
	}

	function main($urlcodec)
	{
		$cat_url	 	= $urlcodec;
		$goback_url		= strrev($cat_url);
		$clear_url		= $this->functions_infotozip->decode_link($goback_url);
		$data			= explode('|||',$clear_url);
		$url		 	= isset($data[0]) ? $data[0] : '';
		$id_prop	 	= isset($data[2]) ? $data[2] : '';
		$ext_theme_path	= generate_board_url() . '/' . $this->functions_infotozip->get_ext_name() . 'styles/all/theme/images';

		// Check enabled.
		if ($this->config['infotozip_enable'])
		{
			$download = $url;
			$link_colour = 'green';
			$link_redirect = '_blank';
			$link_no_arrow = '';
			$title = $this->user->lang['INFOTOZIP_LINK_ADD_NO_PAY'];
			$text = $this->user->lang['INFOTOZIP_LINK_ADD_ENABLED'];
		}
		else
		{
			$download = '';
			$link_colour = 'red';
			$link_redirect = '';
			$link_no_arrow = 'no-';
			$title = $this->user->lang['INFOTOZIP_NOT_ENABLED'];
			$text = '';
		}

		// Exclude Bots & Check that the connected user has access
		if (($this->user->data['is_bot']) or (!$this->auth->acl_get('u_infotozip_use_member')))
		{
			$title = $this->user->lang['INFOTOZIP_LINK_DOWNLOAD_ERROR'];
			$download = '';
			$link_colour = 'red';
			$link_redirect = '';
			$link_no_arrow = 'no-';
			$text = $this->user->lang['INFOTOZIP_LINK_DOWNLOAD_ERROR_NOTIFY'];
		}

		$this->template->assign_vars([
			'LINK_UP_TITLE'	 	=> $title,
			'LINK_DOWNLOAD'	 	=> $download,
			'LINK_TEXT'			=> $text,
			'LINK_THEME_PATH'	=> $ext_theme_path,
			'LINK_COLOUR'		=> $link_colour,
			'LINK_REDIRECT'		=> $link_redirect,
			'LINK_NO_ARROW'		=> $link_no_arrow,
		]);

		// Generate the page
		page_header($this->user->lang['ADD_LINK']);

		// Generate the page template
		$this->template->set_filenames([
			'body'	=> 'infotozip_button.html'
		]);

		page_footer();
	}
}
