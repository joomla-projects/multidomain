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

/**
 * Sites display controller.
 *
 * @since  0.5.0
 */
class DisplayController extends BaseController
{
	/**
	 * The default view.
	 *
	 * @var     string
	 * @since   0.5.0
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
	 * @since   0.5.0
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
			if (!\count($this->app->getMessageQueue()))
			{
					$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_sites&view=' . $editViews[$view], false));

			return $this;
		}

		return parent::display();
	}
}
