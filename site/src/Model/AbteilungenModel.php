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

/**
 * Methods supporting a list of Firefighters records.
 */
class AbteilungenModel extends ListModel
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
                'bild', 'a.bild',
                'beschreibung', 'a.beschreibung',
                'abteilung_farbe', 'a.abteilung_farbe',
                'state', 'a.state',
                'ordering', 'a.ordering',
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
		$page_limit = $params->get('display_abteilungen_pagination_limit','');
		$show_pagination = $params->get('display_abteilungen_pagination','1');
		if (!$page_limit) : $page_limit = $app->get('list_limit'); endif;
		if (!$show_pagination) : $page_limit = ''; endif; 

        // Initialise variables.
        $app = Factory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('abteilungen' . '.list', 'limit', $page_limit);
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

        $query->from('`#__firefighters_abteilungen` AS a');

        
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
                $query->where('( a.name LIKE '.$search.' )');
            }
        }

        

        // Add the list ordering clause.
        //$orderCol = $this->state->get('list.ordering');
        //$orderDirn = $this->state->get('list.direction');
		$orderCol = 'ordering';
        $orderDirn = 'ASC';

        if ($orderCol && $orderDirn)
        {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        
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
