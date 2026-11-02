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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_firefighters', JPATH_ADMINISTRATOR);

$user = Factory::getApplication()->getIdentity();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_firefighters');
$canEdit = $user->authorise('core.edit', 'com_firefighters');
$canCheckin = $user->authorise('core.manage', 'com_firefighters');
$canChange = $user->authorise('core.edit.state', 'com_firefighters');
$canDelete = $user->authorise('core.delete', 'com_firefighters');

		// Get active menu
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$this->menu = $menus->getActive();
		$itemID = '&Itemid='.$this->menu->id;
		if (!$this->menu->id) {
			if ($this->params->get('ausbildungenlink','')) :
			$itemID = '&Itemid='.$this->params->get('ausbildungenlink','');
			endif;
		}
?>
<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header">
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
</div>
<?php endif;?>

<form action="<?php echo Route::_('index.php?option=com_firefighters&view=ausbildungen'); ?>" method="post" name="adminForm" id="adminForm">

    <?php //echo LayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <table class="table table-striped" id = "ausbildungList" >
        <thead >
            <tr >
				<th class='left'>
				<?php echo 'Foto'; ?>
				</th>
    				<th class='left'>
				<?php echo 'Ausbildung'; ?>
				</th>

    				<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo Text::_('Admin-Action'); ?>
				</th>
				<?php endif; ?>

    </tr>
    </thead>
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
    <tbody>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php $canEdit = $user->authorise('core.edit', 'com_firefighters'); ?>

        				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_firefighters')): ?>
					<?php $canEdit = Factory::getApplication()->getIdentity()->id == $item->created_by; ?>
				<?php endif; ?>

        <tr class="row<?php echo $i % 2; ?>">

				<td>
				<?php if ($item->bild) : ?>
					<img class="ftm_ausbildung_image" src="<?php echo Uri::Root();?><?php echo $item->bild;?>" alt="<?php echo $item->bild;?>" title="<?php echo $item->bild;?>"/>
				<?php endif;?>

				</td>
				
            	<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'ausbildungen.', $canCheckin); ?>
				<?php endif; ?>
				

				<?php echo '<span style="color:#d63b37;font-size:20px;font-weight:bold;">'.$this->escape($item->name).'</span>'; ?>
				<br/>
				<?php if ($item->beschreibung) : ?>
				<?php jimport('joomla.html.content'); ?>  
				<?php $Desc = HTMLHelper::_('content.prepare', $item->beschreibung); ?>
				<?php echo $Desc; ?>
				<?php endif; ?>
				<?php if ($this->params->get('show_ausbildung_detail_link','1')) : ?>				
				<p><a href="<?php echo Route::_('index.php?option=com_firefighters&view=ausbildung'.$itemID.'&id='.(int) $item->id); ?>" class="btn btn-primary" role="button">Details</a></p>
				<?php endif; ?>
				</td>

            				<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo Route::_('index.php?option=com_firefighters&task=ausbildungform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-sm" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="btn btn-sm delete-button" type="button"><i class="icon-trash" ></i></button>
						<?php endif; ?>
					</td>
				<?php endif; ?>

        </tr>
    <?php endforeach; ?>
	 </tbody>
    </table>

    <?php if ($canCreate): ?>
        <a href="<?php echo Route::_('index.php?option=com_firefighters&task=ausbildungform.edit&id=0', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo Text::_('Ausbildung hinzufügen'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo Text::_('COM_FIREFIGHTERS_DELETE_MESSAGE'); ?>")) {
            window.location.href = '<?php echo Route::_('index.php?option=com_firefighters&task=ausbildungform.remove&id=', false, 2) ?>' + item_id;
        }
    }
</script>

