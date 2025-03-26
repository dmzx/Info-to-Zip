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

use phpbb\user;
use phpbb\template\template;
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\request\request_interface;
use phpbb\config\config;
use phpbb\pagination;
use dmzx\infotozip\core\functions_infotozip;

class ucp_controller
{
	/** @var user */
	protected $user;

	/** @var template */
	protected $template;

	/** @var db_interface */
	protected $db;

	/** @var request_interface */
	protected $request;

	/** @var config */
	protected $config;

	/** @var pagination */
	protected $pagination;

	/** @var functions_infotozip */
	protected $functions_infotozip;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* The database table
	*
	* @var string
	*/
	protected $infotozip_table;

	/**
	* Constructor
	*
	* @param ser							$user
	* @param template						$template
	* @param db_interface					$db
	* @param request_interface				$request
	* @param config							$config
	* @param pagination						$pagination
	* @param functions_infotozip			$functions_infotozip
	* @param string							$root_path
	* @param string 						$php_ext
	* @param string							$infotozip_table
	*
	*/
	public function __construct(
		user $user,
		template $template,
		db_interface $db,
		request_interface $request,
		config $config,
		pagination $pagination,
		functions_infotozip $functions_infotozip,
		$root_path,
		$php_ext,
		$infotozip_table
	)
	{
		$this->user					= $user;
		$this->template				= $template;
		$this->db					= $db;
		$this->request				= $request;
		$this->config	 			= $config;
		$this->pagination 			= $pagination;
		$this->functions_infotozip 	= $functions_infotozip;
		$this->root_path 			= $root_path;
		$this->php_ext 				= $php_ext;
		$this->infotozip_table 		= $infotozip_table;
	}

