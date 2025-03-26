<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip\controller;

use dmzx\infotozip\core\infotozip_preview_button;
use dmzx\infotozip\core\infotozip_download_button;
use dmzx\infotozip\core\infotozip_input_link;
use dmzx\infotozip\core\infotozip_make_link;
use phpbb\request\request_interface;

class main
{
	/** @var infotozip_preview_button */
	protected $infotozip_preview_button;

	/** @var infotozip_download_button */
	protected $infotozip_download_button;

	/** @var infotozip_input_link */
	protected $infotozip_input_link;

	/** @var infotozip_make_link */
	protected $infotozip_make_link;

	/** @var request_interface */
	protected $request;

	/**
	* Constructor
	*
	* @var infotozip_preview_button		$infotozip_preview_button
	* @var infotozip_download_button	$infotozip_download_button
	* @var infotozip_input_link			$infotozip_input_link
	* @var infotozip_make_link			$infotozip_make_link
	* @param request_interface			$request
	*
	*/
	public function __construct(
		infotozip_preview_button $infotozip_preview_button,
		infotozip_download_button $infotozip_download_button,
		infotozip_input_link $infotozip_input_link,
		infotozip_make_link $infotozip_make_link,
		request_interface $request
	)
	{
		$this->infotozip_preview_button		= $infotozip_preview_button;
		$this->infotozip_download_button	= $infotozip_download_button;
		$this->infotozip_input_link			= $infotozip_input_link;
		$this->infotozip_make_link		 	= $infotozip_make_link;
		$this->request						= $request;
	}

	public function handle_infotozip()
	{
		$mode = $this->request->variable('mode', '');
		$urlcodec = $this->request->variable('urlcodec', '');

		if (!$mode)
		{
			trigger_error('INFOTOZIP_LINK_ADD_ERROR');
		}

		switch ($mode)
		{
			case 'download':
				$this->infotozip_download_button->main($urlcodec);
			break;

			case 'preview':
				$this->infotozip_preview_button->main($urlcodec);
			break;

			case 'input':
				$this->infotozip_input_link->main();
			break;

			case 'make':
				$this->infotozip_make_link->main();
			break;
		}
	}
}
