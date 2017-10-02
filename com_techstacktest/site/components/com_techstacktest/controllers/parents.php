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
class TeachproControllerParents extends TeachproController
{
	
        
        public function checkUsernameAvailability()
        {
            JFactory::getDocument()->setMimeEncoding( 'application/json' );
            JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
           
            try {
                    if($username = JRequest::getVar("username","","get","cmd"))
                    {
                       $db = JFactory::getDbo();

                       $query = $db->getQuery(true);

                       $query->select($db->quoteName("id"))->where($db->quoteName("username")."=".$username);            

                       $db->setQuery($query);

                       $result = $db->loadObjectList();
                       
                       if(!$result)
                       {  
                           throw new Exception($username.JText::_( 'COM_USERS_PROFILE_USERNAME_MESSAGE' ));
                       }
                    }
                    else
                    {
                        throw new Exception("");
                    }
            } 
            catch (Exception $ex) 
            {
                echo new JResponseJson($ex);
            }
            echo new JResponseJson(true);
        }
}
