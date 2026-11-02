<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Version;
/**
 * Mitglied controller class.
 */
class MitgliedformController extends BaseController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = Factory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_firefighters.edit.mitglied.id');
        $editId = Factory::getApplication()->getInput()->getInt('id');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_firefighters.edit.mitglied.id', $editId);

        // Get the model.
        $model = $this->getModel('Mitgliedform');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(Route::_('index.php?option=com_firefighters&view=mitgliedform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        $this->checkToken();

        // Initialise variables.
        $app = Factory::getApplication();
        $model = $this->getModel('Mitgliedform');

        // Get the user data.
        $data = Factory::getApplication()->getInput()->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            throw new \RuntimeException($model->getError(), 500);
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof \Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->getInput();
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_firefighters.edit.mitglied.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_firefighters.edit.mitglied.id');
            $this->setRedirect(Route::_('index.php?option=com_firefighters&view=mitgliedform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_firefighters.edit.mitglied.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_firefighters.edit.mitglied.id');
            $this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(Route::_('index.php?option=com_firefighters&view=mitgliedform&layout=edit&id=' . $id, false));
            return false;
        }

        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_firefighters.edit.mitglied.id', null);

        // Redirect to the list screen.
        $this->setMessage(Text::_('COM_FIREFIGHTERS_ITEM_SAVED_SUCCESSFULLY'));
        $menu = Factory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_firefighters&view=mitglieder' : $item->link);
        $this->setRedirect(Route::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_firefighters.edit.mitglied.data', null);
    }

    function cancel() {
        
        $app = Factory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_firefighters.edit.mitglied.id');

        // Get the model.
        $model = $this->getModel('Mitgliedform');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }
        
        $menu = Factory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_firefighters&view=mitglieder' : $item->link);
        $this->setRedirect(Route::_($url, false));
    }

    public function remove() {

        // Initialise variables.
        $app = Factory::getApplication();
        $model = $this->getModel('Mitgliedform');

        // Get the user data.
        $data = array();
        $data['id'] = $app->getInput()->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof \Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_firefighters.edit.mitglied.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_firefighters.edit.mitglied.id');
            $this->setRedirect(Route::_('index.php?option=com_firefighters&view=mitglied&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_firefighters.edit.mitglied.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_firefighters.edit.mitglied.id');
            $this->setMessage(Text::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(Route::_('index.php?option=com_firefighters&view=mitglied&layout=edit&id=' . $id, false));
            return false;
        }

        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_firefighters.edit.mitglied.id', null);

        // Redirect to the list screen.
        $this->setMessage(Text::_('COM_FIREFIGHTERS_ITEM_DELETED_SUCCESSFULLY'));
        $menu = Factory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_firefighters&view=mitglieder' : $item->link);
        $this->setRedirect(Route::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_firefighters.edit.mitglied.data', null);
    }

}
