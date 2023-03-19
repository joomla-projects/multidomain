<?php
/**
 * @package     Multisitefilter
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\Multisitefilter\Extension\Multisitefilter;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function register(Container $container)
	{

		$container->set(
			PluginInterface::class,
			function (Container $container)
			{
  			$subject = $container->get(DispatcherInterface::class);
	  		$config  = (array)PluginHelper::getPlugin('system', 'multisitefilter');

				$plugin = new Multisitefilter($subject, $config);

				return $plugin;
			}
		);
	}
};
