<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Asset;

/**
 * Group table
 *
 * @since  __DEPLOY_VERSION__
 */
class GroupTable extends Table
{
		/**
		 * An array of key names to be json encoded in the bind function
		 *
		 * @var    array
		 * @since  __DEPLOY_VERSION__
		 */
		protected $_jsonEncode = [
			'params'
		];

		/**
		 * Indicates that columns fully support the NULL value in the database
		 *
		 * @var    boolean
		 * @since __DEPLOY_VERSION__
		 */
		protected $_supportNullValue = true;

		/**
		 * Constructor
		 *
		 * @param DatabaseInterface $db Database driver object.
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		public function __construct(DatabaseInterface $db)
		{
				parent::__construct('#__multisites_groups', 'id', $db);

				$this->setColumnAlias('title', 'name');
				$this->setColumnAlias('published', 'state');
		}

		/**
		 * Stores a Group.
		 *
		 * @param boolean $updateNulls True to update fields even if they are null.
		 *
		 * @return  boolean  True on success, false on failure.
		 *
		 * @since __DEPLOY_VERSION__
		 */
		public function store($updateNulls = true)
		{
				$date   = Factory::getDate()->toSql();
				$userId = Factory::getApplication()->getIdentity()->id;

				// Set created date if not set.
				if (!(int)$this->created) {
						$this->created = $date;
				}

				if ($this->{$this->_tbl_key}) {
						// Existing item
						$this->modified_by = $userId;
						$this->modified    = $date;
				} else {
						// Field created_by field can be set by the user, so we don't touch it if it's set.
						if (empty($this->created_by)) {
								$this->created_by = $userId;
						}

						if (!(int)$this->modified) {
								$this->modified = $date;
						}

						if (empty($this->modified_by)) {
								$this->modified_by = $userId;
						}
				}

				return parent::store($updateNulls);
		}

		/**
		 * Overloaded check function
		 *
		 * @return  boolean  True on success, false on failure
		 *
		 * @see     \JTable::check
		 * @since   __DEPLOY_VERSION__
		 */
		public function check()
		{
			try {
					parent::check();
			} catch (\Exception $e) {
					$this->setError($e->getMessage());

					return false;
			}

			// Check for valid title
			if (trim($this->title) == '') {
					$this->setError(Text::_('COM_MULTISITES_WARNING_PROVIDE_VALID_TITLE'));

					return false;
			}

			if (!$this->modified) {
					$this->modified = $this->created;
			}

			if (empty($this->modified_by)) {
					$this->modified_by = $this->created_by;
			}

			if (empty($this->params)) {
				$this->params = '{}';
			}

			// The default website has to be published
			if (!empty($this->default)) {
				if ((int) $this->state !== 1) {
					$this->setError(Text::_('COM_MULTISITES_WEBSITE_MUST_PUBLISHED'));
	
					return false;
				}
			} else {
				$db    = $this->getDbo();
				$query = $db->getQuery(true);
	
				$query
					->select($db->quoteName('id'))
					->from($db->quoteName('#__multisites_groups'))
					->where(
						[
							$db->quoteName('default') . ' = 1',
						]
					);

				$id = $db->setQuery($query)->loadResult();

				// If there is no default stage => set the current to default to recover
				if (empty($id)) {
					$this->default = '1';
				} elseif ($id === $this->id) {
					// This stage is the default, but someone has tried to disable it => not allowed
					$this->setError(Text::_('COM_MULTISITES_GROUP_CANNOT_DISABLE_DEFAULT'));
	
					return false;
				}
			}

			return true;
		}

		/**
		 * Method to return the title to use for the asset table.
		 *
		 * @return  string
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		protected function _getAssetTitle()
		{
				return $this->title;
		}

		/**
		 * Method to get the parent asset id for the record
		 *
		 * @param Table   $table A Table object (optional) for the asset parent
		 * @param integer $id    The id (optional) of the content.
		 *
		 * @return  integer
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		protected function _getAssetParentId(Table $table = null, $id = null)
		{
				$asset = new Asset($this->_db);
				$asset->loadByName('com_multisites');

				if ((int)$asset->id) {
						$assetId = (int)$asset->id;
				}

				// Return the asset id.
				if ($assetId) {
						return $assetId;
				} else {
						return parent::_getAssetParentId($table, $id);
				}
		}
}
