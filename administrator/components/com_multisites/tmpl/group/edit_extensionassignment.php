<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$this->document->getWebAssetManager()
    ->useScript('joomla.treeselectmenu')
    ->useScript('com_multisites.admin-group-edit-assignment');

?>
<div class="control-group">
    <?php echo $this->form->renderField('extensionassignment'); ?>
</div>
<div id="extensionselect-group" class="control-group">
    <label id="jform_extensionselect-lbl" class="control-label" for="jform_extensionselect"><?php echo Text::_('COM_MULTISITES_ASSIGN_EXTENSIONS'); ?></label>
    <div id="jform_extensionselect" class="controls">
        <?php if (!empty($this->extensions)) : ?>
            <div class="card-header">
                <section class="d-flex align-items-center flex-wrap w-100" aria-label="<?php echo Text::_('COM_MULTISITES_GLOBAL'); ?>">
                    <div class="d-flex align-items-center flex-fill mb-1" role="group" aria-label="<?php echo Text::_('COM_MULTISITES_ASSIGN_EXTENSIONS'); ?>"><?php echo Text::_('COM_MULTISITES_ASSIGN_EXTENSIONS'); ?>
                        <button id="treeCheckAll" class="btn btn-secondary btn-sm mx-1" type="button">
                            <?php echo Text::_('JALL'); ?>
                        </button>
                        <button id="treeUncheckAll" class="btn btn-secondary btn-sm mx-1" type="button">
                            <?php echo Text::_('JNONE'); ?>
                        </button>
                    </div>
                    <div class="d-flex align-items-center mb-1 flex-fill" role="group" aria-label="<?php echo Text::_('COM_MULTISITES_ASSIGN_TREE_EXPAND'); ?>"><?php echo Text::_('COM_MULTISITES_ASSIGN_TREE_EXPAND'); ?>
                        <button id="treeExpandAll" class="btn btn-secondary btn-sm mx-1" type="button">
                            <?php echo Text::_('JALL'); ?>
                        </button>
                        <button id="treeCollapseAll" class="btn btn-secondary btn-sm mx-1" type="button">
                            <?php echo Text::_('JNONE'); ?>
                        </button>
                    </div>
                    <div role="search" class="flex-grow-1">
                        <label for="treeselectfilter" class="visually-hidden"><?php echo Text::_('COM_MULTISITES_SEARCH_EXTENSION'); ?></label>
                        <input type="text" id="treeselectfilter" name="treeselectfilter" class="form-control search-query" autocomplete="off" placeholder="<?php echo Text::_('JSEARCH_FILTER'); ?>">
                    </div>
                </section>
            </div>
            <div class="card-body">
                <ul class="treeselect">
                    <?php foreach ($this->extensions as $type => $extensions) : ?>
                        <li>
                            <div class="treeselect-item treeselect-header">
                                <label class="nav-header"><?php echo Text::_('COM_MULTISITES_EXTENSIONTYPE_' . strtoupper($type) . 'S'); ?></label>
                            </div>
                            <ul class="treeselect-sub">
                                <?php foreach ($extensions as $extension): ?>
                                    <li>
                                    <div class="treeselect-item">
                                        <?php
                                        $uselessMenuItem = in_array($link->type, ['separator', 'heading', 'alias', 'url']);
                                        $id = 'jform_extensionselect';

                                        $selected = 0;
                                        if ($this->item->assignment == 0) {
                                            $selected = 1;
                                        } elseif ($this->item->assignment < 0) {
                                            $selected = in_array(-$extension->extension_id, $this->item->extensionsassigned);
                                        } elseif ($this->item->assignment > 0) {
                                            $selected = in_array($extension->extension_id, $this->item->extensionsassigned);
                                        }
                                        ?>
                                        <input type="checkbox" class="novalidate form-check-input" name="jform[extensionsassigned][]" id="<?php echo $id . $extension->extension_id; ?>" value="<?php echo (int) $extension->extension_id; ?>"<?php echo $selected ? ' checked="checked"' : ''; ?>
                                        <label for="<?php echo $id . $extension->extension_id; ?>" class="">
                                            <?php echo $extension->name; ?>
                                            <?php if ($extension->enabled == 0) : ?>
                                                <?php echo ' <span class="badge bg-secondary">' . Text::_('JUNPUBLISHED') . '</span>'; ?>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <joomla-alert id="noresultsfound" type="warning" style="display:none"><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></joomla-alert>
                <div class="hidden" id="treeselectmenu">
                    <div class="nav-hover treeselect-menu">
                        <div class="dropdown">
                            <button type="button" data-bs-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-light">
                                <span class="caret"></span>
                                <span class="visually-hidden"><?php echo Text::sprintf('JGLOBAL_TOGGLE_DROPDOWN'); ?></span>
                            </button>
                            <div class="dropdown-menu">
                                <h1 class="dropdown-header"><?php echo Text::_('COM_MODULES_SUBITEMS'); ?></h1>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item checkall" href="javascript://"><span class="icon-check-square" aria-hidden="true"></span> <?php echo Text::_('JSELECT'); ?></a>
                                <a class="dropdown-item uncheckall" href="javascript://"><span class="icon-square" aria-hidden="true"></span> <?php echo Text::_('COM_MODULES_DESELECT'); ?></a>
                                <div class="treeselect-menu-expand">
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item expandall" href="javascript://"><span class="icon-plus" aria-hidden="true"></span> <?php echo Text::_('COM_MODULES_EXPAND'); ?></a>
                                    <a class="dropdown-item collapseall" href="javascript://"><span class="icon-minus" aria-hidden="true"></span> <?php echo Text::_('COM_MODULES_COLLAPSE'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
