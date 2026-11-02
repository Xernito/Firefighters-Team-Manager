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

/** @var \Firefighters\Component\Firefighters\Administrator\View\Name\HtmlView $this */

$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useStyle('com_firefighters.firefighters');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
	js('input:hidden.dienstgrad').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('dienstgradhidden')){
			js('#jform_dienstgrad option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js('input:hidden.abteilungen').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('abteilungenhidden')){
			js('#jform_abteilungen option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js('input:hidden.ausbildungen').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('ausbildungenhidden')){
			js('#jform_ausbildungen option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js('input:hidden.missions_eiko').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('missions_eikohidden')){
			js('#jform_missions_eiko option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'mitglied.cancel') {
            Joomla.submitform(task, document.getElementById('mitglied-form'));
        }
        else {
            
            if (task != 'mitglied.cancel' && document.formvalidator.isValid(document.getElementById('mitglied-form'))) {
                
	if(js('#jform_abteilungen option:selected').length == 0){
		js("#jform_abteilungen option[value=0]").attr('selected','selected');
	}
	if(js('#jform_ausbildungen option:selected').length == 0){
		js("#jform_ausbildungen option[value=0]").attr('selected','selected');
	}
	if(js('#jform_missions_eiko option:selected').length == 0){
		js("#jform_missions_eiko option[value=0]").attr('selected','selected');
	}
                Joomla.submitform(task, document.getElementById('mitglied-form'));
            }
            else {
                alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>
<?php
$val= FirefightersHelper::getValidation();
?>
<form action="<?php echo Route::_('index.php?option=com_firefighters&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="mitglied-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_FIREFIGHTERS_TITLE_MITGLIED', true)); ?>
        <div class="row">
            <div class="col-md-10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vorname'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('vorname'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('geburtsdatum'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('geburtsdatum'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('emailadresse'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('emailadresse'); ?></div>
			</div>
			

		<!--	<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name_eiko'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name_eiko'); ?></div>
			</div> -->
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('bild'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('bild'); ?></div>
			</div>
			
			<br/>
			<br/>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('dienstgrad'); ?></div>
				<div class="controls" style="padding-bottom:10px;"><?php echo $this->form->getInput('dienstgrad'); ?></div>
				
				<?php $version = new Version;?>
				<?php if ($version->isCompatible('3.7')) :?>
				<div class="controls hideme"><?php echo $this->form->getInput('list_dienstgrad'); ?></div>
				<?php if (!$val) : ?>
				<div class="controls"><?php echo '<span style="color:red;">Dienstgrad-Historie nur Premium-Version verfügbar</span>'; ?></div>
				<style>
				.hideme {display:none;}
				</style>
				<?php endif;?>
				<?php endif;?>
			</div>
			<br/>

			<?php
				foreach((array)$this->item->dienstgrad as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="dienstgrad" name="jform[dienstgradhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>		
		
			<br/>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('abteilungen'); ?></div>
				<div class="controls" style="padding-bottom:10px;"><?php echo $this->form->getInput('abteilungen'); ?></div>
				
				<?php $version = new Version;?>
				<?php if ($version->isCompatible('3.7')) :?>
				<div class="controls hideme"><?php echo $this->form->getInput('list_abteilungen'); ?></div>
				<?php if (!$val) : ?>
				<div class="controls"><?php echo '<span style="color:red;">Abteilungen-Historie nur Premium-Version verfügbar</span>'; ?></div>
				<style>
				.hideme {display:none;}
				</style>
				<?php endif;?>
				<?php endif;?>
			</div>
			<br/>
			

			<?php
				foreach((array)$this->item->abteilungen as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="abteilungen" name="jform[abteilungenhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>	
			<br/>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('ausbildungen'); ?></div>
				<div class="controls" style="padding-bottom:10px;"><?php echo $this->form->getInput('ausbildungen'); ?></div>
				
				<?php $version = new Version;?>
				<?php if ($version->isCompatible('3.7')) :?>
				<div class="controls"><?php echo $this->form->getInput('list_ausbildungen'); ?></div>
				<?php if (!$val) : ?>
				<div class="controls"><?php echo '<span style="color:red;">Ausbildungen-Historie nur Premium-Version verfügbar</span>'; ?></div>
				<style>
				.hideme {display:none;}
				</style>
				<?php endif;?>
				<?php endif;?>
			</div>
			<br/>

			<?php
				foreach((array)$this->item->ausbildungen as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="ausbildungen" name="jform[ausbildungenhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>	

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
			<br/>
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('eintrittsdatum'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('eintrittsdatum'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('austrittsdatum'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('austrittsdatum'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('zusatz_ordering'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('zusatz_ordering'); ?></div>
			</div>
		<!--	<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('missions_eiko'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('missions_eiko'); ?></div>
			</div> -->

			<?php
				foreach((array)$this->item->missions_eiko as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="missions_eiko" name="jform[missions_eikohidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        
		
		
        <?php if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_firefighters')) : ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>
<?php endif; ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo HTMLHelper::_('form.token'); ?>

    </div>
</form>