<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

/**
 * Site controller.
 *
 * @since  __DEPLOY_VERSION__
 */
class SiteController extends FormController
{
		/**
		 * The prefix to use with controller messages.
		 *
		 * @var    string
		 * @since  1.6
		 */
		protected $text_prefix = 'COM_SITES_SITE';

		/**
		 * Method override to check if you can edit an existing record.
		 *
		 * @param array  $data An array of input data.
		 * @param string $key  The name of the key for the primary key.
		 *
		 * @return  boolean
		 *
		 * @since __DEPLOY_VERSION__
		 */
		protected function allowEdit($data = array(), $key = 'id')
		{
				$recordId = (int)isset($data[$key]) ? $data[$key] : 0;
				$user     = $this->app->getIdentity();

				// Zero record (id:0), return component edit permission by calling parent controller method
				if (!$recordId) {
						return parent::allowEdit($data, $key);
				}

				// Check edit on the record asset (explicit or inherited)
				if ($user->authorise('core.edit', 'com_sites.site.' . $recordId)) {
						return true;
				}

				// Check edit own on the record asset (explicit or inherited)
				if ($user->authorise('core.edit.own', 'com_sites.site.' . $recordId)) {
						// Existing record already has an owner, get it
						$record = $this->getModel()->getItem($recordId);

						if (empty($record)) {
								return false;
						}

						// Grant if current user is owner of the record
						return $user->id == $record->created_by;
				}

				return false;
		}
}
