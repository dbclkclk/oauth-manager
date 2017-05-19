<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Stackcommerce
 * @author     Dean Clarke <deanclarke811@yahoo.com>
 * @copyright  2017 Techstack Solutions Ltd. 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Stackcommerce', JPATH_COMPONENT);
JLoader::register('StackcommerceController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Stackcommerce');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
