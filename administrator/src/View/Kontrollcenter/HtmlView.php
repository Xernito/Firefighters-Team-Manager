<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\View\Kontrollcenter;

defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Version;
/**
 * View class for a list of Firefighters.
 */
class HtmlView extends BaseHtmlView {

    protected $items;
    protected $pagination;
    protected $state;
	protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
		
        $this->state = $this->getModel()->getState();
		$this->params = ComponentHelper::getParams('com_firefighters');
        

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {

        $state = $this->getModel()->getState();
        $canDo = FirefightersHelper::getActions();

        ToolbarHelper::title(Text::_('COM_FIREFIGHTERS_TITLE_KONTROLLCENTERS'), 'kontrollcenters');

        if ($canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_firefighters');
        }

        //
    }

}
