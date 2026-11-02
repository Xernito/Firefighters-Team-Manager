<?php
/**
 * @version     1.0.0
 * @package     com_firefighters
 * @copyright   Copyright (C) 2015 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - https://einsatzkomponente.de
 */

namespace Firefighters\Component\Firefighters\Administrator\Field;

defined('_JEXEC') or die;

use Firefighters\Component\Firefighters\Administrator\Helper\FirefightersHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

/**
 * displays the information panel for SimpleCalendar
 *
 * @package     com_simplecalendar
 * @subpackage  settings
 * @since       3.0
 */
class InfoField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   3.0
	 */
	protected $type = 'info';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   3.0
	 */
	 
	 
	protected function getInput()
	{
		
		$document = Factory::getApplication()->getDocument();
		$html = array();
		$val ='';
		$val= FirefightersHelper::getValidation();

		if ($val == '12') : 
		$html[] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button><span aria-hidden="true" class="icon-cancel"></span> ' . Text::_('<p>Diese Version von <b>Firefighters Team Manager</b> ist validiert. <br/> Vielen Dank f&uuml;r Ihre Unterst&uuml;tzung.</p><p>- Die Copyrights im Frontend, sollten jetzt alle entfernt sein.</p>');		
		else:
		$html[] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button><span aria-hidden="true" class="icon-cancel"></span>Diese Version von <b>Firefighters Team Manager</b> ist nicht validiert. Sie k&ouml;nnen selbstverst&auml;ndlich <b>alle Funktionen</b> kostenlos nutzen,<br/> aber &uuml;ber eine kleine Unterst&uuml;tzung an die Entwickler, w&uuml;rden wir uns nat&uuml;rlich sehr freuen. <br/>Helfen Sie uns, damit wir Ihnen dieses und weitere Projekte auch in Zukunft kostenlos und ohne Werbung anbieten k&ouml;nnen. <br/><br/>Schicken Sie eine Anfrage via Mail an validate@einsatzkomponente.de und holen Sie sich heute noch den Validation-Schl&uuml;ssel.<br/><br/><p>kleiner Vorteil:<br/>- Das Copyright im Frontend wird dadurch entfernt.  ;-)<br/>- und bevorzugten Support im Forum und via Email</p></div><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XPQALX4UFFGM4"><div>Spende &uuml;ber PAYPAL : <img border=0  src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" /></div></a><br/><br/>
';		
		if ($val=='1') :	$html[] =  '<span class="label label-important">Achtung !! Dies ist keine g&uuml;ltige URL. (Wenden Sie sich bitte an den Support im Forum)</span>'; 	endif;
		if ($val=='2') :	$html[] =  '<span class="label label-important">Achtung !! Dies ist kein g&uuml;ltiges Passwort. (Wenden Sie sich bitte an den Support im Forum)</span>'; 	endif; 
		endif;
		$html[] = '<h2>Firefighters Team Manager Version</h2>';
		if ($val=='12') :	$html[] = '';
		else:
		$html[] = '<h3>Entwickler-Version</h3>';
		endif;
		$html[] = '<p></p>';
		$html[] = '<p><a href="https://www.einsatzkomponente.de/" target="_blank">Supportforum: https://www.einsatzkomponente.de/</a></p>';	
		return implode($html);
	}
}