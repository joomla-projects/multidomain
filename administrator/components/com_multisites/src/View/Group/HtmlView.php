<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\View\Group;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\FormView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * Group view class for the Multisites package.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends FormView
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
						$this->option = 'com_multisites';
				}

				$config['toolbar_icon'] = 'user-cog';

				parent::__construct($config);

				$this->canDo = ContentHelper::getActions('com_multisites');
		}

		protected function initializeView()
		{
			$this->extensions = $this->get('Extensions');

			parent::initializeView();
		}

    /**
		 * Add the page title and toolbar.
		 *
		 * @return  void
		 */
		protected function addToolbar()
		{
				// We don't need toolbar in the modal window.
				if ($this->getLayout() === 'modal') {
						return;
				}

				parent::addToolbar();

				ToolbarHelper::inlinehelp();
		}
}
