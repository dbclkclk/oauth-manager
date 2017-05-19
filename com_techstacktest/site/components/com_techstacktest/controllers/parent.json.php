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
 */

require_once JPATH_COMPONENT . '/controller.php';


class TeachproControllerParent extends TeachproController
{
	
        
        public function checkusernameavailability()
        { 
            try {
                    if($username = JRequest::getVar("username","","get","cmd"))
                    {
                       $db = JFactory::getDbo();

                       $query = $db->getQuery(true);

                       $query->select($db->quoteName("id"))->from($db->quoteName("#__users"))->where($db->quoteName("username")."=".$db->quote($username));            

                       $db->setQuery($query);

                       $result = $db->loadObjectList();
                       
                       if(count($result)>0)
                       {  
                           throw new Exception($username." ".JText::_( 'COM_USERS_PROFILE_USERNAME_MESSAGE' ));
                       }
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
            echo new JResponseJson(true);
        }
        
        
         public function checkEmailAvailability()
        { 
             
             
             
            try {
                
                     $jinput = JFactory::getApplication()->input;
                    if($email =  $jinput->get('email', null, "STRING"))
                    {
                       $db = JFactory::getDbo();

                       $query = $db->getQuery(true);

                       $query->select($db->quoteName("id"))->from($db->quoteName("#__users"))->where($db->quoteName("email")."=".$db->quote($email));            

                  
                       $db->setQuery($query);

                       $result = $db->loadObjectList();
                       
                       if(count($result)>0)
                       {  
                           throw new Exception($email." ".JText::_( 'COM_USERS_PROFILE_EMAIL1_MESSAGE' ));
                       }
                    }
                    else
                    {
                        throw new Exception(JText::_("COM_USERS_PROFILE_EMAIL1_DESC"));
                    }
            } 
            catch (Exception $ex) 
            {
                echo new JResponseJson(null,$ex->getMessage(),true);
                return;
            }
            echo new JResponseJson($query->__toString(),true);
        }
}