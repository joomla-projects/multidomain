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
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;

/**
 * Group Model
 *
 * @since  __DEPLOY_VERSION__
 */
class GroupModel extends AdminModel
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

				return $this->getCurrentUser()->authorise('core.delete', 'com_multisites.group.' . (int) $record->id);
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
				// Check for existing Group.
				if (!empty($record->id))
				{
					return $this->getCurrentUser()->authorise('core.edit.state', 'com_multisites.group.' . (int) $record->id);
				}

				// Default to component settings if Group not known.
				return parent::canEditState($record);
		}

		/**
		 * Method to get a single record.
		 *
		 * @param   integer  $pk  The id of the primary key.
		 *
		 * @return  CMSObject|boolean  Object on success, false on failure.
		 *
		 * @since   __DEPLOY _VERSION__
		 */
		public function getItem($pk = null)
		{
				$item = parent::getItem($pk);

				// Convert the extensionsassigned field to an array.
				$registry     = new \Joomla\Registry\Registry($item->extensionsassigned);
				$item->extensionsassigned = ArrayHelper::toInteger($registry->toArray());

				return $item;
		}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
     * @since __DEPLOY_VERSION__
	 */
		public function save($data)
		{
			$data['extensionsassigned'] = empty($data['extensionsassigned']) ? [] : $data['extensionsassigned'];

			return parent::save($data);
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
				$form = $this->loadForm('com_multisites.group', 'group', array('control' => 'jform', 'load_data' => $loadData));

				if (empty($form))
				{
						return false;
				}

				// Object uses for checking edit state permission of group
				$record = new \stdClass;

				// Get ID from input
				$idFromInput = (int) $app->input->get('id', 0, 'INT');

				// On edit, we get ID from the state, but on save, we use data from input
				$id = (int) $this->getState('group.id', $idFromInput);

				$record->id = $id;

				// Modify the form based on Edit State access controls.
				if (!$this->canEditState($record))
				{
						$form->setFieldAttribute('state', 'disabled', 'true');

						// Disable fields while saving.
						// The controller has already verified this is a group you can edit.
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
				$data = $app->getUserState('com_multisites.edit.group.data', array());

				if (empty($data))
				{
						$data = $this->getItem();
				}

				$this->preprocessData('com_multisites.group', $data);

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

		/**
		 * Method to get the list of unprotected extensions
		 *
		 * @return  mixed  The list grouped by extension types.
		 *
		 * @since __DEPLOY_VERSION__
		 */
		public function getExtensions()
		{
			$db = $this->getDatabase();
			$query = $db->getQuery(true);

			$extensions = ["plugin" => [],"component" => [],"module" => []];

			$query->select($db->quoteName([
				'e.extension_id',
				'e.name',
				'e.type',
				'e.enabled'
			]))
				->from($db->quoteName('#__extensions', 'e'))
				->where($db->quoteName('e.protected') . ' = 0')
				->whereIn('type', array_keys($extensions), ParameterType::STRING);

			$rows = $db->setQuery($query)->loadObjectList();

			foreach ($rows as $row)
			{
				$extensions[$row->type][] = $row;
			}

			return $extensions;
		}
}
