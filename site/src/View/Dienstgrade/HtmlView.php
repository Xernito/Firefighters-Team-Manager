<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Site\View\Dienstgrade;

defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Version;
/**
 * View class for a list of Firefighters.
 */
class HtmlView extends BaseHtmlView {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    protected $version;
	
    /**
     * Display the view
     */
    public function display($tpl = null) {
        $app = Factory::getApplication();

        $this->state = $this->getModel()->getState();
        $this->items = $this->getModel()->getItems();
        $this->pagination = $this->getModel()->getPagination();
        $this->params = $app->getParams('com_firefighters');
      
		
		$document = Factory::getApplication()->getDocument();

		// Bootstrap laden
		
		if ($this->params->get('display_dienstgrade_bootstrap','0') == '1') :
		endif;
		if ($this->params->get('display_dienstgrade_bootstrap','0') == '2') :
		endif;

		$document->getWebAssetManager()->useStyle('com_firefighters.firefighters');
		if (trim((string) $this->params->get('dienstgrade_css','')) !== '') {
			$document->getWebAssetManager()->addInlineStyle($this->params->get('dienstgrade_css',''));
		} 

		//Komponentenversion aus Datenbank lesen
		$this->version 		= FirefightersHelper::getVersion (); 

        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = Factory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', Text::_('COM_FIREFIGHTERS_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }
        $this->getDocument()->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->getDocument()->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->getDocument()->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->getDocument()->setMetadata('robots', $this->params->get('robots'));
        }
    }

}