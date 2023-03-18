<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Joomla\Component\Sites\Administrator\Extension;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Language\Text;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_sites
 *
 * @since __DEPLOY_VERSION__
 */
class SitesComponent extends MVCComponent implements
    BootableExtensionInterface,
    RouterServiceInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;

    private ContainerInterface $container;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * If required, some initial set up can be done from services of the container, eg.
     * registering HTML services.
     *
     * @param   ContainerInterface  $container  The container
     *
     * @return  void
     *
     * @since __DEPLOY_VERSION__
     */
    public function boot(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns valid contexts
     *
     * @return  string[]
     *
     * @since __DEPLOY_VERSION__
     */
    public function getContexts(): array
    {
        Factory::getApplication()->getLanguage()->load('com_sites', JPATH_ADMINISTRATOR);

        $contexts = array(
            'com_sites.sites' => Text::_('COM_SITES_SITES'),
        );

        return $contexts;
    }


    /**
     * Returns the model name, based on the context
     *
     * @param   string  $context  The context of the workflow
     *
     * @return string
     */
    public function getModelName($context): string
    {
        $parts = explode('.', $context);

        if (empty($parts[1])) {
            return '';
        }

        return ucfirst($parts[1]);
    }
}
