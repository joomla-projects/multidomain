<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;

use function substr;

/**
 * Sites Component Sites Model
 *
 * @since  0.5.0
 */
class SitesModel extends ListModel
{
		/**
		 * Constructor
		 *
		 * @param array               $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
		 * @param MVCFactoryInterface $factory The factory.
		 *
		 * @throws  \Exception
		 * @since   0.5.0
		 */
		public function __construct($config = [], MVCFactoryInterface $factory = null)
		{
				if (empty($config['filter_fields'])) {
						$config['filter_fields'] = [
							'id',
							'a.idSite',
							'state',
							'a.state',
							'ordering',
							'a.ordering',
						];
				}

				parent::__construct($config, $factory);
		}

		/**
		 * Method to auto-populate the model state.
		 *
		 * Note. Calling getState in this method will result in recursion.
		 *
		 * @param string $ordering  An optional ordering field.
		 * @param string $direction An optional direction (asc|desc).
		 *
		 * @return  void
		 *
		 * @since   0.5.0
		 */
		protected function populateState($ordering = 'a.idSite', $direction = 'asc')
		{
				// List state information.
				parent::populateState($ordering, $direction);
		}

		/**
		 * Method to get a store id based on model configuration state.
		 *
		 * This is necessary because the model is used by the component and
		 * different modules that might need different sets of data or different
		 * ordering requirements.
		 *
		 * @param string $id A prefix for the store id.
		 *
		 * @return  string  A store id.
		 *
		 * @since   0.5.0
		 */
		protected function getStoreId($id = '')
		{
				// Compile the store id.
				$id .= ':' . $this->getState('filter.search');
				$id .= ':' . serialize($this->getState('filter.access'));
				$id .= ':' . $this->getState('filter.published');
				$id .= ':' . serialize($this->getState('filter.author_id'));

				return parent::getStoreId($id);
		}

		/**
		 * Build an SQL query to load the list data.
		 *
		 * @return  \Joomla\Database\DatabaseQuery
		 *
		 * @since   0.5.0
		 */
		protected function getListQuery()
		{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				// Select the required fields from the table.
				$query
					->select(
						$db->quoteName(
							[
								'a.idSite',
								'a.title',
								'a.checked_out',
								'a.checked_out_time',
								'a.state',
								'a.created',
								'a.created_by',
								'a.modified',
								'a.ordering',
							]
						)
					)
					->from($db->quoteName('#__sites_sites', 'a'))
					->join(
						'LEFT',
						$db->quoteName('#__users', 'ua'),
						$db->quoteName('ua.id') . ' = ' . $db->quoteName('a.created_by')
					)
					->select(
						$db->quoteName(
							[
								'ua.name',
							],
							[
								'author_name',
							]
						)
					)
					->join(
						'LEFT',
						$db->quoteName('#__users', 'uc'),
						$db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out')
					)
					->select(
						$db->quoteName(
							[
								'uc.name',
							],
							[
								'editor',
							]
						)
					);

				$state = (string)$this->getState('filter.state');

				if (is_numeric($state)) {
						$state = (int)$state;
						$query->where($db->quoteName('a.state') . ' = :state')
						      ->bind(':state', $state, ParameterType::INTEGER);
				} else {
						$query->where($db->quoteName('a.state') . ' IN (0, 1)');
				}

				// Filter by search in title
				$search = $this->getState('filter.search');

				if (!empty($search)) {
						if (stripos($search, 'id:') === 0) {
								$search = (int)substr($search, 3);
								$query->where($db->quoteName('a.idSite') . ' = :search')
								      ->bind(':search', $search, ParameterType::INTEGER);
						} elseif (stripos($search, 'author:') === 0) {
								$search = '%' . substr($search, 7) . '%';

								$query->where(
									'(' . $db->quoteName('ua.name') . ' LIKE :search1 OR '
									. $db->quoteName('ua.username') . ' LIKE :search2)'
								)
								      ->bind([':search1', ':search2'], $search);
						} else {
								$search = '%' . str_replace(' ', '%', trim($search)) . '%';

								$query->where(
									$db->quoteName('a.title') . ' LIKE :search'
								)
								      ->bind([':search'], $search);
						}
				}

				// Add the list ordering clause
				$orderCol  = $this->state->get('list.ordering', 'a.idSite');
				$orderDirn = $this->state->get('list.direction', 'ASC');

				$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
				$query->order($db->escape('a.idSite') . ' ' . $db->escape($orderDirn));

				return $query;
		}
}
