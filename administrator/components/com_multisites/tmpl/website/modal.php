<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Multisites\Administrator\View\Website\HtmlView $this */

defined('_JEXEC') or die;

?>
<div class="container-popup">
	<?php $this->setLayout('edit'); ?>
	<?php echo $this->loadTemplate(); ?>
</div>
