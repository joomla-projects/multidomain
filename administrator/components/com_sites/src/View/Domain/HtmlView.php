<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\View\Domain;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\FormView;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * Domain view class for the Sites package.
 *
 * @since  0.5.0
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
						$this->option = 'com_sites';
				}

				$config['toolbar_icon'] = 'user-cog';

				parent::__construct($config);

				$this->canDo = ContentHelper::getActions('com_sites');
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
