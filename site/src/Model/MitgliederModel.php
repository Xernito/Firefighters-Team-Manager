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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of Firefighters records.
 */
class MitgliederModel extends ListModel
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
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
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {

        // Initialise variables.
        $app = Factory::getApplication();
		$params = $app->getParams('com_firefighters');
		$page_limit = $params->get('display_mitglieder_pagination_limit','');
		$show_pagination = $params->get('display_mitglieder_pagination','1');
		if (!$page_limit) : $page_limit = $app->get('list_limit'); endif;
		if (!$show_pagination) : $page_limit = ''; endif; 

        // Initialise variables.
        $app = Factory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('mitglieder' . '.list', 'limit', $page_limit);
        $this->setState('list.limit', $limit);

        $limitstart = $app->getInput()->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
        {
            foreach ($list as $name => $value)
            {
                // Extra validations
                switch ($name)
                {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);

                        if (count($orderingParts) >= 2)
                        {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);

                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
                            {
                                $this->setState('list.direction', $fullDirection);
                            }

                            unset($orderingParts[count($orderingParts) - 1]);

                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields))
                            {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        }
                        else
                        {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;

                    case 'ordering':
                        if (!in_array($value, $this->filter_fields))
                        {
                            $value = $ordering;
                        }
                        break;

                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
                        {
                            $value = $direction;
                        }
                        break;

                    case 'limit':
                        $limit = $value;
                        break;

                    // Just to keep the default case
                    default:
                        $value = $value;
                        break;
                }

                $this->setState('list.' . $name, $value);
            }
        }
		
// Filter aus Menülink abfangen 

if (!$app->getInput()->getInt('list', 0)) : // Prüfen ob zurück aus Detailansicht
$params = $app->getParams('com_firefighters');

$this->setState('filter.abteilungen', $params->get('filter_abteilungen',''));
$app->setUserState( $this->context . '.filter.abteilungen',  $params->get('filter_abteilungen','') );

$this->setState('filter.ausbildungen', $params->get('filter_ausbildungen',''));
$app->setUserState( $this->context . '.filter.ausbildungen',  $params->get('filter_ausbildungen','') );

$this->setState('filter.dienstgrad', $params->get('filter_dienstgrad',''));
$app->setUserState( $this->context . '.filter.dienstgrad',  $params->get('filter_dienstgrad','') );

