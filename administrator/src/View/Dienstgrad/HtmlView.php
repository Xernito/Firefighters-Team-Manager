<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\View\Dienstgrad;

defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Version;
/**
 * View to edit
 */
class HtmlView extends BaseHtmlView {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->getModel()->getState();
        $this->item = $this->getModel()->getItem();
        $this->form = $this->getModel()->getForm();

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $user = Factory::getApplication()->getIdentity();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo = FirefightersHelper::getActions();

        ToolbarHelper::title(Text::_('COM_FIREFIGHTERS_TITLE_DIENSTGRAD'), 'dienstgrad');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

            ToolbarHelper::apply('dienstgrad.apply', 'JTOOLBAR_APPLY');
            ToolbarHelper::save('dienstgrad.save', 'JTOOLBAR_SAVE');
        }
        if (!$checkedOut && ($canDo->get('core.create'))) {
            ToolbarHelper::custom('dienstgrad.save2new', 'save-new', '', 'JTOOLBAR_SAVE_AND_NEW', false);
        }
        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            ToolbarHelper::custom('dienstgrad.save2copy', 'save-copy', '', 'JTOOLBAR_SAVE_AS_COPY', false);
        }
        if (empty($this->item->id)) {
            ToolbarHelper::cancel('dienstgrad.cancel', 'JTOOLBAR_CANCEL');
        } else {
            ToolbarHelper::cancel('dienstgrad.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