	public function infotozip_all()
	{
		$number		 	= $this->config['infotozip_count'];
		$start			= $this->request->variable('start', 0);
		$sort_key		= $this->request->variable('sk', 'chrono');
		$sort_dir		= $this->request->variable('sd', 'a');

		$sort_by_text	= ['chrono' => $this->user->lang['INFOTOZIP_ORD_CRONO'], 'creator' => $this->user->lang['INFOTOZIP_LINK_CREATOR'], 'affair' => $this->user->lang['INFOTOZIP_ORD_SUBJECT']];
		$sort_by_sql	= ['chrono' => 'post_time', 'creator' => 'poster_id', 'affair' => 'post_subject'];

		$limit_days	 = [];
		$sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
		$sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$user_id		= $this->user->data['user_id'];
		// Connected User
		$sql_array3 = [
			'SELECT'	=> '*',
			'FROM'		=> [
				USERS_TABLE => 'u',
			],
			'WHERE'	 => 'user_id = ' . (int) $user_id,
		];
		$sql3 = $this->db->sql_build_query('SELECT', $sql_array3);
		$result3 = $this->db->sql_query($sql3);
		$conec = $this->db->sql_fetchrow($result3);
		$this->db->sql_freeresult($result3);
		$user_id_name	= $conec['username'];

		// Search ALL posts for any encoded URLs
		$findtext = '[infotozip]';
		$sql = "SELECT post_id, poster_id, post_subject, post_text, bbcode_uid
			FROM " . POSTS_TABLE . "
			WHERE post_text LIKE '%$findtext%'
			ORDER BY " . $sql_sort_order;
		$result = $this->db->sql_query($sql);

		$total_credits = $max = $contados = 0;
		$rows = false;
		// Take one by one the values of the posts found
		while ($row = $this->db->sql_fetchrow($result))
		{
			$to = $subject_post = $url_tot = '';

			$textodelpost = $row['post_text'];
			decode_message($textodelpost, $row['bbcode_uid']);
			$links = '';
			$links = $this->functions_infotozip->return_substrings($textodelpost, '[infotozip]', '[/infotozip]');

			// All URLs encoded within a message
			for ($i=0; $i<count($links); $i++)
			{
				// We decode the link URL
				$goback_url	 	= strrev($links[$i]);
				$clear_url		= $this->functions_infotozip->decode_link($goback_url);
				$data			= explode('|||',$clear_url);
				$url			= $data[0];
				$id_prop		= $data[2];

				// We verify that the link is owned by the user
				if ($user_id == $id_prop)
				{
					$max++;
					// It only prints the range from start with a maximum of number
					if (($max > $start) and ($number > $contados))
					{
						$contados++;
						// Thread Creator User
						$sql_array2 = [
							'SELECT'	=> '*',
							'FROM'		=> [
								USERS_TABLE => 'u',
							],
							'WHERE'	 => 'user_id = ' . (int) $row['poster_id'],
						];
						$sql2 = $this->db->sql_build_query('SELECT', $sql_array2);
						$result12 = $this->db->sql_query($sql2);
						$owner = $this->db->sql_fetchrow($result12);
						$this->db->sql_freeresult($result12);

						$url_tot = '<a href="' . $url . '" class="button1" title="' . $url . '" target="_blank"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;' . $this->user->lang['INFOTOZIP_LINK_ADD_LINK_FILE'] . '</a>';
						if ($row['post_subject'] == '')
						{
							$subject_post = '<a href="' . $this->root_path . 'viewtopic.php?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $this->user->lang['INFOTOZIP_LINK_NO_SUBJECT'] . '</a><br>';
						}
						else
						{
							$subject_post = '<a href="' . $this->root_path . 'viewtopic.php?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $row['post_subject'] . '</a><br>';
						}
						$to = get_username_string('full', $owner['user_id'], $owner['username'], $owner['user_colour']);
						$rows = !$rows;

						// Add the items to the template
						$this->template->assign_block_vars('logs', [
							'OWNER'		 	=>	$subject_post,
							'LINK'			=>	$url_tot,
							'SUBJECT'		=>	$to,
							'S_ROW_COUNT'	=>	$rows,
						]);
					}
				}
			}
		}
		$this->db->sql_freeresult($result);

		// Make sure $start is set to the last page if it exceeds the amount
		$start = $this->pagination->validate_start($start, $number, $max);
		$base_url = append_sid("{$this->root_path}ucp.php?i=-dmzx-infotozip-ucp-ucp_infotozip_module", "mode=allmyzip&amp;sk=$sort_key&amp;sd=$sort_dir");
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

		// Generate the page template
		$this->template->assign_vars([
			'INFOTOZIP_TITLE'		=> $this->user->lang['INFOTOZIP_LIST_LINK_MEE'],
			'INFOTOZIP_COLUM1'	 	=> $this->user->lang['INFOTOZIP_LINK_LOCATED'],
			'INFOTOZIP_COLUM2'	 	=> $this->user->lang['INFOTOZIP_LINK'],
			'INFOTOZIP_COLUM3'	 	=> $this->user->lang['INFOTOZIP_LINK_CREATOR'],
			'INFOTOZIP_TOT'			=> $this->user->lang['INFOTOZIP_TOT_MEE'],
			'TRUE_LINK'		 		=> $max > 0 ? true : false,
			'LINK_X_PAGE'			=> (($max - $start) < $number ) ? ($max - $start) : $number,
			'S_LOGS_ACTION'	 		=> $base_url,
			'S_SELECT_SORT_KEY' 	=> $s_sort_key,
			'S_SELECT_SORT_DIR' 	=> $s_sort_dir,
			'TOTAL_POSTS'			=> $max,
		]);
	}