endif;

        // Receive & set filters
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
        {
            foreach ($filters as $name => $value)
            {
                $this->setState('filter.' . $name, $value);
            }
        }

        $this->setState('list.ordering', $app->getInput()->get('filter_order'));
        $this->setState('list.direction', $app->getInput()->get('filter_order_Dir'));
    }

    /**
     * Build an SQL query to load the list data. 
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query
                ->select(
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
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

	    
$query->where('a.state = 1');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.name LIKE '.$search.'  OR  a.vorname LIKE '.$search.' )');
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
			$query->where("FIND_IN_SET(" . (int) $filter_abteilungen . ",a.abteilungen)");
		}

		//Filtering ausbildungen
		$filter_ausbildungen = $this->state->get("filter.ausbildungen");
		if ($filter_ausbildungen) {
			$query->where("FIND_IN_SET(" . (int) $filter_ausbildungen . ",a.ausbildungen)");
		}

		//Filtering missions_eiko
		$filter_missions_eiko = $this->state->get("filter.missions_eiko");
		if ($filter_missions_eiko) {
			$query->where("FIND_IN_SET(" . (int) $filter_missions_eiko . ",a.missions_eiko)");
		}

		
		// Filter-Override aus Menü-Parameter
					$app = Factory::getApplication();
					$params = $app->getParams('com_firefighters'); 
					if ($params->get('list_mitglieder','')) : 
						$mitglieder = $params->get('list_mitglieder',''); 
						$mitglieder_array = array();
						foreach ( $mitglieder as $mitglied) :
						$mitglieder_array[] = $mitglied['nur_diese_mitglieder'];
						endforeach;
					    $mitglieder_string = implode (' OR a.id = ',$mitglieder_array);
						$query->where('a.id = '.$mitglieder_string.' ');
					endif;

        // Add the list ordering clause.
        //$orderCol = $this->state->get('list.ordering');
        //$orderDirn = $this->state->get('list.direction');
        $orderCol = 'ordering';
        $orderDirn = 'ASC';
		
					if ($params->get('zusatz_ordering_option','')) : 
						$orderCol = 'zusatz_ordering';
					endif;

        if ($orderCol && $orderDirn)
        {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }
					

				return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        foreach($items as $item){
	
			if (isset($item->list_dienstgrad) && $item->list_dienstgrad != '') 
				{
					$item->list_dienstgrad= json_decode($item->list_dienstgrad);
					$item->list_dienstgrad = ArrayHelper::fromObject($item->list_dienstgrad);
					$n = 0;
					foreach ($item->list_dienstgrad as $itemz) :
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name,bild')
							->from('`#__firefighters_dienstgrade`')
							->where('id = ' . $db->quote($db->escape($item->list_dienstgrad['list_dienstgrad'.$n.'']['dienstgrad'])));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$item->list_dienstgrad['list_dienstgrad'.$n.'']['dienstgrad'] = $results->name;
					}
					$n++;
					endforeach;
				}

				if (isset($item->dienstgrad) && $item->dienstgrad != '') {
				if(is_object($item->dienstgrad)){
					$item->dienstgrad = ArrayHelper::fromObject($item->dienstgrad);
				}
				$values = (is_array($item->dienstgrad)) ? $item->dienstgrad : explode(',',$item->dienstgrad);

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
						$item->dienstgrad_image = $results->bild;
					}
				}

			$item->dienstgrad = !empty($textValue) ? implode(', ', $textValue) : $item->dienstgrad;

			}
			
			

			if (isset($item->abteilungen) && $item->abteilungen != '') {
				if(is_object($item->abteilungen)){
					$item->abteilungen = ArrayHelper::fromObject($item->abteilungen);
				}
				$values = (is_array($item->abteilungen)) ? $item->abteilungen : explode(',',$item->abteilungen);

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

			$item->abteilungen = !empty($textValue) ? implode(', ', $textValue) : $item->abteilungen;

			}

			if (isset($item->ausbildungen) && $item->ausbildungen != '') {
				if(is_object($item->ausbildungen)){
					$item->ausbildungen = ArrayHelper::fromObject($item->ausbildungen);
				}
				$values = (is_array($item->ausbildungen)) ? $item->ausbildungen : explode(',',$item->ausbildungen);

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

			$item->ausbildungen = !empty($textValue) ? implode(', ', $textValue) : $item->ausbildungen;

			}

			if (isset($item->missions_eiko) && $item->missions_eiko != '') {
				if(is_object($item->missions_eiko)){
					$item->missions_eiko = ArrayHelper::fromObject($item->missions_eiko);
				}
				$values = (is_array($item->missions_eiko)) ? $item->missions_eiko : explode(',',$item->missions_eiko);

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

			$item->missions_eiko = !empty($textValue) ? implode(', ', $textValue) : $item->missions_eiko;

			}
}
        return $items;
    }

    /**
     * Overrides the default function to check Date fields format, identified by
     * "_dateformat" suffix, and erases the field if it's not correct.
     */
    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $filters = $app->getUserState($this->context . '.filter', array());
        $error_dateformat = false;
        foreach ($filters as $key => $value)
        {
            if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
            {
                $filters[$key] = '';
                $error_dateformat = true;
            }
        }
        if ($error_dateformat)
        {
            $app->enqueueMessage(Text::_("COM_PRUEBA_SEARCH_FILTER_DATE_FORMAT"), "warning");
            $app->setUserState($this->context . '.filter', $filters);
        }

        return parent::loadFormData();
    }

    /**
     * Checks if a given date is valid and in an specified format (YYYY-MM-DD) 
     * 
     * @param string Contains the date to be checked
     * 
     */
    private function isValidDate($date)
    {
        return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
    }

}
