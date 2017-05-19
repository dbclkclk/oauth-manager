<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
/**
 * Students controller class.
 *
 * @since  1.6
 * 
 * 
 */
 

jimport('joomla.application.component.controlleradmin');
use Joomla\Utilities\ArrayHelper;


class TeachproControllerGoalss extends JControllerAdmin
{
	
        
        public function getgoals()
        { 
            $results = array();
            $jinput = JFactory::getApplication()->input;
            try {
                    if($subject= $jinput->get->get("subjectid","int"))
                    {
                            $model = JModelList::getInstance("Goalss", "TeachproModel");
                           
                           $results =$model->getGoalsBySubject($subject);
                       
                    }
                    else
                    {
                        throw new Exception(JText::_("COM_USERS_REGISTER_USERNAME_DESC"));
                    }
            } 
            catch (Exception $ex) 
            {
                echo new JResponseJson(null,$ex->getMessage(),true);
                return;
            }
            echo new JResponseJson($results);
        }
        
        
        
}