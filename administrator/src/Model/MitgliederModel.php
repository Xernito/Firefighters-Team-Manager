<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;

/**
 * Methods supporting a list of Firefighters records.
 */
class MitgliederModel extends ListModel {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'name', 'a.name',
                'vorname', 'a.vorname',
                'name_eiko', 'a.name_eiko',
                'bild', 'a.bild',
                'dienstgrad', 'a.dienstgrad',
                'list_dienstgrad', 'a.list_dienstgrad',
                'abteilungen', 'a.abteilungen',
                'list_abteilungen', 'a.list_abteilungen',
                'kommando', 'a.kommando',
                'funktion', 'a.funktion',
                'mehr_funktionen', 'a.mehr_funktionen',
                'ausbildungen', 'a.ausbildungen',
                'list_ausbildungen', 'a.list_ausbildungen',
                'geburtsdatum', 'a.geburtsdatum',
                'eintrittsdatum', 'a.eintrittsdatum',
                'austrittsdatum', 'a.austrittsdatum',
                'emailadresse', 'a.emailadresse',
                'missions_eiko', 'a.missions_eiko',
                'state', 'a.state',
                'ordering', 'a.ordering',
                'zusatz_ordering', 'a.zusatz_ordering',
                'created_by', 'a.created_by',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = Factory::getApplication();

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);
        
		//Filtering dienstgrad
		$this->setState('filter.dienstgrad', $app->getUserStateFromRequest($this->context.'.filter.dienstgrad', 'filter_dienstgrad', '', 'string'));

		//Filtering abteilungen
		$this->setState('filter.abteilungen', $app->getUserStateFromRequest($this->context.'.filter.abteilungen', 'filter_abteilungen', '', 'string'));

		//Filtering ausbildungen
		$this->setState('filter.ausbildungen', $app->getUserStateFromRequest($this->context.'.filter.ausbildungen', 'filter_ausbildungen', '', 'string'));

		//Filtering missions_eiko
		$this->setState('filter.missions_eiko', $app->getUserStateFromRequest($this->context.'.filter.missions_eiko', 'filter_missions_eiko', '', 'string'));

        // Load the parameters.
        $params = ComponentHelper::getParams('com_firefighters');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__firefighters` AS a');

        
		// Join over the foreign key 'dienstgrad'
		$query->select('#__firefighters_dienstgrade_1619922.name AS dienstgrade_name_1619922');
		$query->join('LEFT', '#__firefighters_dienstgrade AS #__firefighters_dienstgrade_1619922 ON #__firefighters_dienstgrade_1619922.id = a.dienstgrad');
		// Join over the foreign key 'abteilungen'
		$query->select('#__firefighters_abteilungen_1619923.name AS abteilungen_name_1619923');
		$query->join('LEFT', '#__firefighters_abteilungen AS #__firefighters_abteilungen_1619923 ON #__firefighters_abteilungen_1619923.id = a.abteilungen');
		// Join over the foreign key 'ausbildungen'
		$query->select('#__firefighters_ausbildungen_1620029.name AS ausbildungen_name_1620029');
		$query->join('LEFT', '#__firefighters_ausbildungen AS #__firefighters_ausbildungen_1620029 ON #__firefighters_ausbildungen_1620029.id = a.ausbildungen');
		// Join over the foreign key 'missions_eiko'
		$query->select('#__firefighters_termine_1662891.datum_start AS termine_datum_start_1662891');
		$query->join('LEFT', '#__firefighters_termine AS #__firefighters_termine_1662891 ON #__firefighters_termine_1662891.id = a.missions_eiko');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

       

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.name LIKE '.$search.'  OR  a.vorname LIKE '.$search.'  OR  a.dienstgrad LIKE '.$search.'  OR  a.abteilungen LIKE '.$search.'  OR  a.ausbildungen LIKE '.$search.' )');
            }
        }

        

		//Filtering dienstgrad
		$filter_dienstgrad = $this->state->get("filter.dienstgrad");
		if ($filter_dienstgrad) {
			$query->where("a.dienstgrad = '".$db->escape($filter_dienstgrad)."'");
		}

		//Filtering abteilungen
		$filter_abteilungen = $this->state->get("filter.abteilungen");
		if ($filter_abteilungen) {
			$query->where("FIND_IN_SET(" . $filter_abteilungen. ",a.abteilungen)");
		}

		//Filtering ausbildungen
		$filter_ausbildungen = $this->state->get("filter.ausbildungen");
		if ($filter_ausbildungen) {
			$query->where("FIND_IN_SET(" . $filter_ausbildungen. ",a.ausbildungen)");
		}

		//Filtering missions_eiko
		$filter_missions_eiko = $this->state->get("filter.missions_eiko");
		if ($filter_missions_eiko) {
			$query->where("FIND_IN_SET(" . $filter_missions_eiko. ",a.missions_eiko)");
		}

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
		
			
        $items = parent::getItems();
		
		foreach ($items as $oneItem) {

		
			if (isset($oneItem->dienstgrad)) {
				$values = explode(',', $oneItem->dienstgrad);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('`#__firefighters_dienstgrade`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$oneItem->dienstgrad = !empty($textValue) ? implode(', ', $textValue) : $oneItem->dienstgrad;

			}

			if (isset($oneItem->abteilungen)) {
				$values = explode(',', $oneItem->abteilungen);

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

			$oneItem->abteilungen = !empty($textValue) ? implode(', ', $textValue) : $oneItem->abteilungen;

			}

			if (isset($oneItem->ausbildungen)) {
				$values = explode(',', $oneItem->ausbildungen);

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

			$oneItem->ausbildungen = !empty($textValue) ? implode(', ', $textValue) : $oneItem->ausbildungen;

			}

			if (isset($oneItem->missions_eiko)) {
				$values = explode(',', $oneItem->missions_eiko);

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

			$oneItem->missions_eiko = !empty($textValue) ? implode(', ', $textValue) : $oneItem->missions_eiko;

			}
		}
        return $items;
    }

}
