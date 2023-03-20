<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Multisites\Administrator\View\Websites\HtmlView $this */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_MULTISITES_WEBSITES',
    'formURL'    => 'index.php?option=com_multisites&view=websites',
    'icon'       => 'icon-bookmark order',
];

if ($this->getCurrentUser()->authorise('core.create', 'com_multisites')) {
    $displayData['createURL'] = 'index.php?option=com_multisites&task=website.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
