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
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\String\StringHelper;

/**
 * Websites Component Multisites Model
 *
 * @since  __DEPLOY_VERSION__
 */
class WebsitesModel extends ListModel
{
    /**
     * Constructor
     *
     * @param array               $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
     * @param MVCFactoryInterface $factory The factory.
     *
     * @throws  \Exception
     * @since   __DEPLOY_VERSION__
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'state', 'a.state',
                'default', 'a.default',
                'ordering', 'a.ordering',
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
     * @since   __DEPLOY_VERSION__
     */
    protected function populateState($ordering = 'a.id', $direction = 'asc')
    {
        $app = Factory::getApplication();

        $groupId = $app->getUserStateFromRequest('com_multisites.websites.filter.group_id', 'group_id', 0, 'int');

        if ($groupId) {
            $table = $this->getTable('Group', 'Administrator');

            if ($table->load($groupId)) {
                $this->setState('active_group', $table->title);
            }
        }

        $this->setState('filter.group_id', $groupId);

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
     * @since   __DEPLOY_VERSION__
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . serialize($this->getState('filter.access'));
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . serialize($this->getState('filter.author_id'));
        $id .= ':' . serialize($this->getState('filter.group_id'));

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  \Joomla\Database\DatabaseQuery
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $groupId = $this->getState('filter.group_id');

        // Select the required fields from the table.
        $query
            ->select(
                $db->quoteName(
                    [
                        'a.id',
                        'a.title',
                        'a.default',
                        'a.baseurl',
                        'a.lang_code',
                        'a.language',
                        'a.checked_out',
                        'a.checked_out_time',
                        'a.state',
                        'a.created',
                        'a.created_by',
                        'a.modified',
                    ]
                )
            )
            ->from($db->quoteName('#__multisites_websites', 'a'))
            ->join(
                'INNER',
                $db->quoteName('#__multisites_groups', 'mg'),
                $db->quoteName('mg.id') . ' = ' . $db->quoteName('a.group_id')
            )
            ->select(
                $db->quoteName(
                    [
                        'mg.title',
                    ],
                    [
                        'group_name',
                    ]
                )
            )
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
            )
            ->where($db->quoteName('a.group_id') . ' = :groupId')
            ->bind(':groupId', $groupId, ParameterType::INTEGER);

        $state = (string) $this->getState('filter.state');

        if (is_numeric($state)) {
            $state = (int)$state;
            $query->where($db->quoteName('a.state') . ' = :state')
                ->bind(':state', $state, ParameterType::INTEGER);
        } else {
            $query->whereIn($db->quoteName('a.state'), [0, 1]);
        }

        $default = (int)$this->getState('filter.default');
        if($default === 1){
            $query->where($db->quoteName('a.default') . ' = 1');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $search = (int) \substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :search')
                    ->bind(':search', $search, ParameterType::INTEGER);
            } elseif (stripos($search, 'author:') === 0) {
                $search = '%' . \substr($search, 7) . '%';

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
        $orderCol  = $this->getState('list.ordering', 'a.id');
        $orderDirn = $this->getState('list.direction', 'ASC');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
        $query->order($db->escape('a.id') . ' ' . $db->escape($orderDirn));

        return $query;
    }

    /**
     * Method to change the title
     *
     * @param   integer  $categoryId  The id of the category.
     * @param   string   $alias       The alias.
     * @param   string   $title       The title.
     *
     * @return  array  Contains the modified title and alias.
     *
     * @since   __DEVELOPMENT_VERSION__
     */
    protected function generateNewTitle($categoryId, $alias, $title)
    {
        // Alter the title & alias
        $table = $this->getTable();

        while ($table->load(['title' => $title])) {
            $title = StringHelper::increment($title);
        }

        return [$title, $alias];
    }

    /**
     * Returns a group object
     *
     * @params   $groupId  null|int  The group ID to load
     *
     * @return  object  The group currently viewd
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getGroup($groupId = null)
    {
        $table = $this->getTable('Group', 'Administrator');

        $groupId = $groupId ?? (int) $this->getState('filter.group_id');

        if ($groupId > 0) {
            $table->load($groupId);
        }

        return (object) $table->getProperties();
    }
}
