<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, [
	'INFOTOZIP_LINK_TIT_DESCR'	 	=> 'Download Management',
	'INFOTOZIP_LINK_DESCRIPTION'	=> 'In this section we have the necessary options to control our Zip files.<br>From all listings will have access to both the download URL and the forum thread where the link is.',
	'INFOTOZIP_LIST_LINK_MEE'		=> 'List of all your zip files',
	'INFOTOZIP_LIST_LINK_ALL'		=> 'List of all zip files in the forum',
	'INFOTOZIP_OWNER'				=> 'Owner',
	'INFOTOZIP_LINK'				=> 'files',
	'INFOTOZIP_LINK_LOCATED'		=> 'Posts with zip file',
	'INFOTOZIP_LINK_CREATOR'		=> 'Member',
	'INFOTOZIP_TOT_MEE'				=> 'Total Value by',
	'INFOTOZIP_ORD_CRONO'			=> 'Chronological Order',
	'INFOTOZIP_ORD_SUBJECT'			=> 'Message Subject',
	'INFOTOZIP_INFOTOZIP'	 		=> 'Credits',
	'INFOTOZIP_VERSION'				=> 'Version',
	'INFOTOZIP_PAY_TOTAL'			=> 'Total',
	'INFOTOZIP_SWITCHED_OFF'		=> 'Info to Zip is off',
]);
