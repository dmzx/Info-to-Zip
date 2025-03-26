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
	'TITLE_LINK'									=> 'Info to zip',
	'LINK_EXPLAIN'									=> 'Add your information here and zip link will be generated.',
	'INFOTOZIP_LINK_ADD_TITLE'						=> 'Enter URL in field and all relevant data !',
	'INFOTOZIP_LINK_ADD_TITLE_BOX1'					=> 'Full text:<br>Add all relevant information here.',
	'INFOTOZIP_LINK_ADD_TITLE_BOX2_EXPLAIN'			=> '',
	'INFOTOZIP_LINK_ADD_EXPLAIN'					=> 'Note: This extension will add a .txt file to zip. URL will be generated.',
	'INFOTOZIP_LINK_ADD_TO_POST'					=> 'Insert link direct to the Message',
	'INFOTOZIP_LINK_ADD_ALERT_URL'					=> 'You must enter a valid data !!!',
	'INFOTOZIP_LINK_ADD_FREE_FILE_DOWN'				=> 'Download link',
	'INFOTOZIP_LINK_ADD_FREE_DOWN'					=> '(Download information)',
	'INFOTOZIP_LINK_ADD_NO_PAY'						=> 'Click to download the file',
	'INFOTOZIP_LINK_ADD_ENABLED'					=> '(Click to start downloading)',
	'INFOTOZIP_LINK_ADD_BUTTON'						=> 'Make zip url',
	'INFOTOZIP_COPY'								=> 'Copy',
	'INFOTOZIP_VERSION'								=> 'Version',
	'INFOTOZIP_LINK_ADD_LINK_FILE'					=> 'Link to file',
	'ADD_LINK'										=> 'Download Link',
	'INFOTOZIP_LINK_ADD_CORRECT'					=> 'Link OK !!!',
	'INFOTOZIP_LINK_ADD_OTHER'						=> 'Add another link ?',
	'INFOTOZIP_LINK_ADD_ERROR'						=> '!! ERROR IN THE DATA FROM THE ZIP FILE !!',
	'INFOTOZIP_LINK_DOWNLOAD_ERROR'					=> 'You cannot download this file',
	'INFOTOZIP_LINK_DOWNLOAD_ERROR_NOTIFY'	 		=> '(No permission to download this file)',
	'INFOTOZIP_LINK_ADD_TITLE_BOX3'					=> 'ZIP Link Data:',
	'INFOTOZIP_LINK_ADD_ERROR_NOTIFY'	 			=> '(Report it to the administrator of the board)',
	'INFOTOZIP_NOLOGS'								=> 'Empty log',
	'INFOTOZIP_NOT_ENABLED'							=> 'Info to Zip not enabled.',

	//UCP
	'INFOTOZIP_ZIP_ME'								=> 'All my zip files',
	'INFOTOZIP_ZIP_ALL'								=> 'All Zip Files',
]);
