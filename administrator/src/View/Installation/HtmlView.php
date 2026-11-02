<?php 
/**
 * @version     1.00
 * @package     com_firefighters
 * @copyright   Copyright (C) by Ralf Meyer 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\View\Installation;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
class HtmlView extends BaseHtmlView
{
  function display($tpl = null) 
  {
    $this->addToolBar();
 
    // Display the template
    parent::display($tpl);
  }
        
	protected function addToolbar()
	{
		Factory::getApplication()->getInput()->set('hidemainmenu', true);
		ToolbarHelper::title(Text::_('Installationsmanager'), 'upload');
	}
	
	
}
?>
