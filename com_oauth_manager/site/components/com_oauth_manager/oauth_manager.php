<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Oauth_manager
 * @author     TechStack Solutions Ltd <support@techstacksolutions.com>
 * @copyright  2017 Techstack Solutions Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Oauth_manager', JPATH_COMPONENT);
JLoader::register('Oauth_managerController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Oauth_manager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
