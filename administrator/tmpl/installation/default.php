<?php
/**
 * @version     3.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - https://einsatzkomponente.de
 */
defined('_JEXEC') or die;

use Joomla\Filesystem\Folder;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;
// Import CSS
$document = Factory::getApplication()->getDocument();
$document->getWebAssetManager()->useStyle('com_firefighters.firefighters');
// try to set time limit
@set_time_limit(0);
// try to increase memory limit
if ((int) ini_get('memory_limit') < 32) {
          @ini_set('memory_limit', '64M');
		}
// Versions-Nummer 
$db = Factory::getContainer()->get(DatabaseInterface::class);
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_firefighters"');
$params = json_decode( $db->loadResult(), true );
	

$bug='0';	
		
?>
<div align="left">
<?php
		echo '<h2>'.JTEXT::_('Installationsmanager für Firefighters Team Manager Version ').$params['version'].'</h2>'; 
		
		?>
		<a target="_blank" href="https://www.einsatzkomponente.de/index.php"><img border=0  src="<?php echo Uri::root(true); ?>/media/com_firefighters/images/komponentenbanner.jpg"/></a><br/><br/>
        <?php

		
		
?>
<?php echo '<br/><br/>';?>

<?php

echo '<div class="well">';
// try to set time limit
@set_time_limit(0);
echo '<h2>PHP-Einstellungen:</h2>';
// try to increase memory limit
echo 'memory_limit: '.ini_get('memory_limit').'<br/>';
echo 'upload_max_filesize: '.ini_get('upload_max_filesize').'<br/>';
echo 'post_max_size: '.ini_get('post_max_size').'<br/>';
if ((int) ini_get('memory_limit') < 256) {
          @ini_set('memory_limit', '256M');
		  echo 'memory_limit geändert auf: '.ini_get('memory_limit').'<br/>';
		}
if ((int) ini_get('upload_max_filesize') < 32) {
          @ini_set('upload_max_filesize', '32M');
		  echo 'upload_max_filesize geändert auf: '.ini_get('upload_max_filesize').'<br/>';
		}
if ((int) ini_get('post_max_size') < 8) {
          @ini_set('post_max_size', '8M');
		  echo 'post_max_size geändert auf: '.ini_get('post_max_size').'<br/>';
		}
echo '</div>';

echo '<div class="well">';
echo '<h2>Installation/Update :</h2>';

	// ------------------ Images -------------------------------------------------------------------------
	$discr = "Image-Ordner";
	$dir = JPATH_ROOT.'/images/com_firefighters'; 
	if (!is_dir($dir))   
	{
		echo 'Der '.$discr.' <span class="label label-important">existiert nicht</span>.<br/>';
		$source = JPATH_ROOT.'/'.'media/com_firefighters/'; 
		$target = JPATH_ROOT.'/images/com_firefighters/';
		echo 'Kopiere:&nbsp;&nbsp;&nbsp;'.$source.'&nbsp;&nbsp;&nbsp;&nbsp;<b>nach:</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$target.'<br/>';
		Folder::copy($source, $target);
			if (!is_dir($dir))   
			{
			echo 'Der '.$discr.' <span class="label label-important">wurde nicht erstellt !!!!</span>.<br/><br/>';$bug='1'; 
			}
			else {
					echo 'Der '.$discr.' <span class="label label-success">wurde erstellt.</span>.<br/><br/>'; 
				}
		
	}
	else {
		echo 'Der '.$discr.' <span class="label label-success">existiert</span>.<br/><br/>'; 
		}

echo '</div>';
?>

<?php if ($bug == '0') : ?>
<?php echo '<span class="label label-success">Installation erfolgreich ...</span><br/><br/>';?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
<input
   type="button"
   class="btn btn-primary"
   value=" weiter zum Kontrollzentrum "
   title=""
   onclick="window.location='index.php?option=com_firefighters&view=kontrollcenter'"
   /></div>
</form>
<?php endif; ?>

<?php if ($bug == '1') : ?>
<?php echo '<span class="label label-important">Installation nicht erfolgreich ...</span><br/><br/>Versuchen Sie es nochmal, oder wenden Sie sich an das Supportforum : <a href="https://www.einsatzkomponente.de" target="_blank">https://www.einsatzkomponente.de</a>';?>
<?php endif; ?>

<?php if ($bug == '2') : ?>
<?php echo '<span class="label label-success">Installation erfolgreich ...</span><br/><br/>';?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
<input
   type="button"
   class="btn btn-primary"
   value=" weiter zum Kontrollzentrum "
   title=""
   onclick="window.location='index.php?option=com_firefighters&view=kontrollcenter'"
   />
   </div>
</form>
<?php endif; ?>

</div>
<?php
?>
