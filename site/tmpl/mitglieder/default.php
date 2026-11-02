<?php
/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Version;

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_firefighters', JPATH_ADMINISTRATOR);

$user = Factory::getApplication()->getIdentity();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_firefighters');
$canCheckin = $user->authorise('core.manage', 'com_firefighters');
$canChange = $user->authorise('core.edit.state', 'com_firefighters');
$canDelete = $user->authorise('core.delete', 'com_firefighters');

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_firefighters');
if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_firefighters')) {
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}

		// Get active menu
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$this->menu = $menus->getActive();
		$itemID = '&Itemid='.$this->menu->id;
		if (!$this->menu->id) {
			if ($this->params->get('mitgliederlink','')) :
			$itemID = '&Itemid='.$this->params->get('mitgliederlink','');
			endif;
		}

?>

<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header eiko_header_main">
<h1 class="eiko_header_main_h1"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1> 
</div>
<br/>
<?php endif;?>

<?php
require_once JPATH_SITE.'/components/com_firefighters/views/mitglieder/tmpl/'.$this->params->get('main_layout','main_layout_1.php').''; 

?>
