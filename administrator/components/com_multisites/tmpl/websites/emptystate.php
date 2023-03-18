<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

/** @var Joomla\Component\Sites\Administrator\View\Sites\HtmlView $this */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_SITES_SITES',
    'formURL'    => 'index.php?option=com_sites&view=sites',
    'icon'       => 'icon-bookmark order',
];

if ($this->getCurrentUser()->authorise('core.create', 'com_sites')) {
    $displayData['createURL'] = 'index.php?option=com_sites&task=site.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
