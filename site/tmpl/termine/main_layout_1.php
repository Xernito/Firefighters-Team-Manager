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

\Joomla\CMS\Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('jquery');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

?>

<form action="<?php echo Route::_('index.php?option=com_firefighters&view=termine'); ?>" method="post" name="adminForm" id="adminForm">

   <div class="ftm_container">
		<div class="row">
			<div class="[ col-xs-12 col-sm-offset-2 col-sm-8 ]">
    <?php echo LayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	</br>

				<ul class="event-list">
				    <?php foreach ($this->items as $i => $item) : ?>

					<li>
			<?php 	
						  $curDate = strtotime((string) $item->datum_start); 
						  $curTime = date('H:i', $curDate);
						  $endDate = strtotime((string) $item->datum_ende); 
						  $endTime = date('H:i', $endDate);

						  $abgelaufen = '';
						  if (!$item->datum_ende = "0000-00-00 00:00:00 ") :
						  if ($curDate>$endDate) : $abgelaufen = ' <font color="green"><i class="icon-checkmark" ></i></font color>';endif;
						  else:
						  if (date("Y-m-d H:i:s",$curDate)<date("Y-m-d H:i:s")) : $abgelaufen = ' <font color="green"><i class="icon-checkmark" ></i></font color>';endif;
						  endif;
						  
			?>
						<time>
							<span class="ftm_termine_datum_start"><?php echo date('d.m.Y', $curDate);?></span>
							
						  <?php if ($curTime != '00:00') : ?>
							<span class="time"><?php echo'<i class="icon-arrow-right-3" ></i>'.date('H:i ', $curDate).' Uhr';?></span>
						  <?php endif;?>
						  
					<?php if ($this->params->get('show_termine_ende','1')) : ?>
						  <?php if ($endTime != '00:00') : ?>
							<span class="time"><?php echo'<i class="icon-arrow-left-3" ></i>'.date('H:i ', $endDate).' Uhr';?></span>
						  <?php endif;?>
					<?php endif;?>
						  
						  
					<?php if ($this->params->get('show_termine_image','1')) : ?>
					<?php if ($item->bild) : ?>
						<img class="ftm_termine_image" src="<?php echo Uri::Root();?><?php echo $item->bild;?>" alt="<?php echo $item->bild;?>" title="<?php echo $item->name;?>"/>
					<?php endif;?>
					<?php endif;?>
						  
						  
						</time>
						

						<div class="info">
							<?php if ($this->params->get('show_link_termin','1')) : ?>
							<a href="<?php echo Route::_('index.php?option=com_firefighters&view=termin'.$itemID.'&id='.(int) $item->id); ?>">
							<?php echo '<p class="ftm_termine_title">'.$this->escape($item->name).$abgelaufen.'</p>'; ?></a>
							<?php else: ?>
							<?php echo '<p class="ftm_termine_title">'.$this->escape($item->name).$abgelaufen.'</p>'; ?>
							<?php endif; ?>
							
							<?php if ($item->abteilungen) : ?>
							<?php echo '<p class="ftm_termine_abt"><i class="icon-users" style="margin-right:10px;" ></i>'.$item->abteilungen.'</p>'; ?>
							<?php else:?>
							<?php echo '<p class="ftm_termine_abt"></p>'; ?>
							<?php endif;?>
							
							<?php if ($item->beschreibung) : ?>
							<?php echo '<p class="ftm_termine_desc">'.$item->beschreibung.'</p>'; ?>
							<?php endif;?>
						</div>
						
						 <?php if ($canEdit || $canDelete): ?>
						<div class="social">
							<ul>
								<li class="facebook" style="width:33%;">
								<?php if ($canEdit): ?>
									<a href="<?php echo Route::_('index.php?option=com_firefighters&task=terminform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-sm" type="button"><i class="icon-edit" ></i></a>
								<?php endif; ?>
								</li>
								<li class="twitter" style="width:34%;">
								<?php if ($canDelete): ?>
									<button data-item-id="<?php echo $item->id; ?>" class="btn btn-sm delete-button" type="button"><i class="icon-trash" ></i></button>
								<?php endif; ?>
								</li>
							</ul>
						</div>
						<?php endif;?>
						
					</li>
    <?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

    <?php if ($canCreate): ?>
        <a href="<?php echo Route::_('index.php?option=com_firefighters&task=terminform.edit&id=0', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo Text::_('COM_FIREFIGHTERS_ADD_ITEM'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo HTMLHelper::_('form.token'); ?>
    <tfoot>
    <tr>
        <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
    </tr>
		<?php if (!$this->params->get('ftm')) : ?>
        <tr><!-- Bitte das Copyright nicht entfernen. Danke. -->
        <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
			<span class="copyright">Firefighters Team Manager V<?php echo $this->version; ?>  (C) 2016 by Ralf Meyer ( <a class="copyright_link" href="https://einsatzkomponente.de" target="_blank">www.einsatzkomponente.de</a> )</span></td>
        </tr>
	<?php endif; ?>
    </tfoot>
</form>

<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo Text::_('COM_FIREFIGHTERS_DELETE_MESSAGE'); ?>")) {
            window.location.href = '<?php echo Route::_('index.php?option=com_firefighters&task=terminform.remove&id=', false, 2) ?>' + item_id;
        }
    }
</script>

