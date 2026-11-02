<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;
/**
 * Firefighters model.
 */
class MitgliedModel extends ItemModel {

    protected $_item = null;

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
        $app = Factory::getApplication();

        // Load state from the request userState on edit or from the passed variable on default
        if (Factory::getApplication()->getInput()->get('layout') == 'edit') {
            $id = Factory::getApplication()->getUserState('com_firefighters.edit.mitglied.id');
        } else {
            $id = Factory::getApplication()->getInput()->get('id');
            Factory::getApplication()->setUserState('com_firefighters.edit.mitglied.id', $id);
        }
        $this->setState('mitglied.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if (isset($params_array['item_id'])) {
            $this->setState('mitglied.id', $params_array['item_id']);
        }
        $this->setState('params', $params);
    }

    /**
     * Method to get an ojbect.
     *
     * @param	integer	The id of the object to get.
     *
     * @return	mixed	Object on success, false on failure.
     */
    public function getData($id = null) {
        if ($this->_item === null) {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('mitglied.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = ArrayHelper::toObject($properties, 'stdClass');
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }

        
			if (isset($this->_item->list_dienstgrad) && $this->_item->list_dienstgrad != '') 
				{
					$this->_item->list_dienstgrad= json_decode($this->_item->list_dienstgrad);
					$this->_item->list_dienstgrad = ArrayHelper::fromObject($this->_item->list_dienstgrad);
					$n = 0;
					foreach ($this->_item->list_dienstgrad as $itemz) :
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name,bild')
							->from('`#__firefighters_dienstgrade`')
							->where('id = ' . $db->quote($db->escape($this->_item->list_dienstgrad['list_dienstgrad'.$n.'']['dienstgrad'])));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$this->_item->list_dienstgrad['list_dienstgrad'.$n.'']['dienstgrad'] = $results->name;
					}
					$n++;
					endforeach;
				}
			
			

			if (isset($this->_item->dienstgrad) && $this->_item->dienstgrad != '') {
				if(is_object($this->_item->dienstgrad)){
					$this->_item->dienstgrad = ArrayHelper::fromObject($this->_item->dienstgrad);
				}
				$values = (is_array($this->_item->dienstgrad)) ? $this->_item->dienstgrad : explode(',',$this->_item->dienstgrad);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name,bild')
							->from('`#__firefighters_dienstgrade`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
						$this->_item->dienstgrad_image = $results->bild;
					}
				}

			$this->_item->dienstgrad = !empty($textValue) ? implode(', ', $textValue) : $this->_item->dienstgrad;

			}

			if (isset($this->_item->abteilungen) && $this->_item->abteilungen != '') {
				if(is_object($this->_item->abteilungen)){
					$this->_item->abteilungen = ArrayHelper::fromObject($this->_item->abteilungen);
				}
				$values = (is_array($this->_item->abteilungen)) ? $this->_item->abteilungen : explode(',',$this->_item->abteilungen);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('`#__firefighters_abteilungen`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$this->_item->abteilungen = !empty($textValue) ? implode(', ', $textValue) : $this->_item->abteilungen;

			}

			if (isset($this->_item->ausbildungen) && $this->_item->ausbildungen != '') {
				if(is_object($this->_item->ausbildungen)){
					$this->_item->ausbildungen = ArrayHelper::fromObject($this->_item->ausbildungen);
				}
				$values = (is_array($this->_item->ausbildungen)) ? $this->_item->ausbildungen : explode(',',$this->_item->ausbildungen);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('`#__firefighters_ausbildungen`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$this->_item->ausbildungen = !empty($textValue) ? implode(', ', $textValue) : $this->_item->ausbildungen;

			}

			if (isset($this->_item->missions_eiko) && $this->_item->missions_eiko != '') {
				if(is_object($this->_item->missions_eiko)){
					$this->_item->missions_eiko = ArrayHelper::fromObject($this->_item->missions_eiko);
				}
				$values = (is_array($this->_item->missions_eiko)) ? $this->_item->missions_eiko : explode(',',$this->_item->missions_eiko);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('datum_start')
							->from('`#__firefighters_termine`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->datum_start;
					}
				}

			$this->_item->missions_eiko = !empty($textValue) ? implode(', ', $textValue) : $this->_item->missions_eiko;

			}
		if ( isset($this->_item->created_by) ) {
			$this->_item->created_by_name = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($this->_item->created_by)->name;
		}

        return $this->_item;
    }

    public function getTable($type = 'Mitglied', $prefix = '', $config = [])
    {
        return new \Firefighters\Component\Firefighters\Administrator\Table\MitgliedTable(Factory::getContainer()->get(DatabaseInterface::class));
    }

    /**
     * Method to check in an item.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkin($id = null) {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('mitglied.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkout($id = null) {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('mitglied.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = Factory::getApplication()->getIdentity();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    public function getCategoryName($id) {
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);
        $query
                ->select('title')
                ->from('#__categories')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function publish($id, $state) {
        $table = $this->getTable();
        $table->load($id);
        $table->state = $state;
        return $table->store();
    }

    public function delete($id) {
        $table = $this->getTable();
        return $table->delete($id);
    }

}
