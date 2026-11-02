<?php

/**
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

namespace Firefighters\Component\Firefighters\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;

/**
 * Component class for com_firefighters.
 */
class FirefightersComponent extends MVCComponent implements RouterServiceInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;
}
