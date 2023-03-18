<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Sites controller.
 *
 * @since  0.5.0
 */
class SitesController extends AdminController
{
		/**
		 * The prefix to use with controller messages.
		 *
		 * @var    string
		 * @since  1.6
		 */
		protected $text_prefix = 'COM_SITES_SITES';

		/**
		 * Proxy for getModel
		 *
		 * @param string $name   The model name. Optional.
		 * @param string $prefix The class prefix. Optional.
		 * @param array  $config The array of possible config values. Optional.
		 *
		 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
		 *
		 * @since   0.5.0
		 */
		public function getModel($name = 'Site', $prefix = 'Administrator', $config = array('ignore_request' => true))
		{
				return parent::getModel($name, $prefix, $config);
		}
}
