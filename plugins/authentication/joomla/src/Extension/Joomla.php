<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.joomla
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\Authentication\Joomla\Extension;

use Joomla\CMS\Authentication\Authentication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserHelper;
use Joomla\Database\DatabaseAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Joomla Authentication plugin
 *
 * @since  1.5
 */
final class Joomla extends CMSPlugin
{
    use DatabaseAwareTrait;

    /**
     * This method should handle any authentication and report back to the subject
     *
     * @param   array   $credentials  Array holding the user credentials
     * @param   array   $options      Array of extra options
     * @param   object  &$response    Authentication response object
     *
     * @return  void
     *
     * @since   1.5
     */
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        $response->type = 'Joomla';

        // Joomla does not like blank passwords
        if (empty($credentials['password'])) {
            $response->status        = Authentication::STATUS_FAILURE;
            $response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');

            return;
        }

        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'password']))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('username') . ' = :username')
            ->bind(':username', $credentials['username']);

        $db->setQuery($query);
        $result = $db->loadObject();

        if ($result) {
            $match = UserHelper::verifyPassword($credentials['password'], $result->password, $result->id);

            if ($match === true) {
                // Bring this in line with the rest of the system
                $user               = User::getInstance($result->id);
                $response->email    = $user->email;
                $response->fullname = $user->name;

                // Set default status response to success
                $_status       = Authentication::STATUS_SUCCESS;
                $_errorMessage = '';

                if ($this->getApplication()->isClient('administrator')) {
                    $response->language = $user->getParam('admin_language');

                    if ($user->multisitesGroupId) {
                        // User does not have access to backend, only to the frontend of a website
                        $_status       = Authentication::STATUS_FAILURE;
                        $_errorMessage = Text::_('JLIB_LOGIN_DENIED');
                    }
                } else {
                    $response->language = $user->getParam('language');

                    if ($this->getApplication()->get('offline') && !$user->authorise('core.login.offline')) {
                        // User does not have access in offline mode
                        $_status       = Authentication::STATUS_FAILURE;
                        $_errorMessage = Text::_('JLIB_LOGIN_DENIED');
                    }
                }

                $response->status        = $_status;
                $response->error_message = $_errorMessage;
            } else {
                // Invalid password
                $response->status        = Authentication::STATUS_FAILURE;
                $response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_INVALID_PASS');
            }
        } else {
            // Let's hash the entered password even if we don't have a matching user for some extra response time
            // By doing so, we mitigate side channel user enumeration attacks
            UserHelper::hashPassword($credentials['password']);

            // Invalid user
            $response->status        = Authentication::STATUS_FAILURE;
            $response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_NO_USER');
        }
    }
}
