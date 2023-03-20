<?php
/**
 * @package     Multisites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Component\Multisites\Administrator\Extension\MultisitesComponent;


return new class implements ServiceProviderInterface {
		/**
		 * Registers the service provider with a DI container.
		 *
		 * @param Container $container The DI container.
		 *
		 * @return  void
		 *
		 * @since   __DEPLOY_VERSION__
		 */
		public function register(Container $container)
		{
				$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Multisites'));
				$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Multisites'));

				$container->set(
					ComponentInterface::class,
					function (Container $container) {
							$component = new MultisitesComponent($container->get(ComponentDispatcherFactoryInterface::class));
							$component->setMVCFactory($container->get(MVCFactoryInterface::class));

							return $component;
					}
				);
		}
};