	public function infotozip_total()
	{
		$number		 	= $this->config['infotozip_count'];
		$start			= $this->request->variable('start', 0);
		$sort_key		= $this->request->variable('sk', 'chrono');
		$sort_dir		= $this->request->variable('sd', 'a');

		$sort_by_text	= ['chrono' => $this->user->lang['INFOTOZIP_ORD_CRONO'], 'creator' => $this->user->lang['INFOTOZIP_LINK_CREATOR'], 'affair' => $this->user->lang['INFOTOZIP_ORD_SUBJECT']];
		$sort_by_sql	= ['chrono' => 'post_time', 'creator' => 'poster_id', 'affair' => 'post_subject'];

		$limit_days	 = [];
		$sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
		$sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		// Total of ALL posts with any URL encoded
		$findtext = '[infotozip]';
		$sql = "SELECT COUNT(*) AS total
			FROM " . POSTS_TABLE . "
			WHERE post_text LIKE '%$findtext%'";
		$result = $this->db->sql_query($sql);
		$max = (int) $this->db->sql_fetchfield('total');
		$this->db->sql_freeresult($result);

		// Make sure $start is set to the last page if it exceeds the amount
		$start = $this->pagination->validate_start($start, $number, $max);

		// Search ALL posts for any encoded URLs
		$sql = "SELECT post_id, poster_id, post_subject, post_text, bbcode_uid
			FROM " . POSTS_TABLE . "
			WHERE post_text LIKE '%$findtext%'
			ORDER BY " . $sql_sort_order;
		$result = $this->db->sql_query_limit($sql, $number, $start);

		$total_credits = 0;
		$rows = false;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$who = $subject_post = '';

			$textodelpost = $row['post_text'];
			decode_message($textodelpost, $row['bbcode_uid']);
			$links = '';
			$links = $this->functions_infotozip->return_substrings($textodelpost, '[infotozip]', '[/infotozip]');

			// All URLs of links encoded within a message
			for ($i=0; $i<count($links); $i++)
			{
				// We decode the link URL
				$goback_url	 	= strrev($links[$i]);
				$clear_url		= $this->functions_infotozip->decode_link($goback_url);
				$data			= explode('|||',$clear_url);
				$id_prop		= $data[2];

				// User owner of the link
				$sql_array7 = [
					'SELECT'	=> '*',
					'FROM'		=> [
						USERS_TABLE => 'u',
					],
					'WHERE'	 => 'user_id = ' . (int) $id_prop,
				];
				$sql7 = $this->db->sql_build_query('SELECT', $sql_array7);
				$result7 = $this->db->sql_query($sql7);
				$owner = $this->db->sql_fetchrow($result7);
				$this->db->sql_freeresult($result7);

				if ($row['post_subject'] == '')
				{
					$subject_post = '<a href="' . $this->root_path . 'viewtopic.php?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $this->user->lang['INFOTOZIP_LINK_NO_SUBJECT'] . '</a><br>';
				}
				else
				{
					$subject_post = '<a href="' . $this->root_path . 'viewtopic.php?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $row['post_subject'] . '</a><br>';
				}
				$who = get_username_string('full', $owner['user_id'], $owner['username'], $owner['user_colour']);
				$rows = !$rows;

				// Add the items to the template
				$this->template->assign_block_vars('logs', [
					'OWNER'		 	=>	$subject_post,
					'SUBJECT'		=>	$who,
					'S_ROW_COUNT'	=>	$rows,
				]);
			}
		}
		$this->db->sql_freeresult($result);

		$base_url = append_sid("{$this->root_path}ucp.php?i=-dmzx-infotozip-ucp-ucp_infotozip_module", "mode=totalzip&amp;sk=$sort_key&amp;sd=$sort_dir");
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

		// Generate the page template
		$this->template->assign_vars([
			'INFOTOZIP_TITLE'			=> $this->user->lang['INFOTOZIP_LIST_LINK_ALL'],
			'INFOTOZIP_COLUM1'	 		=> $this->user->lang['INFOTOZIP_LINK_LOCATED'],
			'INFOTOZIP_COLUM3'	 		=> $this->user->lang['INFOTOZIP_LINK_CREATOR'],
			'INFOTOZIP_TOT'				=> $this->user->lang['INFOTOZIP_TOT_MEE'],
			'TRUE_LINK'		 			=> $max > 0 ? true : false,
			'LINK_X_PAGE'				=> (($max - $start) < $number ) ? ($max - $start) : $number,
			'S_LOGS_ACTION'	 			=> $base_url,
			'S_SELECT_SORT_KEY' 		=> $s_sort_key,
			'S_SELECT_SORT_DIR' 		=> $s_sort_dir,
			'TOTAL_POSTS'				=> $max,
		]);
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
