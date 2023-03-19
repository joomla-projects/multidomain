<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Multisites\Administrator\View\Websites;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\ListView;
use Joomla\CMS\Toolbar\Button\DropdownButton;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;

/**
 * Websites view class for the Multisites package.
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends ListView
{

    /**
     * Constructor
     *
     * @param array $config An optional associative array of configuration settings.
     */
    public function __construct(array $config)
    {
        // Set the component name if not passed
        if (empty($config['option'])) {
            $this->option = 'com_multisites';
        }

        $config['toolbar_icon'] = 'users-cog';
        $config['supports_batch'] = false;

        parent::__construct($config);

        $this->canDo = ContentHelper::getActions('com_multisites');
    }

    /**
     * Add the websites title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        $canDo = \Joomla\Component\Content\Administrator\Helper\ContentHelper::getActions('com_multisites');
        $user = $this->getCurrentUser();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_MULTISITES_MANAGER_GROUPS'), 'copy websites');

        if ($canDo->get('core.create')) {
            $toolbar->addNew('website.add');
        }

        if ($canDo->get('core.edit.state')) {
            /** @var  DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childBar = $dropdown->getChildToolbar();

            if ($canDo->get('core.edit.state')) {
                $childBar->publish('websites.publish')->listCheck(true);

                $childBar->unpublish('websites.unpublish')->listCheck(true);

                $childBar->standardButton('featured', 'JFEATURE', 'websites.featured')
                    ->listCheck(true);

                $childBar->standardButton('unfeatured', 'JUNFEATURE', 'websites.unfeatured')
                    ->listCheck(true);

                $childBar->archive('websites.archive')->listCheck(true);

                $childBar->checkin('websites.checkin');

                if ($this->state->get('filter.published') != ContentComponent::CONDITION_TRASHED) {
                    $childBar->trash('websites.trash')->listCheck(true);
                }
            }

            // Add a batch button
            if (
                $user->authorise('core.create', 'com_multisites')
                && $user->authorise('core.edit', 'com_multisites')
                && $user->authorise('core.execute.transition', 'com_multisites')
            ) {
                $childBar->popupButton('batch', 'JTOOLBAR_BATCH')
                    ->selector('collapseModal')
                    ->listCheck(true);
            }
        }
    }
}
