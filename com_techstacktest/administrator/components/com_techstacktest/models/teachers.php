<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     Sandeep Thapa <ersandeepthapa@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelTeachers extends JModelList
{
	
	public  $where;
        
        public   $context = "tutor";

        public function __construct($config = array())
	{   
		$config['filter_fields'] = array(
			'ordering'
		);
		parent::__construct($config);
	}
	
	protected function getListQuery()
	{ 
                $db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'a.id, s.subject_name as subject, s.id as subjectid, s.grade, us.name,us.username, us.id as userid'
			)
		);
		$query->from('`#__teachpro_teacher` AS a');
                $query->join("inner","`#__teachpro_subject` as s on a.subject_id=".$db->quoteName("s.id"));
                $query->join("inner","`#__users` as us on us.id=".$db->quoteName("a.user_id"));
             
                
                
		return $query;
                
                
	}
        
        
        /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_teachpro');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.firstname', 'asc');
	}
       
        
       public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
        
        public function getTeachers()
        {
            
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select($db->quoteName("id"));
            $query->from( $db->quoteName('#__usergroups'));
            $query->where($db->quoteName('title')."=".$db->quote($this->context));
            $db->SetQuery($query);
            $obj = $db->loadObject();
            
            $users = JAccess::getUsersByGroup($obj->id);
            $counter = 0;
            foreach($users as $user){
                $users[$counter] = JFactory::getUser($user);
                $counter++;
            }
            return $users;
        }
        
        public function getTeachersBySubject($subjectId)
        {
            
            $db = JFactory::getDbo();
          
            $query = $this->getListQuery();
            
            $query->where($db->quoteName("a.subject_id")."=".$subjectId);
            
            $db->setQuery($query);
            $teachers = $db->loadAssocList();
            
            $counter = 0;
            foreach($teachers as $teacher){
                
                $teacher[$counter] = JFactory::getUser($teacher["userid"]);
                $counter++;
            }
            return $teachers;
            
        }
        
        
       
}
