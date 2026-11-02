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

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_firefighters', JPATH_ADMINISTRATOR);
$doc = Factory::getApplication()->getDocument();
$doc->addScript(Uri::root(true) . '/media/com_firefighters/js/form.js');

?>
</style>
<script type="text/javascript"> 
    function() {
        jQuery(document).ready(function() {
            jQuery('#form-mitglied').submit(function(event) {
                
            });

            
			jQuery('input:hidden.dienstgrad').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('dienstgradhidden')){
					jQuery('#jform_dienstgrad option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_dienstgrad").trigger("liszt:updated");
			jQuery('input:hidden.abteilungen').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('abteilungenhidden')){
					jQuery('#jform_abteilungen option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
	jQuery('#jform_abteilungen').change(function(){
		if(jQuery('#jform_abteilungen option:selected').length == 0){
		jQuery("#jform_abteilungen option[value=0]").attr('selected', 'selected');
		}
	});
					jQuery("#jform_abteilungen").trigger("liszt:updated");
			jQuery('input:hidden.ausbildungen').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('ausbildungenhidden')){
					jQuery('#jform_ausbildungen option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
	jQuery('#jform_ausbildungen').change(function(){
		if(jQuery('#jform_ausbildungen option:selected').length == 0){
		jQuery("#jform_ausbildungen option[value=0]").attr('selected', 'selected');
		}
	});
					jQuery("#jform_ausbildungen").trigger("liszt:updated");
			jQuery('input:hidden.missions_eiko').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('missions_eikohidden')){
					jQuery('#jform_missions_eiko option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
	jQuery('#jform_missions_eiko').change(function(){
		if(jQuery('#jform_missions_eiko option:selected').length == 0){
		jQuery("#jform_missions_eiko option[value=0]").attr('selected', 'selected');
		}
	});
					jQuery("#jform_missions_eiko").trigger("liszt:updated");
        });
    });

</script>

<div class="mitglied-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Mitglied <small>bearbeiten</small> (ID-Nr.<?php echo $this->item->id; ?>)</h1>
    <?php else: ?>
        <h1>Mitglied <small>hinzufügen</small></h1>
    <?php endif; ?>

    <form id="form-mitglied" action="<?php echo Route::_('index.php?option=com_firefighters&task=mitglied.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vorname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vorname'); ?></div>
	</div>
	
	<div class="control-group" style="display:none;">
		<div class="control-label"><?php echo $this->form->getLabel('name_eiko'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('name_eiko'); ?></div>
	</div> 
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('bild'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('bild'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('dienstgrad'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('dienstgrad'); ?></div>
	</div>
	<?php foreach((array)$this->item->dienstgrad as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="dienstgrad" name="jform[dienstgradhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('abteilungen'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('abteilungen'); ?></div>
	</div>
	<?php foreach((array)$this->item->abteilungen as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="abteilungen" name="jform[abteilungenhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('kommando'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('kommando'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('funktion'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('funktion'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('mehr_funktionen'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('mehr_funktionen'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('ausbildungen'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('ausbildungen'); ?></div>
	</div>
	<?php foreach((array)$this->item->ausbildungen as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="ausbildungen" name="jform[ausbildungenhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('geburtsdatum'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('geburtsdatum'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('eintrittsdatum'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('eintrittsdatum'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('austrittsdatum'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('austrittsdatum'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('emailadresse'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('emailadresse'); ?></div>
	</div>
	
	<div class="control-group" style="display:none;">
		<div class="control-label"><?php echo $this->form->getLabel('missions_eiko'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('missions_eiko'); ?></div>
	</div> 
	
	<?php foreach((array)$this->item->missions_eiko as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="missions_eiko" name="jform[missions_eikohidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<div class="control-group" style="display:none;">
		<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
	</div>				
	
<!--	<div class="fltlft" <?php if (!Factory::getApplication()->getIdentity()->authorise('core.admin','firefighters')): ?> style="display:none;" <?php endif; ?> >
                <?php echo HTMLHelper::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo HTMLHelper::_('sliders.panel', Text::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo HTMLHelper::_('sliders.end'); ?>
    </div> -->
	
				<?php if (!Factory::getApplication()->getIdentity()->authorise('core.admin','firefighters')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-mitglied").appendChild(input);
                    });
                </script>
             <?php endif; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo Text::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo Route::_('index.php?option=com_firefighters&task=mitgliedform.cancel'); ?>" title="<?php echo Text::_('JCANCEL'); ?>"><?php echo Text::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_firefighters" />
        <input type="hidden" name="task" value="mitgliedform.save" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
