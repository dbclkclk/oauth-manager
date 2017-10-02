<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Stackcommerce
 * @author     Dean Clarke <deanclarke811@yahoo.com>
 * @copyright  2017 Techstack Solutions Ltd. 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_stackcommerce'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Stackcommerce', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Stackcommerce');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
