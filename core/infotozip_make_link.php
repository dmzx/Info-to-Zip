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

use phpbb\exception\http_exception;
use phpbb\auth\auth;
use phpbb\template\template;
use phpbb\user;
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\request\request_interface;
use dmzx\infotozip\core\functions_infotozip;

class infotozip_make_link
{
	/** @var auth */
	protected $auth;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var db_interface */
	protected $db;

	/** @var request_interface */
	protected $request;

	/** @var functions_infotozip */
	protected $functions_infotozip;

	/** @var string phpBB root path */
	protected $root_path;

	/**
	* Constructor
	*
	* @param auth						$auth
	* @param template					$template
	* @param user						$user
	* @param db_interface				$db
	* @param request_interface			 $request
	* @param functions_infotozip		$functions_infotozip
	* @param string							$root_path
	*
	*/
	public function __construct(
		auth $auth,
		template $template,
		user $user,
		db_interface $db,
		request_interface $request,
		functions_infotozip $functions_infotozip,
		$root_path
	)
	{
		$this->auth						= $auth;
		$this->template					= $template;
		$this->user						= $user;
		$this->db						= $db;
		$this->request					= $request;
		$this->functions_infotozip 		= $functions_infotozip;
		$this->root_path				= $root_path;
	}

	function main()
	{
		$link = trim($this->request->variable('infotoziplink', ''));
		$id_prop = (int) $this->user->data['user_id'];
		$infotoziplink = $link;
		$credits = 1;

		// Create a zip file containing the $link URL
		$zip = new \ZipArchive();
		$uniquePrefix = $id_prop . '_' . bin2hex(random_bytes(4));
		$zipFilename = tempnam(sys_get_temp_dir(), $uniquePrefix) . '.zip';

		if ($zip->open($zipFilename, \ZipArchive::CREATE) !== TRUE)
		{
			throw new http_exception(400, 'INFOTOZIP_ERROR_WRITEABLE');
		}

		$uniqueFilename = $uniquePrefix . '.txt';
		$zip->addFromString($uniqueFilename, $link);
		$zip->close();

		// Read the zip file content
		$zipContent = file_get_contents($zipFilename);

		// Encode the content to base64
		$ziptemp = base64_encode($zipContent);

		// Store the zip file in the specified path
		$destinationPath = $this->root_path . 'info_to_zip/';

		if (!is_dir($destinationPath))
		{
			mkdir($destinationPath, 0755, true);
		}

		$storedZipFilename = $destinationPath . basename($zipFilename);
		copy($zipFilename, $storedZipFilename);

		// Clean up the temporary zip file
		unlink($zipFilename);

		$realzip = generate_board_url() . '/'	. 'info_to_zip/' . basename($zipFilename);

		$addlink =	$realzip . '|||' . $credits . '|||' . $id_prop;
		$addlinkcodec = $this->functions_infotozip->encode_link($addlink);
		$linkok = '[infotozip]' . strrev($addlinkcodec) . '[/infotozip]';

		$this->template->assign_vars([
			'ERRORS'	=> $infotoziplink == '' ? true : false,
			'T_ERROR'	=> $this->user->lang['INFOTOZIP_LINK_ADD_ALERT_URL'],
			'U_MAKE'	=> "{$this->root_path}app.php/infotozip?mode=input",
			'LINK_OK'	=> $linkok,
		]);

		// Generate the page
		page_header($this->user->lang['ADD_LINK']);

		// Generate the page template
		$this->template->set_filenames([
			'body'	=> 'infotozip_make_link.html'
		]);

		page_footer();
	}
}
