<?php

/**
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

namespace Firefighters\Component\Firefighters\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;

/**
 * Firefighters helper.
 */
class FirefightersHelper
{
    /**
     * Gets the list of the actions the current user may perform.
     *
     * @return  Actions
     */
    public static function getActions()
    {
        $user   = Factory::getApplication()->getIdentity();
        $result = new Actions();

        foreach (['core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'] as $action) {
            $result->set($action, $user->authorise($action, 'com_firefighters'));
        }

        return $result;
    }

    /**
     * Returns the installed component version from the manifest cache.
     *
     * @return  string
     */
    public static function getVersion()
    {
        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true)
            ->select($db->quoteName('manifest_cache'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_firefighters'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));
        $db->setQuery($query);
        $manifest = json_decode((string) $db->loadResult(), true);

        return $manifest['version'] ?? '';
    }

    /**
     * Checks the optional validation key against the server of the extension author.
     *
     * A request is only sent if a validation key is configured in the component
     * options. The result is stored in the component parameter "ftm", which switches
     * off the copyright notes in the frontend.
     *
     * @return  string  '12' if the installation is validated, '0' otherwise
     */
    public static function getValidation()
    {
        $params  = ComponentHelper::getParams('com_firefighters');
        $key     = trim((string) $params->get('validation_key', ''));
        $version = self::getVersion();
        $code    = '0';

        if (str_contains($version, 'Premium')) {
            $code = '12';
        } elseif ($key !== '' && $key !== '0') {
            try {
                $query = http_build_query([
                    'validation' => $key,
                    'domain'     => Uri::getInstance()->getHost(),
                    'version'    => (new Version())->getShortVersion(),
                    'ftmversion' => $version,
                ]);
                $response = HttpFactory::getHttp()->get('https://einsatzkomponente.joomla100.com/gateway/ftm_validation.php?' . $query, [], 5);
                $lines    = preg_split('/\R/', (string) $response->body);

                if ((int) ($lines[1] ?? 0) === 12) {
                    $code = '12';
                }
            } catch (\Throwable $e) {
                // server not reachable: keep the component usable
            }
        }

        $ftm = $code === '12' ? '1' : '0';

        if ((string) $params->get('ftm', '0') !== $ftm) {
            $params->set('ftm', $ftm);

            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('params') . ' = ' . $db->quote((string) $params))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_firefighters'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('component'));
            $db->setQuery($query);

            try {
                $db->execute();
            } catch (\RuntimeException $e) {
                // not critical
            }
        }

        return $code;
    }
}
