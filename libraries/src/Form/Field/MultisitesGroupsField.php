<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Form\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  5.0.0
 */
class MultisitesGroupsField extends ListField
{
    /**
     * The form field type.
     *
     * @var     string
     * @since  5.0.0
     */
    protected $type = 'MultisitesGroups';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     *
     * @since   5.0.0
     */
    protected function getOptions()
    {
        $app = Factory::getApplication();
        $options = [];
        $options[] = HTMLHelper::_('select.option', '0', Text::_('JALL'));

        $component = $app->bootComponent('com_multisites');
        $model = $component->getMVCFactory()->createModel('Groups', 'Administrator', ['ignore_request' => true]);

        // @todo: add a filter to the model to get the correct groups

        foreach ($model->getItems() as $group) {
            $options[] = HTMLHelper::_('select.option', $group->id, $group->title);
        }

        return $options;
    }
}
