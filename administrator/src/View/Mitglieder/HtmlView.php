<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\View\Mitglieder;

defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
/**
 * View class for a list of Firefighters.
 */
class HtmlView extends BaseHtmlView {

    protected $items;
    protected $pagination;
    public $filterForm;
    public $activeFilters;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->getModel()->getState();
        $this->items = $this->getModel()->getItems();
        $this->pagination = $this->getModel()->getPagination();
        $this->filterForm = $this->getModel()->getFilterForm();
        $this->activeFilters = $this->getModel()->getActiveFilters();

        $this->addToolbar();

		
		
		    $user		= Factory::getApplication()->getIdentity();
		    if($this->getLayout() == 'edit') {
            $authorised = $user->authorise('core.create', 'com_firefighters');
            if ($authorised !== true) {
                throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'));
            }			
			}

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

        ToolbarHelper::title(Text::_('COM_FIREFIGHTERS_TITLE_MITGLIEDER'), 'mitglieder');

if($this->getLayout() != 'edit') {

        //Check if the form exists before showing the add/edit buttons
        if ($canDo->get('core.create') || $canDo->get('core.edit')) {

            if ($canDo->get('core.create')) {
                ToolbarHelper::addNew('mitglied.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                ToolbarHelper::editList('mitglied.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                ToolbarHelper::divider();
                ToolbarHelper::custom('mitglieder.publish', 'publish', '', 'JTOOLBAR_PUBLISH', true);
                ToolbarHelper::custom('mitglieder.unpublish', 'unpublish', '', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                ToolbarHelper::deleteList('', 'mitglieder.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                ToolbarHelper::divider();
                ToolbarHelper::archiveList('mitglieder.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                ToolbarHelper::custom('mitglieder.checkin', 'checkin', '', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                ToolbarHelper::deleteList('', 'mitglieder.delete', 'JTOOLBAR_EMPTY_TRASH');
                ToolbarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                ToolbarHelper::trash('mitglieder.trash', 'JTOOLBAR_TRASH');
                ToolbarHelper::divider();
            }
        }

}
        if ($canDo->get('core.admin')) {
            ToolbarHelper::preferences('com_firefighters');
        }
    }

}
