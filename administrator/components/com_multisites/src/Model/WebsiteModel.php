<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;

/**
 * Website Model
 *
 * @since  __DEPLOY_VERSION__
 */
class WebsiteModel extends AdminModel
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $text_prefix = 'COM_MULTISITES';

		/**
		 * Method to test whether a record can be deleted.
		 *
		 * @param   object  $record  A record object.
		 *
		 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
		 *
		 * @since __DEPLOY_VERSION__
		 */
		protected function canDelete($record)
		{
				if (empty($record->id) || ($record->state != -2))
				{
						return false;
				}

				return $this->getCurrentUser()->authorise('core.delete', 'com_multisites.website.' . (int) $record->id);
		}

		/**
		 * Method to test whether a record can have its state edited.
		 *
		 * @param   object  $record  A record object.
		 *
		 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
		 *
		 * @since __DEPLOY_VERSION__
		 */
		protected function canEditState($record)
		{
				// Check for existing Website.
				if (!empty($record->id))
				{
					return $this->getCurrentUser()->authorise('core.edit.state', 'com_multisites.website.' . (int) $record->id);
				}

				// Default to component settings if Website not known.
				return parent::canEditState($record);
		}

		/**
		 * Method to get a single record.
		 *
		 * @param   integer  $pk  The id of the primary key.
		 *
		 * @return  CMSObject|boolean  Object on success, false on failure.
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		public function getItem($pk = null)
		{
				$item = parent::getItem($pk);

				return $item;
		}

		/**
		 * Method to get the record form.
		 *
		 * @param   array    $data      Data for the form.
		 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
		 *
		 * @return  Form|boolean  A Form object on success, false on failure
		 *
		 * @since __DEPLOY_VERSION__
		 */
		public function getForm($data = array(), $loadData = true)
		{
				$app  = Factory::getApplication();

				// Get the form.
				$form = $this->loadForm('com_multisites.website', 'website', array('control' => 'jform', 'load_data' => $loadData));

				if (empty($form))
				{
						return false;
				}

				// Object uses for checking edit state permission of Website
				$record = new \stdClass;

				// Get ID from input
				$idFromInput = (int) $app->input->get('id', 0, 'INT');

				// On edit, we get ID from the state, but on save, we use data from input
				$id = (int) $this->getState('website.id', $idFromInput);

				$record->id = $id;

				// Modify the form based on Edit State access controls.
				if (!$this->canEditState($record))
				{
						$form->setFieldAttribute('state', 'disabled', 'true');

						// Disable fields while saving.
						// The controller has already verified this is a Website you can edit.
						$form->setFieldAttribute('state', 'filter', 'unset');
				}

				// Don't allow to change the created_by user if not allowed to access com_users.
				if (!$this->getCurrentUser()->authorise('core.manage', 'com_users'))
				{
						$form->setFieldAttribute('created_by', 'filter', 'unset');
				}

				return $form;
		}

		/**
		 * Method to change the default state of one item.
		 *
		 * @param   array    $pk     A list of the primary keys to change.
		 * @param   integer  $value  The value of the home state.
		 *
		 * @return  boolean  True on success.
		 *
		 * @since  __DEPLOY_VERSION__
		 */
		public function setDefault($pk, $value = 1)
		{
			$table = $this->getTable();
	
			if ($table->load($pk)) {
				if ($table->state !== 1) {
					$this->setError(Text::_('COM_MULTISITE_DEFAULT_ITEM_HAS_TO_BE_PUBLISHED'));
	
					return false;
				}
			}
	
			if (empty($table->id) || !$this->canEditState($table)) {
				Log::add(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), Log::WARNING, 'jerror');
	
				return false;
			}
	
			$date = Factory::getDate()->toSql();
	
			if ($value) {
				// Unset other default item
				if (
					$table->load(
						[
						'default' => '1',
						'group_id' => (int) $table->group_id
						]
					)
				) {
					$table->default  = 0;
					$table->modified = $date;
					$table->store();
				}
			}
	
			if ($table->load($pk)) {
				$table->modified = $date;
				$table->default  = $value;
				$table->store();
			}
	
			// Clean the cache
			$this->cleanCache();
	
			return true;
		}

		/**
		 * Method to save the form data.
		 *
		 * @param   array  $data  The form data.
		 *
		 * @return   boolean  True on success.
		 *
		 * @since  __DEPLOY_VERSION__
		 */
		public function save($data)
		{
			$table      = $this->getTable();
			$context    = $this->option . '.' . $this->name;
			$app        = Factory::getApplication();
			$user       = $app->getIdentity();
			$input      = $app->getInput();
			$groupId = $app->getUserStateFromRequest('com_multisites.websites.filter.group_id', 'group_id', 0, 'int');

			if (empty($data['group_id'])) {
				$data['group_id'] = $groupId;
			}
	
			$group = $this->getTable('Group');
	
			$group->load($data['group_id']);

			// Make sure we don't change the group ID for existing items
			$key = $table->getKeyName();
			$pk  = (isset($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
	
			if ($pk > 0) {
				$table->load($pk);
	
				if ((int) $table->group_id) {
					$data['group_id'] = (int) $table->group_id;
				}
			}
	
			if ($input->get('task') == 'save2copy') {
				$origTable = clone $this->getTable();
	
				// Alter the title for save as copy
				if ($origTable->load(['title' => $data['title']])) {
					list($title)   = $this->generateNewTitle(0, '', $data['title']);
					$data['title'] = $title;
				}
	
				$data['published'] = 0;
				$data['default']   = 0;
			}
	
			return parent::save($data);
		}

		/**
		 * Method to get the data that should be injected in the form.
		 *
		 * @return  mixed  The data for the form.
		 *
		 * @since __DEPLOY_VERSION__
		 */
		protected function loadFormData()
		{
				// Check the session for previously entered form data.
				$app = Factory::getApplication();
				$data = $app->getUserState('com_multisites.edit.website.data', array());

				if (empty($data))
				{
						$data = $this->getItem();
				}

				// If there are params fieldsets in the form it will fail with a registry object
				if (isset($data->params) && $data->params instanceof Registry)
				{
						$data->params = $data->params->toArray();
				}

				$this->preprocessData('com_multisites.website', $data);

				return $data;
		}



		/**
		 * Method to validate the form data.
		 *
		 * @param   Form    $form   The form to validate against.
		 * @param   array   $data   The data to validate.
		 * @param   string  $group  The name of the field group to validate.
		 *
		 * @return  array|boolean  Array of filtered data if valid, false otherwise.
		 *
		 * @see     \Joomla\CMS\Form\FormRule
		 * @see     JFilterInput
		 * @since __DEPLOY_VERSION__
		 */
		public function validate($form, $data, $group = null)
		{
				if (!Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_multisites'))
				{
						if (isset($data['rules']))
						{
								unset($data['rules']);
						}
				}

				return parent::validate($form, $data, $group);
		}
}
