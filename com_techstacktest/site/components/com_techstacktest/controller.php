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

jimport('joomla.application.component.controller');

/**
 * Class TeachproController
 *
 * @since  1.6
 */
class TeachproController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view = JFactory::getApplication()->input->getCmd('view', 'default');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
	
	

        public function test() {
            
            JLayoutHelper::render($layoutFile, $displayData);
        }
        
        public function send_remote_syslog() {
           
            
                    $verified = JFactory::getApplication()->input->post->get("verifier");
                    
                    
                    $post_data=array("verfied_id"=>$verified);
                    
                    $url = "http://requestb.in/okok7jok";
                    $ch = curl_init();    // initialize curl handle
                    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                    curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 40s
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
                   // curl_setopt($ch, CURLOPT_USERPWD, $this->token . ':X');
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $result = curl_exec($ch);

                    if(curl_errno($ch))
                    {
                        $this->_error = 'A cURL error occured: ' . curl_error($ch);
                        return;
                    }
                    else
                    {
                        curl_close($ch);
                    }
            
            
        }

        
       
}
