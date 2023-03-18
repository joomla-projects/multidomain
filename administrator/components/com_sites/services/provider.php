<?php
/**
 * @package     Sites
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Component\Sites\Administrator\Extension\SitesComponent;


return new class implements ServiceProviderInterface {
		/**
		 * Registers the service provider with a DI container.
		 *
		 * @param Container $container The DI container.
		 *
		 * @return  void
		 *
		 * @since   0.5.0
		 */
		public function register(Container $container)
		{
				$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Sites'));
				$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Sites'));
				$container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Sites'));

				$container->set(
					ComponentInterface::class,
					function (Container $container) {
							$component = new SitesComponent($container->get(ComponentDispatcherFactoryInterface::class));
							$component->setRegistry($container->get(Registry::class));
							$component->setMVCFactory($container->get(MVCFactoryInterface::class));
							$component->setRouterFactory($container->get(RouterFactoryInterface::class));

							return $component;
					}
				);
		}
};
