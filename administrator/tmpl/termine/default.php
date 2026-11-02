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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var \Firefighters\Component\Firefighters\Administrator\View\Termine\HtmlView $this */

$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect')
    ->useStyle('com_firefighters.firefighters');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->id;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_firefighters&task=termine.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_firefighters&view=termine'); ?>" method="post" name="adminForm" id="adminForm">
<div id="j-main-container" class="j-main-container">
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

		<table class="table table-striped"  id="terminList">
			<thead>
				<tr>
                <?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
                <?php endif; ?>
					<th class="w-1 text-center"><?php echo HTMLHelper::_('grid.checkall'); ?></th>
                <?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
                    
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_BILD', 'a.bild', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_ABTEILUNGEN', 'a.abteilungen', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_DATUM_START', 'a.datum_start', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_DATUM_ENDE', 'a.datum_ende', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_EMAIL_GESENDET', 'a.email_gesendet', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_FIREFIGHTERS_TERMINE_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
                    
                    
                <?php if (isset($this->items[0]->id)): ?>
					<th width="1%" class="nowrap center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_firefighters');
                $canEdit	= $user->authorise('core.edit',			'com_firefighters');
                $canCheckin	= $user->authorise('core.manage',		'com_firefighters');
                $canChange	= $user->authorise('core.edit.state',	'com_firefighters');
				?>
				<tr class="row<?php echo $i % 2; ?>" data-draggable-group="1">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center d-none d-md-table-cell">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = Text::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					<td class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'termine.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
                    
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'termine.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo Route::_('index.php?option=com_firefighters&task=termin.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
				</td>
				<td>
					<?php if ($item->bild): ?>
					<?php echo '<img src="../'.$item->bild.'" width="50" height="100%" title="'.$item->bild.'" />'; ?>
					<?php endif; ?>
				</td>
				
				<td>
					<?php if (!empty($item->email)): ?>
						<?php echo '<i class="icon-publish"></i>'; ?>
					<?php else: ?>
						<?php echo '<i class="icon-unpublish"></i>'; ?>
					<?php endif; ?>				
				</td>
				
				<td>

					<?php echo $item->abteilungen; ?>
				</td>
				<td>
					<?php $curDate = strtotime($item->datum_start); 
						  $curTime = date('H:i', $curDate);
						  echo date('d.m.Y ', $curDate); 
						  if ($curTime != '00:00') :
							echo'@ '.date('H:i ', $curDate).' Uhr';
						  endif;
					?>
				</td>
				<td>

					<?php if (!empty($item->datum_ende) && $item->datum_ende != '0000-00-00 00:00:00') :
							echo $item->datum_ende;
						endif;
					?>
				</td>
				<td>
				<?php if ($item->email_gesendet == 1): ?>
					<i class="icon-publish"></i>
				<?php else: ?>
					<i class="icon-unpublish"></i>
				<?php endif; ?>
				</td>
				<td>

					<?php echo $item->created_by; ?>
				</td>


                <?php if (isset($this->items[0]->id)): ?>
					<td class="center d-none d-md-table-cell">
						<?php echo (int) $item->id; ?>
					</td>
                <?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>        

		
