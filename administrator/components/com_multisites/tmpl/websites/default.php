<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Multisites\Administrator\View\Websites\HtmlView $this */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
   ->useScript('multiselect');

$user      = $this->getCurrentUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo Route::_('index.php?option=com_multisites&view=websites'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-warning">
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table itemList" id="websiteList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_MULTISITES_TABLE_CAPTION'); ?>
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-5 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JDEFAULT', 'a.default', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_MULTISITES_HEADING_TITLE', 'a.title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_MULTISITES_HEADING_BASEURL', 'a.baseurl', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_MULTISITES_HEADING_LANG_CODE', 'a.lang_code', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_MULTISITES_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_MULTISITES_HEADING_CREATED', 'a.created', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-1 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->items as $i => $item) :
                                $canEdit          = $user->authorise('core.edit',       'com_multisites.website.' . $item->id);
                                $canCheckin       = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || is_null($item->checked_out);
                                $canEditOwn       = $user->authorise('core.edit.own',   'com_multisites.website.' . $item->id) && $item->created_by == $userId;
                                $canChange        = $user->authorise('core.edit.state', 'com_multisites.website.' . $item->id) && $canCheckin;
                            ?>
                            <tr class="row<?php echo $i % 2; ?>" data-draggable-group="0">
                                <td class="text-center">
                                    <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'sites.', $canChange, 'cb'); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php echo HTMLHelper::_('jgrid.isdefault', $item->default, $i, 'websites.', $canChange); ?>
                                </td>
                                <th scope="row">
                                    <div class="break-word">
                                        <?php if ($item->checked_out) : ?>
                                            <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'sites.', $canCheckin); ?>
                                        <?php endif; ?>
                                        <?php if ($canEdit || $canEditOwn) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_multisites&task=website.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
                                                <?php echo $this->escape($item->title); ?>
                                            </a>
                                        <?php else : ?>
                                            <span><?php echo $this->escape($item->title); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </th>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $this->escape($item->baseurl); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $this->escape($item->lang_code); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $this->escape($item->language); ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $item->created > 0 ? HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC4')) : '-'; ?>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    <?php echo $item->id; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php // load the pagination. ?>
                    <?php echo $this->pagination->getListFooter(); ?>

                <?php endif; ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <input type="hidden" name="group_id" value="<?php echo (int) $this->groupId ?>">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
