<?php
/**
 * @package     Multisitefilter
 *
 * @copyright   ITronic Harald Leithner
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Exception\FilesystemException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

return new class () implements ServiceProviderInterface {
		public function register(Container $container)
		{
				$container->set(
					InstallerScriptInterface::class,
					new class (
						$container->get(AdministratorApplication::class),
						$container->get(DatabaseInterface::class)
					) implements InstallerScriptInterface {
							private AdministratorApplication $app;
							private DatabaseInterface $db;

							public function __construct(AdministratorApplication $app, DatabaseInterface $db)
							{
									$this->app = $app;
									$this->db  = $db;
							}

							public function install(InstallerAdapter $parent): bool
							{
									$this->app->enqueueMessage('Installed by container!!');

									return true;
							}

							public function update(InstallerAdapter $parent): bool
							{
									$this->app->enqueueMessage('Update by container!!');

									return true;
							}

							public function uninstall(InstallerAdapter $parent): bool
							{
									return true;
							}

							public function preflight(string $type, InstallerAdapter $parent): bool
							{
									return true;
							}

							public function postflight(string $type, InstallerAdapter $parent): bool
							{
									static $hasRun;

									if ($hasRan) {
											$hasRan = true;
											return true;
									}

									$this->deleteUnexistingFiles();

									return true;
							}

							private function deleteUnexistingFiles()
							{
									$files = [];

									if (empty($files)) {
											return;
									}

									foreach ($files as $file) {
											try {
													File::delete(JPATH_ROOT . $file);
											} catch (\FilesystemException $e) {
													echo Text::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file) . '<br>';
											}
									}
							}
					}
				);
		}
};
