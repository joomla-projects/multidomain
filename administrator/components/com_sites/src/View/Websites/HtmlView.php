<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\View\Websites;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\ListView;

/**
 * Websites view class for the Sites package.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends ListView
{

		/**
		 * Constructor
		 *
		 * @param   array  $config  An optional associative array of configuration settings.
		 */
		public function __construct(array $config)
		{
				// Set the component name if not passed
				if (empty($config['option']))
				{
						$this->option = 'com_sites';
				}

				$config['toolbar_icon'] = 'users-cog';
				$config['supports_batch'] = false;

				parent::__construct($config);

				$this->canDo = ContentHelper::getActions('com_sites');
		}
}
