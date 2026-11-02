<?php
/**
 * @version     3.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2016 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - https://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;

// Import CSS
$document = Factory::getApplication()->getDocument();
$document->getWebAssetManager()->useStyle('com_firefighters.firefighters');
// Versions-Nummer 
$db = Factory::getContainer()->get(DatabaseInterface::class);
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_firefighters"');
$params = json_decode( $db->loadResult(), true );

$user	= Factory::getApplication()->getIdentity();
$userId	= $user->get('id');
$val= FirefightersHelper::getValidation();

?>

<form action="<?php echo Route::_('index.php?option=com_firefighters&view=kontrollcenters'); ?>" method="post" name="adminForm" id="adminForm">
<div id="j-main-container" class="j-main-container">

							
		<div id="filter-bar" class="btn-toolbar"></div> 
               
        
			  <div class="btn-group btn-group-justified">
						
	    					<a class="btn" href="index.php?option=com_firefighters&view=termine">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('Termine'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_firefighters/images/l_termine.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('Termine'); ?></span>
	    					</a>
							
	    					<a class="btn" href="index.php?option=com_firefighters&view=mitglieder">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('Termine'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_firefighters/images/l_mitglieder.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('Mitglieder'); ?></span>
	    					</a>

	    					<a class="btn" href="index.php?option=com_firefighters&view=abteilungen">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('Abteilungen'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_firefighters/images/l_abteilungen.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('Abteilungen'); ?></span>
	    					</a>
							
	    					<a class="btn" href="index.php?option=com_firefighters&view=dienstgrade">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('Dienstgrade'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_firefighters/images/l_dienstgrade.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('Dienstgrade'); ?></span>
	    					</a>
							
	    					<a class="btn" href="index.php?option=com_firefighters&view=ausbildungen">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('Ausbildungen'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_firefighters/images/l_ausbildungen.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('Ausbildungen'); ?></span>
	    					</a>
							
							</div>
					
					
			<table class="table"> 
			<thead>
				<tr>
					<th>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>

<div class="col-md-4">
<div class="alert alert-info" style=" float:left;">
<a target="_blank" href="https://www.einsatzkomponente.de/index.php"><img src="<?php echo Uri::root(true); ?>/media/com_firefighters/images/komponentenbanner.jpg" style="float:left; margin-right:20px; padding-right:20px;"/></a>
<span class="label label-important">Was könnt Ihr zur Entwicklung beitragen ?</span><br/><br/>
Neben sehr viel Freizeit kostet die Entwicklung unserer Software und der Unterhalt dieser Supportseite natürlich auch Geld.
Unterstützen Sie die Weiterentwicklung unseres Projekts FIREFIGHTERS TEAM MANAGER mit einer Spende, damit wir unsere Software auch weiterhin kostenlos und werbefrei zur Verfügung stellen können.
<br/>Vielen Dank ! <br />
<small>Kontakt: <?php echo $params['authorEmail'];?></small><br />

<p><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XPQALX4UFFGM4"><span style="float:right;">Spenden über PAYPAL : <img border=0  src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" /></span></a>
<p><small><span style="float:right;"></br>Alternativ können Sie die Kontodaten per <a href="mailto:validate@einsatzkomponente.de?Subject=Spende%20Firefighters%20Team%20Manager%20J3.x" target="_top">Email </a>anfordern.</span></small></p></p>

</div>
</div>
<div class="col-md-5">
					<div class="well well-small" style=" float:left;">
						<div class="center">
							<?php echo '<h4>'.Text::_('FIREFIGHTERS TEAM MANAGER für das Joomla-CMS ');?><?php echo '</h4>';?>
						</div>
						<hr class="hr-condensed">
						<dl class="dl-horizontal">
							<dt>Version:</dt>
							<dd><?php echo $params['version'];?>
							<?php if ($val) : ?>
							<?php echo '<span class="label label-success"> ( validiert ) </span>';?>
                            <?php else:?>
							<?php echo '<span class="label label-important"> ( nicht validiert ) </span><br/>siehe Optionen / Info';?>
                            <?php endif;?>
							
								<?php Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.modal'); ?>
								</br>
						<a href="#eiko-changelog" data-bs-toggle="modal"><?php echo 'Changelog'; ?></a>
						<div id="eiko-changelog" class="modal fade" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-dialog-scrollable">
								<div class="modal-content">
									<div class="modal-header">
										<h3 class="modal-title">Changelog</h3>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('JCLOSE'); ?>"></button>
									</div>
									<div class="modal-body">
										<?php
											echo changelog (JPATH_ADMINISTRATOR . '/components/com_firefighters'.'/CHANGELOG.php');
										?>
									</div>
								</div>
							</div>
						</div>

                            </dd>
							<hr>
							<dt>Release-Datum:</dt>
							<dd><?php echo $params['creationDate'];?></dd>
							<dt>Autor:</dt>
							<dd><?php echo $params['author'];?></dd>
							<dt>Autor-Email:</dt>
							<dd><?php echo $params['authorEmail'];?></dd>
							<dt>Copyright:</dt>
							<dd><?php echo $params['copyright'];?></dd>
							<dt>Lizenz:</dt>
							<dd>GNU General Public License version 2 or later </dd>
						</dl>
						<hr>
							<b>Premiumfunktionen:</b></br>
							<?php if ($val) : ?>
							<?php echo '<span style="margin-bottom:5px;" class="label label-success">unlimitierte Anzahl von Mitglieder</span></br>';?>
							<?php echo '<span style="margin-bottom:5px;" class="label label-success">unlimitierte Anzahl von Terminen</span></br>';?>
							<?php else:?>
							<?php echo '<span style="margin-bottom:5px;text-decoration: line-through;" class="label label-important">unlimitierte Anzahl von Mitglieder</span></br>';?>
							<?php echo '<span style="margin-bottom:5px;text-decoration: line-through;" class="label label-important">unlimitierte Anzahl von Terminen</span></br>';?>
							<?php endif;?>
							
							<?php $plugin = PluginHelper::getPlugin('system', 'ftm_event_mail') ;
							if ($plugin) : 
							?>
												<span class="label label-success">Das Plugin ftm_event_mail ist aktivert</span>
							<?php else : ?>
												<span class="label label-important">Das Plugin <b>ftm_event_mail</b> ist nicht aktivert</span><span class="icon-info-2 large-icon" style="font-size:18px;" title="Diese Plugin wird benötigt, wenn Errinnerungsmails zu den Terminen versandt werden sollen. Du findest das Plugin im Downloadcenter bei einsatzkomponente.de"> </span>
							<?php endif;?>
							
							<hr>
						<b>Informationen:</b></br>
						<a target="_blank" style="margin-bottom:5px;" style="margin-bottom:5px;" class="label label-info" href="https://www.einsatzkomponente.de">Download-Link Webseite</a> 
						<br/>
						<!-- Button to trigger modal -->
						<a href="#myModal" role="button" style="margin-bottom:5px;" class="label label-info" data-bs-toggle="modal">Verfügbare Module und Plugins ...</a>
     
						<!-- Modal -->
						<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog"><div class="modal-content">
						<div class="modal-header">
						<h3 id="myModalLabel" class="modal-title">Verfügbare Module und Plugins</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('JCLOSE'); ?>"></button>
						</div>
						<div class="modal-body">
						<ul>
						<li><a href="https://einsatzkomponente.de/wsif/index.php/Category/12-Module-f%C3%BCr-Firefighters-Team-Manager/" target="_blank" class="">mod_ftm_kalender</a> (Modul zur Anzeige der nächsten Termine auf einer Modulposition)</li>
						<li><a href="https://einsatzkomponente.de/wsif/index.php/Category/13-Premiumbereich-FTM-nur-f%C3%BCr-Premium-FTM-Benutzer-zug%C3%A4nglich/" target="_blank" class="">plg_system_ftm_event_email</a> (Plugin zum Verschicken von Erinnerungs-Emails für bevorstehende Termine. )</li>
						</ul>
						<h4>Mehr Infos dazu auf <a href="https://www.einsatzkomponente.de/" target="_blank" class="">www.einsatzkomponente.de</a></h4>
						</div>
						<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal">Schliessen</button>
						</div>
						</div></div>
						</div>
						<hr>
						<b>Info PHP-Funktionen:</b></br>
						
						<?php 
							if( ini_get('allow_url_fopen') ) {
								echo '<span class="label label-success">allow_url_fopen aktiv</span> ';
								} else {
								echo '<span class="label label-important">allow_url_fopen deaktiviert</span> <span class="icon-info-2 large-icon" style="font-size:18px;" title="Diese PHP-Funktion ist leider auf Ihrem Server deaktiviert. Die Funktion wird zwingend für den Fall einer Online-Validation benötigt. Bei einigen Webhoster kann man die Funktion im Controlpanel des Webhostes selbst aktivieren. Ansonsten einfach mal beim Support des Webhosters anfragen, ob diese Funktion freigeschaltet werden kann."> </span>';
								}
						?>
					<hr>
						Aktuellste Version: <iframe  frameborder="0" height="30px" width="250px" src="https://www.feuerwehr-veenhusen.de/images/firefightersJ30/index.html" scrolling="no"></iframe>
						
</div> 
          

				
						
					</td>
                    
               </tr>
               
                
                <tr>
               		 <td>
						<div class="alert alert-block alert-info">
						<button class="close" data-bs-dismiss="alert" type="button">×</button>
						<p> </p>
						<h4 style="margin-bottom:5px;">Weitere Links</h4>
						<ul>
						<li>
						<a target="_blank" href="https://einsatzkomponente.de" style="text-decoration:underline">Supportforum für die Einsatzkomponente</a>
						</li>
						<li>
						<a target="_blank" href="https://demo.einsatzkomponente.de/" style="text-decoration:underline">Testseite für die Einsatzkomponente V3.x für J3</a>
						</li>
						<li>
						<a target="_blank" href="https://www.feuerwehr-veenhusen.de" style="text-decoration:underline">Freiwillige Feuerwehr Veenhusen </a>
						</li>
						</ul>
						</div>
                  </td>
             </tr>
                
            </tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo 'Copyright (C) 2016 by Ralf Meyer. All rights reserved.
 *  GNU General Public License version 2 or later'; ?>
					</td>
				</tr>
			</tfoot>
            
		</table>
		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>     
	
<?php	
	function changelog($file, $onlyLast = false)
	{
		$ret = '';

		$lines = @file($file);

		if(empty($lines)) return $ret;

		array_shift($lines);

		foreach($lines as $line)
		{
			$line = trim($line);

			if(empty($line)) continue;

			$type = substr($line,0,1);

			switch($type)
			{
				case '=':
					break;
				case '-':
					$ret .= '<li><span style="font-size:8pt;color:#ff0000;">Removed:</span> '.substr($line, 1).'</li>';
					break;
				case '+':
					$ret .= '<li><span style="font-size:8pt;color:#ff0000;">Added:</span> '.substr($line, 1)."</li>";
					break;
				case '#':
					$ret .= '<li><span style="font-size:8pt;color:#00e600;">Bugfix:</span> '.substr($line, 1)."</li>";
					break;

				default:

					$ret .= $line;
					break;
			}
		}

		return $ret;
	}
	
?>