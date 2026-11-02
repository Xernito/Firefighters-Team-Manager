<?php

/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Asset;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;
/**
 * dienstgrad Table class
 */
class DienstgradTable extends Table
{

	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(DatabaseInterface $db)
	{
		parent::__construct('#__firefighters_dienstgrade', 'id', $db);
				// Set the alias since the column is called state
				$this->setColumnAlias('published', 'state');
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param    array        Named array
	 *
	 * @return    null|string    null is operation was satisfactory, otherwise returns an error
	 * @see        JTable:bind
	 * @since      1.5
	 */
	public function bind($array, $ignore = '')
	{

		
		$input = Factory::getApplication()->getInput();
		$task = $input->getString('task', '');
		if(($task == 'save' || $task == 'apply') && (!Factory::getApplication()->getIdentity()->authorise('core.edit.state','com_firefighters.dienstgrad.'.$array['id']) && $array['state'] == 1)){
			$array['state'] = 0;
		}
		if($array['id'] == 0){
			$array['created_by'] = Factory::getApplication()->getIdentity()->id;
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new Registry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
		// if (!Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_firefighters.dienstgrad.' . $array['id']))
		// {
			// $actions         = Factory::getACL()->getActions('com_firefighters', 'dienstgrad');
			// $default_actions = Factory::getACL()->getAssetRules('com_firefighters.dienstgrad.' . $array['id'])->getData();
			// $array_jaccess   = array();
			// foreach ($actions as $action)
			// {
				// $array_jaccess[$action->name] = $default_actions[$action->name];
			// }
			// $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		// }
		//Bind the rules for ACL where supported.
		// if (isset($array['rules']) && is_array($array['rules']))
		// {
			// $this->setRules($array['rules']);
		// }

		return parent::bind($array, $ignore);
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 *
	 * @param type $jaccessrules an arrao of JAccessRule objects.
	 */
	// private function JAccessRulestoArray($jaccessrules)
	// {
		// $rules = array();
		// foreach ($jaccessrules as $action => $jaccess)
		// {
			// $actions = array();
			// foreach ($jaccess->getData() as $group => $allow)
			// {
				// $actions[$group] = ((bool) $allow);
			// }
			// $rules[$action] = $actions;
		// }

		// return $rules;
	// }

	/**
	 * Overloaded check function
	 */
	public function check()
	{

		//If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		return parent::check();
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_firefighters.dienstgrad.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @see Table::_getAssetParentId
	 */
	protected function _getAssetParentId(?Table $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = new Asset($this->getDbo());
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();
		// The item has the component as asset-parent
		$assetParent->loadByName('com_firefighters');
		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	public function delete($pk = null)
	{
		$this->load($pk);
		$result = parent::delete($pk);
		if ($result)
		{

			
		}

		return $result;
	}

}
