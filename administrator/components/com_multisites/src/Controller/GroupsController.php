<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Groups controller.
 *
 * @since  __DEPLOY_VERSION__
 */
class GroupsController extends AdminController
{
		/**
		 * The prefix to use with controller messages.
		 *
		 * @var    string
		 * @since  1.6
		 */
		protected $text_prefix = 'COM_MULTISITES_GROUPS';

		/**
		 * Proxy for getModel
		 *
		 * @param string $name   The model name. Optional.
		 * @param string $prefix The class prefix. Optional.
		 * @param array  $config The array of possible config values. Optional.
		 *
		 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		public function getModel($name = 'Group', $prefix = 'Administrator', $config = array('ignore_request' => true))
		{
				return parent::getModel($name, $prefix, $config);
		}
}
