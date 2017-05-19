<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;
/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelShoppingcart extends JModelItem
{
	/**
	 * Method to populate the subject name list.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	public function getSubjectList() {

		$table = $this->getTable('subjects');
		$table->load();
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string  $type    Name of the JTable class to get an instance of.
	 * @param   string  $prefix  Prefix for the table class name. Optional.
	 * @param   array   $config  Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Questions', $prefix = 'TeachproTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	public function insertData($paisa='curl')
	{
		
		////////// **********/
		/**this is not a useful function, we used this function to test if everything is allright with the MVC stuff.*****/
		/////***************/
		
		
		$goals = new stdClass();
		$goals->goal_id = 1001;
		$goals->name=$paisa;
		$goals->subjectid=8;
		$goals->description ='Inserting a record using insertObject()';
	
		 
		// Insert the object into the user profile table.
		$result = JFactory::getDbo()->insertObject('#__teachpro_goal', $goals);
		if($result){
			
			return true;
		} else return false;

			
				
	}
	//saveTransection($_POST['txn_id'],$custom )
	
	public function checkAndSaveTransection($txn, $custom )
	{
            /*
                $db= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,hash,is_paid');
		$query->from('#__teachpro_payment');
		$query->where("hash = ". $custom);
		$db->setQuery((string) $query);
		$result = $db->loadObjectList();
                
            */
            
            // Get a db connection.
$db = JFactory::getDbo();
 
// Create a new query object.
$query = $db->getQuery(true);
 
// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName(array('id', 'hash', 'is_paid')));
$query->from($db->quoteName('#__teachpro_payment'));
$query->where($db->quoteName('hash') . ' LIKE '. $db->quote($custom));

 
// Reset the query using our newly populated query object.
$db->setQuery($query);
 
// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$result = $db->loadObjectList();
print_r($result);

		
		if( count($result)  < 1 ){
                    // no need to do anyting because the has doent exist
                    return false;
		}
		else {
		     // we will update that hash and change the status to 1
                    
                    //echo $result['0']->id;
                    
                    	JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');
			$table = JTable::getInstance('payments', 'TeachproTable', array());
                        
                        	$table-> load($result['0']->id);
                                $table -> is_paid ='1';
                                $table -> paypal_fields  = $txn;
                               
                                if( $table->store()){
                                    
                                    return true;

                                
                                }else {
                                    die('payment cannot be processed. please contact us with the paypal Transection Id');
                                    
                                }	




			
		} 
		
		
	
			
		
	
		 
			
					
		
			
			
				
	}

	
	
	public function checkTransection($txn)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__teachpro_payment');
		$query->where("paypal_fields = ". $txn);
		$db->setQuery((string) $query);
		$txn = $db->loadObjectList();
		if( count($txn)  < 1 ){
			return true;
                        echo 'true';
		}
		else {
			return false;
                          echo 'false';
			
			
		} 
		
	}
	
	


}
