<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Multisites\Administrator\View\Website\HtmlView $this */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();

$wa->useScript('keepalive')
    ->useScript('form.validate');

$this->useCoreUI = true;

$app = Factory::getApplication();
$input = $app->input;

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

// Load formfields
$fieldsets = $this->form->getFieldsets();
$this->ignore_fieldsets = ['language', 'metadata'];
?>
<form action="<?php echo Route::_('index.php?option=com_multisites&layout=' . $layout . $tmpl . '&id=' . $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="row title-alias form-vertical mb-3">
        <div class="col-12 col-md-6">
			<?php echo $this->form->renderField('title'); ?>
        </div>
        <div class="col-12 col-md-6">
	        <?php echo $this->form->renderField('baseurl'); ?>
        </div>
    </div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_MULTISITES_FIELDSET_LANGUAGE')); ?>
        <div class="row">
            <div class="col-lg-7">
                <fieldset>
                    <?php echo $this->form->renderFieldset('language'); ?>
                </fieldset>
            </div>
            <div class="col-lg-3 ms-lg-auto">
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'metadata',  Text::_('JGLOBAL_FIELDSET_METADATA_OPTIONS')); ?>
        <div class="row">
            <div class="col-lg-12">
                <fieldset>
				    <?php echo $this->form->renderFieldset('metadata'); ?>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

        <input type="hidden" name="task" value="">
        <input type="hidden" name="return" value="<?php echo $input->getBase64('return'); ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
