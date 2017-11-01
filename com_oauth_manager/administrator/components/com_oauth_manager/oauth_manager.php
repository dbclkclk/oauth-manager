<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Oauth_manager
 * @author     TechStack Solutions Ltd <support@techstacksolutions.com>
 * @copyright  2017 Techstack Solutions Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_oauth_manager'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once JPATH_COMPONENT_ADMINISTRATOR."/library/vendor/autoload.php";
// Include dependancies
jimport('joomla.application.component.controller');
//
JLoader::registerPrefix('Oauth_manager', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Oauthbaselist', JPATH_COMPONENT_ADMINISTRATOR . '/models/oauthbaselist.php');
//
$controller = JControllerLegacy::getInstance('Oauth_manager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
