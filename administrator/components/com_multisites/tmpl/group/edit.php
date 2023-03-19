<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Multisites\Administrator\View\Group\HtmlView $this */

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
$this->hidden_fields = ['note'];
?>
<form action="<?php echo Route::_('index.php?option=com_multisites&layout=' . $layout . $tmpl . '&id=' . $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>

		<?php
		echo HTMLHelper::_('uitab.addTab', 'general', 'general', Text::_($fieldsets['general']->label ??'JGLOBAL_FIELDSET_CONTENT'));
		?>
		<div class="row">
			<div class="col-lg-7">
				<fieldset class="form-vertical">
						<?php echo $this->form->renderField('note'); ?>
				</fieldset>
			</div>
			<div class="col-lg-3 ms-lg-auto">
				<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php
        echo HTMLHelper::_('uitab.addTab', 'extensionassignment', 'extensionassignment', Text::_('COM_MULTISITES_FIELDSET_EXTENSIONASSIGNMENT'));
        ?>
        <fieldset id="fieldset-extensionassignment" class="options-form">
            <legend><?php echo Text::_('COM_MULTISITES_FIELDSET_EXTENSIONASSIGNMENT'); ?></legend>
            <div>
                <?php echo $this->loadTemplate('extensionassignment'); ?>
            </div>
        </fieldset>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="return" value="<?php echo $input->getBase64('return'); ?>">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
