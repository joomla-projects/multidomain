<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\View\Group;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\FormView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * Group view class for the Multisites package.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends FormView
{
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
                $this->option = 'com_multisites';
        }

        $config['toolbar_icon'] = 'user-cog';

        parent::__construct($config);

        $this->canDo = ContentHelper::getActions('com_multisites');
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolbar()
    {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $isNew   = ($this->item->id == 0);
        $canDo   = $this->canDo;
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title($isNew ? Text::_('COM_MULTISITES_MANAGER_GROUP_NEW') : Text::_('COM_MULTISITES_MANAGER_GROUP_EDIT'), 'bookmark groups');

        if ($canDo->get('core.edit') || $canDo->get('core.create')) {
            $toolbar->apply('user.apply');
        }

        $saveGroup = $toolbar->dropdownButton('save-group');

        $saveGroup->configure(
            function (Toolbar $childBar) use ($canDo) {
                if ($canDo->get('core.edit') || $canDo->get('core.create')) {
                    $childBar->save('user.save');
                }

                if ($canDo->get('core.create') && $canDo->get('core.manage')) {
                    $childBar->save2new('user.save2new');
                }
            }
        );

        if (empty($this->item->id)) {
            $toolbar->cancel('group.cancel', 'JTOOLBAR_CANCEL');
        } else {
            $toolbar->cancel('group.cancel');
        }

        $toolbar->divider();
        ToolbarHelper::inlinehelp();
        $toolbar->help('Multisites_Groups:_Edit');
    }
}
