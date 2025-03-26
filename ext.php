<?php
/**
 *
 * Info to Zip. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2025, dmzx, https://www.dmzx-web.net - martin, https://www.martins-play-ground.uk
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\infotozip;

use phpbb\extension\base;

class ext extends base
{
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return version_compare($config['version'], '3.3.0', '>=');
	}
}
