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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_firefighters', JPATH_ADMINISTRATOR);

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_firefighters.' . $this->item->id);
if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_firefighters' . $this->item->id)) {
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}
?>
<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header eiko_header_main">
<h1 class="ftm_header_h1"><?php echo $this->escape($this->params->get('page_heading')); ?> <span class="icon-info-2"> </span> <br/><small><?php echo $this->item->name; ?></small> </h1> 
</div>
<?php endif;?>

<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
<tr>
			<td>
			<img class="img-rounded ftm_img_ausbildung_detail_1" style="float:right;margin-top:20px;margin-right:10px;margin-left:10px;width:150px;;max-height:300px;" src="<?php echo Uri::Root();?><?php echo $this->item->bild;?>" title="<?php echo $this->item->name;?>"/>
			<?php echo $this->item->beschreibung; ?>
			</td>
</tr>
<tr>
			<td>
  <?php 
		// Get active menu
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$this->menu = $menus->getActive();
		$itemID = '&Itemid='.$this->menu->id.'&list=1';
		if (!$this->menu->id) {
			if ($this->params->get('dienstgradelink','')) :
			$itemID = '&Itemid='.$this->params->get('dienstgradelink','').'&list=1';
			endif;
		}
  echo '<a href="'.Route::_('index.php?option=com_firefighters&view=dienstgrade'.$itemID.'').'" class="btn"><strong>'.Text::_('Übersicht').'</strong></a></br>';
  ?>
  	<!--<input style="float:left;" type="button" class="btn ftm_back_button" value="Zurück" onClick="history.back();">-->
			</td>
</tr>

        </table>
    </div>
    <?php if($canEdit): ?>
		<a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=dienstgrad.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_FIREFIGHTERS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(Factory::getApplication()->getIdentity()->authorise('core.delete','com_firefighters.dienstgrad.'.$this->item->id)):?>
									<a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=dienstgrad.remove&id=' . $this->item->id, false, 2); ?>"><?php echo Text::_("COM_FIREFIGHTERS_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo Text::_('COM_FIREFIGHTERS_ITEM_NOT_LOADED');
endif;
?>
