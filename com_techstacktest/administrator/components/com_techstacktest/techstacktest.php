<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Techstacktest
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_teachpro'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Techstacktest', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Techstacktest');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
