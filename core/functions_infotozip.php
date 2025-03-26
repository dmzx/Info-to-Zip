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

use phpbb\config\config;
use phpbb\template\template;
use phpbb\extension\manager;
use phpbb\path_helper;

class functions_infotozip
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var manager */
	protected $extension_manager;

	/** @var path_helper */
	protected $path_helper;

	/** @var string */
	private $ext_name = 'dmzx/infotozip';

	/**
	 * Constructor
	 *
	 * @param config				$config
	 * @param template				$template
	 * @param manager 				$extension_manager
	 * @param path_helper			$path_helper
	 *
	 */
	public function __construct(
		config $config,
		template $template,
		manager $extension_manager,
		path_helper $path_helper
	)
	{
		$this->config 					= $config;
		$this->template 				= $template;
		$this->extension_manager		= $extension_manager;
		$this->path_helper 				= $path_helper;
	}

	public function assign_authors()
	{
		$md_manager = $this->extension_manager->create_extension_metadata_manager('dmzx/infotozip', $this->template);
		$meta = $md_manager->get_metadata();
		$author_names = [];
		$author_homepages = [];

		foreach (array_slice($meta['authors'], 0, 2) as $author)
		{
			$author_names[] = $author['name'];
			$author_homepages[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', $author['homepage'], $author['name']);
		}

		$this->template->assign_vars([
			'INFOTOZIP_DISPLAY_NAME'		=> $meta['extra']['display-name'],
			'INFOTOZIP_AUTHOR_NAMES'		=> implode(' &amp; ', $author_names),
			'INFOTOZIP_AUTHOR_HOMEPAGES'	=> implode(' &amp; ', $author_homepages),
			'INFOTOZIP_VERSION' 			=> $this->config['infotozip_version'],
		]);
	}

	public function decode_link($string)
	{
		$decode = str_replace("ZDNtN3p4","",$string);
		$decode = str_replace("Vzhibjh0","",$decode);
		$decoded = base64_decode(str_pad(strtr($decode, '-_', '+/'), strlen($decode) % 4, '=', STR_PAD_RIGHT));
		return $decoded;
	}

	public function encode_link($string)
	{
		$encode = rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
		$encode = $encode[0].rtrim(strtr(base64_encode('d3m7zx'), '+/', '-_'), '=').substr($encode,1);
		$encode = substr($encode,0,strlen($encode)-2).rtrim(strtr(base64_encode('W8bn8t'), '+/', '-_'), '=').substr($encode,strlen($encode)-2,2);
		return $encode;
	}

	public function return_substrings($text, $sopener, $scloser)
	{
		$result = [];

		$noresult = substr_count($text, $sopener);
		$ncresult = substr_count($text, $scloser);

		if ($noresult < $ncresult)
		{
			$nresult = $noresult;
		}
		else
		{
			$nresult = $ncresult;
		}

		unset($noresult);
		unset($ncresult);

		for ($i=0; $i<$nresult; $i++)
		{
			$pos = strpos($text, $sopener) + strlen($sopener);
			$text = substr($text, $pos, strlen($text));
			$pos = strpos($text, $scloser);
			$result[] = substr($text, 0, $pos);
			$text = substr($text, $pos + strlen($scloser), strlen($text));
		}
		return $result;
	}

	public function get_ext_name($web_root_path = false)
	{
		return (($web_root_path) ? $this->path_helper->get_web_root_path() : '') . $this->extension_manager->get_extension_path($this->ext_name);
	}
}
