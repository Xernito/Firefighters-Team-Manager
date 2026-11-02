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

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_firefighters.' . $this->item->id);
if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_firefighters' . $this->item->id)) {
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

   <div class="ftm_container">
		<div class="row">
			<div class="[ col-xs-12 col-sm-offset-2 col-sm-8 ]">
				

					<?php if ($this->params->get('show_termine_image','1')) : ?>
					<?php if ($this->item->bild) : ?>
						<img class="ftm_termin_image" src="<?php echo Uri::Root();?><?php echo $this->item->bild;?>" alt="<?php echo $this->item->bild;?>" title="<?php echo $this->item->name;?>"/>
					<?php endif;?>
					<?php endif;?>

					
					
						

						<div class="info">
						
			<?php 	
						  $curDate = strtotime($this->item->datum_start); 
						  $curTime = date('H:i', $curDate);
			?>
						<time datetime="<?php echo date('<b>d.m.Y</b> ', $curDate);?>">
					<!--		<span class="Tag"><?php echo date('D', $curDate);?></span>
							<span class="day"><?php echo date('d', $curDate);?></span>
							<span class="month"><?php echo date('m', $curDate);?></span>
							<span class="year"><?php echo date('Y', $curDate);?></span>  -->
							<span class="ftm_termin_datum_start"><?php echo date('d.m.Y', $curDate);?></span>
						  <?php if ($curTime != '00:00') : ?>
							<span class="ftm_termin_uhrzeit_start"><?php echo'@ '.date('H:i ', $curDate).' Uhr';?></span>
						  <?php endif;?>
						  
						  
						  
						</time>
						
							<?php if ($this->params->get('show_link_termin','1')) : ?>
							<a href="<?php echo Route::_('index.php?option=com_firefighters&view=termin&id='.(int) $this->item->id); ?>">
							<?php echo '<p class="ftm_termine_title">'.$this->escape($this->item->name).'</p>'; ?></a>
							<?php else: ?>
							<?php echo '<p class="ftm_termine_title">'.$this->escape($this->item->name).'</p>'; ?>
							<?php endif; ?>
							
							<?php if ($this->item->abteilungen) : ?>
							<?php echo '<p class="ftm_termine_abt">'.$this->item->abteilungen.'</p>'; ?>
							<?php else:?>
							<?php echo '<p class="ftm_termine_abt"></p>'; ?>
							<?php endif;?>
							
							<?php if ($this->item->beschreibung) : ?>
							<?php echo '<p class="ftm_termine_desc">'.$this->item->beschreibung.'</p>'; ?>
							<?php endif;?>
						</div>
						
						 <?php if ($canEdit || $canDelete): ?>
						<div class="social">
							<ul>
								<li class="facebook" style="width:33%;">
								<?php if ($canEdit): ?>
									<a href="<?php echo Route::_('index.php?option=com_firefighters&task=terminform.edit&id=' . $this->item->id, false, 2); ?>" class="btn btn-sm" type="button"><i class="icon-edit" ></i></a>
								<?php endif; ?>
								</li>
								<li class="twitter" style="width:34%;">
								<?php if ($canDelete): ?>
									<button data-item-id="<?php echo $this->item->id; ?>" class="btn btn-sm delete-button" type="button"><i class="icon-trash" ></i></button>
								<?php endif; ?>
								</li>
							</ul>
						</div>
						<?php endif;?>
						
  </div>
  <?php 
		// Get active menu
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$this->menu = $menus->getActive();
		$itemID = '&Itemid='.$this->menu->id.'&list=1';
		if (!$this->menu->id) {
			if ($this->params->get('terminelink','')) :
			$itemID = '&Itemid='.$this->params->get('terminelink','').'&list=1';
			endif;
		}
  echo '<a href="'.Route::_('index.php?option=com_firefighters&view=termine'.$itemID.'').'" class="btn"><strong>'.Text::_('Übersicht').'</strong></a></br>';
  ?>
  	<!--<input style="float:left;" type="button" class="btn ftm_back_button" value="Zurück" onClick="history.back();">-->
</div>

	</div>

    <?php if($canEdit): ?>
		<a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=termin.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_FIREFIGHTERS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(Factory::getApplication()->getIdentity()->authorise('core.delete','com_firefighters.termin.'.$this->item->id)):?>
									<a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=termin.remove&id=' . $this->item->id, false, 2); ?>"><?php echo Text::_("COM_FIREFIGHTERS_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo Text::_('COM_FIREFIGHTERS_ITEM_NOT_LOADED');
endif;
?>
