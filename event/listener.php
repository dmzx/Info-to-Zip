<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\user;
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\db\tools\tools_interface;
use phpbb\config\config;
use phpbb\template\template;
use phpbb\auth\auth;
use dmzx\infotozip\core\functions_infotozip;

class listener implements EventSubscriberInterface
{
	/** @var user */
	protected $user;

	/** @var db_interface */
	protected $db;

	/** @var tools_interface */
	protected $db_tools;

	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var auth */
	protected $auth;

	/** @var functions_infotozip */
	protected $functions_infotozip;

	/**
	* Constructor
	*
	* @param user								$user
	* @param driver_interface 					$db
	* @param tools_interface					$db_tools
	* @param config								$config
	* @param template							$template
	* @param auth								$auth
	* @param functions_infotozip				$functions_infotozip
	*
	*/
	public function __construct(
		user $user,
		db_interface $db,
		tools_interface $db_tools,
		config $config,
		template $template,
		auth $auth,
		functions_infotozip $functions_infotozip
	)
	{
		$this->user		 			= $user;
		$this->db					= $db;
		$this->db_tools	 			= $db_tools;
		$this->config	 			= $config;
		$this->template				= $template;
		$this->auth 				= $auth;
		$this->functions_infotozip 	= $functions_infotozip;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'		=> 'load_language_on_setup',
			'core.permissions'		=> 'permissions',
			'core.page_header'		=> 'page_header',
		];
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'dmzx/infotozip',
			'lang_set' => 'infotozip',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function permissions($event)
	{
		$event['permissions'] = array_merge($event['permissions'], [
			'u_infotozip_use'	=> [
				'lang'		=> 'ACL_U_INFOTOZIP_USE',
				'cat'		=> 'Info to Zip'
			],'u_infotozip_see_ucp'	=> [
				'lang'		=> 'ACL_U_INFOTOZIP_SEE_UCP',
				'cat'		=> 'Info to Zip'
			],'u_infotozip_use_member'	=> [
				'lang'		=> 'ACL_U_INFOTOZIP_USE_MEMBER',
				'cat'		=> 'Info to Zip'
			],
		]);
		$event['categories'] = array_merge($event['categories'], [
			'Info to Zip'	=> 'ACL_U_INFOTOZIP',
		]);
	}

	public function page_header($event)
	{
		$this->template->assign_vars([
			'S_INFOTOZIP_ENABLE'		=> ($this->auth->acl_get('u_infotozip_use') && $this->config['infotozip_enable']) ? true : false,
			'S_INFOTOZIP_FOOTER_VIEW'	=> true,
		]);
		$this->functions_infotozip->assign_authors();
	}
}
