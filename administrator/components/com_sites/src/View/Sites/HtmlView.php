<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\View\Sites;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\ListView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * Site view class for the Sites package.
 *
 * @since  0.5.0
 */
class HtmlView extends ListView
{
		/**
		 * The total number of records
		 *
		 * @var integer
		 */
		public $total;

		/**
		 * Is this view an Empty State
		 *
		 * @var  boolean
		 */
		private $isEmptyState = false;

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

		/**
		 * Prepare view data
		 *
		 * @return  void
		 */
		protected function initializeView()
		{
				parent::initializeView();

				$this->total = $this->get('Total');

				if (!\count($this->items))
				{
						$this->isEmptyState = $this->get('IsEmptyState');

						if ($this->isEmptyState)
						{
								$this->setLayout('emptystate');
						}
				}
		}
}
