<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Teachpro', JPATH_COMPONENT);

// Execute the task.
$controller = JControllerLegacy::getInstance('Teachpro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
