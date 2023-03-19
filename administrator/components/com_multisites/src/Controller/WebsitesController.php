<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Input\Input;
use Joomla\Utilities\ArrayHelper;

/**
 * Websites controller.
 *
 * @since  __DEPLOY_VERSION__
 */
class WebsitesController extends AdminController
{
		/**
		 * The prefix to use with controller messages.
		 *
		 * @var    string
		 * @since  1.6
		 */
		protected $text_prefix = 'COM_MULTISITES_WEBSITES';

		/**
		 * The group ID where we're working in
		 *
		 * @var integer
		 */
		protected $groupId;

		/**
		 * Constructor.
		 *
		 * @param   array                $config   An optional associative array of configuration settings.
		 * @param   MVCFactoryInterface  $factory  The factory.
		 * @param   CMSApplication       $app      The Application for the dispatcher
		 * @param   Input                $input    Input
		 *
		 * @since   __DEPLOY_VERSION__
		 * @throws  \InvalidArgumentException when no extension or workflow id is set
		 */
		public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?Input $input = null)
		{
			parent::__construct($config, $factory, $app, $input);

			$this->groupId = $app->getUserStateFromRequest('com_multisites.websites.filter.group_id', 'group_id', 0, 'int');
	
			$this->registerTask('unsetDefault', 'setDefault');
		}

		/**
		 * Method to set the home property for a list of items
		 *
		 * @return  void
		 *
		 * @since  __DEPLOY_VERSION__
		 */
		public function setDefault()
		{
			// Check for request forgeries
			$this->checkToken();
	
			// Get items to publish from the request.
			$cid   = (array) $this->input->get('cid', [], 'int');
			$data  = ['setDefault' => 1, 'unsetDefault' => 0];
			$task  = $this->getTask();
			$value = ArrayHelper::getValue($data, $task, 0, 'int');
	
			if (!$value) {
				$this->setMessage(Text::_('COM_MULTISITES_CANNOT_DISABLE_DEFAULT'), 'warning');
				$this->setRedirect(
					Route::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. '&group_id=' . $this->groupId,
						false
					)
				);
	
				return;
			}
	
			// Remove zero values resulting from input filter
			$cid = array_filter($cid);
	
			if (empty($cid)) {
				$this->setMessage(Text::_('COM_WORKFLOW_NO_ITEM_SELECTED'), 'warning');
			} elseif (count($cid) > 1) {
				$this->setMessage(Text::_('COM_WORKFLOW_TOO_MANY_STAGES'), 'error');
			} else {
				// Get the model.
				$model = $this->getModel();
	
				// Make sure the item ids are integers
				$id = reset($cid);
	
				// Publish the items.
				if (!$model->setDefault($id, $value)) {
					$this->setMessage($model->getError(), 'warning');
				} else {
					$this->setMessage(Text::_('COM_MULTISITE_WEBSITE_SET_DEFAULT'));
				}
			}
	
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. '&group_id=' . $this->groupId,
					false
				)
			);
		}

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
		public function getModel($name = 'Website', $prefix = 'Administrator', $config = array('ignore_request' => true))
		{
				return parent::getModel($name, $prefix, $config);
		}
}
