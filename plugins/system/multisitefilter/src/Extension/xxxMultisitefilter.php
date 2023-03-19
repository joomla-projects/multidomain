<?php
/**
 * @package     Multisitefilter
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\Multisitefilter\Extension;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use AcyMailing\Classes\UserClass;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\User\User;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

/**
 * Multisitefilter Plugin.
 */
final class Multisitefilter extends CMSPlugin implements SubscriberInterface
{

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     */
    public static function getSubscribedEvents(): array
    {
		    return [
			    'onContentPrepare' => 'onContentPrepare',
		    ];
    }

		/**
		 * @param   Event    $event  The Event
		 *
		 * @return  void
		 */
		public function onContentPrepare($event):  void
		{
				$context = $event->getArgument(0);
				$item = $event->getArgument(1);

				if (isset($item->jcFieldsByName)) {
						return;
				}

				$item->jcFieldsByName = [];

				if (
					!isset($item->jcfields)
					&& $context === 'com_content.article'
					&& !empty($item->catid)
				) {
						$item->jcfields = FieldsHelper::getFields('com_content.categories', $item->catid, false);
				}

				foreach ($item->jcfields as $field) {
						$item->jcFieldsByName[$field->name] = $field;
				}
		}
}
