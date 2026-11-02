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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_firefighters', JPATH_ADMINISTRATOR);
$doc = Factory::getApplication()->getDocument();
$doc->addScript(Uri::root(true) . '/media/com_firefighters/js/form.js');

?>
<div class="dienstgrad-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Dienstgrad <small>bearbeiten</small> (ID.Nr.<?php echo $this->item->id; ?>)</h1>
    <?php else: ?>
        <h1>Dienstgrad <small>eintragen</small></h1>
    <?php endif; ?>

    <form id="form-dienstgrad" action="<?php echo Route::_('index.php?option=com_firefighters&task=dienstgrad.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bild'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bild'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('beschreibung'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('beschreibung'); ?></div>
	</div>
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<div class="control-group"  style="display:none;">
		<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
	</div>				
				<?php if (!Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_firefighters')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-dienstgrad").appendChild(input);
                    });
                </script>
             <?php endif; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo Text::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=dienstgradform.cancel'); ?>" title="<?php echo Text::_('JCANCEL'); ?>"><?php echo Text::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_firefighters" />
        <input type="hidden" name="task" value="dienstgradform.save" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
