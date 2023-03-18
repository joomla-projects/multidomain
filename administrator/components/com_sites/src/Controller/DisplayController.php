<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/**
 * Sites display controller.
 *
 * @since  __DEPLOY_VERSION__
 */
class DisplayController extends BaseController
{
	/**
	 * The default view.
	 *
	 * @var     string
	 * @since   __DEPLOY_VERSION__
	 */
	protected $default_view = 'Sites';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  static   This object to support chaining.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($cachable = false, $urlparams = [])
	{
		$view   = $this->input->get('view', $this->default_view, 'CMD');
		$layout = $this->input->get('layout', 'default', 'CMD');

		// HERE BE DRAGONS, if we use custom id keys like idReport this key is wrong, we need a mapping for MVC keys
		$id     = $this->input->get('id', null, 'INT');

		$view   = strtolower($view);
		$layout = strtolower($layout);

		$editViews = [
			'site' => 'sites',
		];

		// Check for edit form.
		if (array_key_exists($view, $editViews) && $layout == 'edit' && !$this->checkEditId('com_sites.edit.' . $view, $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (empty($this->app->getMessageQueue()))
			{
					$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_sites&view=' . $editViews[$view], false));

			return $this;
		}

		return parent::display();
	}
}
